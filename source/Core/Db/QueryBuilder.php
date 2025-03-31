<?php

namespace Source\Core\Db;

use PDO;

class QueryBuilder
{
    protected PDO $db;
    protected string $modelClass;
    protected string $table;
    protected array $where = [];
    protected array $params = [];
    protected ?int $limit = null;
    protected ?string $groupBy = null;
    protected ?string $orderBy = null;
    protected array $withRelations = [];

    public function __construct(string $modelClass)
    {
        $this->db = Connect::getConnection();
        $this->modelClass = $modelClass;
        $instance = new $modelClass();
        $this->table = $instance->getTable();
    }

    public function where(string $condition, array $params = []): self
    {
        $this->where[] = $condition;
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function groupBy(string $group): self
    {
        $this->groupBy = $group;
        return $this;
    }

    public function orderBy(string $order): self
    {
        $this->orderBy = $order;
        return $this;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }

    /**
     * Executa a query e retorna os registros como instâncias do modelo,
     * carregando automaticamente as relações definidas em with().
     */
    public function get(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }
        if ($this->groupBy) {
            $sql .= " GROUP BY " . $this->groupBy;
        }
        if ($this->orderBy) {
            $sql .= " ORDER BY " . $this->orderBy;
        }
        if (!is_null($this->limit)) {
            $sql .= " LIMIT " . $this->limit;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->params);
        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $objects = [];
        foreach ($dataList as $data) {
            $obj = new $this->modelClass($data);
            $obj->exists = true;

            // Se relações foram definidas via with(), carrega-as automaticamente
            if (!empty($this->withRelations)) {
                foreach ($this->withRelations as $relation) {
                    if (method_exists($obj, $relation)) {
                        // Carrega a relação e atribui como propriedade do objeto
                        $obj->{$relation} = $obj->$relation();
                    }
                }
            }
            $objects[] = $obj;
        }
        return $objects;
    }

    public function with(string ...$relations): self
    {
        $this->withRelations = $relations;
        return $this;
    }
}