<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\CommandeRepository;
use App\Repositories\LigneCommandeRepository;
use App\Services\CommandeService;

/**
 * Section "ESPACE GÉRANT -> Gestion des commandes" : lister, filtrer
 * par statut, consulter le détail, modifier le statut, annuler.
 */
class CommandeController extends Controller
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private LigneCommandeRepository $ligneCommandeRepository,
        private CommandeService $commandeService,
    ) {
    }

    public function index(): void
    {
        $statut = $_GET['statut'] ?? null;

        $commandes = $statut !== null
            ? $this->commandeRepository->findByStatut($statut)
            : $this->commandeRepository->findAll();

        $this->view('gerant/commandes/index', ['commandes' => $commandes]);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $commande = $this->commandeRepository->findCommandeById($id);

        if ($commande === null) {
            throw new \App\Exceptions\NotFoundException('Commande introuvable.');
        }

        $this->view('gerant/commandes/show', [
            'commande' => $commande,
            'lignes' => $this->ligneCommandeRepository->findByCommande($id),
        ]);
    }

    public function changerStatut(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $statut = $_POST['statut'] ?? '';

            $this->commandeService->changerStatut($id, $statut);
            $this->redirect('/gerant/commandes');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function annuler(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $this->commandeService->annuler($id);
            $this->redirect('/gerant/commandes');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }
}
