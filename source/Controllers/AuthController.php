<?php

namespace Source\Controllers;

use Source\Core\Controller;
use Source\Support\Auth;
use Source\Support\Request;

class AuthController extends Controller
{
    public function index(): void
    {
        $this->view
            ->template('single')
            ->render('auth/index', [
                'title' => 'Login',
            ]);
    }

    public function login(?array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($data['password'], FILTER_DEFAULT);

        $auth = new Auth();

        if (!$auth->attempt($email, $password)) {

            // Flash no request para manter os dados do formulário
            Request::flashRequest($auth->errors());

            $this->message->error($auth->fail()->getMessage())->flash();
            $this->router->redirect('back');
        }

        Request::clear();
        $this->message->success('Login realizado com sucesso')->flash();
        $this->router->redirect('app.index');
    }

    public function logout(): void
    {
        Auth::logout();

        $this->message->info('Logout feito com sucesso')->flash();
        $this->router->redirect('auth.index');
    }
}