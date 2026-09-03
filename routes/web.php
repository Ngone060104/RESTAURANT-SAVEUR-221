<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\AvisController;
use App\Controllers\CommandeController;
use App\Controllers\HomeController;
use App\Controllers\PanierController;
use App\Controllers\ProduitController;
use App\Controllers\ProfilController;

use App\Controllers\Admin\AvisController as AdminAvisController;
use App\Controllers\Admin\ClientController as AdminClientController;
use App\Controllers\Admin\UtilisateurController as AdminUtilisateurController;
use App\Controllers\Gerant\CategorieController as GerantCategorieController;
use App\Controllers\Gerant\CommandeController as GerantCommandeController;
use App\Controllers\Gerant\PaiementController as GerantPaiementController;
use App\Controllers\Gerant\ProduitController as GerantProduitController;

use App\Middleware\AdminMiddleware;
use App\Middleware\ClientMiddleware;
use App\Middleware\GerantMiddleware;

/**
 * Toutes les routes de l'application PHP Web sont déclarées ici.
 * Ce fichier retourne une closure appelée avec le Router déjà prêt.
 */
return function (Router $router): void {

    // Accueil (feature/vues-accueil)
    $router->get('/', [HomeController::class, 'index']);

    // Authentification (feature/auth)
    $router->get('/login', [AuthController::class, 'showLogin']);
    $router->get('/register', [AuthController::class, 'showRegister']);
    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/login', [AuthController::class, 'login']);
    $router->post('/logout', [AuthController::class, 'logout']);

// Catalogue public (feature/produits)

$router->get('/produits', [ProduitController::class, 'index']);

$router->get('/produits/categorie/{id}', [ProduitController::class, 'byCategorie']);

$router->get('/produits/recherche/{terme}', [ProduitController::class, 'search']);

$router->get('/produits/statut/{statut}', [ProduitController::class, 'byStatut']);

$router->get('/produit/{id}', [ProduitController::class, 'show']);


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

    // Avis (feature/avis) - client
    $router->post('/avis', [AvisController::class, 'store'], [ClientMiddleware::class]);

    // Avis - admin (règle métier n°13 : réservé à ADMIN, pas au gérant)
    $router->get('/admin/avis', [AdminAvisController::class, 'index'], [AdminMiddleware::class]);
    $router->post('/admin/avis/delete', [AdminAvisController::class, 'destroy'], [AdminMiddleware::class]);


    // Profil (feature/profil) - client
    $router->get('/profil', [ProfilController::class, 'show'], [ClientMiddleware::class]);
    $router->post('/profil', [ProfilController::class, 'update'], [ClientMiddleware::class]);
    $router->post('/profil/mot-de-passe', [ProfilController::class, 'changerMotDePasse'], [
        ClientMiddleware::class,
    ]);


    // Utilisateurs internes (feature/admin) - réservé à ADMIN
    $router->get('/admin/utilisateurs', [AdminUtilisateurController::class, 'index'], [AdminMiddleware::class]);
    $router->post('/admin/utilisateurs', [AdminUtilisateurController::class, 'store'], [AdminMiddleware::class]);
    $router->post('/admin/utilisateurs/update', [AdminUtilisateurController::class, 'update'], [
        AdminMiddleware::class,
    ]);
    $router->post('/admin/utilisateurs/delete', [AdminUtilisateurController::class, 'destroy'], [
        AdminMiddleware::class,
    ]);
    $router->post('/admin/utilisateurs/activer', [AdminUtilisateurController::class, 'activer'], [
        AdminMiddleware::class,
    ]);
    $router->post('/admin/utilisateurs/desactiver', [AdminUtilisateurController::class, 'desactiver'], [
        AdminMiddleware::class,
    ]);

    // Clients (lecture seule) - réservé à ADMIN
    $router->get('/admin/clients', [AdminClientController::class, 'index'], [AdminMiddleware::class]);
    $router->get('/admin/client', [AdminClientController::class, 'show'], [AdminMiddleware::class]); // ?id=...





};
