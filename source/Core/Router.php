<?php

namespace Source\Core;

use JetBrains\PhpStorm\NoReturn;
use Source\Middlewares\MiddlewareInterface;

class Router
{
    private array $routes = [];

    /**
     * Adiciona uma rota para o método HTTP especificado.
     */
    private function addRoute(
        string $method,
        string $path,
        string $controller,
        string $action,
        ?string $name = null,
        array $middlewares = []
    ): void {
        if (empty($path)) $path = '/';
        if ($path[0] !== '/') $path = '/' . $path;

        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[strtoupper($method)][] = [
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action,
            'name' => $name,
            'middlewares' => $middlewares,
            'path' => $path
        ];
    }

    public function get(string $path, string $controller, string $action, ?string $name = null, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $controller, $action, $name, $middlewares);
    }

    public function post(string $path, string $controller, string $action, ?string $name = null, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $controller, $action, $name, $middlewares);
    }

    public function put(string $path, string $controller, string $action, ?string $name = null, array $middlewares = []): void
    {
        $this->addRoute('PUT', $path, $controller, $action, $name, $middlewares);
    }

    public function patch(string $path, string $controller, string $action, ?string $name = null, array $middlewares = []): void
    {
        $this->addRoute('PATCH', $path, $controller, $action, $name, $middlewares);
    }

    public function delete(string $path, string $controller, string $action, ?string $name = null, array $middlewares = []): void
    {
        $this->addRoute('DELETE', $path, $controller, $action, $name, $middlewares);
    }

    /**
     * Despacha a rota de acordo com o URI atual e o método HTTP.
     * Os parâmetros extraídos da URL são agrupados em um array $data
     * e passados como único parâmetro para o método do Controller.
     */
    public function dispatch(?string $uri = null): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $uri ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');

        if (!isset($this->routes[$method])) {
            $this->redirect('error.show', ['code' => 404]);
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $data = $params;

                if ($method === 'POST') {
                    $data = array_merge($data, $_POST);
                } elseif (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
                    parse_str(file_get_contents('php://input'), $input);
                    $data = array_merge($data, $input);
                }

                $controllerName = 'Source\\Controllers\\' . $route['controller'];
                $action = $route['action'];

                // Execução dos middlewares antes da ação
                foreach ($route['middlewares'] as $middlewareClass) {
                    /** @var MiddlewareInterface $middleware */
                    $middleware = new $middlewareClass();
                    $middleware->handle($this);
                }

                if (class_exists($controllerName)) {
                    $controller = new $controllerName($this);
                    if ($controller instanceof Controller) {
                        $controller->setRouter($this);
                    }
                    if (method_exists($controller, $action)) {
                        call_user_func([$controller, $action], $data);
                        return;
                    }
                }
            }
        }

        $this->redirect('error.show', ['code' => 404]);
    }


    /**
     * Recupera a rota pelo alias (name).
     */
    public function getRouteByName(string $name): ?array
    {
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $route) {
                if ($route['name'] === $name) {
                    return $route;
                }
            }
        }
        return null;
    }

    /**
     * Retorna a URL de uma rota pelo seu alias.
     */
    public function url(string $name, array $params = []): string
    {
        $route = $this->getRouteByName($name);
        if (!$route) {
            return '#';
        }
        $url = $route['path'];

        // Substitui os placeholders pelos valores informados
        foreach ($params as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }
        return $url;
    }

    #[NoReturn] public function redirect(string $name, array $params = [], int $code = 302): void
    {
        $url = match ($name) {
            'back' => $_SERVER['HTTP_REFERER'] ?? '/',
            default => $this->url($name, $params),
        };

        if ($url === '#') {
            die("Rota não encontrada para o alias: " . $name);
        }

        header("Location: " . $url, true, $code);
        exit();
    }
}
