<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\CategorieRepository;
use App\Repositories\ProduitRepository;

/**
 * Partie publique (visiteur) du catalogue :
 * voir les produits, rechercher, filtrer par catégorie,
 * filtrer par disponibilité, voir le détail.
 */
class ProduitController extends Controller
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private CategorieRepository $categorieRepository,
    ) {
    }

    /**
     * Catalogue général
     *
     * Exemple :
     * /produits
     */
    public function index(): void
    {
        $produits = $this->produitRepository->findCatalogue();

        $this->view('catalogue/index', [
            'produits' => $produits,
            'categories' => $this->categorieRepository->findAll(),
            'categorieActive' => null,
        ]);
    }

    /**
     * Catalogue filtré par catégorie
     *
     * Exemple :
     * /produits/categorie/5
     */
    public function byCategorie(int $id): void
    {
        $produits = $this->produitRepository->findCatalogue(
            $id,
            null,
            null
        );

        $this->view('catalogue/index', [
            'produits' => $produits,
            'categories' => $this->categorieRepository->findAll(),
            'categorieActive' => $id,
        ]);
    }

    /**
     * Catalogue recherché par terme
     *
     * Exemple :
     * /produits/recherche/yassa
     */
    public function search(string $terme): void
    {
        $terme = trim(urldecode($terme));

        $produits = $this->produitRepository->findCatalogue(
            null,
            null,
            $terme
        );

        $this->view('catalogue/index', [
            'produits' => $produits,
            'categories' => $this->categorieRepository->findAll(),
            'categorieActive' => null,
            'termeActuel' => $terme,
        ]);
    }

    /**
     * Catalogue filtré par disponibilité
     *
     * Exemples :
     * /produits/statut/disponible
     * /produits/statut/en_rupture
     */
    public function byStatut(string $statut): void
    {
        if (!in_array($statut, ['disponible', 'en_rupture'], true)) {
            throw new \App\Exceptions\NotFoundException(
                'Statut de produit invalide.'
            );
        }

        $produits = $this->produitRepository->findCatalogue(
            null,
            $statut,
            null
        );

        $this->view('catalogue/index', [
            'produits' => $produits,
            'categories' => $this->categorieRepository->findAll(),
            'categorieActive' => null,
            'statutActuel' => $statut,
        ]);
    }

    /**
     * Détail d'un produit
     *
     * Exemple :
     * /produit/12
     */
    public function show(int $id): void
    {
        $produit = $this->produitRepository->findProduitById($id);

        if ($produit === null) {
            throw new \App\Exceptions\NotFoundException(
                'Produit introuvable.'
            );
        }

        $this->view('catalogue/show', [
            'produit' => $produit,
        ]);
    }
}