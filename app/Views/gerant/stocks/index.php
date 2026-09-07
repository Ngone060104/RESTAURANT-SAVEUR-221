<?php

$produits = $produits ?? [];

$nombreProduits = $nombreProduits ?? 0;
$nombreStockFaible = $nombreStockFaible ?? 0;
$nombreEnRupture = $nombreEnRupture ?? 0;


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

<main class="sm:px-6 lg:px-8">



    <!-- ===================================================== -->
    <!-- EN-TÊTE -->
    <!-- ===================================================== -->

    <section
        class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-orange-900 shadow-sm">

        <div
            class="flex flex-col gap-5 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <div class="mb-2 flex items-center gap-2 text-orange-300">

                    <i class="fa-solid fa-boxes-stacked"></i>

                    <span
                        class="text-xs font-semibold uppercase tracking-wider">
                        Gestion des stocks
                    </span>

                </div>

                <h1
                    class="text-2xl font-bold text-white sm:text-3xl">
                    Gestion des stocks
                </h1>

                <p
                    class="mt-2 max-w-2xl text-sm text-gray-300">
                    Suivez les niveaux de stock et approvisionnez vos produits.
                </p>

            </div>
        </div>
    </section>


    <!-- ===================================================== -->
    <!-- STATISTIQUES -->
    <!-- ===================================================== -->

   <!-- ===================================================== -->
<!-- STATISTIQUES -->
<!-- ===================================================== -->
<section class="mb-6">

 


    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

        <!-- ================================================= -->
        <!-- TOTAL PRODUITS -->
        <!-- ================================================= -->
        <div
            class="group relative cursor-pointer overflow-hidden rounded-2xl
                   border border-gray-100 bg-white p-6 shadow-sm
                   transition-all duration-300 ease-out
                   hover:-translate-y-1.5 hover:shadow-xl
                   active:-translate-y-2 active:shadow-2xl"
        >

            <!-- Petit accent -->
            <div
                class="absolute left-0 top-0 h-1 w-0 rounded-r-full bg-blue-500
                       transition-all duration-300
                       group-hover:w-full"
            ></div>

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Total produits
                    </p>

                    <p
                        class="mt-3 text-3xl font-bold tracking-tight text-gray-900
                               transition-all duration-300
                               group-hover:translate-x-0.5"
                    >
                        <?= (int) $nombreProduits ?>
                    </p>

                    <div class="mt-3 flex items-center gap-2">
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-blue-500"
                        ></span>

                        <p class="text-xs font-medium text-gray-400">
                            Produits enregistrés
                        </p>
                    </div>
                </div>


                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center
                           rounded-2xl bg-blue-50
                           transition-all duration-300
                           group-hover:scale-110 group-hover:rotate-3"
                >
                    <i
                        class="fa-solid fa-box text-xl text-blue-600
                               transition-transform duration-300
                               group-hover:scale-110"
                    ></i>
                </div>

            </div>

            <!-- Flèche discrète -->
            <div
                class="absolute bottom-5 right-6 opacity-0
                       translate-x-2
                       transition-all duration-300
                       group-hover:translate-x-0 group-hover:opacity-100"
            >
                <i class="fa-solid fa-arrow-up-right text-xs text-blue-500"></i>
            </div>

        </div>


        <!-- ================================================= -->
        <!-- STOCK FAIBLE -->
        <!-- ================================================= -->
        <div
            class="group relative cursor-pointer overflow-hidden rounded-2xl
                   border border-gray-100 bg-white p-6 shadow-sm
                   transition-all duration-300 ease-out
                   hover:-translate-y-1.5 hover:shadow-xl
                   active:-translate-y-2 active:shadow-2xl"
        >

            <!-- Petit accent -->
            <div
                class="absolute left-0 top-0 h-1 w-0 rounded-r-full bg-[#ff9500]
                       transition-all duration-300
                       group-hover:w-full"
            ></div>

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Stock faible
                    </p>

                    <p
                        class="mt-3 text-3xl font-bold tracking-tight text-gray-900
                               transition-all duration-300
                               group-hover:translate-x-0.5"
                    >
                        <?= (int) $nombreStockFaible ?>
                    </p>

                    <div class="mt-3 flex items-center gap-2">
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-[#ff9500]"
                        ></span>

                        <p class="text-xs font-medium text-gray-400">
                            Produits à surveiller
                        </p>
                    </div>
                </div>


                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center
                           rounded-2xl bg-orange-50
                           transition-all duration-300
                           group-hover:scale-110 group-hover:rotate-3"
                >
                    <i
                        class="fa-solid fa-triangle-exclamation text-xl text-[#ff9500]
                               transition-transform duration-300
                               group-hover:scale-110"
                    ></i>
                </div>

            </div>

            <!-- Flèche discrète -->
            <div
                class="absolute bottom-5 right-6 opacity-0
                       translate-x-2
                       transition-all duration-300
                       group-hover:translate-x-0 group-hover:opacity-100"
            >
                <i class="fa-solid fa-arrow-up-right text-xs text-[#ff9500]"></i>
            </div>

        </div>


        <!-- ================================================= -->
        <!-- RUPTURE -->
        <!-- ================================================= -->
        <div
            class="group relative cursor-pointer overflow-hidden rounded-2xl
                   border border-gray-100 bg-white p-6 shadow-sm
                   transition-all duration-300 ease-out
                   hover:-translate-y-1.5 hover:shadow-xl
                   active:-translate-y-2 active:shadow-2xl"
        >

            <!-- Petit accent -->
            <div
                class="absolute left-0 top-0 h-1 w-0 rounded-r-full bg-red-500
                       transition-all duration-300
                       group-hover:w-full"
            ></div>

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        En rupture
                    </p>

                    <p
                        class="mt-3 text-3xl font-bold tracking-tight text-gray-900
                               transition-all duration-300
                               group-hover:translate-x-0.5"
                    >
                        <?= (int) $nombreEnRupture ?>
                    </p>

                    <div class="mt-3 flex items-center gap-2">
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-red-500"
                        ></span>

                        <p class="text-xs font-medium text-gray-400">
                            Produits indisponibles
                        </p>
                    </div>
                </div>


                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center
                           rounded-2xl bg-red-50
                           transition-all duration-300
                           group-hover:scale-110 group-hover:rotate-3"
                >
                    <i
                        class="fa-solid fa-circle-xmark text-xl text-red-500
                               transition-transform duration-300
                               group-hover:scale-110"
                    ></i>
                </div>

            </div>

            <!-- Flèche discrète -->
            <div
                class="absolute bottom-5 right-6 opacity-0
                       translate-x-2
                       transition-all duration-300
                       group-hover:translate-x-0 group-hover:opacity-100"
            >
                <i class="fa-solid fa-arrow-up-right text-xs text-red-500"></i>
            </div>

        </div>

    </div>

</section>


    <!-- ===================================================== -->
    <!-- TABLEAU -->
    <!-- ===================================================== -->

    <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-5 py-4">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        État des stocks
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Liste des produits et niveaux de stock.
                    </p>
                </div>

                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input
                        type="text"
                        id="rechercheStock"
                        placeholder="Rechercher..."
                        class="h-10 w-full rounded-xl border border-gray-200 bg-gray-50 pl-9 pr-3 text-sm outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100 sm:w-64">
                </div>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-4 font-semibold">
                            Plat
                        </th>

                        <th class="px-5 py-4 font-semibold">
                            Catégorie
                        </th>

                        <th class="px-5 py-4 text-center font-semibold">
                            Seuil 
                        </th>

                        <th class="px-5 py-4 font-semibold">
                            État actuel
                        </th>

                        <th class="px-5 py-4 text-right font-semibold">
                            Action
                        </th>
                    </tr>
                </thead>


                <tbody id="tableauStocks" class="divide-y divide-gray-100">

                    <?php if (empty($produits)): ?>

                        <tr>
                            <td
                                colspan="4"
                                class="px-5 py-10 text-center text-gray-500">
                                Aucun produit trouvé.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($produits as $produit): ?>

                            <?php
                            $stock = (int) $produit->getStock();

                            if ($stock === 0) {
                                $etat = 'Rupture';
                                $etatClasse = 'bg-red-50 text-red-600';
                            } elseif ($stock <= $produit->getSeuilAlerte()) {
                                $etat = 'Stock faible';
                                $etatClasse = 'bg-orange-50 text-orange-600';
                            } else {
                                $etat = 'Disponible';
                                $etatClasse = 'bg-green-50 text-green-600';
                            }
                            ?>
                            <tr
                                class="ligne-stock transition hover:bg-gray-50"
                                data-nom="<?= htmlspecialchars(
                                                strtolower($produit->getNom())
                                            ) ?>">

                                <!-- ================================================= -->
                                <!-- PLAT -->
                                <!-- ================================================= -->

                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <img
                                            src="<?= htmlspecialchars($imageProduit($produit)) ?>"
                                            alt="<?= htmlspecialchars($produit->getNom()) ?>"
                                            class="h-11 w-11 shrink-0 rounded-xl object-cover ring-1 ring-gray-100">

                                        <div class="min-w-0">

                                            <p class="truncate font-semibold text-gray-800">
                                                <?= htmlspecialchars($produit->getNom()) ?>
                                            </p>

                                            <p class="text-xs text-gray-400">
                                                Produit #<?= (int) $produit->getId() ?>
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                <!-- ================================================= -->
                                <!-- CATÉGORIE -->
                                <!-- ================================================= -->

                                <td class="px-5 py-4">

                                    <span class="text-sm text-gray-600">
                                        <?= htmlspecialchars(
                                            $produit->getCategorieLibelle()
                                                ?? 'Sans catégorie'
                                        ) ?>
                                    </span>

                                </td>


                                <!-- ================================================= -->
                                <!-- SEUIL ALERTE -->
                                <!-- ================================================= -->

                                <td class="px-5 py-4 text-center">

                                    <span
                                        class="inline-flex h-8 min-w-8 items-center justify-center rounded-full bg-gray-50 px-2.5 font-semibold text-gray-700">
                                        <?= (int) $produit->getSeuilAlerte() ?>
                                    </span>
                                </td>


                                <!-- ================================================= -->
                                <!-- ÉTAT ACTUEL -->
                                <!-- ================================================= -->

                                <td class="px-5 py-4">

                                    <div class="flex flex-col gap-1">

                                        <span
                                            class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold <?= $etatClasse ?>">

                                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>

                                            <?= $etat ?>

                                            (<?= $stock ?>)

                                        </span>

                                    </div>

                                </td>


                                <!-- ================================================= -->
                                <!-- ACTION -->
                                <!-- ================================================= -->
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        <!-- Ajustement rapide -->
                                        <div
                                            class="flex h-10 items-center overflow-hidden rounded-xl border border-gray-200 bg-white">

                                            <!-- MOINS -->
                                            <form
                                                method="POST"
                                                action="/gerant/stocks/ajuster/<?= (int) $produit->getId() ?>">

                                                <input
                                                    type="hidden"
                                                    name="variation"
                                                    value="-1">

                                                <button
                                                    type="submit"
                                                    class="flex h-10 w-9 items-center justify-center text-gray-500 transition hover:bg-gray-50 hover:text-red-500 disabled:cursor-not-allowed disabled:opacity-40"
                                                    title="Diminuer le stock"
                                                    <?= $stock <= 0 ? 'disabled' : '' ?>>
                                                    <i class="fa-solid fa-minus text-xs"></i>
                                                </button>

                                            </form>


                                            <!-- STOCK -->
                                            <span
                                                class="flex h-10 min-w-12 items-center justify-center border-x border-gray-200 px-2 text-sm font-bold text-gray-800">
                                                <?= $stock ?>
                                            </span>


                                            <!-- PLUS -->
                                            <form
                                                method="POST"
                                                action="/gerant/stocks/ajuster/<?= (int) $produit->getId() ?>">

                                                <input
                                                    type="hidden"
                                                    name="variation"
                                                    value="1">

                                                <button
                                                    type="submit"
                                                    class="flex h-10 w-9 items-center justify-center text-gray-500 transition hover:bg-gray-50 hover:text-green-500"
                                                    title="Augmenter le stock">
                                                    <i class="fa-solid fa-plus text-xs"></i>
                                                </button>

                                            </form>

                                        </div>


                                        <!-- APPROVISIONNER -->
                                        <button
                                            type="button"
                                            onclick="ouvrirModalApprovisionnement(
                <?= (int) $produit->getId() ?>,
                <?= htmlspecialchars(
                                json_encode(
                                    $produit->getNom(),
                                    JSON_HEX_TAG |
                                        JSON_HEX_AMP |
                                        JSON_HEX_APOS |
                                        JSON_HEX_QUOT
                                )
                            ) ?>,
                <?= (int) $stock ?>
            )"
                                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-orange-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-orange-600">
                                            <i class="fa-solid fa-boxes-stacked"></i>
                                            Approvisionner
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>


    <!-- ===================================================== -->
    <!-- MODAL APPROVISIONNEMENT -->
    <!-- ===================================================== -->

    <div
        id="modalApprovisionnement"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
        aria-hidden="true">

        <div
            id="contenuModalApprovisionnement"
            class="w-full max-w-md scale-95 rounded-2xl bg-white p-6 shadow-2xl transition-transform duration-200">

            <!-- En-tête -->
            <div class="mb-5 flex items-start justify-between">

                <div>

                    <div class="mb-2 flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>

                    <h2 class="text-xl font-bold text-gray-800">
                        Approvisionner
                    </h2>

                    <p
                        id="nomProduitApprovisionnement"
                        class="mt-1 text-sm text-gray-500">
                        Produit
                    </p>

                </div>

                <button
                    type="button"
                    onclick="fermerModalApprovisionnement()"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                    aria-label="Fermer">
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>


            <!-- Informations stock -->
            <div class="mb-5 rounded-xl bg-gray-50 p-4">

                <div class="flex items-center justify-between">

                    <span class="text-sm text-gray-500">
                        Stock actuel
                    </span>

                    <span
                        id="stockActuelApprovisionnement"
                        class="text-lg font-bold text-gray-800">
                        0
                    </span>

                </div>

            </div>


            <!-- Formulaire -->
            <form
                id="formApprovisionnement"
                method="POST"
                action="">

                <label
                    for="quantiteApprovisionnement"
                    class="mb-2 block text-sm font-semibold text-gray-700">
                    Quantité à ajouter
                </label>

                <div class="relative">

                    <input
                        type="number"
                        id="quantiteApprovisionnement"
                        name="quantite"
                        min="1"
                        step="1"
                        value="<?= htmlspecialchars(
                                    $quantiteApprovisionnement ?? ''
                                ) ?>"
                        placeholder="Ex : 10"
                        class="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm font-medium outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-100"
                        required>

                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                        unités
                    </span>

                </div>


                <?php if (!empty($erreurApprovisionnement)): ?>

                    <div class="mt-3 flex items-start gap-2 rounded-xl bg-red-50 p-3 text-sm text-red-600">

                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                        <span>
                            <?= htmlspecialchars(
                                $erreurApprovisionnement
                            ) ?>
                        </span>

                    </div>

                <?php endif; ?>


                <!-- Nouveau stock -->
                <div class="mt-4 rounded-xl border border-orange-100 bg-orange-50 p-4">

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-gray-600">
                            Nouveau stock
                        </span>

                        <span
                            id="nouveauStockApprovisionnement"
                            class="text-lg font-bold text-orange-600">
                            0
                        </span>

                    </div>

                </div>


                <!-- Boutons -->
                <div class="mt-6 flex gap-3">

                    <button
                        type="button"
                        onclick="fermerModalApprovisionnement()"
                        class="flex-1 rounded-xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-orange-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-orange-600">
                        <i class="fa-solid fa-plus mr-1"></i>
                        Approvisionner
                    </button>

                </div>

            </form>

        </div>

    </div>

</main>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const recherche = document.getElementById('rechercheStock');
        const lignes = document.querySelectorAll('.ligne-stock');

        if (!recherche) {
            return;
        }

        recherche.addEventListener('input', function() {

            const terme = this.value
                .trim()
                .toLowerCase();

            lignes.forEach(function(ligne) {

                const nom = ligne.dataset.nom || '';

                ligne.style.display =
                    nom.includes(terme) ?
                    '' :
                    'none';

            });

        });

    });

    function ouvrirModalApprovisionnement(id, nom, stock) {
        const modal =
            document.getElementById('modalApprovisionnement');

        const contenu =
            document.getElementById(
                'contenuModalApprovisionnement'
            );

        const nomProduit =
            document.getElementById(
                'nomProduitApprovisionnement'
            );

        const stockActuel =
            document.getElementById(
                'stockActuelApprovisionnement'
            );

        const nouveauStock =
            document.getElementById(
                'nouveauStockApprovisionnement'
            );

        const quantite =
            document.getElementById(
                'quantiteApprovisionnement'
            );

        const formulaire =
            document.getElementById(
                'formApprovisionnement'
            );

        if (
            !modal ||
            !contenu ||
            !nomProduit ||
            !stockActuel ||
            !nouveauStock ||
            !quantite ||
            !formulaire
        ) {
            return;
        }

        nomProduit.textContent = nom;

        stockActuel.textContent = stock;

        nouveauStock.textContent = stock;

        formulaire.action =
            '/gerant/stocks/approvisionner/' + id;

        quantite.value = '';

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('overflow-hidden');

        setTimeout(function() {
            contenu.classList.remove('scale-95');
            contenu.classList.add('scale-100');
        }, 10);

        quantite.focus();
    }


    function fermerModalApprovisionnement() {
        const modal =
            document.getElementById(
                'modalApprovisionnement'
            );

        const contenu =
            document.getElementById(
                'contenuModalApprovisionnement'
            );

        if (!modal || !contenu) {
            return;
        }

        contenu.classList.remove('scale-100');
        contenu.classList.add('scale-95');

        setTimeout(function() {

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            modal.setAttribute(
                'aria-hidden',
                'true'
            );

            document.body.classList.remove(
                'overflow-hidden'
            );

        }, 150);
    }

    document.addEventListener('DOMContentLoaded', function() {

        const quantite =
            document.getElementById(
                'quantiteApprovisionnement'
            );

        const nouveauStock =
            document.getElementById(
                'nouveauStockApprovisionnement'
            );

        const stockActuel =
            document.getElementById(
                'stockActuelApprovisionnement'
            );

        if (
            !quantite ||
            !nouveauStock ||
            !stockActuel
        ) {
            return;
        }

        quantite.addEventListener('input', function() {

            const stock =
                parseInt(
                    stockActuel.textContent,
                    10
                ) || 0;

            const ajout =
                parseInt(
                    this.value,
                    10
                ) || 0;

            nouveauStock.textContent =
                stock + ajout;
        });

    });
</script>