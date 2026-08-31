<?php

namespace App\Core;

use App\Exceptions\AppException;
use Throwable;

/**
 * Point d'entrée applicatif : construit le container, charge les routes
 * puis dispatch la requête courante. Sert de "Front Controller".
 */
class App
{
    private Router $router;

    public function __construct(private Container $container)
    {
        $this->router = new Router($this->container);
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        try {
            $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] ?? '/');
        } catch (AppException $e) {
            http_response_code(404);
            echo "Erreur : " . htmlspecialchars($e->getMessage());
        } catch (Throwable $e) {
            http_response_code(500);
            echo "Erreur serveur : " . htmlspecialchars($e->getMessage());
        }
    }
}
