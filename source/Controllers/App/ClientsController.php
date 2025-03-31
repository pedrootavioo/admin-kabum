<?php

namespace Source\Controllers\App;

use Source\Core\Controller;
use Source\Models\Client;
use Source\Support\Breadcrumb;
use Source\Support\Request;

class ClientsController extends Controller
{
    public function index(): void
    {
        $this->view->render('app/clients/index', [
            'title' => 'Clientes',
            'clients' => Client::query()->with('person')->get(),
            'breadcrumb' => new Breadcrumb()
                ->add('Início', 'app.index')
                ->add('Clientes')
                ->render($this->router)
        ]);
    }

    public function create(): void
    {
        $this->view->render('app/clients/create-edit', [
            'title' => 'Novo Cliente',
            'client' => [],
            'breadcrumb' => new Breadcrumb()
                ->add('Início', 'app.index')
                ->add('Clientes', 'clients.index')
                ->add('Novo Cliente')
                ->render($this->router)
        ]);
    }

    public function edit(?array $data): void
    {
        /** @var Client $client */
        if (!$client = $this->loadModel($data, Client::class, 'clientId', ['person'])) return;

        $this->view->render('app/clients/create-edit', [
            'title' => "Editar Cliente - {$client->person->name}",
            'client' => $client,
            'person' => $client->person,
            'addresses' => $client->person->addresses(),
            'breadcrumb' => new Breadcrumb()
                ->add('Início', 'app.index')
                ->add('Clientes', 'clients.index')
                ->add('Editar Cliente')
                ->render($this->router)
        ]);
    }

    public function store(?array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        /** @var Client $client */
        $client = new Client();

        if (!$client->persist($data)) {

            // Flash no request para manter os dados do formulário
            Request::flashRequest($client->errors());

            $this->message->error($client?->fail()?->getMessage() ?? 'Erro ao gravar')->flash();
            $this->router->redirect('clients.create');
        }

        Request::clear();
        $this->message->success('Cadastro realizado com sucesso')->flash();
        $this->router->redirect('clients.edit', [
            'clientId' => $client->id,
        ]);
    }

    public function update(?array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        /** @var Client $client */
        if (!$client = $this->loadModel($data, Client::class, 'clientId', ['person'])) return;

        if (!$client->persist($data)) {

            Request::flashRequest($client->errors());

            $this->message->error($client?->fail()?->getMessage() ?? 'Erro ao atualizar')->flash();
            $this->router->redirect('clients.edit', [
                'clientId' => $client->id,
            ]);
        }

        Request::clear();
        $this->message->success('Cadastro atualizado com sucesso')->flash();
        $this->router->redirect('clients.edit', [
            'clientId' => $client->id,
        ]);
    }

    public function destroy(?array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        /** @var Client $client */
        if (!$client = $this->loadModel($data, Client::class, 'clientId', ['person'])) return;

        if (!$client->destroy()) {
            $this->message->error($client?->fail()?->getMessage() ?? 'Erro ao remover')->flash();
            $this->router->redirect('back');
        }

        Request::clear();
        $this->message->success('Cliente removido com sucesso')->flash();
        $this->router->redirect('clients.index');
    }
}