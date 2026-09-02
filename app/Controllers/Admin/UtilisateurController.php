<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\UtilisateurRepository;
use App\Services\UtilisateurService;

class UtilisateurController extends Controller
{
    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
        private UtilisateurService $utilisateurService,
    ) {
    }

    public function index(): void
    {
        $terme = trim($_GET['q'] ?? '');

        $utilisateurs = $terme !== ''
            ? $this->utilisateurRepository->searchInternes($terme)
            : $this->utilisateurRepository->findInternes();

        $this->view('admin/utilisateurs/index', ['utilisateurs' => $utilisateurs]);
    }

    public function store(): void
    {
        try {
            $this->utilisateurService->creer($_POST);
            $this->redirect('/admin/utilisateurs');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function update(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $this->utilisateurService->modifier($id, $_POST);
            $this->redirect('/admin/utilisateurs');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->utilisateurService->supprimer($id);
        $this->redirect('/admin/utilisateurs');
    }

    public function activer(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->utilisateurService->activer($id);
        $this->redirect('/admin/utilisateurs');
    }

    public function desactiver(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->utilisateurService->desactiver($id);
        $this->redirect('/admin/utilisateurs');
    }
}
