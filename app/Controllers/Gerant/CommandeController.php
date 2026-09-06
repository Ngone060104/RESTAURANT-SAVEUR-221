<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Repositories\ClientRepository;
use App\Repositories\CommandeRepository;
use App\Repositories\LigneCommandeRepository;
use App\Repositories\PaiementRepository;
use App\Services\CommandeService;

class CommandeController extends Controller
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private LigneCommandeRepository $ligneCommandeRepository,
        private PaiementRepository $paiementRepository,
        private ClientRepository $clientRepository,
        private CommandeService $commandeService,
    ) {}

    /**
     * Liste de toutes les commandes
     */
    public function index(): void
    {
        $this->afficherCommandes(
            $this->commandeRepository->findAll()
        );
    }

    /**
     * Liste des commandes filtrées par statut.
     *
     * URL :
     * /gerant/commandes/statut/EN_ATTENTE
     * /gerant/commandes/statut/EN_PREPARATION
     * etc.
     */
    public function byStatut(string $statut): void
    {
        $statutsValides = [
            'EN_ATTENTE',
            'EN_PREPARATION',
            'PRETE',
            'RETIREE',
            'ANNULEE',
        ];

        $statut = strtoupper(
            trim(urldecode($statut))
        );

        if (!in_array($statut, $statutsValides, true)) {
            $this->redirect('/gerant/commandes');
            return;
        }

        $this->afficherCommandes(
            $this->commandeRepository->findByStatut($statut),
            $statut
        );
    }

    /**
     * Prépare les données communes à la liste des commandes.
     */
    private function afficherCommandes(
        array $commandes,
        string $statutActif = ''
    ): void {
        /*
         * Récupérer les clients liés aux commandes.
         */
        $clients = [];

        foreach ($commandes as $commande) {
            $clientId = $commande->getClientId();

            if (!isset($clients[$clientId])) {
                $clients[$clientId] =
                    $this->clientRepository->findClientById($clientId);
            }
        }

        /*
         * Récupérer le statut de paiement de chaque commande.
         */
        $paiements = [];

        foreach ($commandes as $commande) {
            $commandeId = $commande->getId();

            $paiements[$commandeId] =
                $this->paiementRepository->getStatutCommande(
                    $commandeId
                );
        }

        $this->view(
            'gerant/commandes/index',
            [
                'titre' => 'Commandes',
                'commandes' => $commandes,
                'clients' => $clients,
                'paiements' => $paiements,
                'statutActif' => $statutActif,
            ],
            'layouts/gerant'
        );
    }

    /**
     * Détail d'une commande
     */
    public function show(int $id): void
    {
        $id = (int) $id;

        $commande =
            $this->commandeRepository->findCommandeById($id);

        if ($commande === null) {
            throw new NotFoundException(
                'Commande introuvable.'
            );
        }

        $client =
            $this->clientRepository->findClientById(
                $commande->getClientId()
            );

        $lignes =
            $this->ligneCommandeRepository->findByCommande($id);

        $paiements =
            $this->paiementRepository->findByCommande($id);

        $statutPaiement =
            $this->paiementRepository->getStatutCommande($id);

        $this->view(
            'gerant/commandes/show',
            [
                'titre' => 'Détail commande',
                'commande' => $commande,
                'client' => $client,
                'lignes' => $lignes,
                'paiements' => $paiements,
                'statutPaiement' => $statutPaiement,
            ],
            'layouts/gerant'
        );
    }

    /**
     * Modifier le statut d'une commande
     */
    public function changerStatut(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $statut = trim($_POST['statut'] ?? '');

        try {
            $this->commandeService->changerStatut(
                $id,
                $statut
            );

            $this->redirect('/gerant/commandes');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    /**
     * Annuler une commande
     *
     * Le service vérifie que la commande est annulable.
     * Le trigger PostgreSQL restaure automatiquement le stock.
     */
    public function annuler(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->commandeService->annuler($id);

            $this->redirect('/gerant/commandes');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }
}
