<?php

namespace Source\Controllers\App;

use Source\Core\Controller;
use Source\Models\User;
use Source\Support\Breadcrumb;
use Source\Support\Request;

class UsersController extends Controller
{
    public function index(): void
    {
        $this->view->render('app/users/index', [
            'title' => 'Usuários',
            'users' => User::query()->with('person')->get(),
            'breadcrumb' => new Breadcrumb()
                ->add('Início', 'app.index')
                ->add('Usuários')
                ->render($this->router)
        ]);
    }

    public function create(): void
    {
        $this->view->render('app/users/create-edit', [
            'title' => 'Novo Usuário',
            'user' => [],
            'breadcrumb' => new Breadcrumb()
                ->add('Início', 'app.index')
                ->add('Usuários', 'users.index')
                ->add('Novo Usuário')
                ->render($this->router)
        ]);
    }

    public function edit(?array $data): void
    {
        /** @var User $user */
        if (!$user = $this->loadModel($data, User::class, 'userId', ['person'])) return;

        $this->view->render('app/users/create-edit', [
            'title' => "Editar Usuário - {$user->person->name}",
            'user' => $user,
            'person' => $user->person,
            'addresses' => $user->person->addresses(),
            'breadcrumb' => new Breadcrumb()
                ->add('Início', 'app.index')
                ->add('Usuários', 'users.index')
                ->add('Editar Usuário')
                ->render($this->router)
        ]);
    }

    public function store(?array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        /** @var User $user */
        $user = new User();

        if (!$user->persist($data)) {

            // Flash no request para manter os dados do formulário
            Request::flashRequest($user->errors());

            $this->message->error($user?->fail()?->getMessage() ?? 'Erro ao gravar')->flash();
            $this->router->redirect('users.create');
        }

        Request::clear();
        $this->message->success('Cadastro realizado com sucesso')->flash();
        $this->router->redirect('users.edit', [
            'userId' => $user->id,
        ]);
    }

    public function update(?array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        /** @var User $user */
        if (!$user = $this->loadModel($data, User::class, 'userId')) return;

        if (!$user->persist($data)) {

            Request::flashRequest($user->errors());

            $this->message->error($user?->fail()?->getMessage() ?? 'Erro ao atualizar')->flash();
            $this->router->redirect('users.edit', [
                'userId' => $user->id,
            ]);
        }

        Request::clear();
        $this->message->success('Cadastro atualizado com sucesso')->flash();
        $this->router->redirect('users.edit', [
            'userId' => $user->id,
        ]);
    }

    public function destroy(?array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        /** @var User $user */
        if (!$user = $this->loadModel($data, User::class, 'userId', ['person'])) return;

        if (!$user->destroy()) {
            $this->message->error($user?->fail()?->getMessage() ?? 'Erro ao remover')->flash();
            $this->router->redirect('back');
        }

        Request::clear();
        $this->message->success('Usuário removido com sucesso')->flash();
        $this->router->redirect('users.index');
    }
}