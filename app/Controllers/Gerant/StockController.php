<?php

namespace App\Controllers\Gerant;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\ProduitRepository;
use App\Services\ProduitService;

class StockController extends Controller
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private ProduitService $produitService,
    ) {}

    /**
     * GET /gerant/stocks
     */
    public function index(): void
    {
        $produits = $this->produitRepository->findAll();

        $produitsStockFaible =
            $this->produitRepository->findStockFaible(5);

        $produitsEnRupture =
            $this->produitRepository->findEnRupture();

        $this->renderIndex([
            'produits' => $produits,

            'nombreProduits' =>
                count($produits),

            'nombreStockFaible' =>
                count($produitsStockFaible),

            'nombreEnRupture' =>
                count($produitsEnRupture),

            'produitApprovisionnement' => null,

            'erreurApprovisionnement' => null,

            'quantiteApprovisionnement' => '',
        ]);
    }

    /**
     * POST /gerant/stocks/approvisionner/{id}
     */
    public function approvisionner(int $id): void
    {
        $quantite = (int) ($_POST['quantite'] ?? 0);

        try {
            $this->produitService->approvisionner(
                $id,
                $quantite
            );

            $this->redirect('/gerant/stocks');

        } catch (ValidationException $e) {

            $produit =
                $this->produitRepository->findProduitById($id);

            $produits =
                $this->produitRepository->findAll();

            $produitsStockFaible =
                $this->produitRepository->findStockFaible(5);

            $produitsEnRupture =
                $this->produitRepository->findEnRupture();

            $this->renderIndex([
                'produits' => $produits,

                'nombreProduits' =>
                    count($produits),

                'nombreStockFaible' =>
                    count($produitsStockFaible),

                'nombreEnRupture' =>
                    count($produitsEnRupture),

                'produitApprovisionnement' =>
                    $produit,

                'erreurApprovisionnement' =>
                    $e->getMessage(),

                'quantiteApprovisionnement' =>
                    $_POST['quantite'] ?? '',
            ]);
        }
    }


    /**
 * POST /gerant/stocks/ajuster/{id}
 */
public function ajusterStock(int $id): void
{
    $variation = (int) ($_POST['variation'] ?? 0);

    try {

        $this->produitService->ajusterStock(
            $id,
            $variation
        );

        $this->redirect('/gerant/stocks');

    } catch (ValidationException $e) {

        $this->redirect('/gerant/stocks');
    }
}

    /**
     * Rend la page Stocks.
     */
    private function renderIndex(array $data = []): void
    {
        $data = array_merge(
            [
                'titre' => 'Gestion des stocks',

                'produits' => [],

                'nombreProduits' => 0,

                'nombreStockFaible' => 0,

                'nombreEnRupture' => 0,

                'produitApprovisionnement' => null,

                'erreurApprovisionnement' => null,

                'quantiteApprovisionnement' => '',
            ],
            $data
        );

        $this->view(
            'gerant/stocks/index',
            $data,
            'layouts/gerant'
        );
    }
}