<div class="text-center mb-4">
    <img src="<?= CONF_SYSTEM_LOGO ?>" alt="<?= CONF_SYSTEM_TITLE ?>">
</div>
<h1 class="h3 mb-3 fw-bolder text-center">Instalação</h1>

<form class="needs-validation"
      method="POST"
      action="<?= $router->url('install.process') ?>">
    <?= $csrf ?>

    <h5>Banco de Dados</h5>

    <div class="form-floating p-1">
        <input class="form-control" name="db_host" id="db_host" value="147.79.104.233" required>
        <label for="db_host">Endereço (host) do banco de dados</label>
    </div>

    <div class="form-floating p-1">
        <input class="form-control" name="db_port" id="db_port" value="3306" required>
        <label for="db_port">Porta do Banco</label>
    </div>

    <div class="form-floating p-1">
        <input class="form-control" name="db_name" id="db_name" value="kabum" required>
        <label for="db_name">Nome do Banco</label>
    </div>
    <div class="form-floating p-1">
        <input class="form-control" name="db_user" id="db_user" value="kabum" required>
        <label for="db_user">Usuário do Banco</label>
    </div>
    <div class="form-floating p-1">
        <input class="form-control" name="db_password" id="db_password" value="2cmPCt69pL>" required>
        <label for="db_password">Senha do Banco</label>
    </div>

    <div class="mt-4">
        <h5>Usuário Administrador (default)</h5>
    </div>

    <div class="form-floating p-1">
        <input class="form-control" name="admin_name" id="admin_name"
               value="<?= CONF_USER_ADMIN_TEST['name'] ?>" required>
        <label for="admin_name">Nome</label>
    </div>

    <div class="form-floating p-1">
        <input type="email" class="form-control" id="admin_email" name="admin_email"
               value="<?= CONF_USER_ADMIN_TEST['email'] ?>"
               required>
        <label for="admin_email">E-mail</label>
    </div>

    <div class="form-floating p-1">
        <input type="text" class="form-control" name="admin_password" id="admin_password"
               value="<?= CONF_USER_ADMIN_TEST['password'] ?>" required>
        <label for="admin_password">Senha para usar no sistema</label>
    </div>

    <div class="text-center my-2">
        <button class="btn btn-primary w-75" type="submit">
            <i data-feather="start"></i>
            Instalar
        </button>
    </div>
</form>