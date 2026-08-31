<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;
use App\Repositories\ProduitRepository;

/**
 * Règle métier (section VI - Gestion du stock) : "Si quantite_stock = 0,
 * le produit doit être indisponible." Le statut n'est donc jamais choisi
 * à la main par le gérant, il est TOUJOURS déduit du stock ici, ce qui
 * évite qu'un produit à stock=0 reste marqué "disponible" par erreur.
 */
class ProduitService
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private CategorieRepository $categorieRepository,
    ) {
    }

    public function create(array $data): int
    {
        $this->validate($data);

        return $this->produitRepository->create($this->withStatutDerive($data));
    }

    public function update(int $id, array $data): bool
    {
        $this->validate($data);

        return $this->produitRepository->update($id, $this->withStatutDerive($data));
    }

    /**
     * Approvisionnement : augmente le stock d'un produit existant
     * (section V/VI - "approvisionner un produit", "augmenter la quantité").
     */
    public function approvisionner(int $id, int $quantite): bool
    {
        if ($quantite <= 0) {
            throw new ValidationException('La quantité approvisionnée doit être positive.');
        }

        $produit = $this->produitRepository->findProduitById($id);

        if ($produit === null) {
            throw new ValidationException('Produit introuvable.');
        }

        return $this->produitRepository->update($id, $this->withStatutDerive([
            'nom' => $produit->getLibelle(),
            'description' => $produit->getDescription(),
            'prix' => $produit->getPrix(),
            'stock' => $produit->getStock() + $quantite,
            'image' => $produit->getImage(),
            'categorie_id' => $produit->getCategorieId(),
        ]));
    }

    public function delete(int $id): bool
    {
        return $this->produitRepository->delete($id);
    }

    private function withStatutDerive(array $data): array
    {
        $data['statut'] = ((int) $data['stock']) === 0 ? 'en_rupture' : 'disponible';

        return $data;
    }

    private function validate(array $data): void
    {
        $required = ['nom', 'prix', 'stock', 'categorie_id'];

        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                throw new ValidationException("Le champ {$field} est obligatoire.");
            }
        }

        if ((float) $data['prix'] < 0) {
            throw new ValidationException('Le prix ne peut pas être négatif.');
        }

        if ((int) $data['stock'] < 0) {
            throw new ValidationException('Le stock ne peut pas être négatif.');
        }

        if ($this->categorieRepository->findById((int) $data['categorie_id']) === null) {
            throw new ValidationException('Catégorie inconnue.');
        }
    }
}
