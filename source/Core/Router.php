<?php

namespace Source\Core;

use JetBrains\PhpStorm\NoReturn;

class Router
{
    private array $routes = [];
    private string $basePath = '';
    private array $middlewares = [];

    public function __construct()
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $this->basePath = str_replace('/index.php', '', $scriptName);
    }

    private function addRoute(string $method, string $path, string $controller, string $action, ?string $name = null, array $middlewares = []): void
    {
        if (empty($path)) {
            $path = '/';
        }

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[strtoupper($method)][] = [
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action,
            'name' => $name,
            'path' => $path,
            'middlewares' => $middlewares
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

    public function use(string $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function dispatch(?string $uri = null): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $uri = $uri ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = '/' . ltrim(str_replace($this->basePath, '', $uri), '/');

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

                // Executa middlewares globais
                foreach ($this->middlewares as $middleware) {
                    if (class_exists($middleware)) {
                        (new $middleware())->handle($this);
                    }
                }

                // Executa middlewares específicos da rota
                if (!empty($route['middlewares'])) {
                    foreach ($route['middlewares'] as $middleware) {
                        if (class_exists($middleware)) {
                            (new $middleware())->handle($this);
                        }
                    }
                }

                $controllerName = 'Source\\Controllers\\' . $route['controller'];
                $action = $route['action'];

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

    public function getRouteByName(string $name): ?array
    {
        foreach ($this->routes as $routes) {
            foreach ($routes as $route) {
                if ($route['name'] === $name) {
                    return $route;
                }
            }
        }
        return null;
    }

    public function url(string $name, array $params = []): string
    {
        $route = $this->getRouteByName($name);
        if (!$route) {
            return '#';
        }

        $url = $route['path'];
        foreach ($params as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }

        return rtrim($this->basePath, '/') . $url;
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

    public function basePath(): string
    {
        return $this->basePath;
    }
}