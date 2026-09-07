<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Repositories\CommandeRepository;
use App\Repositories\LigneCommandeRepository;

class StatistiqueController extends Controller
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private LigneCommandeRepository $ligneCommandeRepository,
    ) {
    }

    public function index(): void
    {
        $this->view(
            'gerant/statistiques/index',
            [
                'titre' => 'Statistiques & Ventes',

                // Chiffre d'affaires
                'caJour' =>
                    $this->commandeRepository->getCaJour(),

                'caSemaine' =>
                    $this->commandeRepository->getCaSemaine(),

                'caMois' =>
                    $this->commandeRepository->getCaMois(),

                // Commandes
                'nombreCommandes' =>
                    $this->commandeRepository->countCommandes(),

                'commandesEnCours' =>
                    $this->commandeRepository->countCommandesEnCours(),

                'commandesParStatut' =>
                    $this->commandeRepository
                        ->countCommandesParStatut(),

                // Produits vendus
                'produitPlusVendu' =>
                    $this->ligneCommandeRepository
                        ->getProduitPlusVendu(),

                'top3Produits' =>
                    $this->ligneCommandeRepository
                        ->getTop3Produits(),
            ],
            'layouts/gerant'
        );
    }
}