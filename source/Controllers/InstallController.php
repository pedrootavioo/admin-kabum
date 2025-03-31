<?php

namespace Source\Controllers;

use PDO;
use PDOException;
use PHPUnit\TextUI\Help;
use Source\Core\Controller;
use Source\Core\Db\Connect;
use Source\Core\Env;
use Source\Core\Router;
use Source\Models\User;
use Source\Support\Helper;

class InstallController extends Controller
{
    private string $envPath;
    private string $dbPath;

    public function __construct(Router $router)
    {
        parent::__construct($router);
        $this->envPath = __DIR__ . '/../../.env';
        $this->dbPath = __DIR__ . '/../../db.sql';
    }

    public function form(): void
    {
        $this->view->template('single')->render('install/form');
    }

    public function process(array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        if (in_array('', $data)) {
            $this->message->error('Preencha todos os campos')->flash();
            $this->router->redirect('install.form');
        }

        // Gera e salva o .env
        if (!$this->handleEnv($data)) {
            $this->message->error('Erro ao criar o arquivo .env. Tente novamente.')->flash();
            $this->router->redirect('install.form');
        }

        $this->router->redirect('install.db');
    }

    public function db(): void
    {
        $pdo = Connect::getConnection();

        if (!$pdo) {
            $this->resetEnv();
            $this->message->error('Erro ao conectar ao banco de dados. Verifique os dados e tente novamente.')->flash();
            $this->router->redirect('install.form');
        }

        if (!file_exists($this->dbPath)) {
            $this->message->error('Arquivo do banco de dados não encontrado.')->flash();
            $this->router->redirect('install.form');
        }

        $dbContent = file_get_contents($this->dbPath);

        try {
            $pdo->exec($dbContent);

            $this->appendToEnv("INSTALLED=1");

            $this->router->redirect('auth.index');

        } catch (PDOException $e) {

            $this->message->error('Erro ao importar o banco de dados: ' . $e->getMessage())->flash();
            $this->resetEnv();
            $this->router->redirect('install.form');
        }
    }

    private function seedMainAdmin(): User|false
    {
        $data = [
            'name' => CONF_USER_ADMIN_TEST['name'],
            'email' => CONF_USER_ADMIN_TEST['email'],
            'password' => CONF_USER_ADMIN_TEST['password'],
            'confirm_password' => CONF_USER_ADMIN_TEST['password'],
        ];

        $user = new User();
        return $user->persist($data);
    }

    private function handleEnv(array $data): int|false
    {
        // Remove possíveis quebras de linha
        foreach ($data as $key => $value) {
            $data[$key] = str_replace(["\r", "\n"], '', trim($value));
        }

        $envContent = <<<ENV
DB_DRIVER='mysql'
DB_HOST='{$data['db_host']}'
DB_PORT={$data['db_port']}
DB_NAME='{$data['db_name']}'
DB_USER='{$data['db_user']}'
DB_PASSWD='{$data['db_password']}'

CONF_USER_ADMIN_TEST_NAME='{$data['admin_name']}'
CONF_USER_ADMIN_TEST_EMAIL='{$data['admin_email']}'
CONF_USER_ADMIN_TEST_PASSWORD='{$data['admin_password']}'
ENV;

        return file_put_contents($this->envPath, $envContent);
    }

    private function resetEnv(): void
    {
        if (file_exists($this->envPath)) {
            unlink($this->envPath);
        }

        $this->message->warning('Conexão não estabelecida. Por favor, revise os dados e tente novamente.')->flash();
        $this->router->redirect('install.form');
    }

    private function appendToEnv(string $line): void
    {
        file_put_contents($this->envPath, PHP_EOL . $line . PHP_EOL, FILE_APPEND);
    }
}
