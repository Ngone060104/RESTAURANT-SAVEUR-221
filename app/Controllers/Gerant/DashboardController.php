<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Repositories\CommandeRepository;
use App\Repositories\ProduitRepository;

class DashboardController extends Controller
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private ProduitRepository $produitRepository,
    ) {
    }

    public function index(): void
    {
        $commandes = $this->commandeRepository->findAll();

        $commandesEnAttente = 0;
        $commandesEnPreparation = 0;
        $commandesPretes = 0;

        foreach ($commandes as $commande) {
            switch ($commande->getStatut()) {
                case 'EN_ATTENTE':
                    $commandesEnAttente++;
                    break;

                case 'EN_PREPARATION':
                    $commandesEnPreparation++;
                    break;

                case 'PRETE':
                    $commandesPretes++;
                    break;
            }
        }

        $produitsStockFaible =
            $this->produitRepository->findStockFaible(5);

        $produitsEnRupture =
            $this->produitRepository->findEnRupture();

        $this->view(
            'gerant/dashboard',
            [
                'titre' => 'Dashboard',

                'commandesEnAttente' => $commandesEnAttente,
                'commandesEnPreparation' => $commandesEnPreparation,
                'commandesPretes' => $commandesPretes,

                'produitsStockFaible' => $produitsStockFaible,
                'produitsEnRupture' => $produitsEnRupture,

                'nombreStockFaible' => count($produitsStockFaible),
                'nombreEnRupture' => count($produitsEnRupture),
            ],
            'layouts/gerant'
        );
    }
}