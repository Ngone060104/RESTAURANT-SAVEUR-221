<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\AvisRepository;
use App\Services\AvisService;

/**
 * Section "ESPACE ADMINISTRATEUR -> Gestion des avis" : lister,
 * consulter les commentaires, voir les notes, supprimer un avis
 * inapproprié. Réservé au rôle ADMIN (pas GERANT) - AdminMiddleware.
 */
class AvisController extends Controller
{
    public function __construct(
        private AvisRepository $avisRepository,
        private AvisService $avisService,
    ) {
    }

    public function index(): void
    {
        $this->view('admin/avis/index', [
            'avis' => $this->avisRepository->findAll(),
        ]);
    }

    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->avisService->supprimer($id);
        $this->redirect('/admin/avis');
    }
}
