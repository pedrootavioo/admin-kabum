<?php

use Source\Support\Request;

echo $breadcrumb ?? '';

?>

<div class="container-fluid">
    <form method="POST" class="needs-validation"
          action="<?= empty($user) ? $router->url('users.store') : $router->url('users.update', [
              'userId' => $user->id
          ]) ?>">

        <?= $csrf ?>

        <div class="row">
            <div class="col">
                <h1 class="mt-4">
                    <?= !empty($user) ? 'Edição de Usuário' : 'Cadastro de Usuário' ?>
                </h1>
            </div>
        </div>

        <div class="mt-4">
            <h4 class="alert-heading">Preencha os dados do usuário</h4>
            <hr>
            <p>Os campos com * são obrigatórios.</p>
        </div>
        <div class="row">
            <div class="col-5">
                <div class="form-floating my-3">
                    <input type="text"
                           class="form-control uppercase <?= Request::error('name') ? 'is-invalid' : null ?>"
                           id="name" name="name"
                           value="<?= Request::value('name') ?? $user?->person?->name ?? null ?>"
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
                           value="<?= Request::value('birthdate') ?? $user?->person?->birthdate ?? null ?>"
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
                           value="<?= Request::value('document') ?? $user?->person?->document ?? null ?>"
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
                               value="<?= Request::value('identity') ?? $user?->person?->identity ?? null ?>"
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
                               value="<?= Request::value('email') ?? $user?->person?->email ?? null ?>"
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
                               value="<?= Request::value('phone') ?? $user?->person?->phone ?? null ?>"
                               placeholder="Telefone ou Celular"
                        >
                        <label for="identity">Telefone ou Celular</label>
                        <?= Request::error('phone') ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-floating my-3">
                        <input type="password"
                               class="form-control <?= Request::error('password') ? 'is-invalid' : null ?>"
                               id="password" name="password"
                               value="<?= Request::value('password') ?? null ?>"
                               placeholder="Senha"
                        >
                        <label for="password">Senha <span class="fw-lighter">(*)</span></label>
                        <?= Request::error('password') ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating my-3">
                        <input type="password"
                               class="form-control <?= Request::error('confirm_password') ? 'is-invalid' : null ?>"
                               id="confirm_password" name="confirm_password"
                               value="<?= Request::value('confirm_password') ?? null ?>"
                               placeholder="Confirmar Senha"
                        >
                        <label for="confirm_password">Confirmar Senha <span class="fw-lighter">(*)</span></label>
                        <?= Request::error('confirm_password') ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-primary m-5">
                        <i class="i-small" data-feather="save"></i>
                        <?= !empty($user) ? 'Atualizar dados' : 'Cadastrar' ?>
                    </button>
                </div>
            </div>
    </form>
</div>
