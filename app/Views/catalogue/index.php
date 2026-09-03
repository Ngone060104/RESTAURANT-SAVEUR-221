<?php

$titre = 'Catalogue des Plats';

$prixFormate = static function (float $prix): string {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
};

$descriptionCourte = static function (?string $description): string {
    $description = trim($description ?? '');

    if ($description === '') {
        return 'Une spécialité préparée avec soin par Saveur 221.';
    }

    return $description;
};

/*
|--------------------------------------------------------------------------
| Filtres actuels
|--------------------------------------------------------------------------
|
| La catégorie active, le terme de recherche et le statut
| sont maintenant fournis par le contrôleur.
|
| URLs utilisées :
| - /produits
| - /produits/categorie/{id}
| - /produits/recherche/{terme}
| - /produits/statut/{statut}
|
*/

$categorieActive = $categorieActive ?? null;

$statutActuel = $statutActuel ?? '';

if (!in_array($statutActuel, ['', 'disponible', 'en_rupture'], true)) {
    $statutActuel = '';
}

$termeActuel = trim($termeActuel ?? '');

/*
|--------------------------------------------------------------------------
| URL - Toutes les catégories
|--------------------------------------------------------------------------
*/

$urlToutesCategories = '/produits';

?>

<div class="min-h-screen bg-[#fdfbf7]">

    <!-- =====================================================
         INTRODUCTION
    ====================================================== -->

    <section class="mx-auto max-w-[1240px] px-4 pt-8 sm:px-6 sm:pt-10">

        <p
            class="font-['DM_Sans'] text-[12px] font-extrabold uppercase tracking-wide text-[#ff9900] sm:text-[13px]"
        >
            Notre Menu &amp; Carte Gourmande
        </p>

        <h1
            class="mt-2 font-['Inter'] text-[26px] font-extrabold leading-tight text-black sm:text-[32px] md:text-[36px]"
        >
            Catalogue des Plats Saveur 221
        </h1>

        <p
            class="mt-3 max-w-[760px] font-['DM_Sans'] text-[14px] leading-6 text-[#757575] sm:text-[15px]"
        >
            Découvrez nos spécialités dakaroises, mijotées avec des ingrédients frais du jour.
        </p>

        <div class="mt-6 h-px bg-[#dedede] sm:mt-8"></div>

    </section>


    <!-- =====================================================
         RECHERCHE + FILTRES
    ====================================================== -->

    <section class="mx-auto mt-5 max-w-[1240px] px-4 sm:px-6">

        <div
            class="rounded-[16px] border border-[#eeeeee] bg-white px-4 py-4 shadow-[0_2px_10px_rgba(0,0,0,0.04)] sm:px-5 sm:py-4"
        >

            <!-- =================================================
                 RECHERCHE + DISPONIBILITÉ
            ================================================== -->

            <form
                id="catalogue-filters-form"
                method="GET"
                action="/produits"
                class="flex flex-col gap-3 lg:flex-row lg:items-center"
            >

                <!-- Recherche -->

                <div class="flex min-w-0 flex-1 gap-2">

                    <div class="relative min-w-0 flex-1">

                        <span
                            class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[13px] text-[#999999]"
                        >
                            🔍
                        </span>

                        <input
                            type="text"
                            id="recherche"
                            name="q"
                            value="<?= htmlspecialchars($termeActuel) ?>"
                            placeholder="Rechercher un plat..."
                            class="h-[38px] w-full rounded-[8px] border border-[#dddddd] bg-white pl-9 pr-3 font-['DM_Sans'] text-[12px] text-[#333333] outline-none transition placeholder:text-[#999999] focus:border-[#fe9a00]"
                        >

                    </div>

                    <button
                        type="submit"
                        class="h-[38px] shrink-0 rounded-[8px] bg-[#fe9a00] px-4 font-['DM_Sans'] text-[12px] font-bold text-white transition hover:bg-[#e88900]"
                    >
                        Rechercher
                    </button>

                </div>


                <!-- Disponibilité -->

                <div class="relative w-full lg:w-[190px]">

                    <select
                        id="statut"
                        name="statut"
                        onchange="filtrerParStatut(this.value)"
                        class="h-[38px] w-full cursor-pointer appearance-none rounded-[8px] border border-[#dddddd] bg-white px-3 pr-8 font-['DM_Sans'] text-[12px] font-medium text-[#333333] outline-none transition focus:border-[#fe9a00]"
                    >

                        <option value="">
                            Toutes les disponibilités
                        </option>

                        <option
                            value="disponible"
                            <?= $statutActuel === 'disponible' ? 'selected' : '' ?>
                        >
                            Disponibles
                        </option>

                        <option
                            value="en_rupture"
                            <?= $statutActuel === 'en_rupture' ? 'selected' : '' ?>
                        >
                            En rupture
                        </option>

                    </select>

                    <span
                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-[#666666]"
                    >
                        ▼
                    </span>

                </div>

            </form>


            <!-- =================================================
                 CATÉGORIES
            ================================================== -->

            <div class="mt-4 border-t border-[#eeeeee] pt-4">

                <div
                    class="flex flex-wrap items-center gap-x-3 gap-y-3"
                >

                    <!-- Toutes les catégories -->

                    <a
                        href="<?= htmlspecialchars($urlToutesCategories) ?>"
                        class="<?= $categorieActive === null
                            ? 'bg-[#262626] text-white'
                            : 'bg-[#f5f5f5] text-[#666666]' ?>
                            inline-flex h-[34px] items-center rounded-full px-4 font-['DM_Sans'] text-[11px] font-bold transition hover:bg-[#262626] hover:text-white"
                    >
                        Toutes les catégories
                    </a>


                    <!-- Catégories -->

                    <?php foreach ($categories as $categorie): ?>

                        <?php

                        $idCategorie = $categorie->getId();

                        /*
                         * URL propre :
                         * /produits/categorie/5
                         */

                        $urlCategorie = '/produits/categorie/' . $idCategorie;

                        $active = $categorieActive === $idCategorie;

                        ?>

                        <a
                            href="<?= htmlspecialchars($urlCategorie) ?>"
                            class="<?= $active
                                ? 'bg-[#262626] text-white'
                                : 'bg-[#f5f5f5] text-[#666666]' ?>
                                inline-flex h-[34px] items-center rounded-full px-4 font-['DM_Sans'] text-[11px] font-bold transition hover:bg-[#262626] hover:text-white"
                        >
                            <?= htmlspecialchars($categorie->getLibelle()) ?>
                        </a>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         PRODUITS
    ====================================================== -->

    <section
        class="mx-auto max-w-[1240px] px-4 pb-16 pt-7 sm:px-6 sm:pb-20 sm:pt-8"
    >

        <?php if (empty($produits)): ?>

            <!-- =================================================
                 AUCUN PRODUIT
            ================================================== -->

            <div
                class="rounded-[16px] bg-white p-8 text-center shadow-sm sm:p-10"
            >

                <div class="text-4xl">
                    🍽️
                </div>

                <h2
                    class="mt-4 font-['DM_Sans'] text-xl font-extrabold text-stone-900"
                >
                    Aucun plat trouvé
                </h2>

                <p
                    class="mt-2 font-['DM_Sans'] text-sm text-stone-500"
                >
                    Aucun produit ne correspond aux filtres sélectionnés.
                </p>

                <a
                    href="/produits"
                    class="mt-5 inline-flex rounded-[8px] bg-[#fe9a00] px-5 py-2.5 font-['DM_Sans'] text-sm font-bold text-white transition hover:bg-[#e88900]"
                >
                    Réinitialiser les filtres
                </a>

            </div>


        <?php else: ?>


            <!-- =================================================
                 GRILLE PRODUITS

                 Mobile  : 1 colonne
                 Tablette: 2 colonnes
                 Desktop : 3 colonnes
            ================================================== -->

            <div
                class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:gap-6 xl:grid-cols-3"
            >

                <?php foreach ($produits as $produit): ?>

                    <?php

                    $disponible = $produit->isDisponible();

                    ?>

                    <!-- =================================================
                         CARTE PRODUIT
                    ================================================== -->

                    <article
                        class="overflow-hidden rounded-[16px] bg-white shadow-[0_3px_14px_rgba(0,0,0,0.07)] transition duration-200 hover:-translate-y-1 hover:shadow-[0_8px_22px_rgba(0,0,0,0.10)]"
                    >

                        <!-- =================================================
                             IMAGE
                        ================================================== -->

                        <div
                            class="relative h-[180px] overflow-hidden bg-[#e5e5e5] sm:h-[195px] lg:h-[190px]"
                        >

                            <?php if ($produit->getImage()): ?>

                                <img
                                    src="<?= htmlspecialchars($produit->getImage()) ?>"
                                    alt="<?= htmlspecialchars($produit->getNom()) ?>"
                                    class="h-full w-full object-cover transition duration-300 hover:scale-[1.02]"
                                >

                            <?php else: ?>

                                <div
                                    class="flex h-full items-center justify-center text-5xl"
                                >
                                    🍽️
                                </div>

                            <?php endif; ?>


                            <!-- Disponibilité -->

                            <div class="absolute left-3 top-3">

                                <?php if ($disponible): ?>

                                    <span
                                        class="inline-flex items-center rounded-full bg-[#009966] px-2.5 py-1 font-['DM_Sans'] text-[11px] font-bold text-white"
                                    >
                                        Disponible
                                    </span>

                                <?php else: ?>

                                    <span
                                        class="inline-flex items-center rounded-full bg-[#757575] px-2.5 py-1 font-['DM_Sans'] text-[11px] font-bold text-white"
                                    >
                                        Rupture
                                    </span>

                                <?php endif; ?>

                            </div>


                            <!-- Catégorie -->

                            <div class="absolute right-3 top-3">

                                <span
                                    class="inline-flex max-w-[150px] truncate rounded-full bg-[#262626] px-2.5 py-1 font-['DM_Sans'] text-[11px] font-bold text-white"
                                >
                                    <?= htmlspecialchars($produit->getCategorieLibelle() ?? '') ?>
                                </span>

                            </div>

                        </div>


                        <!-- =================================================
                             CONTENU
                        ================================================== -->

                        <div class="p-4 sm:p-5">

                            <!-- Nom -->

                            <h2
                                class="font-['DM_Sans'] text-[18px] font-extrabold leading-6 text-black sm:text-[19px]"
                            >
                                <?= htmlspecialchars($produit->getNom()) ?>
                            </h2>


                            <!-- Description -->

                            <p
                                class="mt-2 h-[48px] overflow-hidden font-['DM_Sans'] text-[13px] leading-6 text-[#757575] sm:text-[14px]"
                            >
                                <?= htmlspecialchars(
                                    $descriptionCourte($produit->getDescription())
                                ) ?>
                            </p>


                            <!-- Préparation -->

                            <div
                                class="mt-3 flex items-center gap-2 font-['DM_Sans'] text-[12px] font-bold text-[#757575]"
                            >

                                <span>⏱</span>

                                <span>
                                    Préparation sur place
                                </span>

                            </div>


                            <!-- Séparation -->

                            <div class="my-4 h-px bg-[#eeeeee]"></div>


                            <!-- =================================================
                                 BAS DE CARTE
                            ================================================== -->

                            <div
                                class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"
                            >

                                <!-- Prix -->

                                <div>

                                    <p
                                        class="font-['DM_Sans'] text-[11px] font-bold text-[#757575]"
                                    >
                                        Prix
                                    </p>

                                    <p
                                        class="mt-1 font-['DM_Sans'] text-[19px] font-black leading-6 text-[#e37d15] sm:text-[20px]"
                                    >
                                        <?= $prixFormate($produit->getPrix()) ?>
                                    </p>

                                </div>


                                <!-- =================================================
                                     BOUTONS
                                ================================================== -->

                                <div
                                    class="flex w-full items-center gap-1 sm:w-auto"
                                >

                                    <!-- Détail -->

                                    <a
                                        href="/produit/<?= $produit->getId() ?>"
                                        class="inline-flex h-[38px] flex-1 items-center justify-center rounded-[8px] px-3 font-['DM_Sans'] text-[12px] font-bold text-black transition hover:bg-[#f5f5f5] sm:flex-none"
                                    >
                                        Détail
                                    </a>


                                    <!-- Ajouter au panier -->

                                    <?php if ($disponible): ?>

                                        <form
                                            action="/panier/ajouter"
                                            method="post"
                                            class="flex-1 sm:flex-none"
                                        >

                                            <input
                                                type="hidden"
                                                name="produit_id"
                                                value="<?= $produit->getId() ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="quantite"
                                                value="1"
                                            >

                                            <button
                                                type="submit"
                                                class="inline-flex h-[38px] w-full items-center justify-center rounded-[9px] bg-[#fe9a00] px-3 font-['DM_Sans'] text-[12px] font-bold text-white transition hover:bg-[#e88900] active:scale-[0.98]"
                                            >
                                                🛒 Ajouter
                                            </button>

                                        </form>

                                    <?php else: ?>

                                        <button
                                            type="button"
                                            disabled
                                            class="inline-flex h-[38px] flex-1 cursor-not-allowed items-center justify-center rounded-[9px] bg-[#d9d9d9] px-3 font-['DM_Sans'] text-[12px] font-bold text-white sm:flex-none"
                                        >
                                            Rupture
                                        </button>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</div>


<!-- =====================================================
     JAVASCRIPT
     URLs propres pour recherche et disponibilité
====================================================== -->

<script>
    function rechercherProduit() {
        const terme = document.getElementById('recherche').value.trim();

        if (terme === '') {
            window.location.href = '/produits';
            return;
        }

        window.location.href =
            '/produits/recherche/' + encodeURIComponent(terme);
    }


    function filtrerParStatut(statut) {
        if (statut === '') {
            window.location.href = '/produits';
            return;
        }

        window.location.href =
            '/produits/statut/' + encodeURIComponent(statut);
    }


    document
        .getElementById('catalogue-filters-form')
        .addEventListener('submit', function (event) {
            event.preventDefault();

            rechercherProduit();
        });
</script>