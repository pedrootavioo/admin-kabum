<div class="container-fluid mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= $router->url('app.index') ?>">Início</a>
            </li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
    <div class="card bg-body-tertiary">
        <div class="card-body">
            <div class="jumbotron p-5">
                <h1 class="display-4 fw-medium">Olá, <?= $person?->shortName() ?? null ?></h1>

                <p class="lead">
                    Este breve MVC da <span class="fw-bold">"Área do guardião"</span> foi criado para fins de candidatura ao cargo de
                    desenvolvedor PHP backoffice na empresa
                    KaBuM.
                </p>
                <hr class="my-4">
                <p>Abaixo você poderá clicar para ir ao CRUD de clientes ou criar usuários guardiões</p>
                <a class="btn btn-primary btn-lg" href="<?= $router->url('clients.index') ?>" role="button">Clientes</a>
                <a class="btn btn-secondary btn-lg" href="<?= $router->url('users.index') ?>" role="button">Guardiões</a>
            </div>
        </div>
    </div>
</div>