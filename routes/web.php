<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\ProduitController;
use App\Controllers\Gerant\CategorieController as GerantCategorieController;
use App\Controllers\Gerant\ProduitController as GerantProduitController;
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


     // Espace gérant - catégories (GERANT + ADMIN, règle métier n°14)
    $router->get('/gerant/categories', [GerantCategorieController::class, 'index'], [
        GerantMiddleware::class,
    ]);
    $router->post('/gerant/categories', [GerantCategorieController::class, 'store'], [
        GerantMiddleware::class,
    ]);
    $router->post('/gerant/categories/update', [GerantCategorieController::class, 'update'], [
        GerantMiddleware::class,
    ]);
    $router->post('/gerant/categories/delete', [GerantCategorieController::class, 'destroy'], [
        GerantMiddleware::class,
    ]);


      // Espace gérant - produits
    $router->get('/gerant/produits', [GerantProduitController::class, 'index'], [
        GerantMiddleware::class,
    ]);
    $router->post('/gerant/produits', [GerantProduitController::class, 'store'], [
        GerantMiddleware::class,
    ]);
    $router->post('/gerant/produits/update', [GerantProduitController::class, 'update'], [
        GerantMiddleware::class,
    ]);
    $router->post('/gerant/produits/delete', [GerantProduitController::class, 'destroy'], [
        GerantMiddleware::class,
    ]);
    $router->post('/gerant/produits/approvisionner', [GerantProduitController::class, 'approvisionner'], [
        GerantMiddleware::class,
    ]);

};
