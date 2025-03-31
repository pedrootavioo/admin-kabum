<?php

namespace Source\Models;

use Source\Core\Db\Model;

class Person extends Model
{
    protected string $table = 'persons';
    protected array $columns = [
        'name' => 'required|uppercase|min:3|max:100',
        'birthdate' => 'required|date:Y-m-d,d/m/Y',
        'email' => 'email|lowercase|unique|max:50',
        'document' => 'required|cpf|numeric|unique|min:11|max:14',
        'identity' => 'min:5|max:20',
        'phone' => 'min:10|max:20',
    ];

    public function addresses(): ?array
    {
        return $this->hasMany(Address::class, 'person_id');
    }

    public function user(): ?Model
    {
        return $this->hasOne(User::class, 'person_id');
    }

    public function shortName(): ?string
    {
        if (empty($this->name)) return null;

        // Retorna o primeiro, ou primeiro e último nome, caso tenha
        $name = explode(' ', $this->name);

        if (count($name) > 1) {
            return $name[0] . ' ' . end($name);
        }

        return $name[0];
    }

    public function bootstrap(
        ?string $name,
        ?string $birthdate,
        ?string $document,
        ?string $identity,
        ?string $email = null,
        ?string $phone = null
    ): self
    {
        $this->name = $name;
        $this->birthdate = $birthdate;
        $this->document = $document;
        $this->identity = $identity;
        $this->email = $email;
        $this->phone = $phone;

        return $this;
    }

    public function generate(?array $data): false|self
    {
        $this->bootstrap(
            $data['name'] ?? null,
            $data['birthdate'] ?? null,
            $data['document'] ?? null,
            $data['identity'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null
        );

        if (!$this->validate()) {
            return false;
        }

        if (!$this->save()) {
            return false;
        }

        return $this;
    }
}