<?php

$total = (float) ($total ?? 0);

$nombreArticles = 0;

foreach ($lignes ?? [] as $ligne) {
    $nombreArticles += (int) ($ligne['quantite'] ?? 0);
}

?>

<div class="bg-stone-50 min-h-[calc(100vh-80px)] py-10">

    <div class="max-w-6xl mx-auto px-6">

        <!-- =========================
             EN-TÊTE
        ========================== -->

        <div class="flex items-end justify-between border-b border-stone-200 pb-8 mb-8">

            <div>
                <p class="text-sm font-bold text-orange-500 uppercase tracking-wide">
                    Récapitulatif de commande
                </p>

                <h1 class="text-4xl font-extrabold text-stone-900 mt-2">
                    Mon Panier
                    (<?= $nombreArticles ?>
                    <?= $nombreArticles > 1 ? 'Articles' : 'Article' ?>)
                </h1>
            </div>

            <?php if (!empty($lignes)): ?>

                <form action="/panier/vider" method="post">

                    <button
                        type="button"
                        onclick="ouvrirModalViderPanier()"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-red-500 hover:text-red-600 transition">
                        <i class="fa-solid fa-trash"></i>
                        Vider le panier
                    </button>

                </form>

            <?php endif; ?>

        </div>


        <!-- =========================
             PANIER VIDE
        ========================== -->

        <?php if (empty($lignes)): ?>

            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm p-12 text-center">

                <div class="w-20 h-20 mx-auto rounded-full bg-orange-100 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-cart-shopping text-3xl text-orange-500"></i>
                </div>

                <h2 class="text-2xl font-extrabold text-stone-900">
                    Votre panier est vide
                </h2>

                <p class="text-stone-500 mt-2 mb-7">
                    Découvrez nos plats et ajoutez vos favoris à votre panier.
                </p>

                <a
                    href="/produits"
                    class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-full transition">
                    <i class="fa-solid fa-utensils"></i>
                    Voir le menu
                </a>

            </div>


        <?php else: ?>


            <!-- =========================
                 CONTENU DU PANIER
            ========================== -->

            <div class="grid lg:grid-cols-[1.65fr_1fr] gap-8 items-start">


                <!-- =========================
                     LISTE DES PRODUITS
                ========================== -->

                <div class="space-y-4">

                    <?php foreach ($lignes as $ligne): ?>

                        <?php

                        $produit = $ligne['produit'];

                        $quantite = (int) ($ligne['quantite'] ?? 1);

                        $sousTotal = (float) ($ligne['sousTotal'] ?? 0);

                        $stock = (int) $produit->getStock();

                        $image = $produit->getImage();

                        $categorie = '';

                        if (method_exists($produit, 'getCategorieLibelle')) {
                            $categorie = $produit->getCategorieLibelle();
                        }

                        ?>

                        <!-- Carte produit -->

                        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-5">

                            <div class="flex items-center gap-5">


                                <!-- IMAGE -->

                                <div class="w-28 h-24 shrink-0 rounded-2xl overflow-hidden bg-orange-100">

                                    <?php if ($image): ?>

                                        <img
                                            src="<?= htmlspecialchars($image) ?>"
                                            alt="<?= htmlspecialchars($produit->getNom()) ?>"
                                            class="w-full h-full object-cover">

                                    <?php else: ?>

                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fa-solid fa-utensils text-3xl text-orange-400"></i>
                                        </div>

                                    <?php endif; ?>

                                </div>


                                <!-- INFORMATIONS -->

                                <div class="flex-1 min-w-0">

                                    <?php if ($categorie): ?>

                                        <p class="text-[10px] uppercase text-stone-400 font-medium tracking-wide mb-2">
                                            <?= htmlspecialchars($categorie) ?>
                                        </p>

                                    <?php else: ?>

                                        <p class="text-[10px] uppercase text-stone-400 font-medium tracking-wide mb-2">
                                            Plats traditionnels
                                        </p>

                                    <?php endif; ?>


                                    <h2 class="font-extrabold text-base text-stone-900">
                                        <?= htmlspecialchars($produit->getNom()) ?>
                                    </h2>


                                    <p class="mt-2">

                                        <span class="font-bold text-orange-500">
                                            <?= number_format($produit->getPrix(), 0, ',', ' ') ?> FCFA
                                        </span>

                                        <span class="text-sm text-stone-900">
                                            l'unité
                                        </span>

                                    </p>

                                </div>


                                <!-- QUANTITÉ -->

                                <form
                                    action="/panier/quantite"
                                    method="post"
                                    class="flex items-center">

                                    <input
                                        type="hidden"
                                        name="produit_id"
                                        value="<?= $produit->getId() ?>">

                                    <div class="flex items-center border border-stone-300 rounded-full overflow-hidden h-9">

                                        <!-- MOINS -->

                                        <button
                                            type="button"
                                            onclick="changerQuantite(this, -1)"
                                            class="w-9 h-9 flex items-center justify-center text-stone-500 hover:bg-orange-50 hover:text-orange-500 transition">
                                            <i class="fa-solid fa-minus text-xs"></i>
                                        </button>


                                        <!-- QUANTITÉ -->

                                        <input
                                            type="number"
                                            name="quantite"
                                            value="<?= $quantite ?>"
                                            min="1"
                                            max="<?= $stock ?>"
                                            class="w-10 h-9 text-center border-x border-stone-200 outline-none font-bold text-sm">


                                        <!-- PLUS -->

                                        <button
                                            type="button"
                                            onclick="changerQuantite(this, 1)"
                                            class="w-9 h-9 flex items-center justify-center text-stone-500 hover:bg-orange-50 hover:text-orange-500 transition">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                        </button>

                                    </div>

                                </form>


                                <!-- SOUS-TOTAL -->

                                <div class="min-w-[90px]">

                                    <p class="text-[9px] uppercase text-stone-400 mb-1">
                                        Sous-total
                                    </p>

                                    <p class="font-extrabold text-sm text-stone-900">
                                        <?= number_format($sousTotal, 0, ',', ' ') ?>
                                        FCFA
                                    </p>

                                </div>


                                <!-- SUPPRIMER -->

                                <form action="/panier/supprimer" method="post">

                                    <input
                                        type="hidden"
                                        name="produit_id"
                                        value="<?= $produit->getId() ?>">

                                    <button
                                        type="submit"
                                        title="Supprimer"
                                        class="text-stone-400 hover:text-red-500 transition">
                                        <i class="fa-solid fa-trash text-base"></i>
                                    </button>

                                </form>

                            </div>

                        </div>

                    <?php endforeach; ?>


                    <!-- STOCK -->

                    <div class="flex justify-end">

                        <p class="text-xs text-stone-400">
                            Les quantités sont limitées au stock disponible.
                        </p>

                    </div>

                </div>


                <!-- =========================
                     TOTAL DE LA COMMANDE
                ========================== -->

                <aside class="bg-white rounded-2xl border border-stone-200 shadow-sm p-7 lg:sticky lg:top-6">

                    <h2 class="text-xl font-extrabold text-stone-900">
                        Total de la Commande
                    </h2>


                    <div class="border-t border-stone-100 mt-7 pt-6">


                        <!-- SOUS-TOTAL -->

                        <div class="flex items-center justify-between text-base text-stone-500">

                            <span>
                                Sous-total articles :
                            </span>

                            <span class="font-semibold text-stone-900">
                                <?= number_format($total, 0, ',', ' ') ?> FCFA
                            </span>

                        </div>


                        <!-- FRAIS -->

                        <div class="flex items-center justify-between text-base text-stone-500 mt-5">

                            <span>
                                Frais de préparation :
                            </span>

                            <span class="font-semibold text-emerald-500">
                                Offerts
                            </span>

                        </div>


                        <!-- EMBALLAGE -->

                        <div class="flex items-center justify-between text-base text-stone-500 mt-5">

                            <span>
                                Emballage écologique :
                            </span>

                            <span class="font-semibold text-emerald-500">
                                Inclus
                            </span>

                        </div>

                    </div>


                    <!-- TOTAL -->

                    <div class="border-t border-stone-100 mt-7 pt-7">

                        <div class="flex items-center justify-between">

                            <span class="text-xl font-extrabold text-stone-900">
                                Total à payer :
                            </span>

                            <span class="text-xl font-extrabold text-orange-600">
                                <?= number_format($total, 0, ',', ' ') ?>
                                <span class="text-sm">
                                    FCFA
                                </span>
                            </span>

                        </div>

                    </div>


                    <!-- PASSER LA COMMANDE -->

                    <form
                        action="/commandes/valider"
                        method="post"
                        class="mt-7">

                        <a
                            href="/commande"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-stone-900 font-extrabold py-4 rounded-2xl transition flex items-center justify-center gap-3 shadow-sm">
                            <span>Passer la commande</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    </form>


                    <!-- PAIEMENT -->

                    <p class="text-xs text-center text-stone-400 mt-5 leading-relaxed">

                        Paiement flexible au retrait par Wave, Orange Money
                        <br>
                        ou Espèces.

                    </p>

                </aside>

            </div>

        <?php endif; ?>

    </div>

</div>

<!-- =========================
     MODAL CONFIRMATION
     VIDER LE PANIER
========================= -->

<div
    id="modalViderPanier"
    class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 px-4">
    <div
        class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-7">

        <!-- Icône -->

        <div class="w-14 h-14 mx-auto rounded-full bg-red-50 flex items-center justify-center mb-5">

            <i class="fa-solid fa-trash-can text-xl text-red-500"></i>

        </div>


        <!-- Titre -->

        <h2 class="text-xl font-extrabold text-stone-900 text-center">
            Vider le panier ?
        </h2>


        <!-- Message -->

        <p class="text-sm text-stone-500 text-center mt-3 leading-relaxed">
            Êtes-vous sûr de vouloir supprimer tous les articles
            de votre panier ?
            <br>
            Cette action est irréversible.
        </p>


        <!-- Boutons -->

        <div class="flex gap-3 mt-7">

            <!-- Annuler -->

            <button
                type="button"
                onclick="fermerModalViderPanier()"
                class="flex-1 py-3 rounded-full border border-stone-200 text-stone-700 font-bold hover:bg-stone-50 transition">
                Annuler
            </button>


            <!-- Confirmer -->

            <form
                action="/panier/vider"
                method="post"
                class="flex-1">

                <button
                    type="submit"
                    class="w-full py-3 rounded-full bg-red-500 hover:bg-red-600 text-white font-bold transition">
                    Oui, vider
                </button>

            </form>

        </div>

    </div>
</div>


<script>
    function changerQuantite(button, variation) {

        const form = button.closest('form');

        const input = form.querySelector('input[name="quantite"]');

        const min = parseInt(input.min, 10) || 1;

        const max = parseInt(input.max, 10) || 999;

        let valeur = parseInt(input.value, 10) || min;

        valeur += variation;

        if (valeur < min) {
            valeur = min;
        }

        if (valeur > max) {
            valeur = max;
        }

        input.value = valeur;

        form.submit();
    }


    /* =========================
       MODAL VIDER LE PANIER
    ========================= */

    function ouvrirModalViderPanier() {

        const modal = document.getElementById('modalViderPanier');

        modal.classList.remove('hidden');

        modal.classList.add('flex');

    }


    function fermerModalViderPanier() {

        const modal = document.getElementById('modalViderPanier');

        modal.classList.remove('flex');

        modal.classList.add('hidden');

    }


    /* Fermer en cliquant sur l'arrière-plan */

    document
        .getElementById('modalViderPanier')
        .addEventListener('click', function(event) {

            if (event.target === this) {
                fermerModalViderPanier();
            }

        });


    /* Fermer avec la touche Échap */

    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {
            fermerModalViderPanier();
        }

    });
</script>