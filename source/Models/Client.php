<?php

namespace Source\Models;

use Exception;
use Source\Core\Db\Connect;
use Source\Core\Db\Model;

class Client extends Model
{
    protected string $table = 'clients';
    protected array $columns = [
        'person_id' => 'required|unique',
    ];

    public function person(): ?Model
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function user(): ?Model
    {
        /** @var Person $person */
        if (!$person = $this->person()) return null;

        return $person->user();
    }

    public function persist(?array $data): false|self
    {
        $pdo = Connect::getConnection();
        $pdo->beginTransaction();

        try {

            // Verifica se a pessoa já existe para criar ou atualizar
            $person = $this->id ? $this->person() : new Person();


            if (!$person->generate($data)) {
                $this->errors = array_merge($this->errors, $person->errors());
            }

            // Verifica se o cliente já existe para criar um novo cliente ou atualizar o existente.
            if (empty($this->id)) {
                $this->person_id = $person->id;
                $this->save();
            }

            if (count($this->errors)) {
                throw new Exception('Erro ao gravar dados');
            }

            $pdo->commit();
            return $this;

        } catch (Exception $e) {
            $pdo->rollBack();
            $this->errors[] = "Error: {$e->getMessage()}";
            $this->fail = $e;

            return false;
        }
    }

    public function destroy(): bool
    {
        $this->db->beginTransaction();

        try {

            if ($person = $this->person()) $person->destroy();

            parent::destroy();

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            $this->errors[] = "Error: {$e->getMessage()}";
            return false;
        }
    }
}