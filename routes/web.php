<?php

use Source\Core\Router;
use Source\Middlewares\AppMiddleware;

$router = new Router();



/**
 * Auth
 */
$router->get('/login', 'AuthController', 'index', 'auth.index');
$router->post('/auth/login', 'AuthController', 'login', 'auth.login');
$router->get('/auth/logout', 'AuthController', 'logout', 'auth.logout');

/**
 * App
 */
$router->get('/app', 'App\AppController', 'index', 'app.index', [AppMiddleware::class]);

require_once __DIR__ . '/install.php';
require_once __DIR__ . '/app/users.php';
require_once __DIR__ . '/app/clients.php';
require_once __DIR__ . '/app/addresses.php';


/**
 * Errors
 */
$router->get('/error/{code}', 'ErrorController', 'show', 'error.show');

$router->dispatch();