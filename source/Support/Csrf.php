<?php

namespace Source\Support;

use Exception;
use Source\Core\Session;

class Csrf
{
    /**
     * Gera ou retorna o token CSRF armazenado na sessão.
     *
     * Se a requisição for GET e o token não existir ou estiver expirado, gera um novo.
     * Para métodos não-GET, retorna o token atual (se existir) sem gerar um novo.
     *
     * @return string|null O token gerado ou existente, ou null em caso de erro.
     */
    public static function token(): ?string
    {
        $session = new Session();

        // Se já existe um token e não expirou, retorne-o
        if (!empty($session->csrf_token) && !self::expired()) {
            return $session->csrf_token;
        }

        // Para métodos que não sejam GET, não geramos novo token
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return $session->csrf_token ?? null;
        }

        try {
            $token = bin2hex(random_bytes(32));
            $session->logCsrf($token);
            return $token;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Retorna um input oculto com o token CSRF.
     *
     * @return string O HTML do input.
     */
    public static function input(): string
    {
        $token = self::token();
        return '<input type="hidden" name="csrf_token" value="' . ($token ?? '') . '">';
    }

    /**
     * Valida o token CSRF enviado.
     *
     * @param string $submittedToken O token recebido do formulário.
     * @return bool True se o token for válido; false caso contrário.
     */
    public static function validate(?string $submittedToken): bool
    {
        if (empty($submittedToken)) return false;

        $session = new Session();
        if (empty($session->csrf_token)) {
            return false;
        }
        return hash_equals($session->csrf_token, $submittedToken);
    }

    /**
     * Verifica se o token CSRF armazenado está expirado.
     *
     * @param int $timeToExpire Tempo em segundos para expirar (padrão 1800 segundos = 30 minutos).
     * @return bool True se o token estiver expirado ou não existir; false caso contrário.
     */
    public static function expired(int $timeToExpire = 1800): bool
    {
        $session = new Session();
        if (empty($session->csrf_token) || empty($session->csrf_renewal_time)) {
            return true;
        }
        $currentTime = time();
        $tokenTime = $session->csrf_renewal_time;
        return ($currentTime - $tokenTime) > $timeToExpire;
    }
}
