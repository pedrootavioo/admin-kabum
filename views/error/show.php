<div class="container text-center">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-8">
            <h1 class="display-1 fw-bold"><?= $errorCode ?></h1>
            <p class="fs-3"><span class="text-danger">Oops!</span> Página não encontrada.</p>
            <p class="lead">
                A página que você está procurando não foi encontrada.
            </p>
            <a href="<?= $router->url('app.index') ?>" class="btn btn-primary">Voltar para o Início</a>
        </div>
    </div>
</div>