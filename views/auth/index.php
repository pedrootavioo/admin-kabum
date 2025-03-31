<?php

use Source\Support\Request;

?>

<form method="POST" class="needs-validation"
      action="<?= $router->url('auth.login') ?>">
    <?= $csrf ?>
    <div class="text-center mb-4">
        <img src="<?= CONF_SYSTEM_LOGO ?>" alt="<?= CONF_SYSTEM_TITLE ?>">
    </div>
    <h1 class="h3 mb-3 fw-bolder text-center">Acessar</h1>

    <div class="form-floating my-3">
        <input type="email"
               class="form-control lowercase <?= Request::error('email') ? 'is-invalid' : null ?>"
               id="email" name="email"
               value="<?= Request::value('email') ?>"
                placeholder="E-mail"
        >
        <label for="email">E-mail</label>
        <?= Request::error('email') ?>
    </div>
    <div class="form-floating my-3">
        <input type="password"
               class="form-control <?= Request::error('password') ? 'is-invalid' : null ?>"
               id="password" name="password"
               placeholder="Senha"
        >
        <label for="password">Senha</label>
        <?= Request::error('password') ?>
    </div>

    <button class="btn btn-primary w-100 py-2" type="submit">
        <i data-feather="log-in"></i>
        Acessar
    </button>
    <p class="mt-5 mb-3 text-body-secondary text-center text-light">BackOffice © <?= date("Y") ?></p>
</form>