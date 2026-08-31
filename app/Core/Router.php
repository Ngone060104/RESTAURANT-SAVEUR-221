<?php

namespace App\Core;

use App\Exceptions\NotFoundException;

/**
 * Route les requêtes HTTP vers un [Controller, méthode], avec
 * middlewares optionnels (auth, rôle...).
 */
class Router
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    public function get(string $uri, array $action, array $middlewares = []): void
    {
        $this->addRoute('GET', $uri, $action, $middlewares);
    }

    public function post(string $uri, array $action, array $middlewares = []): void
    {
        $this->addRoute('POST', $uri, $action, $middlewares);
    }

    private function addRoute(string $method, string $uri, array $action, array $middlewares): void
    {
        $this->routes[$method][$this->normalize($uri)] = [
            'action' => $action,
            'middlewares' => $middlewares,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = $this->normalize(parse_url($uri, PHP_URL_PATH));
        $route = $this->routes[$method][$uri] ?? null;

        if ($route === null) {
            throw new NotFoundException("Route introuvable : {$method} {$uri}");
        }

        foreach ($route['middlewares'] as $middlewareClass) {
            /** @var \App\Interfaces\MiddlewareInterface $middleware */
            $middleware = $this->container->make($middlewareClass);

            if (!$middleware->handle()) {
                return; // le middleware a déjà géré la réponse (redirection, 403...)
            }
        }

        [$controllerClass, $action] = $route['action'];
        $controller = $this->container->make($controllerClass);
        $controller->$action();
    }

    private function normalize(string $uri): string
    {
        $uri = '/' . trim($uri, '/');

        return $uri === '//' ? '/' : $uri;
    }
}
