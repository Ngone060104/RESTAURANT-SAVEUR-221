<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\CommandeController;
use App\Controllers\PanierController;
use App\Controllers\ProduitController;
use App\Controllers\Gerant\CategorieController as GerantCategorieController;
use App\Controllers\Gerant\ProduitController as GerantProduitController;
use App\Controllers\Gerant\CommandeController as GerantCommandeController;
use App\Controllers\Gerant\PaiementController as GerantPaiementController;

use App\Middleware\AdminMiddleware;
use App\Middleware\ClientMiddleware;
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

    // Panier (feature/panier) - réservé aux clients connectés
    $router->get('/panier', [PanierController::class, 'index'], [ClientMiddleware::class]);
    $router->post('/panier/ajouter', [PanierController::class, 'ajouter'], [ClientMiddleware::class]);
    $router->post('/panier/quantite', [PanierController::class, 'modifierQuantite'], [ClientMiddleware::class]);
    $router->post('/panier/supprimer', [PanierController::class, 'supprimer'], [ClientMiddleware::class]);
    $router->post('/panier/vider', [PanierController::class, 'vider'], [ClientMiddleware::class]);

     // Commandes (feature/commandes) - client
    $router->post('/commandes/valider', [CommandeController::class, 'valider'], [ClientMiddleware::class]);
    $router->get('/commande', [CommandeController::class, 'show'], [ClientMiddleware::class]); // ?id=...
    $router->get('/mes-commandes', [CommandeController::class, 'historique'], [ClientMiddleware::class]);

    // Commandes - gérant
    $router->get('/gerant/commandes', [GerantCommandeController::class, 'index'], [GerantMiddleware::class]);
    $router->get('/gerant/commande', [GerantCommandeController::class, 'show'], [GerantMiddleware::class]); // ?id=...
    $router->post('/gerant/commandes/statut', [GerantCommandeController::class, 'changerStatut'], [
        GerantMiddleware::class,
    ]);
    $router->post('/gerant/commandes/annuler', [GerantCommandeController::class, 'annuler'], [
        GerantMiddleware::class,
    ]);

    
    // Paiements (feature/paiements) - gérant
    $router->get('/gerant/paiements', [GerantPaiementController::class, 'index'], [GerantMiddleware::class]);
    $router->get('/gerant/paiements/impayees', [GerantPaiementController::class, 'impayees'], [
        GerantMiddleware::class,
    ]);
    $router->get('/gerant/paiements/partielles', [GerantPaiementController::class, 'partielles'], [
        GerantMiddleware::class,
    ]);
    $router->post('/gerant/paiements', [GerantPaiementController::class, 'enregistrer'], [
        GerantMiddleware::class,
    ]);





};
