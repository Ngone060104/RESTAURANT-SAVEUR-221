<?php

/**
 * Vue : détail d'un produit
 *
 * Variable attendue :
 * $produit
 */

$prix = number_format(
    (float) $produit->getPrix(),
    0,
    ',',
    ' '
);

$stock = (int) $produit->getStock();

$disponible = $produit->isDisponible() && $stock > 0;

$categorie = $produit->getCategorieLibelle() ?? 'Plats Traditionnels';

$description = $produit->getDescription() ?? '';

$image = $produit->getImage();

?>

<!-- =========================================================
     PAGE DÉTAIL PRODUIT
========================================================= -->

<main class="min-h-screen bg-[#faf9f7]">

    <!-- =====================================================
         RETOUR AU CATALOGUE
    ====================================================== -->

    <section class="mx-auto max-w-[1100px] px-6 pt-7">

        <a
            href="/produits"
            class="inline-flex items-center gap-3 font-['DM_Sans'] text-[15px] text-[#333333] transition hover:text-[#fe9a00]">
            <span class="text-[16px]">
                <i class="fa-solid fa-arrow-left"></i>
            </span>

            <span>
                Retour au catalogue des plats
            </span>
        </a>

    </section>


    <!-- =====================================================
         CARTE PRODUIT
    ====================================================== -->

    <section class="mx-auto max-w-[1100px] px-6 pb-12 pt-6">

        <div
            class="rounded-[17px] border border-[#dddddd] bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] lg:p-6">

            <div
                class="grid grid-cols-1 gap-7 lg:grid-cols-[1.08fr_0.92fr]">

                <!-- =================================================
                     COLONNE GAUCHE
                ================================================== -->

                <div>

                    <!-- =================================================
                         IMAGE
                    ================================================== -->

                    <div
                        class="relative h-[350px] overflow-hidden rounded-[15px] bg-[#e8e8e8]">

                        <?php if ($image): ?>

                            <img
                                src="<?= htmlspecialchars($image) ?>"
                                alt="<?= htmlspecialchars($produit->getNom()) ?>"
                                class="h-full w-full object-cover">

                        <?php else: ?>

                            <div
                                class="flex h-full items-center justify-center text-6xl text-[#cccccc]">
                                <i class="fa-solid fa-utensils"></i>
                            </div>

                        <?php endif; ?>


                        <!-- =================================================
                             DISPONIBILITÉ SUR L'IMAGE
                        ================================================== -->

                        <div class="absolute left-5 top-5">

                            <?php if ($disponible): ?>

                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-[#009966] px-4 py-2 font-['DM_Sans'] text-[13px] font-bold text-white shadow-sm">

                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full border border-white text-[9px]">
                                        <i class="fa-solid fa-check"></i>
                                    </span>

                                    Disponible

                                </span>

                            <?php else: ?>

                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-[#757575] px-4 py-2 font-['DM_Sans'] text-[13px] font-bold text-white">
                                    <i class="fa-solid fa-xmark"></i>

                                    Rupture
                                </span>

                            <?php endif; ?>

                        </div>


                        <!-- =================================================
                             CATÉGORIE SUR L'IMAGE
                        ================================================== -->

                        <div class="absolute right-5 top-5">

                            <span
                                class="inline-flex rounded-full bg-[#262626] px-4 py-2 font-['DM_Sans'] text-[13px] font-bold text-white">
                                <?= htmlspecialchars($categorie) ?>
                            </span>

                        </div>

                    </div>


                    <!-- =================================================
                         INFORMATIONS SUPPLÉMENTAIRES
                    ================================================== -->

                    <div
                        class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

                        <!-- PRÉPARATION -->

                        <div
                            class="flex h-[78px] flex-col items-center justify-center rounded-[15px] border border-[#dddddd] bg-white">

                            <div
                                class="text-[18px] text-[#fe9a00]">
                                <i class="fa-regular fa-clock"></i>
                            </div>

                            <p
                                class="mt-1 font-['DM_Sans'] text-[10px] font-medium uppercase text-[#777777]">
                                Préparation
                            </p>

                            <p
                                class="mt-1 font-['DM_Sans'] text-[11px] font-bold text-[#333333]">
                                20-25 Min
                            </p>

                        </div>


                        <!-- PIMENT / ÉPICE -->

                        <div
                            class="flex h-[78px] flex-col items-center justify-center rounded-[15px] border border-[#dddddd] bg-white">

                            <div
                                class="text-[18px] text-[#fe9a00]">
                                <i class="fa-solid fa-pepper-hot"></i>
                            </div>

                            <p
                                class="mt-1 font-['DM_Sans'] text-[10px] font-medium uppercase text-[#777777]">
                                Piment / Épice
                            </p>

                            <p
                                class="mt-1 font-['DM_Sans'] text-[11px] font-bold text-[#333333]">
                                Relevé
                            </p>

                        </div>


                        <!-- ORIGINE -->

                        <div
                            class="flex h-[78px] flex-col items-center justify-center rounded-[15px] border border-[#dddddd] bg-white">

                            <div
                                class="text-[18px] text-[#009966]">
                                <i class="fa-solid fa-earth-africa"></i>
                            </div>

                            <p
                                class="mt-1 font-['DM_Sans'] text-[10px] font-medium uppercase text-[#777777]">
                                Origine
                            </p>

                            <p
                                class="mt-1 font-['DM_Sans'] text-[11px] font-bold text-[#333333]">
                                100% Sénégal
                            </p>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     COLONNE DROITE
                ================================================== -->

                <div class="flex flex-col justify-center">

                    <!-- CATÉGORIE -->

                    <p
                        class="font-['DM_Sans'] text-[11px] font-bold uppercase text-[#fe9a00]">
                        <?= htmlspecialchars($categorie) ?>
                    </p>


                    <!-- NOM DU PRODUIT -->

                    <h1
                        class="mt-4 font-['DM_Sans'] text-[26px] font-extrabold leading-[1.25] text-black sm:text-[28px]">
                        <?= htmlspecialchars($produit->getNom()) ?>
                    </h1>


                    <!-- PRIX -->

                    <div
                        class="mt-5 flex items-center gap-6">

                        <span
                            class="font-['DM_Sans'] text-[34px] font-black leading-none text-[#a94310]">
                            <?= $prix ?>
                        </span>

                        <span
                            class="font-['DM_Sans'] text-[16px] font-bold text-[#777777]">
                            FCFA
                        </span>

                        <span
                            class="hidden font-['DM_Sans'] text-[9px] uppercase text-[#999999] sm:inline">
                            (TTC / PORTION GÉNÉREUSE)
                        </span>

                    </div>


                    <!-- SÉPARATION -->

                    <div
                        class="mt-6 border-t border-[#eeeeee]"></div>


                    <!-- DESCRIPTION -->

                    <div class="pt-5">

                        <h2
                            class="font-['DM_Sans'] text-[15px] font-extrabold uppercase text-[#171717]">
                            Description &amp; composition
                        </h2>

                        <p
                            class="mt-3 font-['DM_Sans'] text-[13px] leading-6 text-[#777777]">
                            <?= nl2br(htmlspecialchars($description)) ?>
                        </p>

                    </div>


                    <!-- STOCK -->

                    <div
                        class="mt-5 flex items-center gap-3">

                        <?php if ($disponible): ?>

                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full border border-[#00a878] text-[9px] text-[#00a878]">
                                <i class="fa-solid fa-check"></i>
                            </span>

                            <span
                                class="font-['DM_Sans'] text-[14px] text-[#009966]">
                                En Stock
                                (<?= $stock ?> Unités Disponibles)
                            </span>

                        <?php else: ?>

                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full border border-[#999999] text-[9px] text-[#777777]">
                                <i class="fa-solid fa-xmark"></i>
                            </span>

                            <span
                                class="font-['DM_Sans'] text-[14px] text-[#777777]">
                                Produit actuellement indisponible
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- SÉPARATION -->

                    <div
                        class="mt-5 border-t border-[#eeeeee]"></div>


                    <?php if ($disponible): ?>

                        <!-- =================================================
                             QUANTITÉ
                        ================================================== -->

                        <div
                            class="mt-3 flex flex-wrap items-center justify-between gap-4">

                            <div
                                class="flex items-center gap-4">

                                <label
                                    for="quantite"
                                    class="font-['DM_Sans'] text-[15px] text-[#333333]">
                                    Quantité :
                                </label>


                                <!-- COMPTEUR -->

                                <div
                                    class="flex h-[40px] items-center overflow-hidden rounded-full border border-[#cccccc]">

                                    <!-- MOINS -->

                                    <button
                                        type="button"
                                        onclick="modifierQuantite(-1)"
                                        class="flex h-full w-[44px] items-center justify-center text-[12px] text-[#777777] transition hover:bg-[#f5f5f5]">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>


                                    <!-- QUANTITÉ -->

                                    <input
                                        id="quantite"
                                        type="number"
                                        name="quantite_affichee"
                                        value="1"
                                        min="1"
                                        max="<?= $stock ?>"
                                        class="h-full w-[45px] border-x border-[#eeeeee] text-center font-['DM_Sans'] text-[15px] font-bold outline-none">


                                    <!-- PLUS -->

                                    <button
                                        type="button"
                                        onclick="modifierQuantite(1)"
                                        class="flex h-full w-[44px] items-center justify-center text-[12px] text-[#222222] transition hover:bg-[#f5f5f5]">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>

                                </div>

                            </div>


                            <!-- =================================================
                                 SOUS-TOTAL
                            ================================================== -->

                            <div
                                class="font-['DM_Sans'] text-[12px] text-[#888888]">
                                Sous-Total :

                                <span
                                    id="sous-total"
                                    class="font-bold text-[#222222]">
                                    <?= $prix ?> FCFA
                                </span>

                            </div>

                        </div>


                        <!-- =================================================
                             BOUTONS
                        ================================================== -->

                        <div
                            class="mt-7 flex flex-col gap-3 sm:flex-row">

                            <!-- =================================================
                                 AJOUTER AU PANIER
                                 ACCESSIBLE AUX VISITEURS ET AUX CLIENTS
                            ================================================== -->

                            <form
                                action="/panier/ajouter"
                                method="post"
                                class="flex-1">

                                <input
                                    type="hidden"
                                    name="produit_id"
                                    value="<?= $produit->getId() ?>">

                                <input
                                    id="quantite-panier"
                                    type="hidden"
                                    name="quantite"
                                    value="1">

                                <button
                                    type="submit"
                                    class="flex h-[52px] w-full items-center justify-center gap-3 rounded-[9px] bg-[#fe9a00] px-5 font-['DM_Sans'] text-[13px] font-bold text-black shadow-[0_3px_6px_rgba(0,0,0,0.18)] transition hover:bg-[#e88900] active:scale-[0.98]">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    Ajouter au Panier
                                    · <span id="prix-bouton"><?= $prix ?></span> FCFA
                                </button>

                            </form>


                            <!-- =================================================
                                 VOIR MON PANIER
                            ================================================== -->

                            <a
                                href="/panier"
                                class="flex h-[52px] items-center justify-center gap-2 rounded-[9px] border border-[#dddddd] bg-white px-6 font-['DM_Sans'] text-[13px] font-bold text-[#555555] transition hover:bg-[#f7f7f7]">

                                <i class="fa-solid fa-cart-shopping text-[12px]"></i>

                                Voir mon panier

                            </a>

                        </div>


                    <?php else: ?>


                        <!-- =================================================
                             PRODUIT INDISPONIBLE
                        ================================================== -->

                        <div
                            class="mt-7 rounded-[9px] bg-[#f5f5f5] p-4 text-center font-['DM_Sans'] text-[13px] font-semibold text-[#777777]">

                            <i class="fa-solid fa-circle-exclamation mr-2"></i>

                            Ce produit est actuellement indisponible.

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>

</main>


<!-- =========================================================
     JAVASCRIPT : GESTION DE LA QUANTITÉ
========================================================= -->

<?php if ($disponible): ?>

    <script>
        function modifierQuantite(delta) {

            const input = document.getElementById('quantite');

            const hiddenInput = document.getElementById('quantite-panier');

            const sousTotal = document.getElementById('sous-total');

            const prixBouton = document.getElementById('prix-bouton');

            const prixUnitaire = <?= (float) $produit->getPrix() ?>;

            const stockMaximum = <?= $stock ?>;


            let quantite = parseInt(input.value) || 1;

            quantite += delta;


            if (quantite < 1) {
                quantite = 1;
            }


            if (quantite > stockMaximum) {
                quantite = stockMaximum;
            }


            input.value = quantite;

            hiddenInput.value = quantite;


            const total = prixUnitaire * quantite;


            sousTotal.textContent =
                new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';

            prixBouton.textContent =
                new Intl.NumberFormat('fr-FR').format(total);
        }


        document
            .getElementById('quantite')
            .addEventListener('input', function() {

                const stockMaximum = <?= $stock ?>;

                let quantite = parseInt(this.value) || 1;


                if (quantite < 1) {
                    quantite = 1;
                }


                if (quantite > stockMaximum) {
                    quantite = stockMaximum;
                }


                this.value = quantite;


                document.getElementById('quantite-panier').value = quantite;


                const prixUnitaire = <?= (float) $produit->getPrix() ?>;

                const total = prixUnitaire * quantite;


                document.getElementById('sous-total').textContent =
                    new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';

                document.getElementById('prix-bouton').textContent =
                    new Intl.NumberFormat('fr-FR').format(total);

            });
    </script>

<?php endif; ?>