<?php

namespace Source\Core;

class Env
{
    /**
     * Caminho para o arquivo .env
     *
     * @var string
     */
    private string $file;

    /**
     * Construtor recebe o caminho do arquivo .env.
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Carrega as variáveis do arquivo .env para o ambiente.
     */
    public function load(): void
    {
        if (!file_exists($this->file)) {
            return;
        }

        $lines = file($this->file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Remove espaços em branco e ignora linhas de comentário
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            // Divide a linha pelo primeiro "=" encontrado
            if (!str_contains($line, '=')) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Remove aspas se estiverem presentes
            $value = trim($value, "\"'");

            // Define a variável de ambiente se ainda não estiver definida
            if (getenv($name) === false) {
                putenv("$name=$value");
            }

            // Também adiciona no $_ENV e $_SERVER, se necessário
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
