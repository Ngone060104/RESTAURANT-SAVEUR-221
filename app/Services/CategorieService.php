<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;

class CategorieService
{
    public function __construct(private CategorieRepository $categorieRepository)
    {
    }

    public function create(array $data): int
    {
        $this->validate($data);

        if ($this->categorieRepository->findByLibelle($data['libelle']) !== null) {
            throw new ValidationException('Cette catégorie existe déjà.');
        }

        return $this->categorieRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $this->validate($data);

        $existante = $this->categorieRepository->findByLibelle($data['libelle']);

        if ($existante !== null && (int) $existante->id !== $id) {
            throw new ValidationException('Une autre catégorie porte déjà ce libellé.');
        }

        return $this->categorieRepository->update($id, $data);
    }

    /**
     * Règle métier n°9 (catégorie avec produits non supprimable) est déjà
     * appliquée dans le repository ; on se contente de relayer l'appel.
     */
    public function delete(int $id): bool
    {
        return $this->categorieRepository->delete($id);
    }

    private function validate(array $data): void
    {
        if (empty($data['libelle'])) {
            throw new ValidationException('Le libellé de la catégorie est obligatoire.');
        }
    }
}
