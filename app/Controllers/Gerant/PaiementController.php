<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\PaiementRepository;
use App\Services\PaiementService;

/**
 * Section "ESPACE GÉRANT -> Paiements" : afficher les paiements,
 * commandes impayées, commandes partiellement payées, enregistrer
 * un paiement.
 */
class PaiementController extends Controller
{
    public function __construct(
        private PaiementRepository $paiementRepository,
        private PaiementService $paiementService,
    ) {
    }

    public function index(): void
    {
        $this->view('gerant/paiements/index', [
            'paiements' => $this->paiementRepository->findAll(),
        ]);
    }

    public function impayees(): void
    {
        $this->view('gerant/paiements/impayees', [
            'commandes' => $this->paiementRepository->findCommandesImpayees(),
        ]);
    }

    public function partielles(): void
    {
        $this->view('gerant/paiements/partielles', [
            'commandes' => $this->paiementRepository->findCommandesPartiellementPayees(),
        ]);
    }

    public function enregistrer(): void
    {
        try {
            $commandeId = (int) ($_POST['commande_id'] ?? 0);
            $montant = (float) ($_POST['montant'] ?? 0);

            $this->paiementService->enregistrer($commandeId, $montant);
            $this->redirect('/gerant/paiements');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }
}
