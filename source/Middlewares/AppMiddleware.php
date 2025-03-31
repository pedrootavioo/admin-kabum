<?php

namespace Source\Middlewares;


use Source\Core\Router;
use Source\Support\Auth;
use Source\Support\Message;

class AppMiddleware implements MiddlewareInterface
{
    public function handle(Router $router): void
    {
        if (!Auth::check()) {
            (new Message())->error('Efetue login para acessar')->flash();
            $router->redirect('auth.index');
        }
    }
}