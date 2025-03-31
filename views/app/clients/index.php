<?= $breadcrumb ?? ''; ?>

<div class="container-fluid">

    <div class="row mb-1">
        <div class="col">
            <h1 class="mt-4">Clientes</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $router->url('clients.create') ?>" class="btn btn-primary mt-4">
                <i class="i-small" data-feather="plus"></i>
                Novo Cliente
            </a>
        </div>
    </div>

    <?php if (!empty($clients)): ?>
        <div class="card bg-body-tertiary px-1 py-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Nascimento</th>
                            <th scope="col">CPF</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Identidade</th>
                            <th scope="col">Telefone</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <th scope="row"><?= $client->id ?></th>
                                <td>
                                    <a href="<?= $router->url('clients.edit', ['clientId' => $client->id]) ?>">
                                        <?= $client->person->name ?>
                                    </a>
                                </td>
                                <td><?= $client->person->birthdate ?></td>
                                <td><?= $client->person->document ?></td>
                                <td><?= $client->person->email ?? 'Sem e-mail' ?></td>
                                <td><?= $client->person->identity ?></td>
                                <td><?= $client->person->phone ?></td>
                                <td>
                                    <form action="<?= $router->url('clients.destroy', [
                                        'clientId' => $client->id,
                                    ]) ?>" method="post">
                                        <?= $csrf ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="i-small" data-feather="trash"></i>
                                            Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    <?php else: ?>

        <div class="alert alert-warning mt-3" role="alert">
            <h4 class="alert-heading">Sem clientes cadastrados até o momento!</h4>
            <hr>
            <p>Clique no botão 'novo cliente' para adicionar.</p>
        </div>

    <?php endif; ?>

</div>