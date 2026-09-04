<?php

/**
 * Vue : Accueil Saveur 221
 *
 * Variables reçues :
 * - $categories
 * - $produitsVedettes
 * - $avisRecents
 */

$prixFormate = static function (float $prix): string {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
};

/**
 * URL d'une image produit.
 *
 * La base peut contenir :
 * - "Thieboudieune.jpg"
 * - "/images/Thieboudieune.jpg"
 * - "images/Thieboudieune.jpg"
 */
$imageProduit = static function (?string $image): ?string {
    if (empty($image)) {
        return null;
    }

    $image = trim($image);

    if (str_starts_with($image, '/')) {
        return $image;
    }

    if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
        return $image;
    }

    if (str_starts_with($image, 'images/')) {
        return '/' . $image;
    }

    return '/images/' . ltrim($image, '/');
};

/**
 * Icône Font Awesome par catégorie.
 */
$iconeParCategorie = static function (string $libelle): string {
    $libelle = strtolower($libelle);

    return match (true) {
        str_contains($libelle, 'entr') =>
            'fa-solid fa-leaf',

        str_contains($libelle, 'plat') =>
            'fa-solid fa-bowl-food',

        str_contains($libelle, 'grillade'),
        str_contains($libelle, 'dibi') =>
            'fa-solid fa-fire',

        str_contains($libelle, 'fast'),
        str_contains($libelle, 'pastel') =>
            'fa-solid fa-burger',

        str_contains($libelle, 'boisson'),
        str_contains($libelle, 'jus') =>
            'fa-solid fa-glass-water',

        str_contains($libelle, 'dessert'),
        str_contains($libelle, 'douceur') =>
            'fa-solid fa-cake-candles',

        default =>
            'fa-solid fa-utensils',
    };
};

$nomInitiale = static function (?string $nom): string {
    $nom = trim($nom ?? '');

    if ($nom === '') {
        return '?';
    }

    if (function_exists('mb_substr')) {
        return mb_substr($nom, 0, 1);
    }

    return substr($nom, 0, 1);
};

?>

<main class="bg-[#faf9f7] text-[#222222]">

    <!-- =====================================================
         HERO
    ====================================================== -->

    <section class="mx-auto max-w-[1180px] px-5 pt-8 pb-12 md:px-7 md:pt-10 md:pb-14">

        <div class="grid items-center gap-8 lg:grid-cols-[0.92fr_1.08fr] lg:gap-10">

            <!-- TEXTE -->
            <div class="max-w-[570px]">

                <h1
                    class="font-['Inter'] text-[32px] font-black leading-[1.08] tracking-[-1.2px] text-[#111111] sm:text-[38px] lg:text-[42px]"
                >
                    Les meilleures saveurs de
                    Dakar à portée de clic avec
                    <span class="text-[#ff9000]">
                        Saveur 221.
                    </span>
                </h1>

                <p
                    class="mt-5 max-w-[540px] font-['DM_Sans'] text-[13px] leading-5 text-[#555555]"
                >
                    Dégustez notre authentique Thiéboudienne Penda Mbaye,
                    nos Dibis braisés à point et nos jus de fruits locaux
                    fraîchement pressés. Commandez en ligne, suivez votre
                    commande en temps réel et retirez au restaurant.
                </p>

                <!-- BOUTONS -->
                <div class="mt-7 flex flex-wrap items-center gap-3">

                    <a
                        href="/produits"
                        class="inline-flex h-[42px] items-center justify-center gap-2 rounded-[6px] bg-[#ff9500] px-5 font-['DM_Sans'] text-[12px] font-bold text-white transition hover:bg-[#e98500]"
                    >
                        <i class="fa-solid fa-basket-shopping text-[11px]"></i>
                        Commandez maintenant
                    </a>

                    <a
                        href="/produits"
                        class="inline-flex h-[42px] items-center justify-center gap-2 rounded-[6px] bg-[#292929] px-5 font-['DM_Sans'] text-[12px] font-bold text-white transition hover:bg-[#1d1d1d]"
                    >
                        Consulter la carte
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>

                </div>

                <!-- ARGUMENTS -->
                <div class="mt-7 flex flex-wrap gap-x-7 gap-y-3">

                    <div class="flex items-center gap-2 font-['DM_Sans'] text-[10px] text-[#555555]">
                        <span class="flex h-5 w-5 items-center justify-center text-[#ff9500]">
                            <i class="fa-solid fa-circle-check"></i>
                        </span>

                        Produits 100% Frais &amp; Locaux
                    </div>

                    <div class="flex items-center gap-2 font-['DM_Sans'] text-[10px] text-[#555555]">
                        <span class="flex h-5 w-5 items-center justify-center text-[#ff9500]">
                            <i class="fa-solid fa-heart"></i>
                        </span>

                        Véritable Teranga Dakaroise
                    </div>

                </div>

            </div>


            <!-- IMAGES HERO -->
            <div class="relative mx-auto h-[390px] w-full max-w-[620px] sm:h-[430px]">

                <!-- GRANDE IMAGE -->
                <div
                    class="absolute right-0 top-0 h-[300px] w-[76%] overflow-hidden rounded-tl-[90px] rounded-tr-[90px] rounded-br-[4px] rounded-bl-[90px] bg-[#dedbd5] sm:h-[350px]"
                >

                    <img
                        src="/images/hero-pancakes.jpg"
                        alt="Cuisine Saveur 221"
                        class="h-full w-full object-cover"
                    >

                </div>

                <!-- PETITE IMAGE -->
                <div
                    class="absolute bottom-0 left-[8%] z-10 h-[190px] w-[48%] overflow-hidden rounded-tl-[70px] rounded-tr-[70px] rounded-br-[4px] rounded-bl-[4px] border-[6px] border-[#faf9f7] bg-[#dedbd5] sm:h-[215px]"
                >

                    <img
                        src="/images/hero-thieboudienne.jpg"
                        alt="Thiéboudienne Saveur 221"
                        class="h-full w-full object-cover"
                    >

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         CATÉGORIES
    ====================================================== -->

    <section class="border-t border-[#e8e5e0]">

        <div class="mx-auto max-w-[1180px] px-5 py-9 md:px-7 md:py-11">

            <p
                class="font-['DM_Sans'] text-[9px] font-bold uppercase tracking-wide text-[#ff8c00]"
            >
                Explorer la carte
            </p>

            <h2
                class="mt-2 font-['Inter'] text-[22px] font-black tracking-[-0.5px] text-[#111111] md:text-[24px]"
            >
                Nos Grandes Catégories Culinaires
            </h2>

            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">

                <?php foreach (array_slice($categories, 0, 5) as $categorie): ?>

                    <a
                        href="/produits?categorie=<?= $categorie->getId() ?>"
                        class="group overflow-hidden rounded-[11px] border border-[#e2e2e2] bg-white shadow-[0_2px_3px_rgba(0,0,0,0.08)] transition hover:-translate-y-0.5 hover:shadow-md"
                    >

                        <!-- IMAGE / ICÔNE -->
                        <div
                            class="flex h-[105px] items-center justify-center overflow-hidden bg-[#f2eee8] sm:h-[115px]"
                        >
                            <div
                                class="flex h-[64px] w-[64px] items-center justify-center rounded-full bg-[#fff0d8] text-[#ff9000] transition group-hover:scale-105"
                            >
                                <i
                                    class="<?= htmlspecialchars($iconeParCategorie($categorie->getLibelle()), ENT_QUOTES, 'UTF-8') ?> text-[28px]"
                                ></i>
                            </div>
                        </div>

                        <div class="px-3 py-3">

                            <p
                                class="truncate font-['DM_Sans'] text-[11px] font-bold text-[#222222]"
                            >
                                <?= htmlspecialchars($categorie->getLibelle(), ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <?php if ($categorie->getDescription()): ?>

                                <p
                                    class="mt-1 truncate font-['DM_Sans'] text-[8px] text-[#888888]"
                                >
                                    <?= htmlspecialchars($categorie->getDescription(), ENT_QUOTES, 'UTF-8') ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        </div>

    </section>


    <!-- =====================================================
         PLATS VEDETTES
    ====================================================== -->

    <section class="mx-auto max-w-[1180px] px-5 py-10 md:px-7 md:py-12">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <p
                    class="font-['DM_Sans'] text-[9px] font-bold uppercase tracking-wide text-[#ff8c00]"
                >
                    Les incontournables
                </p>

                <h2
                    class="mt-2 font-['Inter'] text-[22px] font-black tracking-[-0.5px] text-[#111111] md:text-[24px]"
                >
                    Plats les Plus Plébiscités
                </h2>

            </div>

            <p
                class="max-w-[350px] font-['DM_Sans'] text-[10px] leading-5 text-[#555555]"
            >
                Des recettes mijotées dès l'aube par notre brigade sénégalaise
                pour vous offrir un goût inimitable.
            </p>

        </div>


        <!-- PRODUITS -->

        <div class="mt-6 grid gap-5 md:grid-cols-3">

            <?php foreach ($produitsVedettes as $produit): ?>

                <?php
                $image = $imageProduit($produit->getImage());
                ?>

                <article
                    class="overflow-hidden rounded-[12px] border border-[#e7e7e7] bg-white shadow-[0_2px_5px_rgba(0,0,0,0.04)]"
                >

                    <!-- IMAGE -->
                    <div class="relative h-[190px] overflow-hidden bg-[#e9e6e0]">

                        <?php if ($image): ?>

                            <img
                                src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($produit->getNom(), ENT_QUOTES, 'UTF-8') ?>"
                                class="h-full w-full object-cover transition duration-300 hover:scale-[1.03]"
                            >

                        <?php else: ?>

                            <div class="flex h-full items-center justify-center text-[#b8b2aa]">
                                <i class="fa-solid fa-utensils text-[40px]"></i>
                            </div>

                        <?php endif; ?>


                        <!-- DISPONIBILITÉ -->

                        <span
                            class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-[#00a875] px-2.5 py-1 font-['DM_Sans'] text-[8px] font-bold text-white"
                        >
                            <i class="fa-solid fa-circle text-[5px]"></i>
                            Disponible
                        </span>


                        <!-- CATÉGORIE -->

                        <span
                            class="absolute right-3 top-3 max-w-[125px] truncate rounded-full bg-[#292929]/90 px-2.5 py-1 font-['DM_Sans'] text-[8px] font-bold text-white"
                        >
                            <?= htmlspecialchars($produit->getCategorieLibelle() ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </span>

                    </div>


                    <!-- CONTENU -->

                    <div class="p-4">

                        <h3
                            class="truncate font-['Inter'] text-[14px] font-black text-[#222222]"
                        >
                            <?= htmlspecialchars($produit->getNom(), ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p
                            class="mt-2 line-clamp-2 min-h-[32px] font-['DM_Sans'] text-[9px] leading-4 text-[#888888]"
                        >
                            <?= htmlspecialchars($produit->getDescription() ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </p>


                        <!-- BAS CARTE -->

                        <div
                            class="mt-4 flex items-end justify-between border-t border-[#eeeeee] pt-3"
                        >

                            <div>

                                <p
                                    class="font-['DM_Sans'] text-[8px] text-[#888888]"
                                >
                                    Prix
                                </p>

                                <p
                                    class="mt-1 font-['Inter'] text-[13px] font-black text-[#ff8500]"
                                >
                                    <?= $prixFormate($produit->getPrix()) ?>
                                </p>

                            </div>


                            <div class="flex items-center gap-3">

                                <a
                                    href="/produit/<?= $produit->getId() ?>"
                                    class="font-['DM_Sans'] text-[9px] font-bold text-[#333333] transition hover:text-[#ff8500]"
                                >
                                    Détail
                                </a>


                                <form
                                    action="/panier/ajouter"
                                    method="post"
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
                                        class="inline-flex h-[31px] items-center gap-1.5 rounded-[7px] bg-[#ff9500] px-3 font-['DM_Sans'] text-[9px] font-bold text-white transition hover:bg-[#e98400]"
                                    >
                                        <i class="fa-solid fa-basket-shopping text-[8px]"></i>
                                        Ajouter
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </section>


    <!-- =====================================================
         BANDEAU PROMOTIONNEL
    ====================================================== -->

    <section class="mx-auto max-w-[1180px] px-5 pb-10 md:px-7">

        <div
            class="flex flex-col gap-6 rounded-[20px] bg-gradient-to-r from-[#ff8c00] to-[#ad3e00] px-7 py-7 text-white sm:px-9 sm:py-8 md:flex-row md:items-center md:justify-between"
        >

            <div class="max-w-[620px]">

                <span
                    class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 font-['DM_Sans'] text-[8px] font-bold"
                >
                    Formule Déjeuner &amp; Dîner
                </span>

                <h2
                    class="mt-3 font-['Inter'] text-[22px] font-black leading-tight md:text-[25px]"
                >
                    Savourez notre Thiéboudienne
                    avec un Jus de Bouye artisanal
                    offert !
                </h2>

                <p
                    class="mt-3 max-w-[580px] font-['DM_Sans'] text-[9px] leading-4 text-white/90"
                >
                    Commandez en ligne et profitez d'un temps de préparation
                    garanti de 15 à 25 minutes. Retrait direct à notre comptoir express.
                </p>

            </div>


            <a
                href="/produits"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-[6px] bg-white px-5 py-3 font-['DM_Sans'] text-[9px] font-bold text-[#b84b00] transition hover:bg-[#fff7ee]"
            >
                Découvrez la formule
                <i class="fa-solid fa-arrow-right text-[8px]"></i>
            </a>

        </div>

    </section>


    <!-- =====================================================
         AVIS CLIENTS
    ====================================================== -->

    <?php if (!empty($avisRecents)): ?>

        <section class="mx-auto max-w-[1180px] px-5 py-10 text-center md:px-7 md:py-12">

            <p
                class="font-['DM_Sans'] text-[9px] font-bold uppercase tracking-wide text-[#ff8c00]"
            >
                Avis &amp; témoignages clients
            </p>

            <h2
                class="mt-2 font-['Inter'] text-[22px] font-black tracking-[-0.5px] text-[#111111] md:text-[24px]"
            >
                La Satisfaction De Nos Clients Saveur 221
            </h2>

            <p
                class="mx-auto mt-2 max-w-[560px] font-['DM_Sans'] text-[10px] leading-5 text-[#888888]"
            >
                Retours authentiques enregistrés après retrait de commande
                par nos clients vérifiés.
            </p>


            <!-- AVIS -->

            <div class="mt-7 grid gap-5 text-left md:grid-cols-2">

                <?php foreach ($avisRecents as $avis): ?>

                    <?php
                    $nomClient = $avis->getClientNomComplet() ?? 'Client';
                    $initiale = $nomInitiale($nomClient);
                    $note = max(0, min(5, (int) $avis->getNote()));
                    ?>

                    <article
                        class="rounded-[12px] border border-[#e1e1e1] bg-white p-5 shadow-[0_2px_4px_rgba(0,0,0,0.04)]"
                    >

                        <!-- HEADER AVIS -->

                        <div class="flex items-center justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-[34px] w-[34px] shrink-0 items-center justify-center overflow-hidden rounded-full bg-[#f1eee9] font-['DM_Sans'] text-[11px] font-bold text-[#ff8500]"
                                >
                                    <?= htmlspecialchars($initiale, ENT_QUOTES, 'UTF-8') ?>
                                </div>

                                <div>

                                    <p
                                        class="font-['DM_Sans'] text-[10px] font-bold text-[#222222]"
                                    >
                                        <?= htmlspecialchars($nomClient, ENT_QUOTES, 'UTF-8') ?>
                                    </p>

                                    <p
                                        class="mt-0.5 font-['DM_Sans'] text-[7px] text-[#999999]"
                                    >
                                        Commande CMD-2026-001
                                    </p>

                                </div>

                            </div>


                            <!-- ÉTOILES -->

                            <div class="flex items-center gap-0.5 text-[#ff9500]">

                                <?php for ($i = 1; $i <= 5; $i++): ?>

                                    <?php if ($i <= $note): ?>

                                        <i class="fa-solid fa-star text-[9px]"></i>

                                    <?php else: ?>

                                        <i class="fa-regular fa-star text-[9px]"></i>

                                    <?php endif; ?>

                                <?php endfor; ?>

                            </div>

                        </div>


                        <!-- COMMENTAIRE -->

                        <p
                            class="mt-4 font-['DM_Sans'] text-[10px] italic leading-5 text-[#666666]"
                        >
                            "<?= htmlspecialchars($avis->getCommentaire() ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        </p>


                        <!-- PRODUIT -->

                        <?php if ($avis->getProduitNom()): ?>

                            <div
                                class="mt-4 flex items-center justify-between gap-3 border-t border-[#eeeeee] pt-3"
                            >

                                <span
                                    class="font-['DM_Sans'] text-[8px] text-[#777777]"
                                >
                                    Plat apprécié :
                                </span>

                                <span
                                    class="text-right font-['DM_Sans'] text-[8px] font-bold text-[#ff8500]"
                                >
                                    <?= htmlspecialchars($avis->getProduitNom(), ENT_QUOTES, 'UTF-8') ?>
                                </span>

                            </div>

                        <?php endif; ?>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>

    <?php endif; ?>

</main>