<?php

use Source\Middlewares\AppMiddleware;

// Clients
$router->get('/app/clientes', 'App\ClientsController', 'index', 'clients.index', [AppMiddleware::class]);
$router->get('/app/clientes/cadastrar', 'App\ClientsController', 'create', 'clients.create', [AppMiddleware::class]);
$router->get('/app/clientes/editar/{clientId}', 'App\ClientsController', 'edit', 'clients.edit', [AppMiddleware::class]);
$router->post('/app/clients/store', 'App\ClientsController', 'store', 'clients.store', [AppMiddleware::class]);
$router->post('/app/clients/update/{clientId}', 'App\ClientsController', 'update', 'clients.update', [AppMiddleware::class]);
$router->post('/app/clients/destroy/{clientId}', 'App\ClientsController', 'destroy', 'clients.destroy', [AppMiddleware::class]);