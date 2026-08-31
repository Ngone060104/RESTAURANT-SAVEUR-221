<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\ProduitRepository;

/**
 * Le panier n'existe pas en base (pas de table `paniers` dans le schéma) :
 * il vit en session PHP tant que le client n'a pas validé sa commande.
 * Stockage : $_SESSION['panier'][produit_id] = quantite.
 *
 * Règle métier n°6 : "Un client ne peut pas commander plus que le stock."
 * On la vérifie ici dès l'ajout au panier (meilleure expérience), sachant
 * que le trigger PostgreSQL la revérifiera de toute façon à la validation
 * finale de la commande (le stock peut bouger entre-temps).
 */
class PanierService
{
    private const SESSION_KEY = 'panier';

    public function __construct(private ProduitRepository $produitRepository)
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
    }

    public function ajouter(int $produitId, int $quantite): void
    {
        if ($quantite <= 0) {
            throw new ValidationException('La quantité doit être positive.');
        }

        $produit = $this->produitRepository->findProduitById($produitId);

        if ($produit === null) {
            throw new ValidationException('Produit introuvable.');
        }

        $quantiteDejaPresente = $_SESSION[self::SESSION_KEY][$produitId] ?? 0;
        $quantiteTotale = $quantiteDejaPresente + $quantite;

        if ($quantiteTotale > $produit->getStock()) {
            throw new ValidationException(
                "Stock insuffisant pour {$produit->getLibelle()} (disponible : {$produit->getStock()})."
            );
        }

        $_SESSION[self::SESSION_KEY][$produitId] = $quantiteTotale;
    }

    public function modifierQuantite(int $produitId, int $quantite): void
    {
        if ($quantite <= 0) {
            throw new ValidationException('La quantité doit être positive (utilisez la suppression pour retirer un article).');
        }

        if (!isset($_SESSION[self::SESSION_KEY][$produitId])) {
            throw new ValidationException("Ce produit n'est pas dans le panier.");
        }

        $produit = $this->produitRepository->findProduitById($produitId);

        if ($produit === null) {
            throw new ValidationException('Produit introuvable.');
        }

        if ($quantite > $produit->getStock()) {
            throw new ValidationException(
                "Stock insuffisant pour {$produit->getLibelle()} (disponible : {$produit->getStock()})."
            );
        }

        $_SESSION[self::SESSION_KEY][$produitId] = $quantite;
    }

    public function supprimer(int $produitId): void
    {
        unset($_SESSION[self::SESSION_KEY][$produitId]);
    }

    public function vider(): void
    {
        $_SESSION[self::SESSION_KEY] = [];
    }

    public function estVide(): bool
    {
        return count($_SESSION[self::SESSION_KEY]) === 0;
    }

    /**
     * @return array<int, array{produit: \App\Models\Produit, quantite: int, sousTotal: float}>
     */
    public function getLignes(): array
    {
        $lignes = [];

        foreach ($_SESSION[self::SESSION_KEY] as $produitId => $quantite) {
            $produit = $this->produitRepository->findProduitById($produitId);

            // Le produit a pu être supprimé entre temps par le gérant.
            if ($produit === null) {
                unset($_SESSION[self::SESSION_KEY][$produitId]);
                continue;
            }

            $lignes[] = [
                'produit' => $produit,
                'quantite' => $quantite,
                'sousTotal' => $produit->getPrix() * $quantite,
            ];
        }

        return $lignes;
    }

    public function getTotal(): float
    {
        return array_reduce(
            $this->getLignes(),
            fn (float $total, array $ligne) => $total + $ligne['sousTotal'],
            0.0
        );
    }

    public function getNombreArticles(): int
    {
        return array_sum($_SESSION[self::SESSION_KEY]);
    }
}
