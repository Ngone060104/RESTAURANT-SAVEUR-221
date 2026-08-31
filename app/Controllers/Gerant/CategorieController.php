<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;
use App\Services\CategorieService;

/**
 * CRUD complet des catégories - section "ESPACE GÉRANT" du cahier des
 * charges. Accessible à GERANT et ADMIN (voir GerantMiddleware sur les
 * routes correspondantes).
 */
class CategorieController extends Controller
{
    public function __construct(
        private CategorieRepository $categorieRepository,
        private CategorieService $categorieService,
    ) {
    }

    public function index(): void
    {
        $terme = trim($_GET['q'] ?? '');

        $categories = $terme !== ''
            ? $this->categorieRepository->search($terme)
            : $this->categorieRepository->findAll();

        $this->view('gerant/categories/index', ['categories' => $categories]);
    }

    public function store(): void
    {
        try {
            $this->categorieService->create($_POST);
            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function update(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $this->categorieService->update($id, $_POST);
            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function destroy(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $this->categorieService->delete($id);
            $this->redirect('/gerant/categories');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }
}
