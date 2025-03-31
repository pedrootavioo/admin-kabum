<?php

use Source\Support\Request;

echo $breadcrumb ?? '';

?>

<div class="container-fluid">

    <div class="row">
        <div class="col">
            <h4 class="mt-4"><?= $client->person->name ?></h4>
        </div>
    </div>

    <hr>

    <div class="mt-3" id="addressForm">
        <div class="row">
            <form method="POST" class="needs-validation"
                  action="<?= empty($address) ? $router->url('address.store', ['clientId' => $client->id])
                      : $router->url('address.update', [
                          'clientId' => $client->id,
                          'addressId' => $address?->id ?? 'none'
                      ]) ?>">
                <?= $csrf ?>
                <div class="row">
                    <div class="col">
                        <h2 class="mt-4">
                            <?= empty($address) ? 'Cadastro' : 'Edição' ?> de Endereço
                        </h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2">
                        <div class="form-floating my-2">
                            <input type="text"
                                   class="form-control zipcode-mask <?= Request::error('zipcode') ? 'is-invalid' : '' ?>"
                                   id="zipcode" name="zipcode"
                                   value="<?= Request::value('zipcode') ?? $address?->zipcode ?? '' ?>"
                                   placeholder="CEP">
                            <label for="zipcode">CEP <span class="fw-lighter">(*)</span></label>
                            <?= Request::error('zipcode') ?>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating my-2">
                            <input type="text"
                                   class="form-control uppercase <?= Request::error('street') ? 'is-invalid' : '' ?>"
                                   id="street" name="street"
                                   value="<?= Request::value('street') ?? $address?->street ?? '' ?>"
                                   placeholder="Logradouro (rua, avenida, estrada)">
                            <label for="street">Logradouro (rua, avenida, estrada) <span
                                        class="fw-lighter">(*)</span></label>
                            <?= Request::error('street') ?>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating my-2">
                            <input type="text"
                                   class="form-control <?= Request::error('number') ? 'is-invalid' : '' ?>"
                                   id="number" name="number"
                                   value="<?= Request::value('number') ?? $address?->number ?? '' ?>"
                                   placeholder="Número">
                            <label for="number">Número <span class="fw-lighter">(*)</span></label>
                            <?= Request::error('number') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="form-floating my-2">
                            <input type="text"
                                   class="form-control uppercase <?= Request::error('neighborhood') ? 'is-invalid' : '' ?>"
                                   id="neighborhood" name="neighborhood"
                                   value="<?= Request::value('neighborhood') ?? $address?->neighborhood ?? '' ?>"
                                   placeholder="Bairro">
                            <label for="neighborhood">Bairro <span class="fw-lighter">(*)</span></label>
                            <?= Request::error('neighborhood') ?>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating my-2">
                            <input type="text"
                                   class="form-control uppercase <?= Request::error('complement') ? 'is-invalid' : '' ?>"
                                   id="complement" name="complement"
                                   value="<?= Request::value('complement') ?? $address?->complement ?? '' ?>"
                                   placeholder="Complemento">
                            <label for="complement">Complemento</label>
                            <?= Request::error('complement') ?>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating my-2">
                            <select class="form-control uppercase <?= Request::error('state') ? 'is-invalid' : '' ?>"
                                    name="state" id="state" data-selected="<?= $address?->state ?? '' ?>">
                                <?php if (!empty($address)): ?>
                                    <option value="<?= $address->state ?>" selected><?= $address->state ?></option>
                                <?php else: ?>
                                    <option value="">Selecionar</option>
                                <?php endif; ?>
                            </select>
                            <label for="state">Estado (UF) <span class="fw-lighter">(*)</span></label>
                            <?= Request::error('state') ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating my-2">
                            <select class="form-control uppercase <?= Request::error('city') ? 'is-invalid' : '' ?>"
                                    name="city" id="city" data-selected="<?= $address?->city ?? '' ?>">
                                <?php if (!empty($address)): ?>
                                    <option value="<?= $address->city ?>" selected><?= $address->city ?></option>
                                <?php else: ?>
                                    <option value="">Selecionar</option>
                                <?php endif; ?>
                            </select>
                            <label for="city">Município <span class="fw-lighter">(*)</span></label>
                            <?= Request::error('city') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group mt-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="main" name="main"
                                <?= empty($address) || $address->main ? 'checked' : '' ?>>
                            <label class="form-check-label fw-medium" for="main">
                                Marcar como endereço principal
                                <i class="i-small text-warning" data-feather="star"></i>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col text-center">
                        <input type="hidden" name="ibge_code" id="ibge_code" value="<?= $address->ibge_code ?? '' ?>">
                        <input type="hidden" name="state_code" id="state_code"
                               value="<?= $address->state_code ?? '' ?>">
                        <button type="submit" class="btn btn-primary m-5">
                            <i class="i-small" data-feather="save"></i>
                            <?= empty($address) ? 'Cadastrar' : 'Atualizar dados' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
