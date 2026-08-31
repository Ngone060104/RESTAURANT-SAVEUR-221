<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\CategorieRepository;
use App\Repositories\ProduitRepository;

/**
 * Partie publique (visiteur) du catalogue - section VI, "Partie publique" :
 * voir les produits, rechercher, filtrer par catégorie, voir le détail.
 */
class ProduitController extends Controller
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private CategorieRepository $categorieRepository,
    ) {
    }

    public function index(): void
    {
        $categorieId = isset($_GET['categorie']) ? (int) $_GET['categorie'] : null;
        $terme = trim($_GET['q'] ?? '');

        if ($terme !== '') {
            $produits = $this->produitRepository->search($terme);
        } elseif ($categorieId !== null) {
            $produits = $this->produitRepository->findByCategorie($categorieId);
        } else {
            $produits = $this->produitRepository->findDisponibles();
        }

        $this->view('catalogue/index', [
            'produits' => $produits,
            'categories' => $this->categorieRepository->findAll(),
        ]);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $produit = $this->produitRepository->findProduitById($id);

        if ($produit === null) {
            throw new \App\Exceptions\NotFoundException('Produit introuvable.');
        }

        $this->view('catalogue/show', ['produit' => $produit]);
    }
}
