<?php

use Source\Middlewares\AppMiddleware;

// Addresses
$router->get('/app/clientes/{clientId}/enderecos/cadastrar', 'App\AddressController', 'create', 'address.create', [AppMiddleware::class]);
$router->post('/app/clientes/{clientId}/store', 'App\AddressController', 'store', 'address.store', [AppMiddleware::class]);
$router->get('/app/clientes/{clientId}/enderecos/editar/{addressId}', 'App\AddressController', 'edit', 'address.edit', [AppMiddleware::class]);
$router->post('/app/clientes/{clientId}/enderecos/update/{addressId}', 'App\AddressController', 'update', 'address.update', [AppMiddleware::class]);
$router->post('/app/clientes/{clientId}/enderecos/destroy/{addressId}', 'App\AddressController', 'destroy', 'address.destroy', [AppMiddleware::class]);