<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\AvisRepository;
use App\Repositories\CategorieRepository;
use App\Repositories\ProduitRepository;

class HomeController extends Controller
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private CategorieRepository $categorieRepository,
        private AvisRepository $avisRepository,
    ) {
    }

    public function index(): void
    {
        $this->view('home/index', [
            'titre' => 'Accueil',
            'categories' => $this->categorieRepository->findAll(),
            'produitsVedettes' => $this->produitRepository->findVedettes(3),
            'avisRecents' => $this->avisRepository->findRecents(2),
        ]);
    }
}
