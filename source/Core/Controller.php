<?php

namespace Source\Core;

use Source\Core\Db\Model;
use Source\Support\Auth;
use Source\Support\Csrf;
use Source\Support\Message;

class Controller
{
    protected View $view;
    protected Router $router;
    protected Session $session;
    protected Message $message;
    protected bool $userIsLoggedIn;

    public function __construct(Router $router)
    {

        $this->view = new View();
        $this->session = new Session();
        $this->message = new Message();
        $this->userIsLoggedIn = Auth::check();
        $this->router = $router;
        $this->install();
    }

    public function setRouter(Router $router): void
    {
        $this->router = $router;
        $this->view->handleData([
            'router' => $router,
            'csrf' => Csrf::input(),
            'session' => $this->session,
            'userIsLoggedIn' => $this->userIsLoggedIn,
        ]);
    }

    public function csrfVerify(array $data): bool
    {
        $csrfToken = $data['csrf_token'] ?? null;

        if (empty($csrfToken)) {
            $this->message->error('Erro ao processar, recarregue a página')->flash();
            $this->router->redirect('back');
        }

        if (!Csrf::validate($csrfToken)) {
            $this->message->error('Erro ao validar, recarregue a página')->flash();
            $this->router->redirect('back');
        }

        return true;
    }

    protected function loadModel(
        array $data,
        string $modelClass,
        string $paramKey,
        array $with = [],
        string $redirectTo = 'back',
        string $notFoundMessage = 'Registro não encontrado'
    ): ?Model
    {
        $id = $data[$paramKey] ?? null;

        if (!$id) {
            $this->message->error($notFoundMessage)->flash();
            $this->router->redirect($redirectTo);
        }

        $model = $modelClass::findById((int) $id);

        if (!$model) {
            $this->message->error($notFoundMessage)->flash();
            $this->router->redirect($redirectTo);
        }

        if (!empty($with)) {
            $model->with(...$with);
        }

        return $model;
    }

    public function install(): void
    {
        $envFilePath = __DIR__ . '/../../.env';
        $envFileExists = file_exists($envFilePath);

        // Verifica se está na rota de instalação
        if (str_contains($_SERVER['REQUEST_URI'], '/install')) {

            // Se já instalado, redireciona
            if ($envFileExists && ($_ENV['INSTALLED'] ?? null) === '1') {
                $this->message->info('O sistema já está instalado.')->flash();
                $this->router->redirect('auth.index');
            }

            return;
        }

        // Se ainda não instalado, redireciona para o install
        if (!$envFileExists || ($_ENV['INSTALLED'] ?? null) !== '1') {
            session_destroy();
            $this->message->warning('Realize a instalação para prosseguir')->flash();
            $this->router->redirect('install.form');
        }
    }
}
