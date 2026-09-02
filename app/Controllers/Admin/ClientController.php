<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Exceptions\NotFoundException;
use App\Repositories\ClientRepository;
use App\Repositories\CommandeRepository;

/**
 * Section "ESPACE ADMINISTRATEUR -> Gestion des clients". Contrairement
 * aux utilisateurs internes, il n'y a PAS de CRUD ici - le cahier des
 * charges ne prévoit que lister/rechercher/consulter/historique, jamais
 * créer/modifier/supprimer un client (un client gère son propre compte
 * via /profil).
 */
class ClientController extends Controller
{
    public function __construct(
        private ClientRepository $clientRepository,
        private CommandeRepository $commandeRepository,
    ) {
    }

    public function index(): void
    {
        $terme = trim($_GET['q'] ?? '');

        $clients = $terme !== ''
            ? $this->clientRepository->search($terme)
            : $this->clientRepository->findAll();

        $this->view('admin/clients/index', ['clients' => $clients]);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $client = $this->clientRepository->findClientById($id);

        if ($client === null) {
            throw new NotFoundException('Client introuvable.');
        }

        $this->view('admin/clients/show', [
            'client' => $client,
            'commandes' => $this->commandeRepository->findByClient($id),
        ]);
    }
}
