<?php

namespace App\Core;

use App\Exceptions\NotFoundException;
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
        } catch (NotFoundException $e) {
            http_response_code(404);
            View::render('errors/404');
        } catch (Throwable $e) {
            // On n'affiche JAMAIS $e->getMessage() ici : une erreur PDO
            // pourrait révéler des détails sensibles (requête SQL,
            // structure de la base...) à un visiteur.
            http_response_code(500);
            View::render('errors/500');
        }
    }
}
