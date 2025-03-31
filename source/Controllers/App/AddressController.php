<?php

namespace Source\Controllers\App;

use Source\Core\Controller;
use Source\Models\Address;
use Source\Models\Client;
use Source\Support\Breadcrumb;
use Source\Support\Request;

class AddressController extends Controller
{
    public function create(?array $data): void
    {
        /** @var Client $client */
        if (!$client = $this->loadModel($data, Client::class, 'clientId', ['person'])) return;

        $this->view->render('app/clients/addresses/create-edit', [
                'title' => 'Cadastrar Endereço',
                'client' => $client,
                'address' => null,
                'breadcrumb' => new Breadcrumb()
                    ->add('Início', 'app.index')
                    ->add('Clientes', 'clients.index')
                    ->add($client->person->shortName(), 'clients.edit', [
                        'clientId' => $client->id,
                    ])
                    ->add('Cadastrar Endereço')
                    ->render($this->router)
            ]);
    }

    public function edit(?array $data): void
    {
        /** @var Client $client */
        if (!$client = $this->loadModel($data, Client::class, 'clientId', ['person'])) return;

        /** @var Address $address */
        if (!$address = $this->loadModel($data, Address::class, 'addressId')) return;

        $this->view->render('app/clients/addresses/create-edit', [
                'title' => "Editar Endereço - {$client->person->name}",
                'client' => $client,
                'address' => $address,
                'breadcrumb' => new Breadcrumb()
                    ->add('Início', 'app.index')
                    ->add('Clientes', 'clients.index')
                    ->add($client->person->shortName(), 'clients.edit', [
                        'clientId' => $client->id,
                    ])
                    ->add('Editar Endereço')
                    ->render($this->router)
            ]);
    }

    public function store(?array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        /** @var Client $client */
        if (!$client = $this->loadModel($data, Client::class, 'clientId', ['person'])) return;

        $address = new Address();

        if (!$address->persist($client->person, $data)) {

            // Flash no request para manter os dados do formulário
            Request::flashRequest($address->errors());

            $this->message->error($address?->fail()?->getMessage() ?? 'Erro ao gravar')->flash();
            $this->router->redirect('address.create', ['clientId' => $client->id]);
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

        /** @var Address $address */
        if (!$address = $this->loadModel($data, Address::class, 'addressId')) return;

        if (!$address->persist($client->person, $data)) {

            // Flash no request para manter os dados do formulário
            Request::flashRequest($address->errors());

            $this->message->error($address?->fail()?->getMessage() ?? 'Erro ao atualizar')->flash();
            $this->router->redirect('address.edit', [
                'clientId' => $client->id,
                'addressId' => $address->id,
            ]);
        }

        Request::clear();
        $this->message->success('Cadastro realizado com sucesso')->flash();
        $this->router->redirect('clients.edit', [
            'clientId' => $client->id,
        ]);
    }

    public function destroy(?array $data): void
    {
        if (!$this->csrfVerify($data)) return;

        /** @var Client $client */
        if (!$client = $this->loadModel($data, Client::class, 'clientId', ['person'])) return;

        /** @var Address $address */
        if (!$address = $this->loadModel($data, Address::class, 'addressId')) return;

        if (!$address->destroy()) {
            $this->message->error($address?->fail()?->getMessage() ?? 'Erro ao remover')->flash();
            $this->router->redirect('back');
        }

        Request::clear();
        $this->message->success('Endereço removido com sucesso')->flash();
        $this->router->redirect('clients.edit', [
            'clientId' => $client->id,
        ]);
    }
}