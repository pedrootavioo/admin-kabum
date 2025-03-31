<div class="row">
    <div class="col">
        <h3 class="mt-2 fw-medium">
            <i data-feather="map-pin"></i>
            Endereços
        </h3>
    </div>
    <div class="col text-end">
        <a class="btn btn-primary"
           href="<?= $router->url('address.create', ['clientId' => $client->id]) ?>">
            <i class="i-small" data-feather="plus"></i>
            Novo endereço
        </a>
    </div>
</div>

<div class="card bg-body-tertiary p-2 container-fluid mt-1">
    <div class="card-body">

        <?php if (empty($addresses)): ?>
            <div class="alert alert-warning mt-3" role="alert">
                <h5 class="alert-heading">Sem registro(s) cadastrado(s) até o momento!</h5>
            </div>
        <?php endif; ?>

        <?php if (!empty($addresses)): ?>

            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">CEP</th>
                                <th scope="col">Logradouro</th>
                                <th scope="col">Nº</th>
                                <th scope="col">Bairro</th>
                                <th scope="col">Município</th>
                                <th scope="col">UF</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($addresses as $address): ?>
                                <tr>
                                    <th scope="row"><?= $address->id ?></th>
                                    <th><?= $address->zipcode ?></th>
                                    <td>
                                        <a href="<?= $router->url('address.edit', [
                                            'clientId' => $client->id,
                                            'addressId' => $address->id,
                                        ]) ?>">
                                            <?= $address->street ?>
                                        </a>
                                    </td>
                                    <td><?= $address->number ?></td>
                                    <td><?= $address->neighborhood ?></td>
                                    <td><?= $address->city ?></td>
                                    <td><?= $address->state ?></td>
                                    <td>
                                        <i class="i-small <?= $address->main ? 'text-warning' : '' ?>"
                                           data-feather="<?= $address->main ? 'star' : 'archive' ?>"></i>
                                    </td>
                                    <td>
                                        <form action="<?= $router->url('address.destroy', [
                                            'clientId' => $client->id,
                                            'addressId' => $address->id,
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
        <?php endif; ?>
    </div>
</div>
