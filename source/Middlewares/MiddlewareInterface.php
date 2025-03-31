<?php

namespace Source\Middlewares;

use Source\Core\Router;

interface MiddlewareInterface
{
    public function handle(Router $router): void;
}
