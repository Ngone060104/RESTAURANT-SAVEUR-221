<?php

$produits = $produits ?? [];
$categories = $categories ?? [];
$form = $form ?? [];
$erreurs = $erreurs ?? [];

$editId = $editId ?? null;
$produitEdition = $produitEdition ?? null;

$deleteId = $deleteId ?? null;
$produitSuppression = $produitSuppression ?? null;

$message = $message ?? null;
$messageFormulaire = $messageFormulaire ?? null;

$terme = $terme ?? '';
$categorieActive = $categorieActive ?? null;
$statutActive = $statutActive ?? '';

/*
|--------------------------------------------------------------------------
| État des modals
|--------------------------------------------------------------------------
*/

$modeEdition =
    $editId !== null
    && $produitEdition !== null;

$ouvrirModal =
    $modeEdition
    || !empty($erreurs)
    || $messageFormulaire !== null;

$ouvrirSuppression =
    $deleteId !== null
    && $produitSuppression !== null;

/*
|--------------------------------------------------------------------------
| Valeurs du formulaire
|--------------------------------------------------------------------------
*/

$valeur = static function (
    string $champ,
    mixed $defaut = ''
) use ($form, $produitEdition): mixed {

    if (array_key_exists($champ, $form)) {
        return $form[$champ];
    }

    if ($produitEdition !== null) {
        return match ($champ) {
            'nom' =>
                $produitEdition->getNom(),

            'description' =>
                $produitEdition->getDescription() ?? '',

            'prix' =>
                $produitEdition->getPrix(),

            'stock' =>
                $produitEdition->getStock(),

            'image' =>
                $produitEdition->getImage() ?? '',

            'categorie_id' =>
                $produitEdition->getCategorieId(),

            default =>
                $defaut,
        };
    }

    return $defaut;
};

/*
|--------------------------------------------------------------------------
| Erreur d'un champ
|--------------------------------------------------------------------------
*/

$erreurChamp = static function (
    string $champ
) use ($erreurs): ?string {

    return $erreurs[$champ] ?? null;
};

/*
|--------------------------------------------------------------------------
| Classe d'un champ en erreur
|--------------------------------------------------------------------------
*/

$classeChamp = static function (
    string $champ
) use ($erreurs): string {

    return isset($erreurs[$champ])
        ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
        : 'border-gray-200 focus:border-orange-400 focus:ring-orange-100';
};

/*
|--------------------------------------------------------------------------
| Image produit
|--------------------------------------------------------------------------
*/

$imageProduit = static function ($produit): string {

    $image = $produit->getImage() ?? '';

    if ($image === '') {
        return 'https://placehold.co/100x100/f3f4f6/9ca3af?text=Plat';
    }

    if (
        str_starts_with($image, 'http://')
        || str_starts_with($image, 'https://')
        || str_starts_with($image, '/')
    ) {
        return $image;
    }

    return '/' . ltrim($image, '/');
};

?>

<!-- ========================================================= -->
<!-- CONTENU PRINCIPAL -->
<!-- ========================================================= -->

<main class="sm:px-6 lg:px-8">

    <!-- ===================================================== -->
    <!-- HERO -->
    <!-- ===================================================== -->

    <section
        class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-orange-900 shadow-sm">

        <div
            class="flex flex-col gap-5 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <div class="mb-2 flex items-center gap-2 text-orange-300">

                    <i class="fa-solid fa-utensils"></i>

                    <span
                        class="text-xs font-semibold uppercase tracking-wider">
                        Carte du restaurant
                    </span>

                </div>

                <h1
                    class="text-2xl font-bold text-white sm:text-3xl">
                    Gestion des Produits &amp; Carte
                </h1>

                <p
                    class="mt-2 max-w-2xl text-sm text-gray-300">
                    Gérez les plats, leurs catégories, leurs prix et leurs stocks.
                </p>

            </div>

            <button
                type="button"
                onclick="ouvrirModalProduit()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-300/30">

                <i class="fa-solid fa-plus"></i>

                Nouveau Produit

            </button>

        </div>

    </section>


    <!-- ===================================================== -->
    <!-- FILTRES -->
    <!-- ===================================================== -->

    <section
        class="mb-6 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">

        <form
            id="formFiltresProduits"
            class="grid grid-cols-1 gap-3 lg:grid-cols-12"
            onsubmit="rechercherProduit(event)">

            <!-- Recherche -->

            <div class="lg:col-span-5">

                <label
                    for="q"
                    class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Recherche
                </label>

                <div class="relative">

                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">
                    </i>

                    <input
                        id="q"
                        type="text"
                        value="<?= htmlspecialchars($terme) ?>"
                        placeholder="Rechercher un produit..."
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 pl-9 pr-3 text-sm text-gray-700 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">

                </div>

            </div>


            <!-- Disponibilité -->

            <div class="lg:col-span-3">

                <label
                    for="statut"
                    class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Disponibilité
                </label>

                <select
                    id="statut"
                    onchange="filtrerParStatut(this.value)"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-700 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">

                    <option value="">
                        Tous les produits
                    </option>

                    <option
                        value="disponible"
                        <?= $statutActive === 'disponible' ? 'selected' : '' ?>>
                        Disponible
                    </option>

                    <option
                        value="en_rupture"
                        <?= $statutActive === 'en_rupture' ? 'selected' : '' ?>>
                        En rupture
                    </option>

                </select>

            </div>


            <!-- Catégorie -->

            <div class="lg:col-span-3">

                <label
                    for="categorie"
                    class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Catégorie
                </label>

                <select
                    id="categorie"
                    onchange="filtrerParCategorie(this.value)"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-700 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">

                    <option value="">
                        Toutes les catégories
                    </option>

                    <?php foreach ($categories as $categorie): ?>

                        <option
                            value="<?= (int) $categorie->getId() ?>"
                            <?= (int) $categorieActive === (int) $categorie->getId()
                                ? 'selected'
                                : '' ?>>

                            <?= htmlspecialchars(
                                $categorie->getLibelle()
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- Bouton recherche -->

            <div class="flex items-end lg:col-span-1">

                <button
                    type="submit"
                    title="Rechercher"
                    class="h-11 w-full rounded-xl bg-gray-900 text-white transition hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-200 lg:w-11">

                    <i class="fa-solid fa-filter"></i>

                </button>

            </div>

        </form>

    </section>


    <!-- ===================================================== -->
    <!-- MESSAGE GLOBAL -->
    <!-- ===================================================== -->

    <?php if ($message !== null): ?>

        <div
            class="mb-5 flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">

            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

            <span>
                <?= htmlspecialchars($message) ?>
            </span>

        </div>

    <?php endif; ?>


    <!-- ===================================================== -->
    <!-- TABLEAU DES PRODUITS -->
    <!-- ===================================================== -->

    <section
        class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        <!-- ================================================= -->
        <!-- DESKTOP -->
        <!-- ================================================= -->

        <div class="hidden overflow-x-auto lg:block">

            <table class="w-full min-w-[850px]">

                <thead>

                    <tr
                        class="border-b border-gray-100 bg-gray-50/80">

                        <th
                            class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            Plat / Image
                        </th>

                        <th
                            class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            Catégorie
                        </th>

                        <th
                            class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            Prix
                        </th>

                        <th
                            class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            Stock
                        </th>

                        <th
                            class="px-5 py-4 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    <?php if (empty($produits)): ?>

                        <tr>

                            <td
                                colspan="5"
                                class="px-5 py-16 text-center">

                                <div
                                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                                    <i class="fa-solid fa-utensils text-xl"></i>

                                </div>

                                <p
                                    class="mt-4 text-sm font-semibold text-gray-700">
                                    Aucun produit trouvé
                                </p>

                                <p
                                    class="mt-1 text-xs text-gray-400">
                                    Essayez de modifier vos filtres.
                                </p>

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($produits as $produit): ?>

                            <?php

                            $stock = (int) $produit->getStock();

                            $stockDisponible = $stock > 0;

                            ?>

                            <tr
                                class="transition hover:bg-orange-50/30">

                                <!-- Produit -->

                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <img
                                            src="<?= htmlspecialchars(
                                                $imageProduit($produit)
                                            ) ?>"
                                            alt="<?= htmlspecialchars(
                                                $produit->getNom()
                                            ) ?>"
                                            class="h-12 w-12 rounded-xl object-cover ring-1 ring-gray-100">

                                        <div class="min-w-0">

                                            <p
                                                class="truncate text-sm font-semibold text-gray-800">

                                                <?= htmlspecialchars(
                                                    $produit->getNom()
                                                ) ?>

                                            </p>

                                            <?php if ($produit->getDescription()): ?>

                                                <p
                                                    class="mt-0.5 max-w-[280px] truncate text-xs text-gray-400">

                                                    <?= htmlspecialchars(
                                                        $produit->getDescription()
                                                    ) ?>

                                                </p>

                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </td>


                                <!-- Catégorie -->

                                <td class="px-5 py-4">

                                    <span
                                        class="inline-flex rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-600">

                                        <?= htmlspecialchars(
                                            $produit->getCategorieLibelle()
                                                ?? 'Sans catégorie'
                                        ) ?>

                                    </span>

                                </td>


                                <!-- Prix -->

                                <td class="px-5 py-4">

                                    <span
                                        class="text-sm font-bold text-orange-500">

                                        <?= number_format(
                                            (float) $produit->getPrix(),
                                            0,
                                            ',',
                                            ' '
                                        ) ?>

                                        FCFA

                                    </span>

                                </td>


                                <!-- Stock -->

                                <td class="px-5 py-4">

                                    <?php if ($stockDisponible): ?>

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-600">

                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-green-500">
                                            </span>

                                            <?= $stock ?>

                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">

                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-red-500">
                                            </span>

                                            Rupture

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- Actions -->

                                <td class="px-5 py-4">

                                    <div
                                        class="flex justify-end gap-1">

                                        <a
                                            href="/gerant/produits/update/<?= (int) $produit->getId() ?>"
                                            title="Modifier"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-orange-50 hover:text-orange-500">

                                            <i class="fa-solid fa-pen-to-square"></i>

                                        </a>


                                        <a
                                            href="/gerant/produits/delete/<?= (int) $produit->getId() ?>"
                                            title="Supprimer"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-red-50 hover:text-red-500">

                                            <i class="fa-solid fa-trash"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>


        <!-- ================================================= -->
        <!-- MOBILE -->
        <!-- ================================================= -->

        <div class="divide-y divide-gray-100 lg:hidden">

            <?php if (empty($produits)): ?>

                <div class="px-5 py-16 text-center">

                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                        <i class="fa-solid fa-utensils text-xl"></i>

                    </div>

                    <p
                        class="mt-4 text-sm font-semibold text-gray-700">
                        Aucun produit trouvé
                    </p>

                    <p
                        class="mt-1 text-xs text-gray-400">
                        Essayez de modifier vos filtres.
                    </p>

                </div>

            <?php else: ?>

                <?php foreach ($produits as $produit): ?>

                    <?php

                    $stock = (int) $produit->getStock();

                    $stockDisponible = $stock > 0;

                    ?>

                    <div class="p-4">

                        <div class="flex items-start gap-3">

                            <img
                                src="<?= htmlspecialchars(
                                    $imageProduit($produit)
                                ) ?>"
                                alt="<?= htmlspecialchars(
                                    $produit->getNom()
                                ) ?>"
                                class="h-16 w-16 shrink-0 rounded-xl object-cover ring-1 ring-gray-100">


                            <div class="min-w-0 flex-1">

                                <div
                                    class="flex items-start justify-between gap-3">

                                    <div class="min-w-0">

                                        <p
                                            class="truncate text-sm font-semibold text-gray-800">

                                            <?= htmlspecialchars(
                                                $produit->getNom()
                                            ) ?>

                                        </p>

                                        <span
                                            class="mt-1 inline-flex rounded-full bg-orange-50 px-2.5 py-1 text-[10px] font-semibold text-orange-600">

                                            <?= htmlspecialchars(
                                                $produit->getCategorieLibelle()
                                                    ?? 'Sans catégorie'
                                            ) ?>

                                        </span>

                                    </div>


                                    <!-- Actions -->

                                    <div
                                        class="flex shrink-0 gap-1">

                                        <a
                                            href="/gerant/produits/update/<?= (int) $produit->getId() ?>"
                                            title="Modifier"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 transition hover:bg-orange-50 hover:text-orange-500">

                                            <i class="fa-solid fa-pen-to-square text-sm"></i>

                                        </a>

                                        <a
                                            href="/gerant/produits/delete/<?= (int) $produit->getId() ?>"
                                            title="Supprimer"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition hover:bg-red-50 hover:text-red-500">

                                            <i class="fa-solid fa-trash text-sm"></i>

                                        </a>

                                    </div>

                                </div>


                                <div
                                    class="mt-3 flex items-center justify-between">

                                    <span
                                        class="text-sm font-bold text-orange-500">

                                        <?= number_format(
                                            (float) $produit->getPrix(),
                                            0,
                                            ',',
                                            ' '
                                        ) ?>

                                        FCFA

                                    </span>


                                    <?php if ($stockDisponible): ?>

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-semibold text-green-600">

                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-green-500">
                                            </span>

                                            Stock :
                                            <?= $stock ?>

                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-[10px] font-semibold text-red-600">

                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-red-500">
                                            </span>

                                            Rupture

                                        </span>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </section>

</main>


<!-- ========================================================= -->
<!-- MODAL PRODUIT : AJOUT / MODIFICATION -->
<!-- ========================================================= -->

<div
    id="modalProduit"
    class="<?= $ouvrirModal ? 'flex' : 'hidden' ?> fixed inset-0 z-[100] items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm"
    aria-hidden="<?= $ouvrirModal ? 'false' : 'true' ?>">

    <div
        id="contenuModalProduit"
        class="max-h-[92vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl">


        <!-- Header -->

        <div
            class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-100 bg-white px-5 py-4 sm:px-6">

            <div class="flex items-center gap-3">

                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-500">

                    <i
                        class="<?= $modeEdition
                            ? 'fa-solid fa-pen-to-square'
                            : 'fa-solid fa-plus'
                        ?>">
                    </i>

                </div>

                <div>

                    <h2
                        class="text-base font-bold text-gray-800">

                        <?= $modeEdition
                            ? 'Modifier le produit'
                            : 'Nouveau Produit'
                        ?>

                    </h2>

                    <p
                        class="text-xs text-gray-400">

                        <?= $modeEdition
                            ? 'Modifiez les informations du produit.'
                            : 'Ajoutez un nouveau plat à votre carte.'
                        ?>

                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="fermerModalProduit()"
                title="Fermer"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>


        <!-- Formulaire -->

        <form
            method="POST"
            action="<?= $modeEdition
                ? '/gerant/produits/update/' . (int) $editId
                : '/gerant/produits'
            ?>"
            enctype="multipart/form-data"
            class="p-5 sm:p-6">


            <!-- Message général -->

            <?php if (
                $messageFormulaire !== null
                && empty($erreurs)
            ): ?>

                <div
                    class="mb-5 flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">

                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                    <span>
                        <?= htmlspecialchars(
                            $messageFormulaire
                        ) ?>
                    </span>

                </div>

            <?php endif; ?>


            <!-- ================================================= -->
            <!-- NOM -->
            <!-- ================================================= -->

            <div class="mb-4">

                <label
                    for="nom"
                    class="mb-1.5 block text-xs font-semibold text-gray-700">

                    Nom du produit

                    <span class="text-red-500">*</span>

                </label>


                <input
                    id="nom"
                    name="nom"
                    type="text"
                    value="<?= htmlspecialchars(
                        (string) $valeur('nom')
                    ) ?>"
                    class="<?= $classeChamp('nom') ?> h-11 w-full rounded-xl border bg-gray-50 px-3 text-sm text-gray-700 outline-none transition focus:bg-white focus:ring-4"
                    placeholder="Ex : Thiéboudienne">


                <?php if ($erreurChamp('nom')): ?>

                    <p
                        class="mt-1.5 flex items-start gap-1 text-xs text-red-500">

                        <i
                            class="fa-solid fa-circle-exclamation mt-0.5">
                        </i>

                        <span>
                            <?= htmlspecialchars(
                                $erreurChamp('nom')
                            ) ?>
                        </span>

                    </p>

                <?php endif; ?>

            </div>


            <!-- ================================================= -->
            <!-- DESCRIPTION -->
            <!-- ================================================= -->

            <div class="mb-4">

                <label
                    for="description"
                    class="mb-1.5 block text-xs font-semibold text-gray-700">

                    Description

                </label>


                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="<?= $classeChamp('description') ?> w-full resize-none rounded-xl border bg-gray-50 px-3 py-3 text-sm text-gray-700 outline-none transition focus:bg-white focus:ring-4"
                    placeholder="Décrivez brièvement le plat..."><?= htmlspecialchars(
                        (string) $valeur('description')
                    ) ?></textarea>


                <?php if ($erreurChamp('description')): ?>

                    <p
                        class="mt-1.5 flex items-start gap-1 text-xs text-red-500">

                        <i
                            class="fa-solid fa-circle-exclamation mt-0.5">
                        </i>

                        <span>
                            <?= htmlspecialchars(
                                $erreurChamp('description')
                            ) ?>
                        </span>

                    </p>

                <?php endif; ?>

            </div>


            <!-- ================================================= -->
            <!-- PRIX / STOCK -->
            <!-- ================================================= -->

            <div
                class="grid grid-cols-1 gap-4 sm:grid-cols-2">


                <!-- Prix -->

                <div>

                    <label
                        for="prix"
                        class="mb-1.5 block text-xs font-semibold text-gray-700">

                        Prix

                        <span class="text-red-500">*</span>

                    </label>


                    <div class="relative">

                        <input
                            id="prix"
                            name="prix"
                            type="number"
                            min="0"
                            step="1"
                            value="<?= htmlspecialchars(
                                (string) $valeur('prix')
                            ) ?>"
                            class="<?= $classeChamp('prix') ?> h-11 w-full rounded-xl border bg-gray-50 px-3 pr-16 text-sm text-gray-700 outline-none transition focus:bg-white focus:ring-4"
                            placeholder="0">

                        <span
                            class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400">

                            FCFA

                        </span>

                    </div>


                    <?php if ($erreurChamp('prix')): ?>

                        <p
                            class="mt-1.5 flex items-start gap-1 text-xs text-red-500">

                            <i
                                class="fa-solid fa-circle-exclamation mt-0.5">
                            </i>

                            <span>
                                <?= htmlspecialchars(
                                    $erreurChamp('prix')
                                ) ?>
                            </span>

                        </p>

                    <?php endif; ?>

                </div>


                <!-- Stock -->

                <div>

                    <label
                        for="stock"
                        class="mb-1.5 block text-xs font-semibold text-gray-700">

                        Stock

                        <span class="text-red-500">*</span>

                    </label>


                    <input
                        id="stock"
                        name="stock"
                        type="number"
                        min="0"
                        step="1"
                        value="<?= htmlspecialchars(
                            (string) $valeur('stock')
                        ) ?>"
                        class="<?= $classeChamp('stock') ?> h-11 w-full rounded-xl border bg-gray-50 px-3 text-sm text-gray-700 outline-none transition focus:bg-white focus:ring-4"
                        placeholder="0">


                    <?php if ($erreurChamp('stock')): ?>

                        <p
                            class="mt-1.5 flex items-start gap-1 text-xs text-red-500">

                            <i
                                class="fa-solid fa-circle-exclamation mt-0.5">
                            </i>

                            <span>
                                <?= htmlspecialchars(
                                    $erreurChamp('stock')
                                ) ?>
                            </span>

                        </p>

                    <?php endif; ?>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- CATÉGORIE -->
            <!-- ================================================= -->

            <div class="mt-4">

                <label
                    for="categorie_id"
                    class="mb-1.5 block text-xs font-semibold text-gray-700">

                    Catégorie

                    <span class="text-red-500">*</span>

                </label>


                <select
                    id="categorie_id"
                    name="categorie_id"
                    class="<?= $classeChamp('categorie_id') ?> h-11 w-full rounded-xl border bg-gray-50 px-3 text-sm text-gray-700 outline-none transition focus:bg-white focus:ring-4">

                    <option value="">
                        Sélectionner une catégorie
                    </option>


                    <?php foreach ($categories as $categorie): ?>

                        <option
                            value="<?= (int) $categorie->getId() ?>"
                            <?= (string) $valeur('categorie_id')
                                === (string) $categorie->getId()
                                ? 'selected'
                                : ''
                            ?>>

                            <?= htmlspecialchars(
                                $categorie->getLibelle()
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <?php if ($erreurChamp('categorie_id')): ?>

                    <p
                        class="mt-1.5 flex items-start gap-1 text-xs text-red-500">

                        <i
                            class="fa-solid fa-circle-exclamation mt-0.5">
                        </i>

                        <span>
                            <?= htmlspecialchars(
                                $erreurChamp('categorie_id')
                            ) ?>
                        </span>

                    </p>

                <?php endif; ?>

            </div>


            <!-- ================================================= -->
            <!-- IMAGE -->
            <!-- ================================================= -->

            <div class="mt-4">

                <label
                    for="produit_image"
                    class="mb-1.5 block text-xs font-semibold text-gray-700">

                    Image du produit

                </label>


                <?php if (
                    $modeEdition
                    && $produitEdition !== null
                    && $produitEdition->getImage()
                ): ?>

                    <div
                        class="mb-3 flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 p-3">

                        <img
                            src="<?= htmlspecialchars(
                                $imageProduit($produitEdition)
                            ) ?>"
                            alt="<?= htmlspecialchars(
                                $produitEdition->getNom()
                            ) ?>"
                            class="h-16 w-16 rounded-xl object-cover ring-1 ring-gray-200">


                        <div class="min-w-0">

                            <p
                                class="text-xs font-semibold text-gray-700">
                                Image actuelle
                            </p>

                            <p
                                class="mt-0.5 text-[11px] text-gray-400">
                                Sélectionnez un nouveau fichier pour la remplacer.
                            </p>

                        </div>

                    </div>

                <?php endif; ?>


                <label
                    for="produit_image"
                    class="<?= $classeChamp('image') ?> flex h-24 w-full cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed bg-gray-50 px-4 text-center transition hover:border-orange-400 hover:bg-orange-50/40">

                    <i
                        class="fa-solid fa-cloud-arrow-up text-xl text-gray-400">
                    </i>

                    <span
                        id="nomFichierImage"
                        class="mt-2 text-xs text-[#777777]">

                        Aucun fichier sélectionné

                    </span>

                    <span
                        class="mt-1 text-[10px] text-gray-400">

                        JPG, JPEG, PNG ou WEBP

                    </span>


                    <input
                        id="produit_image"
                        name="image"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="hidden">

                </label>


                <?php if ($erreurChamp('image')): ?>

                    <p
                        class="mt-1.5 flex items-start gap-1 text-xs text-red-500">

                        <i
                            class="fa-solid fa-circle-exclamation mt-0.5">
                        </i>

                        <span>
                            <?= htmlspecialchars(
                                $erreurChamp('image')
                            ) ?>
                        </span>

                    </p>

                <?php endif; ?>

            </div>


            <!-- ================================================= -->
            <!-- ACTIONS -->
            <!-- ================================================= -->

            <div
                class="mt-6 flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    onclick="fermerModalProduit()"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-gray-200 px-5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">

                    Annuler

                </button>


                <button
                    type="submit"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-orange-500 px-5 text-sm font-semibold text-white transition hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-100">

                    <i
                        class="<?= $modeEdition
                            ? 'fa-solid fa-floppy-disk'
                            : 'fa-solid fa-plus'
                        ?>">
                    </i>

                    <?= $modeEdition
                        ? 'Enregistrer les modifications'
                        : 'Créer le produit'
                    ?>

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- MODAL SUPPRESSION -->
<!-- ========================================================= -->

<div
    id="modalSuppression"
    class="<?= $ouvrirSuppression ? 'flex' : 'hidden' ?> fixed inset-0 z-[110] items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm"
    aria-hidden="<?= $ouvrirSuppression ? 'false' : 'true' ?>">

    <div
        id="contenuModalSuppression"
        class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">

        <div class="p-6 text-center">


            <!-- Icône -->

            <div
                class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500">

                <i class="fa-solid fa-trash text-xl"></i>

            </div>


            <!-- Titre -->

            <h2
                class="mt-4 text-lg font-bold text-gray-800">

                Supprimer le produit ?

            </h2>


            <!-- Texte -->

            <?php if ($produitSuppression !== null): ?>

                <p
                    class="mx-auto mt-2 max-w-sm text-sm leading-6 text-gray-500">

                    Vous êtes sur le point de supprimer

                    <span class="font-semibold text-gray-700">

                        « <?= htmlspecialchars(
                            $produitSuppression->getNom()
                        ) ?> »

                    </span>.

                    Cette action est irréversible.

                </p>

            <?php else: ?>

                <p
                    class="mx-auto mt-2 max-w-sm text-sm leading-6 text-gray-500">

                    Cette action est irréversible.
                    Voulez-vous vraiment supprimer ce produit ?

                </p>

            <?php endif; ?>


            <!-- Actions -->

            <div
                class="mt-6 grid grid-cols-2 gap-3">

                <button
                    type="button"
                    onclick="fermerModalSuppression()"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-gray-200 px-4 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">

                    Annuler

                </button>


                <form
                    method="POST"
                    action="<?= $deleteId !== null
                        ? '/gerant/produits/delete/' . (int) $deleteId
                        : '#'
                    ?>">

                    <button
                        type="submit"
                        class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-red-500 px-4 text-sm font-semibold text-white transition hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-100">

                        <i class="fa-solid fa-trash"></i>

                        Supprimer

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->

<script>

/*
|--------------------------------------------------------------------------
| MODAL PRODUIT
|--------------------------------------------------------------------------
*/

function ouvrirModalProduit() {

    const modal =
        document.getElementById('modalProduit');

    if (!modal) {
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    modal.setAttribute(
        'aria-hidden',
        'false'
    );

    document.body.classList.add(
        'overflow-hidden'
    );
}


function fermerModalProduit() {

    const modal =
        document.getElementById('modalProduit');

    if (!modal) {
        return;
    }

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    modal.setAttribute(
        'aria-hidden',
        'true'
    );

    document.body.classList.remove(
        'overflow-hidden'
    );


    if (
        window.location.pathname.startsWith(
            '/gerant/produits/update/'
        )
    ) {

        window.history.replaceState(
            {},
            '',
            '/gerant/produits'
        );

    }

}


/*
|--------------------------------------------------------------------------
| MODAL SUPPRESSION
|--------------------------------------------------------------------------
*/

function ouvrirModalSuppression() {

    const modal =
        document.getElementById(
            'modalSuppression'
        );

    if (!modal) {
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    modal.setAttribute(
        'aria-hidden',
        'false'
    );

    document.body.classList.add(
        'overflow-hidden'
    );
}


function fermerModalSuppression() {

    const modal =
        document.getElementById(
            'modalSuppression'
        );

    if (!modal) {
        return;
    }

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    modal.setAttribute(
        'aria-hidden',
        'true'
    );

    document.body.classList.remove(
        'overflow-hidden'
    );


    if (
        window.location.pathname.startsWith(
            '/gerant/produits/delete/'
        )
    ) {

        window.history.replaceState(
            {},
            '',
            '/gerant/produits'
        );

    }

}


/*
|--------------------------------------------------------------------------
| RECHERCHE
|--------------------------------------------------------------------------
*/

function rechercherProduit(event) {

    event.preventDefault();

    const input =
        document.getElementById('q');

    if (!input) {
        return;
    }

    const terme =
        input.value.trim();

    if (terme === '') {

        window.location.href =
            '/gerant/produits';

        return;
    }

    window.location.href =
        '/gerant/produits/recherche/' +
        encodeURIComponent(terme);
}


/*
|--------------------------------------------------------------------------
| FILTRE CATÉGORIE
|--------------------------------------------------------------------------
*/

function filtrerParCategorie(id) {

    if (!id) {

        window.location.href =
            '/gerant/produits';

        return;
    }

    window.location.href =
        '/gerant/produits/categorie/' +
        encodeURIComponent(id);
}


/*
|--------------------------------------------------------------------------
| FILTRE STATUT
|--------------------------------------------------------------------------
*/

function filtrerParStatut(statut) {

    if (!statut) {

        window.location.href =
            '/gerant/produits';

        return;
    }

    window.location.href =
        '/gerant/produits/statut/' +
        encodeURIComponent(statut);
}


/*
|--------------------------------------------------------------------------
| INITIALISATION
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const modalProduit =
            document.getElementById(
                'modalProduit'
            );

        const contenuModalProduit =
            document.getElementById(
                'contenuModalProduit'
            );

        const modalSuppression =
            document.getElementById(
                'modalSuppression'
            );

        const contenuModalSuppression =
            document.getElementById(
                'contenuModalSuppression'
            );


        /*
        |----------------------------------------------------------------------
        | Clic sur le fond du modal produit
        |----------------------------------------------------------------------
        */

        if (modalProduit) {

            modalProduit.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target ===
                        modalProduit
                    ) {

                        fermerModalProduit();

                    }

                }
            );

        }


        /*
        |----------------------------------------------------------------------
        | Empêcher fermeture modal produit
        |----------------------------------------------------------------------
        */

        if (contenuModalProduit) {

            contenuModalProduit.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();

                }
            );

        }


        /*
        |----------------------------------------------------------------------
        | Clic sur le fond du modal suppression
        |----------------------------------------------------------------------
        */

        if (modalSuppression) {

            modalSuppression.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target ===
                        modalSuppression
                    ) {

                        fermerModalSuppression();

                    }

                }
            );

        }


        /*
        |----------------------------------------------------------------------
        | Empêcher fermeture modal suppression
        |----------------------------------------------------------------------
        */

        if (contenuModalSuppression) {

            contenuModalSuppression.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();

                }
            );

        }


        /*
        |----------------------------------------------------------------------
        | Touche Échap
        |----------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function (event) {

                if (event.key !== 'Escape') {
                    return;
                }


                if (
                    modalSuppression
                    && !modalSuppression.classList.contains(
                        'hidden'
                    )
                ) {

                    fermerModalSuppression();

                    return;

                }


                if (
                    modalProduit
                    && !modalProduit.classList.contains(
                        'hidden'
                    )
                ) {

                    fermerModalProduit();

                }

            }
        );


        /*
        |----------------------------------------------------------------------
        | Ouverture automatique du modal produit
        |----------------------------------------------------------------------
        */

        <?php if ($ouvrirModal): ?>

            ouvrirModalProduit();

        <?php endif; ?>


        /*
        |----------------------------------------------------------------------
        | Ouverture automatique suppression
        |----------------------------------------------------------------------
        */

        <?php if ($ouvrirSuppression): ?>

            ouvrirModalSuppression();

        <?php endif; ?>

    }
);


/*
|--------------------------------------------------------------------------
| Nom du fichier image
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const inputImage =
            document.getElementById(
                'produit_image'
            );

        const nomFichier =
            document.getElementById(
                'nomFichierImage'
            );

        if (
            !inputImage
            || !nomFichier
        ) {
            return;
        }


        inputImage.addEventListener(
            'change',
            function () {

                if (
                    this.files
                    && this.files.length > 0
                ) {

                    nomFichier.textContent =
                        this.files[0].name;

                    nomFichier.classList.remove(
                        'text-[#777777]'
                    );

                    nomFichier.classList.add(
                        'font-semibold',
                        'text-[#ff9900]'
                    );

                } else {

                    nomFichier.textContent =
                        'Aucun fichier sélectionné';

                    nomFichier.classList.remove(
                        'font-semibold',
                        'text-[#ff9900]'
                    );

                    nomFichier.classList.add(
                        'text-[#777777]'
                    );

                }

            }
        );

    }
);

</script>