<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Services\PanierService;

/**
 * Section "Client -> Panier" du cahier des charges : ajouter, modifier
 * la quantité, supprimer, vider, voir le total. Toutes les routes sont
 * protégées par ClientMiddleware (un visiteur non connecté n'a pas de
 * panier persistant).
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
