<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;
use App\Services\CategorieService;

class CategorieController extends Controller
{
    public function __construct(
        private CategorieRepository $categorieRepository,
        private CategorieService $categorieService,
    ) {}

    /**
     * Liste des catégories
     */
    public function index(): void
    {
        $this->afficherListe();
    }

    /**
     * Ouvre le formulaire en mode modification
     * URL : /gerant/categories/update/{id}
     */
    public function edit(int $id): void
    {
        $categorie = $this->categorieRepository->findById($id);

        if ($categorie === null) {
            $this->redirect('/gerant/categories');
            return;
        }

        $this->afficherListe([
            'editId' => $id,
            'categorieEdition' => $categorie,
            'form' => [
                'libelle' => $categorie->getLibelle(),
                'description' => $categorie->getDescription() ?? '',
            ],
        ]);
    }

    /**
     * Ajouter une catégorie
     * POST : /gerant/categories
     */
    public function store(): void
    {
        try {
            $this->categorieService->create($_POST);

            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);

            $this->afficherListe([
                'erreurs' => $e->getErrors(),
                'form' => $_POST,
                'editId' => null,
            ]);
        }
    }

    /**
     * Modifier une catégorie
     * POST : /gerant/categories/update/{id}
     */
    public function update(int $id): void
    {
        try {
            $this->categorieService->update($id, $_POST);

            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);

            $categorie = $this->categorieRepository->findById($id);

            $this->afficherListe([
                'erreurs' => $e->getErrors(),
                'form' => $_POST,
                'editId' => $id,
                'categorieEdition' => $categorie,
            ]);
        }
    }

    /**
     * Ouvre le modal de confirmation de suppression
     * GET : /gerant/categories/delete/{id}
     */
    public function confirmDelete(int $id): void
    {
        $categorie = $this->categorieRepository->findById($id);

        if ($categorie === null) {
            $this->redirect('/gerant/categories');
            return;
        }

        $this->afficherListe([
            'deleteId' => $id,
            'categorieSuppression' => $categorie,
        ]);
    }

    /**
     * Supprimer une catégorie
     * POST : /gerant/categories/delete/{id}
     */
    public function destroy(int $id): void
    {
        try {
            $this->categorieService->delete($id);

            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);

            $this->afficherListe([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Recherche
     * GET : /gerant/categories/recherche/{terme}
     */
    public function recherche(string $terme): void
    {
        $terme = trim(urldecode($terme));

        $categories = $terme !== ''
            ? $this->categorieRepository->search($terme)
            : $this->categorieRepository->findAll();

        $this->renderIndex([
            'categories' => $categories,
            'terme' => $terme,
        ]);
    }

    /**
     * Charge la liste complète + données des modals.
     */
    private function afficherListe(array $data = []): void
    {
        $terme = trim($_GET['q'] ?? '');

        $categories = $terme !== ''
            ? $this->categorieRepository->search($terme)
            : $this->categorieRepository->findAll();

        $this->renderIndex(array_merge(
            [
                'titre' => 'Catégories',
                'categories' => $categories,
                'terme' => $terme,

                'erreurs' => [],
                'form' => [],

                'editId' => null,
                'categorieEdition' => null,

                'deleteId' => null,
                'categorieSuppression' => null,

                'message' => null,

                'toast' => null,
                'toastType' => null,
            ],
            $data
        ));
    }

    /**
     * Affichage de la vue.
     */
    private function renderIndex(array $data = []): void
    {
        $data = array_merge(
            [
                'titre' => 'Catégories',
                'categories' => [],
                'terme' => '',

                'erreurs' => [],
                'form' => [],

                'editId' => null,
                'categorieEdition' => null,

                'deleteId' => null,
                'categorieSuppression' => null,

                'message' => null,

                'toast' => null,
                'toastType' => null,
            ],
            $data
        );

        $this->view(
            'gerant/categories/index',
            $data,
            'layouts/gerant'
        );
    }
}
