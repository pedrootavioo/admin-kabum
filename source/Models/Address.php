<?php

namespace Source\Models;

use Exception;
use Source\Core\Db\Model;

class Address extends Model
{
    protected string $table = 'addresses';
    protected array $columns = [
        'zipcode' => 'required|numeric|min:8|max:10',
        'street' => 'required|uppercase|min:3|max:100',
        'number' => 'required|string|min:1|max:10',
        'complement' => 'string|uppercase|max:50',
        'neighborhood' => 'required|uppercase|min:3|max:50',
        'city' => 'required|uppercase|min:3|max:50',
        'ibge_code' => 'numeric|min:7|max:7',
        'state' => 'required|uppercase|min:2|max:2',
        'state_code' => 'numeric|min:2|max:2',
        'main' => 'checkbox',
    ];

    public function bootstrap(
        Person  $person,
        ?string $zipcode,
        ?string $street,
        ?string $number,
        ?string $complement,
        ?string $neighborhood,
        ?string $city,
        ?string $ibge_code,
        ?string $state,
        ?string $state_code,
        ?string $main = 'off',
    ): self
    {
//        Helper::dd($main);

        $this->person_id = $person->id;
        $this->zipcode = $zipcode;
        $this->street = $street;
        $this->number = $number;
        $this->complement = $complement;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->ibge_code = $ibge_code;
        $this->state = $state;
        $this->state_code = $state_code;
        $this->main = $main === 'off' ? 0 : 1;

        return $this;
    }

    public function generate(Person $person, ?array $data): false|self
    {
        $this->bootstrap(
            $person,
            $data['zipcode'] ?? null,
            $data['street'] ?? null,
            $data['number'] ?? null,
            $data['complement'] ?? null,
            $data['neighborhood'] ?? null,
            $data['city'] ?? null,
            $data['ibge_code'] ?? null,
            $data['state'] ?? null,
            $data['state_code'] ?? null,
            $data['main'] ?? 'off'
        );

        if (!$this->save()) {
            return false;
        }

        return $this;
    }

    public function persist(Person $person, ?array $data): false|self
    {
        try {

            if (!$this->generate($person, $data)) {
                $this->errors = array_merge($this->errors, $person->errors());
                throw new Exception('Erro ao gravar dados');
            }

            return $this;

        } catch (Exception $e) {
            $this->fail = $e;
            return false;
        }
    }

    public function person(): ?Model
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function handleMainAddresses(): bool
    {
        // Se estiver atualizando um endereço existente, exclua ele mesmo da atualização.
        if (!$this->main) {
            return true;
        }

        if (!empty($this->attributes[$this->primaryKey])) {
            $stmt = $this->db->prepare(
                "UPDATE {$this->table} SET main = 0 WHERE person_id = :person_id AND {$this->primaryKey} <> :id"
            );
            return $stmt->execute([
                'person_id' => $this->person_id,
                'id' => $this->attributes[$this->primaryKey]
            ]);
        }

        // Se for um novo registro, atualiza todos os endereços para essa pessoa.
        $stmt = $this->db->prepare("UPDATE {$this->table} SET main = 0 WHERE person_id = :person_id");
        return $stmt->execute([
            'person_id' => $this->person_id
        ]);
    }

    public function save(): false|Model
    {
        if (!$this->handleMainAddresses()) {
            $this->errors[] = 'Erro ao atualizar o endereço principal';
            return false;
        }

        return parent::save();
    }
}