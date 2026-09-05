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
        $terme = trim($_GET['q'] ?? '');

        $categories = $terme !== ''
            ? $this->categorieRepository->search($terme)
            : $this->categorieRepository->findAll();

        $this->view(
            'gerant/categories/index',
            [
                'titre' => 'Catégories',
                'categories' => $categories,
                'terme' => $terme,
                'erreurs' => [],
                'form' => [],
                'editId' => null,

                // Toast
                'toast' => $_GET['toast'] ?? null,
                'toastType' => $_GET['toast_type'] ?? null,
            ],
            'layouts/gerant'
        );
    }

    /**
     * Ajouter une catégorie
     */
    public function store(): void
    {
        try {
            $this->categorieService->create($_POST);

            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);

            $terme = trim($_GET['q'] ?? '');

            $categories = $terme !== ''
                ? $this->categorieRepository->search($terme)
                : $this->categorieRepository->findAll();

            $this->view(
                'gerant/categories/index',
                [
                    'titre' => 'Catégories',
                    'categories' => $categories,
                    'terme' => $terme,
                    'erreurs' => $e->getErrors(),
                    'form' => $_POST,
                    'editId' => null,

                    // Toast
                    'toast' => null,
                    'toastType' => null,
                ],
                'layouts/gerant'
            );
        }
    }

    /**
     * Modifier une catégorie
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

            $categories = $terme !== ''
                ? $this->categorieRepository->search($terme)
                : $this->categorieRepository->findAll();

            $this->view(
                'gerant/categories/index',
                [
                    'titre' => 'Catégories',
                    'categories' => $categories,
                    'terme' => $terme,
                    'erreurs' => $e->getErrors(),
                    'form' => $_POST,
                    'editId' => $id,

                    // Toast
                    'toast' => null,
                    'toastType' => null,
                ],
                'layouts/gerant'
            );
        }
    }

    /**
     * Supprimer une catégorie
     */
   public function destroy(): void
{
    $id = (int) ($_POST['id'] ?? 0);

    try {
        $this->categorieService->delete($id);

        // Suppression réussie
        $this->redirect('/gerant/categories');

    } catch (ValidationException $e) {

        // On recharge les catégories
        $terme = trim($_GET['q'] ?? '');

        $categories = $terme !== ''
            ? $this->categorieRepository->search($terme)
            : $this->categorieRepository->findAll();

        // On reste sur la même page et on transmet le message au toast
        $this->view(
            'gerant/categories/index',
            [
                'titre' => 'Catégories',
                'categories' => $categories,
                'terme' => $terme,
                'erreurs' => [],
                'form' => [],
                'editId' => null,
                'message' => $e->getMessage(),
            ],
            'layouts/gerant'
        );
    }
}
}