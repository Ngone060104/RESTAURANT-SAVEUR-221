<?php

namespace App\Controllers\Admin;

use App\Exceptions\NotFoundException;
use App\Core\Controller;
use App\Repositories\ClientRepository;
use App\Repositories\CommandeRepository;
use App\Repositories\LigneCommandeRepository;

class ClientController extends Controller
{
    public function __construct(
        private ClientRepository $clientRepository,
        private CommandeRepository $commandeRepository,
        private LigneCommandeRepository $ligneCommandeRepository,
    ) {
    }

    public function index(): void
    {
        $terme = trim($_GET['q'] ?? '');

        $clients = $terme !== ''
            ? $this->clientRepository->search($terme)
            : $this->clientRepository->findAll();

        $this->view(
            'admin/clients/index',
            [
                'clients' => $clients,
                'termeRecherche' => $terme,
            ],
            'layouts/gerant'
        );
    }

    public function recherche(string $terme): void
{
    $terme = trim(urldecode($terme));

    if ($terme === '') {
        $this->redirect('/admin/clients');
        return;
    }

    $clients = $this->clientRepository->search($terme);

    $this->view(
        'admin/clients/index',
        [
            'clients' => $clients,
            'termeRecherche' => $terme,
        ],
        'layouts/gerant'
    );
}

public function show(int $id): void
{
    $client = $this->clientRepository->findClientById($id);

    if ($client === null) {
        throw new NotFoundException('Client introuvable.');
    }

    $commandes = $this->commandeRepository->findByClient($id);

    $lignesParCommande = [];

    foreach ($commandes as $commande) {
        $lignesParCommande[$commande->getId()] =
            $this->ligneCommandeRepository
                ->findByCommande($commande->getId());
    }

    $this->view(
        'admin/clients/show',
        [
            'client' => $client,
            'commandes' => $commandes,
            'lignesParCommande' => $lignesParCommande,
        ],
        'layouts/gerant'
    );
}
}