<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\ProduitController;
use App\Middleware\AdminMiddleware;
use App\Middleware\GerantMiddleware;

/**
 * Toutes les routes de l'application PHP Web sont déclarées ici.
 * Ce fichier retourne une closure appelée avec le Router déjà prêt.
 */
return function (Router $router): void {
    // Authentification (feature/auth)
    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/login', [AuthController::class, 'login']);
    $router->post('/logout', [AuthController::class, 'logout']);

     // Catalogue public (feature/produits)
    $router->get('/produits', [ProduitController::class, 'index']);
    $router->get('/produit', [ProduitController::class, 'show']); // ?id=...
};
