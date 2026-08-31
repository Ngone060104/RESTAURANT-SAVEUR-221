<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;
use App\Repositories\ProduitRepository;
use App\Services\ProduitService;

/**
 * CRUD complet des produits + gestion du stock - section "ESPACE GÉRANT".
 * Contrairement à App\Controllers\ProduitController (public, ne montre
 * que les produits disponibles), ici le gérant voit TOUT (y compris
 * en rupture) et peut créer/modifier/supprimer/approvisionner.
 */
class ProduitController extends Controller
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private CategorieRepository $categorieRepository,
        private ProduitService $produitService,
    ) {
    }

    public function index(): void
    {
        $categorieId = isset($_GET['categorie']) ? (int) $_GET['categorie'] : null;
        $statut = $_GET['statut'] ?? null;
        $terme = trim($_GET['q'] ?? '');

        if ($terme !== '') {
            $produits = $this->produitRepository->search($terme);
        } else {
            $produits = $this->produitRepository->filter($categorieId, $statut);
        }

        $this->view('gerant/produits/index', [
            'produits' => $produits,
            'categories' => $this->categorieRepository->findAll(),
        ]);
    }

    public function store(): void
    {
        try {
            $this->produitService->create($_POST);
            $this->redirect('/gerant/produits');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function update(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $this->produitService->update($id, $_POST);
            $this->redirect('/gerant/produits');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function destroy(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $this->produitService->delete($id);
            $this->redirect('/gerant/produits');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    /**
     * Approvisionnement (section V/VI : "approvisionner un produit",
     * "augmenter la quantité"). Le statut disponible/en_rupture est
     * recalculé automatiquement par ProduitService.
     */
    public function approvisionner(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $quantite = (int) ($_POST['quantite'] ?? 0);
            $this->produitService->approvisionner($id, $quantite);
            $this->redirect('/gerant/produits');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }
}
