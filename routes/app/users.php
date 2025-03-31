<?php

use Source\Middlewares\AppMiddleware;

// Users
$router->get('/app/usuarios', 'App\UsersController', 'index', 'users.index', [AppMiddleware::class]);
$router->get('/app/usuarios/cadastrar', 'App\UsersController', 'create', 'users.create', [AppMiddleware::class]);
$router->get('/app/usuarios/editar/{userId}', 'App\UsersController', 'edit', 'users.edit', [AppMiddleware::class]);
$router->post('/app/usuarios/store', 'App\UsersController', 'store', 'users.store', [AppMiddleware::class]);
$router->post('/app/usuarios/update/{userId}', 'App\UsersController', 'update', 'users.update', [AppMiddleware::class]);
$router->post('/app/usuarios/destroy/{userId}', 'App\UsersController', 'destroy', 'users.destroy', [AppMiddleware::class]);