<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;
use App\Services\CategorieService;

/**
 * CRUD complet des catégories - section "ESPACE GÉRANT".
 *
 * Accessible à GERANT et ADMIN via GerantMiddleware.
 */
class CategorieController extends Controller
{
    public function __construct(
        private CategorieRepository $categorieRepository,
        private CategorieService $categorieService,
    ) {
    }

    /**
     * Liste des catégories + recherche.
     */
    public function index(): void
    {
        $terme = trim($_GET['q'] ?? '');

        $categories = $this->getCategories($terme);

        $this->renderIndex(
            $categories,
            $terme
        );
    }

    /**
     * Création d'une catégorie.
     */
    public function store(): void
    {
        try {
            $this->categorieService->create($_POST);

            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);

            $terme = trim($_GET['q'] ?? '');
            $categories = $this->getCategories($terme);

            $this->renderIndex(
                $categories,
                $terme,
                $e->getErrors(),
                $_POST
            );
        }
    }

    /**
     * Modification d'une catégorie.
     */
    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->categorieService->update($id, $_POST);

            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);

            $terme = trim($_GET['q'] ?? '');
            $categories = $this->getCategories($terme);

            $this->renderIndex(
                $categories,
                $terme,
                $e->getErrors(),
                $_POST,
                $id
            );
        }
    }

    /**
     * Suppression d'une catégorie.
     */
    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->categorieService->delete($id);

            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);

            $terme = trim($_GET['q'] ?? '');
            $categories = $this->getCategories($terme);

            $this->renderIndex(
                $categories,
                $terme,
                $e->getErrors(),
                [],
                null,
                $e->getMessage()
            );
        }
    }

    /**
     * Récupérer les catégories selon la recherche.
     */
    private function getCategories(string $terme): array
    {
        return $terme !== ''
            ? $this->categorieRepository->search($terme)
            : $this->categorieRepository->findAll();
    }

    /**
     * Afficher la page des catégories.
     */
    private function renderIndex(
        array $categories,
        string $terme = '',
        array $erreurs = [],
        array $form = [],
        ?int $editId = null,
        ?string $message = null
    ): void {
        $this->view(
            'gerant/categories/index',
            [
                'titre' => 'Catégories',
                'categories' => $categories,
                'terme' => $terme,
                'erreurs' => $erreurs,
                'form' => $form,
                'editId' => $editId,
                'message' => $message,
            ],
            'layouts/gerant'
        );
    }
}