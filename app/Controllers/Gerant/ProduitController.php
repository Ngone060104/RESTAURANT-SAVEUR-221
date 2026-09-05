<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;
use App\Repositories\ProduitRepository;
use App\Services\ProduitService;

class ProduitController extends Controller
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private CategorieRepository $categorieRepository,
        private ProduitService $produitService,
    ) {}

    /**
     * Liste des produits
     *
     * GET /gerant/produits
     */
    public function index(): void
    {
        $this->afficherListe();
    }


    /**
     * Recherche des produits.
     *
     * GET /gerant/produits/recherche/{terme}
     */
    public function recherche(string $terme): void
    {
        $terme = trim($terme);

        if ($terme === '') {
            $this->redirect('/gerant/produits');
            return;
        }

        $produits = $this->produitRepository->findCatalogue(
            null,
            null,
            $terme
        );

        $this->renderIndex([
            'produits' => $produits,
            'categories' => $this->categorieRepository->findAll(),
            'terme' => $terme,
            'categorieActive' => null,
            'statutActive' => '',
        ]);
    }

    /**
     * Produits d'une catégorie.
     *
     * GET /gerant/produits/categorie/{id}
     */
    public function byCategorie(int $id): void
    {
        if ($id <= 0) {
            $this->redirect('/gerant/produits');
            return;
        }

        $produits = $this->produitRepository->findCatalogue(
            $id,
            null,
            null
        );

        $this->renderIndex([
            'produits' => $produits,
            'categories' => $this->categorieRepository->findAll(),
            'terme' => '',
            'categorieActive' => $id,
            'statutActive' => '',
        ]);
    }

    /**
     * Produits selon leur disponibilité.
     *
     * GET /gerant/produits/statut/{statut}
     */
    public function byStatut(string $statut): void
    {
        if (!in_array(
            $statut,
            ['disponible', 'en_rupture'],
            true
        )) {
            $this->redirect('/gerant/produits');
            return;
        }

        $produits = $this->produitRepository->findCatalogue(
            null,
            $statut,
            null
        );

        $this->renderIndex([
            'produits' => $produits,
            'categories' => $this->categorieRepository->findAll(),
            'terme' => '',
            'categorieActive' => null,
            'statutActive' => $statut,
        ]);
    }

    /**
     * Ouvre le modal de modification.
     *
     * GET /gerant/produits/update/{id}
     */
    public function edit(int $id): void
    {
        $produit = $this->produitRepository->findProduitById($id);

        if ($produit === null) {
            $this->redirect('/gerant/produits');
            return;
        }

        $this->afficherListe([
            'editId' => $id,
            'produitEdition' => $produit,
            'form' => [
                'nom' => $produit->getNom(),
                'description' => $produit->getDescription() ?? '',
                'prix' => $produit->getPrix(),
                'stock' => $produit->getStock(),
                'image' => $produit->getImage() ?? '',
                'categorie_id' => $produit->getCategorieId(),
            ],
        ]);
    }

    /**
     * Création d'un produit.
     *
     * POST /gerant/produits
     */
    public function store(): void
    {
        try {
            $this->produitService->create($_POST);

            $this->redirect('/gerant/produits');
        } catch (ValidationException $e) {
            http_response_code(422);

            $this->renderIndexAvecErreur(
                $e,
                $_POST,
                null
            );
        }
    }

    /**
     * Modification d'un produit.
     *
     * POST /gerant/produits/update/{id}
     */
    public function update(int $id): void
    {
        try {
            $this->produitService->update(
                $id,
                $_POST
            );

            $this->redirect('/gerant/produits');
        } catch (ValidationException $e) {
            http_response_code(422);

            $this->renderIndexAvecErreur(
                $e,
                $_POST,
                $id
            );
        }
    }

    /**
     * Ouvre le modal de confirmation de suppression.
     *
     * GET /gerant/produits/delete/{id}
     */
    public function confirmDelete(int $id): void
    {
        $produit = $this->produitRepository->findProduitById($id);

        if ($produit === null) {
            $this->redirect('/gerant/produits');
            return;
        }

        $this->afficherListe([
            'deleteId' => $id,
            'produitSuppression' => $produit,
        ]);
    }

    /**
     * Suppression réelle.
     *
     * POST /gerant/produits/delete/{id}
     */
    public function destroy(int $id): void
    {
        try {
            $this->produitService->delete($id);

            $this->redirect('/gerant/produits');
        } catch (ValidationException $e) {
            http_response_code(422);

            $this->renderIndex([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Approvisionnement du stock.
     *
     * POST /gerant/produits/approvisionner/{id}
     */
    public function approvisionner(int $id): void
    {
        $quantite = (int) ($_POST['quantite'] ?? 0);

        try {
            $this->produitService->approvisionner(
                $id,
                $quantite
            );

            $this->redirect('/gerant/produits');
        } catch (ValidationException $e) {
            http_response_code(422);

            $this->renderIndex([
                'message' => $e->getMessage(),
                'erreursStock' => $e->getErrors(),
            ]);
        }
    }

    /**
     * Prépare et affiche la liste.
     */
    private function afficherListe(array $data = []): void
    {
        $terme = trim(
            $_GET['q'] ?? ''
        );

        $categorieId = (
            isset($_GET['categorie'])
            && $_GET['categorie'] !== ''
        )
            ? (int) $_GET['categorie']
            : null;

        $statut = trim(
            $_GET['statut'] ?? ''
        );

        if (!in_array(
            $statut,
            ['', 'disponible', 'en_rupture'],
            true
        )) {
            $statut = '';
        }

        $produits = $this->produitRepository->findCatalogue(
            $categorieId,
            $statut !== '' ? $statut : null,
            $terme !== '' ? $terme : null
        );

        $this->renderIndex(
            array_merge(
                [
                    'produits' => $produits,
                    'categories' => $this->categorieRepository->findAll(),

                    'terme' => $terme,
                    'categorieActive' => $categorieId,
                    'statutActive' => $statut,

                    'form' => [],
                    'erreurs' => [],

                    'editId' => null,
                    'produitEdition' => null,

                    'deleteId' => null,
                    'produitSuppression' => null,

                    'message' => null,
                    'messageFormulaire' => null,
                ],
                $data
            )
        );
    }

    /**
     * Réaffiche la page avec les erreurs du formulaire.
     */
    private function renderIndexAvecErreur(
        ValidationException $e,
        array $form,
        ?int $editId
    ): void {
        $terme = trim(
            $_GET['q'] ?? ''
        );

        $categorieId = (
            isset($_GET['categorie'])
            && $_GET['categorie'] !== ''
        )
            ? (int) $_GET['categorie']
            : null;

        $statut = trim(
            $_GET['statut'] ?? ''
        );

        if (!in_array(
            $statut,
            ['', 'disponible', 'en_rupture'],
            true
        )) {
            $statut = '';
        }

        $produitEdition = null;

        if ($editId !== null) {
            $produitEdition =
                $this->produitRepository->findProduitById(
                    $editId
                );
        }

        $produits =
            $this->produitRepository->findCatalogue(
                $categorieId,
                $statut !== '' ? $statut : null,
                $terme !== '' ? $terme : null
            );

        $this->renderIndex([
            'produits' => $produits,

            'categories' =>
            $this->categorieRepository->findAll(),

            'form' => $form,

            'erreurs' =>
            $e->getErrors(),

            'editId' => $editId,

            'produitEdition' =>
            $produitEdition,

            'deleteId' => null,

            'produitSuppression' => null,

            'messageFormulaire' =>
            $e->getMessage(),

            'message' => null,

            'terme' => $terme,

            'categorieActive' =>
            $categorieId,

            'statutActive' =>
            $statut,
        ]);
    }

    /**
     * Rendu final de la vue.
     */
    private function renderIndex(array $data = []): void
    {
        $data = array_merge(
            [
                'produits' => [],
                'categories' => [],

                'form' => [],
                'erreurs' => [],

                'editId' => null,
                'produitEdition' => null,

                'deleteId' => null,
                'produitSuppression' => null,

                'message' => null,
                'messageFormulaire' => null,

                'terme' => '',
                'categorieActive' => null,
                'statutActive' => '',
            ],
            $data
        );

        $this->view(
            'gerant/produits/index',
            $data,
            'layouts/gerant'
        );
    }
}
