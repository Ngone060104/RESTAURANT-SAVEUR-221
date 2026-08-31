<?php

use App\Core\Router;

/**
 * Toutes les routes de l'application PHP Web sont déclarées ici.
 * Ce fichier retourne une closure appelée avec le Router déjà prêt.
 *
 * Exemples à venir au fur et à mesure des sprints :
 *
 * $router->get('/', [HomeController::class, 'index']);
 * $router->get('/produits', [ProduitController::class, 'index']);
 * $router->post('/login', [AuthController::class, 'login']);
 * $router->get('/gerant/produits', [ProduitAdminController::class, 'index'], [
 *     RoleMiddleware::class,
 * ]);
 */
return function (Router $router): void {
    // Les routes seront ajoutées ici branche par branche
    // (feature/auth, feature/produits, feature/commandes...).
};
