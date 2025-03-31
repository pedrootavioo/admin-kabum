<?= $breadcrumb ?? ''; ?>

<div class="container-fluid">

    <div class="row mb-1">
        <div class="col">
            <h1 class="mt-4">
                Usuários Guardiões
            </h1>
        </div>
        <div class="col text-end">
            <a href="<?= $router->url('users.create') ?>" class="btn btn-primary mt-4">
                <i class="i-small" data-feather="plus"></i>
                Novo Usuário
            </a>
        </div>
    </div>

    <?php if (!empty($users)): ?>
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
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <th scope="row"><?= $user->id ?></th>
                                <td>
                                    <a href="<?= $router->url('users.edit', ['userId' => $user->id]) ?>">
                                        <?= $user->person->name ?>
                                    </a>
                                </td>
                                <td><?= $user->person->birthdate ?></td>
                                <td><?= $user->person->document ?></td>
                                <td><?= $user->person->email ?? 'Sem e-mail' ?></td>
                                <td><?= $user->person->identity ?></td>
                                <td><?= $user->person->phone ?></td>
                                <td>
                                    <form action="<?= $router->url('users.destroy', [
                                        'userId' => $user->id,
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
            <h4 class="alert-heading">Sem usuários cadastrados até o momento!</h4>
            <hr>
            <p>Clique no botão 'novo usuário' para adicionar.</p>
        </div>

    <?php endif; ?>

</div>