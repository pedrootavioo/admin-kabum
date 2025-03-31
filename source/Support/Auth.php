<?php

namespace Source\Support;

use Exception;
use Source\Core\Session;
use Source\Models\Person;
use Source\Models\User;

class Auth
{
    private ?Exception $fail;

    private const SESSION_KEY = 'userId';

    private const PASSWORD_MIN_LENGTH = 1;

    private array $errors = [];

    public function attempt(?string $email, ?string $password): bool
    {
        try {

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'E-mail inválido';
            }

            if (empty($password) || strlen($password) < self::PASSWORD_MIN_LENGTH) {
                $this->errors['password'] = 'Senha deve ter no mínimo '. self::PASSWORD_MIN_LENGTH .' caracteres';
            }

            /** @var Person $person */
            $person = Person::findByCondition("email = :email", ['email' => $email], false);

            /** @var User $user */
            if (empty($person) || (!$user = $person->user())) {
                $this->errors['general'] = 'Dados inválidos, tente novamente';
            }

            if (!password_verify($password, $user->password)) {
                $this->errors['general'] = 'Dados inválidos, tente novamente';
            }

            if (!empty($this->errors)) {
                throw new Exception('Dados inválidos para acesso');
            }

            self::login($user);
            return true;

        } catch (Exception $e) {
            $this->fail = $e;
            return false;
        }
    }

    public static function login(User $user): void
    {
        $session = new Session();
        $session->set(self::SESSION_KEY, $user->id);
        $session->regenerate();
    }
    public static function logout(): void
    {
        $session = new Session();
        $session->unset(self::SESSION_KEY);
        $session->regenerate();
    }

    public static function check(): bool
    {
        $session = new Session();
        return $session->has(self::SESSION_KEY);
    }

    public static function user(): ?\Source\Core\Db\Model
    {
        $session = new Session();
        if (empty($session->{self::SESSION_KEY})) return null;

        return User::findById($session->{self::SESSION_KEY});
    }

    public function fail(): ?Exception
    {
        return $this->fail ?? null;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}