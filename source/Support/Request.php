<?php

namespace Source\Support;

use Source\Core\Session;

class Request
{
    private const REQUEST_SESSION_KEY = 'formRequest';
    private const EXPIRE_SECONDS = 2;

    /**
     * Salva os dados do formulário, erros e timestamp.
     */
    public static function flashRequest(array $errors = []): void
    {
        $session = new Session();
        $session->set(self::REQUEST_SESSION_KEY, [
            '_data' => $_POST,
            '_errors' => $errors,
            '_time' => time()
        ]);
    }


    /**
     * Retorna o valor de um campo, se ainda estiver válido.
     */
    public static function value(string $key): ?string
    {
        $session = new Session();
        $formRequest = $session->{self::REQUEST_SESSION_KEY} ?? null;

        if (
            !$formRequest ||
            !isset($formRequest['_data'], $formRequest['_time']) ||
            !self::isValid($formRequest['_time'])
        ) {
            self::clear();
            return null;
        }

        return $formRequest['_data'][$key] ?? null;
    }

    /**
     * Verifica se o dado ainda está dentro do tempo de validade.
     */
    private static function isValid(int $timestamp): bool
    {
        return (time() - $timestamp) <= self::EXPIRE_SECONDS;
    }

    /**
     * Remove os dados da sessão.
     */
    public static function clear(): void
    {
        $session = new Session();
        $session->unset(self::REQUEST_SESSION_KEY);
    }

    /**
     * Retorna a mensagem de erro para um campo específico, se houver.
     */
    public static function error(string $key, bool $html = true): ?string
    {
        $session = new Session();
        $formRequest = $session->{self::REQUEST_SESSION_KEY} ?? null;

        if (
            !$formRequest ||
            !isset($formRequest['_errors'], $formRequest['_time']) ||
            !self::isValid($formRequest['_time'])
        ) {
            return null;
        }

        $error =  $formRequest['_errors'][$key] ?? null;

        // Se não houver erro, retorna null
        if (empty($error)) return null;

        // Se o erro não for uma string, retorna null
        if (!$html) return $error;

        // Se o erro for uma string, retorna o HTML formatado e seguro
        return "<div class='invalid-feedback'>" . htmlspecialchars($error) . "</div>";
    }
}
