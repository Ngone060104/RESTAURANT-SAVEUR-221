<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Services\PanierService;

/**
 * Gestion du panier.
 *
 * Le panier est accessible aux visiteurs et aux clients.
 * Il est stocké en session et peut être consulté, modifié ou vidé
 * sans authentification.
 *
 * L'authentification est demandée uniquement lors de la validation
 * de la commande.
 */
class PanierController extends Controller
{
    public function __construct(private PanierService $panierService)
    {
    }

    public function index(): void
    {
        $this->view('panier/index', [
            'lignes' => $this->panierService->getLignes(),
            'total' => $this->panierService->getTotal(),
        ]);
    }

    public function ajouter(): void
    {
        try {
            $produitId = (int) ($_POST['produit_id'] ?? 0);
            $quantite = (int) ($_POST['quantite'] ?? 1);

            $this->panierService->ajouter($produitId, $quantite);
            $this->redirect('/panier');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function modifierQuantite(): void
    {
        try {
            $produitId = (int) ($_POST['produit_id'] ?? 0);
            $quantite = (int) ($_POST['quantite'] ?? 0);

            $this->panierService->modifierQuantite($produitId, $quantite);
            $this->redirect('/panier');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function supprimer(): void
    {
        $produitId = (int) ($_POST['produit_id'] ?? 0);
        $this->panierService->supprimer($produitId);
        $this->redirect('/panier');
    }

    public function vider(): void
    {
        $this->panierService->vider();
        $this->redirect('/panier');
    }
}
