<?php

$formatPrix = static function (float $prix): string {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
};

$nomComplet = htmlspecialchars(
    $client->getPrenom() . ' ' . $client->getNom(),
    ENT_QUOTES,
    'UTF-8'
);

$email = htmlspecialchars(
    $client->getEmail(),
    ENT_QUOTES,
    'UTF-8'
);

$telephone = htmlspecialchars(
    $client->getTelephone(),
    ENT_QUOTES,
    'UTF-8'
);

$adresse = htmlspecialchars(
    $client->getAdresse(),
    ENT_QUOTES,
    'UTF-8'
);

$total = (float) ($total ?? 0);
$lignes = $lignes ?? [];

?>

<div class="min-h-screen bg-stone-50 py-10">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- =====================================================
             EN-TÊTE
        ====================================================== -->

        <div class="mb-8">

            <a
                href="/panier"
                class="inline-flex items-center gap-2 text-stone-600 hover:text-orange-600 font-semibold mb-5 transition"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Retour au panier
            </a>

            <h1 class="text-3xl md:text-4xl font-extrabold text-stone-900">
                Validation & Confirmation de votre commande
            </h1>

            <p class="mt-2 text-stone-500">
                Vérifiez vos informations et votre commande avant de confirmer.
            </p>

        </div>


        <!-- =====================================================
             CONTENU
        ====================================================== -->

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">


            <!-- =================================================
                 COLONNE PRINCIPALE
            ================================================== -->

            <div class="lg:col-span-2 space-y-6">


                <!-- =================================================
                     INFORMATIONS CLIENT
                ================================================== -->

                <section
                    class="bg-white rounded-3xl shadow-sm border border-stone-200 p-6 md:p-8"
                >

                    <div class="flex items-center gap-3 mb-6">

                        <div
                            class="w-11 h-11 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center"
                        >
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <div>

                            <h2 class="text-xl font-extrabold text-stone-900">
                                Vos informations
                            </h2>

                            <p class="text-sm text-stone-500">
                                Vérifiez que vos coordonnées sont correctes.
                            </p>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        <!-- Nom -->

                        <div>

                            <label class="block text-sm font-bold text-stone-700 mb-2">
                                Nom complet
                            </label>

                            <div
                                class="flex items-center gap-3 bg-stone-50 border border-stone-200 rounded-2xl px-4 py-3"
                            >

                                <i class="fa-solid fa-user text-stone-400"></i>

                                <span class="font-semibold text-stone-800">
                                    <?= $nomComplet ?>
                                </span>

                            </div>

                        </div>


                        <!-- Email -->

                        <div>

                            <label class="block text-sm font-bold text-stone-700 mb-2">
                                Adresse e-mail
                            </label>

                            <div
                                class="flex items-center gap-3 bg-stone-50 border border-stone-200 rounded-2xl px-4 py-3"
                            >

                                <i class="fa-solid fa-envelope text-stone-400"></i>

                                <span class="font-semibold text-stone-800 break-all">
                                    <?= $email ?>
                                </span>

                            </div>

                        </div>


                        <!-- Téléphone -->

                        <div>

                            <label class="block text-sm font-bold text-stone-700 mb-2">
                                Téléphone
                            </label>

                            <div
                                class="flex items-center gap-3 bg-stone-50 border border-stone-200 rounded-2xl px-4 py-3"
                            >

                                <i class="fa-solid fa-phone text-stone-400"></i>

                                <span class="font-semibold text-stone-800">
                                    <?= $telephone ?>
                                </span>

                            </div>

                        </div>


                        <!-- Adresse -->

                        <div>

                            <label class="block text-sm font-bold text-stone-700 mb-2">
                                Adresse
                            </label>

                            <div
                                class="flex items-center gap-3 bg-stone-50 border border-stone-200 rounded-2xl px-4 py-3"
                            >

                                <i class="fa-solid fa-location-dot text-stone-400"></i>

                                <span class="font-semibold text-stone-800">
                                    <?= $adresse ?>
                                </span>

                            </div>

                        </div>

                    </div>

                </section>


                <!-- =================================================
                     MODE DE RETRAIT
                ================================================== -->

                <section
                    class="bg-white rounded-3xl shadow-sm border border-stone-200 p-6 md:p-8"
                >

                    <div class="flex items-center gap-3 mb-6">

                        <div
                            class="w-11 h-11 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center"
                        >
                            <i class="fa-solid fa-store"></i>
                        </div>

                        <div>

                            <h2 class="text-xl font-extrabold text-stone-900">
                                Mode de retrait
                            </h2>

                            <p class="text-sm text-stone-500">
                                Comment souhaitez-vous récupérer votre commande ?
                            </p>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                        <!-- À emporter -->

                        <label class="relative cursor-pointer">

                            <input
                                type="radio"
                                name="mode_retrait"
                                value="A_EMPORTER"
                                class="peer sr-only"
                                checked
                            >

                            <div
                                class="border-2 border-stone-200 rounded-2xl p-5 transition
                                       peer-checked:border-orange-500
                                       peer-checked:bg-orange-50
                                       hover:border-orange-300"
                            >

                                <div class="flex items-start gap-4">

                                    <div
                                        class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0"
                                    >
                                        <i class="fa-solid fa-bag-shopping"></i>
                                    </div>

                                    <div>

                                        <h3 class="font-extrabold text-stone-900">
                                            À emporter
                                        </h3>

                                        <p class="text-sm text-stone-500 mt-1">
                                            Comptoir
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </label>


                        <!-- Sur place -->

                        <label class="relative cursor-pointer">

                            <input
                                type="radio"
                                name="mode_retrait"
                                value="SUR_PLACE"
                                class="peer sr-only"
                            >

                            <div
                                class="border-2 border-stone-200 rounded-2xl p-5 transition
                                       peer-checked:border-orange-500
                                       peer-checked:bg-orange-50
                                       hover:border-orange-300"
                            >

                                <div class="flex items-start gap-4">

                                    <div
                                        class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0"
                                    >
                                        <i class="fa-solid fa-utensils"></i>
                                    </div>

                                    <div>

                                        <h3 class="font-extrabold text-stone-900">
                                            Sur place
                                        </h3>

                                        <p class="text-sm text-stone-500 mt-1">
                                            Au restaurant
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </label>

                    </div>


                    <div
                        class="mt-5 flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl p-4"
                    >

                        <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>

                        <p class="text-sm text-amber-800">
                            Le mode de retrait sera pris en compte lors de la préparation de votre commande.
                        </p>

                    </div>

                </section>


                <!-- =================================================
                     ARTICLES
                ================================================== -->

                <section
                    class="bg-white rounded-3xl shadow-sm border border-stone-200 p-6 md:p-8"
                >

                    <div class="flex items-center justify-between gap-4 mb-6">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-11 h-11 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center"
                            >
                                <i class="fa-solid fa-basket-shopping"></i>
                            </div>

                            <div>

                                <h2 class="text-xl font-extrabold text-stone-900">
                                    Votre commande
                                </h2>

                                <p class="text-sm text-stone-500">
                                    <?= count($lignes) ?>
                                    <?= count($lignes) > 1 ? 'articles' : 'article' ?>
                                </p>

                            </div>

                        </div>


                        <a
                            href="/panier"
                            class="text-sm font-bold text-orange-600 hover:text-orange-700"
                        >
                            Modifier
                        </a>

                    </div>


                    <div class="space-y-4">

                        <?php foreach ($lignes as $ligne): ?>

                            <?php

                            $produit = $ligne['produit'];

                            $quantite = (int) ($ligne['quantite'] ?? 1);

                            $prixUnitaire = (float) $produit->getPrix();

                            $sousTotal = $prixUnitaire * $quantite;

                            $nomProduit = htmlspecialchars(
                                $produit->getNom(),
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            $image = $produit->getImage();

                            ?>

                            <div
                                class="flex gap-4 p-4 bg-stone-50 rounded-2xl border border-stone-100"
                            >


                                <!-- Image produit -->

                                <div
                                    class="w-20 h-20 md:w-24 md:h-24 rounded-2xl overflow-hidden bg-stone-200 shrink-0"
                                >

                                    <?php if (!empty($image)): ?>

                                        <img
                                            src="<?= htmlspecialchars(
                                                $image,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                            alt="<?= $nomProduit ?>"
                                            class="w-full h-full object-cover"
                                        >

                                    <?php else: ?>

                                        <div
                                            class="w-full h-full flex items-center justify-center text-stone-400"
                                        >
                                            <i class="fa-solid fa-utensils text-2xl"></i>
                                        </div>

                                    <?php endif; ?>

                                </div>


                                <!-- Informations produit -->

                                <div class="flex-1 min-w-0">

                                    <h3 class="font-extrabold text-stone-900 truncate">
                                        <?= $nomProduit ?>
                                    </h3>

                                    <p class="text-sm text-stone-500 mt-1">
                                        <?= $formatPrix($prixUnitaire) ?> / unité
                                    </p>


                                    <div
                                        class="flex items-center justify-between gap-4 mt-3"
                                    >

                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full bg-white border border-stone-200 text-sm font-bold text-stone-700"
                                        >
                                            Quantité : <?= $quantite ?>
                                        </span>

                                        <span class="font-extrabold text-stone-900">
                                            <?= $formatPrix($sousTotal) ?>
                                        </span>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </section>

            </div>


            <!-- =================================================
                 RÉSUMÉ
            ================================================== -->

            <aside class="lg:col-span-1">

                <div
                    class="bg-white rounded-3xl shadow-sm border border-stone-200 p-6 sticky top-6"
                >

                    <h2 class="text-xl font-extrabold text-stone-900 mb-6">
                        Résumé de la commande
                    </h2>


                    <div class="space-y-4 text-sm">


                        <!-- Sous-total -->

                        <div class="flex items-center justify-between">

                            <span class="text-stone-500">
                                Sous-total
                            </span>

                            <span class="font-bold text-stone-800">
                                <?= $formatPrix($total) ?>
                            </span>

                        </div>


                        <!-- Frais -->

                        <div class="flex items-center justify-between">

                            <span class="text-stone-500">
                                Frais supplémentaires
                            </span>

                            <span class="font-bold text-stone-800">
                                0 FCFA
                            </span>

                        </div>

                    </div>


                    <div class="border-t border-stone-200 my-6"></div>


                    <!-- Total -->

                    <div class="flex items-center justify-between mb-6">

                        <span class="text-lg font-extrabold text-stone-900">
                            Total
                        </span>

                        <span class="text-2xl font-black text-orange-600">
                            <?= $formatPrix($total) ?>
                        </span>

                    </div>


                    <!-- =================================================
                         TEMPS DE PRÉPARATION
                    ================================================== -->

                    <div class="bg-stone-50 rounded-2xl p-4 mb-6">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center"
                            >
                                <i class="fa-solid fa-clock"></i>
                            </div>

                            <div>

                                <p class="text-xs text-stone-500 font-semibold">
                                    Temps de préparation estimé
                                </p>

                                <p class="font-extrabold text-stone-900">
                                    Environ 20 à 30 min
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         CONFIRMATION
                    ================================================== -->

                    <form
                        action="/commandes/valider"
                        method="POST"
                    >

                        <button
                            type="submit"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-stone-900 font-extrabold py-4 rounded-2xl transition flex items-center justify-center gap-3 shadow-sm"
                        >

                            <span>
                                Confirmer et Commander
                            </span>

                            <i class="fa-solid fa-check"></i>

                        </button>

                    </form>


                    <p class="text-xs text-center text-stone-400 mt-4">
                        En confirmant, votre commande sera enregistrée et envoyée en préparation.
                    </p>

                </div>

            </aside>

        </div>

    </div>

</div>