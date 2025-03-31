<?php

namespace Source\Core;

use Source\Support\Message;

class Session
{
    private string $identifier;
    // Removemos as propriedades públicas csrf_token e csrf_renewal_time,
    // pois os valores são armazenados em $_SESSION via o método set().
    private ?Message $flash;

    public function __construct()
    {
        if (!session_id()) {
            session_set_cookie_params([
                "httponly" => true,
                // Considere "secure" => true se usar HTTPS
            ]);
            session_start();
        }

        $this->identifier = CONF_SESSION_IDENTIFIER;
    }

    /**
     * Retorna o valor armazenado na sessão para o nome fornecido.
     */
    public function __get($name)
    {
        return $_SESSION[$this->identifier][$name] ?? null;
    }

    /**
     * Delegamos __set para o método set() para centralizar a lógica.
     */
    public function __set(string $name, $value): void
    {
        $this->set($name, $value);
    }

    /**
     * Verifica se um valor existe na sessão para o nome fornecido.
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * Armazena um valor na sessão. Se $mult for informado, armazena em um sub-array.
     */
    public function set(string $key, mixed $value, ?string $mult = null): Session
    {
        if ($mult) {
            $_SESSION[$this->identifier][$mult][$key] = $value;
            return $this;
        }
        $_SESSION[$this->identifier][$key] = $value;
        return $this;
    }

    /**
     * Remove um valor da sessão.
     */
    public function unset(string $key): Session
    {
        unset($_SESSION[$this->identifier][$key]);
        return $this;
    }

    /**
     * Verifica se existe um valor para a chave na sessão.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$this->identifier][$key]);
    }

    /**
     * Regenera o ID da sessão.
     */
    public function regenerate(): Session
    {
        session_regenerate_id(true);
        return $this;
    }

    /**
     * Retorna a mensagem flash armazenada e a remove da sessão.
     */
    public function flash(): ?Message
    {
        if (!$this->has("flash")) return null;

        $flash = $this->__get("flash");
        $this->unset("flash");

        return $flash;
    }

    /**
     * Armazena o token CSRF e o horário de renovação na sessão.
     */
    public function logCsrf(string $token): void
    {
        $this->set('csrf_token', $token);
        $this->set('csrf_renewal_time', time());
    }
}
