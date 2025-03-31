<?php

namespace Source\Core\Db;

use Exception;
use PDO;
use Source\Support\Formatter;
use Source\Support\Helper;
use Source\Support\Validator;

abstract class Model
{
    protected PDO $db;
    public string $primaryKey = 'id';
    protected string $table;
    protected array $columns = [];
    protected array $attributes = [];
    protected array $unique = [];
    protected array $errors = [];
    protected mixed $fail;
    protected array $withRelations = [];

    public function __construct(array $data = [])
    {
        $this->db = Connect::getConnection();
        $this->fill($data);
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {

            // Armazena o campo se ele estiver definido em $columns ou se for a chave primária.
            if (array_key_exists($key, $this->columns) || $key === $this->primaryKey) {
                $this->attributes[$key] = $value;
            }
        }
    }

    public static function findById(?int $id): ?self
    {
        if (empty($id)) return null;

        $instance = new static();
        $stmt = $instance->db->prepare("SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);

        if (!$data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return null;
        }

        $instance->fill($data);

        if (!empty($instance->withRelations)) {
            $instance->loadRelations();
        }

        return $instance;
    }

    /**
     * Método unificado para buscas customizadas.
     * Se $all for true, retorna array de objetos; caso contrário, retorna um único objeto ou null.
     */
    public static function find(string $where, array $params = [], bool $all = true): null|self|array
    {
        $instance = new static();
        $query = "SELECT * FROM {$instance->table} WHERE {$where}" . ($all ? "" : " LIMIT 1");
        $stmt = $instance->db->prepare($query);
        $stmt->execute($params);

        if (!$stmt->rowCount()) return null;

        if ($all) {
            $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $objects = [];
            foreach ($dataList as $data) {
                $obj = new static();
                $obj->fill($data);
                $objects[] = $obj;
            }
            return $objects;
        }

        if (!$data = $stmt->fetch(PDO::FETCH_ASSOC)) return null;
        $instance->fill($data);
        return $instance;
    }

    /**
     * Retorna um QueryBuilder para consultas avançadas.
     */
    public static function query(): QueryBuilder
    {
        // Late static binding: passa a classe atual para o QueryBuilder
        return new QueryBuilder(static::class);
    }

    public function save(): false|self
    {
        if (!$this->validate()) {
            return false;
        }

        if (empty($this->{$this->primaryKey})) { // Se o ID não estiver definido, significa que é um novo registro.

            // INSERT
            $columns = array_keys($this->attributes);
            $columnsStr = implode(', ', $columns);
            $placeholders = ':' . implode(', :', $columns);
            $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columnsStr) VALUES ($placeholders)");

            if (!$stmt->execute($this->attributes)) {
                $this->errors = array_merge($this->errors, ['general' => $stmt->errorInfo()[2]]);
                $this->fail = new Exception("Erro ao inserir o registro: {$stmt->errorInfo()[2]}");
                return false;
            }

            // Pega o ID gerado e seta no objeto
            $this->attributes[$this->primaryKey] = $this->db->lastInsertId();

            return $this;
        }

        // UPDATE
        $setClause = '';
        $params = [];
        foreach ($this->attributes as $column => $value) {
            if ($column === $this->primaryKey) continue;
            $setClause .= "$column = :$column, ";
            $params[$column] = $value;
        }
        $setClause = rtrim($setClause, ', ');
        $params['id'] = $this->attributes[$this->primaryKey];
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = :id");

        if (!$stmt->execute($params)) {
            $this->errors = array_merge($this->errors, ['general' => $stmt->errorInfo()[2]]);
            $this->fail = new Exception("Erro ao atualizar o registro: {$stmt->errorInfo()[2]}");
            return false;
        }
        return $this;
    }

    public function destroy(): bool
    {
        if (empty($this->{$this->primaryKey})) return false;

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $result = $stmt->execute(['id' => $this->attributes[$this->primaryKey]]);

        if (!$result) return false;

        return true;
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    public function __get($key)
    {
        $value = $this->attributes[$key] ?? null;

        if (isset($this->columns[$key]) && str_contains($this->columns[$key], 'date')) {
            // Chama o método do Formatter para aplicar o formato de exibição
            return Formatter::displayDate($value, $this->columns[$key]);
        }

        return $value;
    }

    public function __set($key, $value): void
    {
        if (isset($this->columns[$key])) {

            // Obtém as regras definidas para o campo
            $rulesString = $this->columns[$key];

            // Separa as regras usando o pipe (|) como delimitador
            $rules = explode('|', $rulesString);

            // Aplica as regras de formatação usando o Formatter
            $value = Formatter::applyRules($value, $rules);
        }
        $this->attributes[$key] = $value;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function belongsTo(string $relatedClass, string $foreignKey, string $ownerKey = 'id'): ?Model
    {
        $fkValue = $this->{$foreignKey}; // utiliza o __get() se definido
        if (empty($fkValue)) {
            return null;
        }

        return $relatedClass::findByCondition("$ownerKey = :value", ['value' => $fkValue], false);
    }

    /*
     * Relacionamento: hasMany
     * Exemplo: um User tem muitos Posts.
     * @param string $relatedClass Nome completo da classe relacionada.
     * @param string $foreignKey Nome da coluna na tabela relacionada que referencia a tabela atual.
     * @param string $localKey Nome da coluna na tabela atual (normalmente 'id').
     */
    public function hasMany(string $relatedClass, string $foreignKey, string $localKey = 'id'): ?array
    {
        if (!isset($this->attributes[$localKey])) {
            return null;
        }

        return $relatedClass::findByCondition("$foreignKey = :value", ['value' => $this->attributes[$localKey]], true);
    }

    /*
     * Relacionamento: hasOne
     * Exemplo: um User tem um Profile.
     * @param string $relatedClass Nome completo da classe relacionada.
     * @param string $foreignKey Nome da coluna na tabela relacionada que referencia a tabela atual.
     * @param string $localKey Nome da coluna na tabela atual (normalmente 'id').
     * @return Model|null
     */
    public function hasOne(string $relatedClass, string $foreignKey, string $localKey = 'id'): ?Model
    {
        return $relatedClass::findByCondition("$foreignKey = :fk", ['fk' => $this->$localKey], false);
    }

    /**
     * Método auxiliar para buscas customizadas – pode ser utilizado pelos relacionamentos.
     * Este método pode ser similar ao findByCondition que vimos antes.
     */
    public static function findByCondition(string $where, array $params = [], bool $all = true): null|self|array
    {
        return static::find($where, $params, $all);
    }

    public function with(string ...$relations): static
    {
        $this->withRelations = $relations;
        $this->loadRelations();
        return $this;
    }

    protected function loadRelations(): void
    {
        foreach ($this->withRelations as $relation) {
            if (method_exists($this, $relation)) {
                // Armazena o resultado do relacionamento na chave com o nome da relação.
                $this->attributes[$relation] = $this->$relation();
            }
        }
    }

    public function validate(): bool
    {
        $validator = new Validator();
        $validator->validate($this, $this->attributes, $this->columns);

        if ($validator->fails()) {
            $this->errors = array_merge($validator->errors(), $this->errors);
            return false;
        }

        return true;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function fail(): ?Exception
    {
        return $this->fail ?? null;
    }
}
