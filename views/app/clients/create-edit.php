<?php

use Source\Support\Request;

echo $breadcrumb ?? '';

?>

    <div class="container-fluid">
        <form method="POST" class="needs-validation"
              action="<?= empty($client) ? $router->url('clients.store') : $router->url('clients.update', [
                  'clientId' => $client->id
              ]) ?>">

            <?= $csrf ?>

            <div class="row">
                <div class="col">
                    <h1 class="mt-4">
                        <?= !empty($client) ? 'Edição de Cliente' : 'Cadastro de Cliente' ?>
                    </h1>
                </div>
            </div>

            <div class="mt-4">
                <h4 class="alert-heading">Preencha os dados do cliente</h4>
                <hr>
                <p>Os campos com * são obrigatórios.</p>
            </div>
            <div class="row">
                <div class="col-5">
                    <div class="form-floating my-3">
                        <input type="text"
                               class="form-control uppercase <?= Request::error('name') ? 'is-invalid' : null ?>"
                               id="name" name="name"
                               value="<?= Request::value('name') ?? $client?->person?->name ?? null ?>"
                               placeholder="Nome"
                        >
                        <label for="name">Nome <span class="fw-lighter">(*)</span></label>
                        <?= Request::error('name') ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating my-3">
                        <input type="text"
                               class="form-control date-mask <?= Request::error('birthdate') ? 'is-invalid' : null ?>"
                               id="birthdate" name="birthdate"
                               value="<?= Request::value('birthdate') ?? $client?->person?->birthdate ?? null ?>"
                               placeholder="Data de Nascimento"
                        >
                        <label for="birthdate">Data de Nascimento <span class="fw-lighter">(*)</span></label>
                        <?= Request::error('birthdate') ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating my-3">
                        <input type="text"
                               class="form-control cpf-mask <?= Request::error('document') ? 'is-invalid' : null ?>"
                               id="document" name="document"
                               value="<?= Request::value('document') ?? $client?->person?->document ?? null ?>"
                               placeholder="Número de CPF"
                        >
                        <label for="document">Número de CPF <span class="fw-lighter">(*)</span></label>
                        <?= Request::error('document') ?>
                    </div>
                </div>
                <div class="row">
                <div class="col">
                    <div class="form-floating my-3">
                        <input type="text"
                               class="form-control <?= Request::error('identity') ? 'is-invalid' : null ?>"
                               id="identity" name="identity"
                               value="<?= Request::value('identity') ?? $client?->person?->identity ?? null ?>"
                               placeholder="Número de Identidade"
                        >
                        <label for="identity">Número de Identidade <span class="fw-lighter">(*)</span></label>
                        <?= Request::error('identity') ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating my-3">
                        <input type="text"
                               class="form-control lowercase <?= Request::error('email') ? 'is-invalid' : null ?>"
                               id="email" name="email"
                               value="<?= Request::value('email') ?? $client?->person?->email ?? null ?>"
                               placeholder="E-mail"
                        >
                        <label for="email">E-mail</label>
                        <?= Request::error('email') ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating my-3">
                        <input type="text"
                               class="form-control phone-mask <?= Request::error('phone') ? 'is-invalid' : null ?>"
                               id="phone" name="phone"
                               value="<?= Request::value('phone') ?? $client?->person?->phone ?? null ?>"
                               placeholder="Telefone ou Celular"
                        >
                        <label for="identity">Telefone ou Celular</label>
                        <?= Request::error('phone') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-primary m-5">
                        <i class="i-small" data-feather="save"></i>
                        <?= !empty($client) ? 'Atualizar dados' : 'Cadastrar' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php if (!empty($client)) $view->insert('../app/clients/addresses/index');
