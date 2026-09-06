<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\PaiementRepository;
use App\Services\PaiementService;

/**
 * Section "ESPACE GÉRANT -> Paiements".
 */
class PaiementController extends Controller
{
    public function __construct(
        private PaiementRepository $paiementRepository,
        private PaiementService $paiementService,
    ) {}

    /**
     * Tous les paiements.
     */
    public function index(): void
    {
        $this->afficherPage('TOUS');
    }

    /**
     * Filtrer par statut.
     *
     * URL :
     * /gerant/paiements/statut/PAYEE
     * /gerant/paiements/statut/IMPAYEE
     * /gerant/paiements/statut/PARTIELLEMENT_PAYEE
     */
    public function byStatut(string $statut): void
    {
        $statut = strtoupper(trim($statut));

        $statutsAutorises = [
            'TOUS',
            'PAYEE',
            'IMPAYEE',
            'PARTIELLEMENT_PAYEE',
        ];

        if (!in_array($statut, $statutsAutorises, true)) {
            http_response_code(404);
            return;
        }

        $this->afficherPage($statut);
    }

    /**
     * Recherche par client ou commande.
     *
     * URL :
     * /gerant/paiements/recherche/{terme}
     */
    public function recherche(string $terme): void
    {
        $terme = trim(urldecode($terme));

        if ($terme === '') {
            $this->redirect('/gerant/paiements');
            return;
        }

        $paiements = $this->paiementRepository->search($terme);

        $this->view(
            'gerant/paiements/index',
            [
                'paiements' => $paiements,

                'commandesImpayees' =>
                $this->paiementRepository->findCommandesImpayees(),

                'commandesPartielles' =>
                $this->paiementRepository->findCommandesPartiellementPayees(),

                'statutFiltre' => 'TOUS',

                'termeRecherche' => $terme,
            ],
            'layouts/gerant'
        );
    }

    /**
     * Affiche la page unique des paiements.
     */
    private function afficherPage(string $statutFiltre): void
    {
        /*
         * Les paiements réels servent à l'historique.
         */
        if ($statutFiltre === 'TOUS') {
            $paiements = $this->paiementRepository->findAll();
        } elseif ($statutFiltre === 'PAYEE') {
            $paiements = $this->paiementRepository->findByStatut('PAYEE');
        } else {
            /*
             * Les commandes impayées et partiellement payées
             * viennent directement de la vue de paiement.
             */
            $paiements = $this->paiementRepository->findAll();
        }

        $this->view(
            'gerant/paiements/index',
            [
                'paiements' => $paiements,

                'commandesImpayees' =>
                $this->paiementRepository->findCommandesImpayees(),

                'commandesPartielles' =>
                $this->paiementRepository->findCommandesPartiellementPayees(),

                'statutFiltre' => $statutFiltre,

                'termeRecherche' => null,
            ],
            'layouts/gerant'
        );
    }

    /**
     * Enregistre un nouveau paiement.
     */
   public function enregistrer(): void
{
    try {
        $commandeId = (int) ($_POST['commande_id'] ?? 0);
        $montant = (float) ($_POST['montant'] ?? 0);

        $this->paiementService->enregistrer(
            $commandeId,
            $montant
        );

        $this->redirect('/gerant/paiements');

    } catch (ValidationException $e) {
        $this->view(
            'gerant/paiements/index',
            [
                'paiements' =>
                    $this->paiementRepository->findAll(),

                'commandesImpayees' =>
                    $this->paiementRepository
                        ->findCommandesImpayees(),

                'commandesPartielles' =>
                    $this->paiementRepository
                        ->findCommandesPartiellementPayees(),

                'statutFiltre' => 'TOUS',

                'termeRecherche' => null,

                'erreurPaiement' => $e->getMessage(),

                'commandePaiement' => $commandeId,

                'montantPaiement' => $montant,
            ],
            'layouts/gerant'
        );
    }
}
}
