<?php

namespace Source\Models;

use Exception;
use Source\Core\Db\Connect;
use Source\Core\Db\Model;
use Source\Support\Auth;
use Source\Support\Helper;

class User extends Model
{
    protected string $table = 'users';
    protected array $columns = [
        'person_id' => 'required|unique',
        'password' => 'required|encrypted',
    ];

    public function bootstrap(Person $person, ?array $data): false|self
    {
        $this->person_id = $person->id;

        if (!self::handlePasswordInput($data)) {
            return false;
        }

        return $this;
    }

    public function handlePasswordInput(?array $data): false|self
    {
        $password = $data['password'] ?? null;
        $passwordConfirm = $data['confirm_password'] ?? null;

        if (empty($password) && empty($passwordConfirm)) {
            return $this;
        }

        if (empty($password)) {
            $this->errors['password'] = 'Você deve informar a senha';
            return false;
        }

        if (empty($passwordConfirm)) {
            $this->errors['confirm_password'] = 'Você deve confirmar a senha';
            return false;
        }

        if ($password !== $passwordConfirm) {
            $this->errors['confirm_password'] = 'As senhas não conferem';
            return false;
        }

        $this->password = $password;

        return $this;
    }

    public function person(): ?Model
    {
        return $this->belongsTo(Person::class, 'person_id');
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

            $this->bootstrap($person, $data);

            $this->save();

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
        $authUser = Auth::user();

        if ($this->id === $authUser->id) {
            $this->errors[] = 'Você não pode remover seu próprio usuário';
            return false;
        }

        return parent::destroy();
    }
}