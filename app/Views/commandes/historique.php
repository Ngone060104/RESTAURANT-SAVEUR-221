<?php

/*
|--------------------------------------------------------------------------
| Formatage
|--------------------------------------------------------------------------
*/

$formatPrix = static function (float $prix): string {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
};

$formatDate = static function (string $date): string {
    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return $date;
    }

    return date('d/m/Y à H:i', $timestamp);
};


/*
|--------------------------------------------------------------------------
| Libellés des statuts
|--------------------------------------------------------------------------
*/

$statutLabel = static function (string $statut): string {

    return match ($statut) {

        'EN_ATTENTE' =>
        'En attente',

        'EN_PREPARATION' =>
        'En préparation',

        'PRETE' =>
        'Prête',

        'RETIREE' =>
        'Retirée',

        'ANNULEE' =>
        'Annulée',

        default =>
        $statut,
    };
};


/*
|--------------------------------------------------------------------------
| Classes des statuts
|--------------------------------------------------------------------------
*/

$statutClass = static function (string $statut): string {

    return match ($statut) {

        'EN_ATTENTE' =>
        'bg-amber-100 text-amber-700',

        'EN_PREPARATION' =>
        'bg-blue-100 text-blue-700',

        'PRETE' =>
        'bg-emerald-100 text-emerald-700',

        'RETIREE' =>
        'bg-green-100 text-green-700',

        'ANNULEE' =>
        'bg-red-100 text-red-700',

        default =>
        'bg-stone-100 text-stone-700',
    };
};

?>


<div class="min-h-screen bg-[#faf9f7] py-10">

    <div class="mx-auto max-w-[1150px] px-6">


        <!-- =====================================================
             RETOUR + EN-TÊTE
        ====================================================== -->

        <div class="mb-8">

            <a
                href="/"
                class="mb-5 inline-flex items-center gap-3 font-['DM_Sans'] text-[14px] text-[#333333] transition hover:text-[#fe9a00]">

                <i class="fa-solid fa-arrow-left text-[14px]"></i>

                <span>
                    Retour à l'accueil
                </span>

            </a>


            <div
                class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">

                <div>

                    <h1
                        class="font-['Inter'] text-[38px] font-extrabold leading-tight text-[#171717]">
                        Historique de mes Commandes
                    </h1>

                    <p
                        class="mt-2 font-['DM_Sans'] text-[16px] text-[#777777]">
                        Retrouvez toutes vos commandes passées et leur statut.
                    </p>

                </div>


                <!-- PANIER -->

                <a
                    href="/panier"
                    class="inline-flex h-[54px] items-center justify-center gap-3 rounded-[18px] bg-[#fe7518] px-7 font-['DM_Sans'] text-[15px] font-extrabold text-black shadow-sm transition hover:bg-[#e96810]">

                    <i class="fa-solid fa-cart-shopping"></i>

                    Mon panier

                </a>

            </div>

        </div>



        <!-- =====================================================
             RECHERCHE + FILTRE
        ====================================================== -->

        <section
            class="mb-8 rounded-[24px] border border-[#e5e5e5] bg-white p-6 shadow-sm">

            <div
                class="grid grid-cols-1 gap-5 md:grid-cols-3">


                <!-- RECHERCHE -->

                <div class="md:col-span-2">

                    <label
                        for="rechercheCommande"
                        class="mb-2 block font-['DM_Sans'] text-[14px] font-extrabold text-[#333333]">
                        Rechercher une commande
                    </label>


                    <div class="relative">

                        <i
                            class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-[#999999]"></i>


                        <input
                            id="rechercheCommande"
                            type="search"
                            placeholder="Rechercher par numéro de commande..."
                            class="h-[54px] w-full rounded-[18px] border border-[#e5e5e5] bg-[#fafafa] pl-12 pr-5 font-['DM_Sans'] text-[14px] text-[#333333] outline-none transition placeholder:text-[#a5a5a5] focus:border-[#fe9a00] focus:bg-white focus:ring-2 focus:ring-orange-100">

                    </div>

                </div>



                <!-- FILTRE -->

                <div>

                    <label
                        for="filtreStatut"
                        class="mb-2 block font-['DM_Sans'] text-[14px] font-extrabold text-[#333333]">
                        Filtrer par statut
                    </label>


                    <select
                        id="filtreStatut"
                        class="h-[54px] w-full rounded-[18px] border border-[#e5e5e5] bg-[#fafafa] px-5 font-['DM_Sans'] text-[14px] text-[#333333] outline-none transition focus:border-[#fe9a00] focus:bg-white focus:ring-2 focus:ring-orange-100">

                        <option value="">
                            Tous les statuts
                        </option>

                        <option value="EN_ATTENTE">
                            En attente
                        </option>

                        <option value="EN_PREPARATION">
                            En préparation
                        </option>

                        <option value="PRETE">
                            Prête
                        </option>

                        <option value="RETIREE">
                            Retirée
                        </option>

                        <option value="ANNULEE">
                            Annulée
                        </option>

                    </select>

                </div>

            </div>

        </section>



        <!-- =====================================================
             LISTE DES COMMANDES
        ====================================================== -->

        <div
            id="listeCommandes"
            class="space-y-5">

            <?php if (empty($commandes)): ?>

                <!-- =================================================
                     AUCUNE COMMANDE
                ================================================== -->

                <div
                    id="aucuneCommande"
                    class="rounded-[24px] border border-[#e5e5e5] bg-white p-12 text-center shadow-sm">

                    <div
                        class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-[18px] bg-orange-100 text-orange-600">

                        <i class="fa-solid fa-receipt text-2xl"></i>

                    </div>


                    <h2
                        class="font-['Inter'] text-[22px] font-extrabold text-[#171717]">
                        Aucune commande
                    </h2>


                    <p
                        class="mx-auto mt-2 max-w-md font-['DM_Sans'] text-[14px] text-[#777777]">
                        Vous n'avez pas encore passé de commande.
                    </p>


                    <a
                        href="/produits"
                        class="mt-6 inline-flex items-center gap-2 rounded-[14px] bg-[#fe7518] px-6 py-3 font-['DM_Sans'] text-[13px] font-extrabold text-black transition hover:bg-[#e96810]">

                        <i class="fa-solid fa-utensils"></i>

                        Découvrir le menu

                    </a>

                </div>


            <?php else: ?>


                <?php foreach ($commandes as $commande): ?>

                    <?php

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT
                    |--------------------------------------------------------------------------
                    | $commande est un objet App\Models\Commande.
                    | On utilise directement ses getters.
                    |--------------------------------------------------------------------------
                    */


                    $commandeObj = $commande['commande'];

                    $id = $commandeObj->getId();

                    $statut = $commandeObj->getStatut();

                    $montant = $commandeObj->getMontantTotal();

                    $date = $commandeObj->getDateCommande();

                    ?>


                    <!-- =================================================
                         CARTE COMMANDE
                    ================================================== -->

                    <article
                        class="commande-card rounded-[24px] border border-[#e5e5e5] bg-white p-6 shadow-sm transition hover:shadow-md"
                        data-id="<?= (int) $id ?>"
                        data-statut="<?= htmlspecialchars($statut, ENT_QUOTES, 'UTF-8') ?>"
                        data-search="commande #<?= (int) $id ?>">

                        <div
                            class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


                            <!-- =================================================
                                 INFORMATIONS
                            ================================================== -->

                            <div class="flex items-start gap-5">

                                <div
                                    class="flex h-[60px] w-[60px] shrink-0 items-center justify-center rounded-[20px] bg-orange-100 text-orange-600">

                                    <i
                                        class="fa-solid fa-receipt text-[20px]"></i>

                                </div>


                                <div>

                                    <div
                                        class="flex flex-wrap items-center gap-3">

                                        <h2
                                            class="font-['Inter'] text-[19px] font-extrabold text-[#171717]">
                                            Commande #<?= (int) $id ?>
                                        </h2>


                                        <span
                                            class="statut-badge inline-flex items-center rounded-full px-3 py-1 font-['DM_Sans'] text-[11px] font-extrabold <?= htmlspecialchars(
                                                                                                                                                                $statutClass($statut),
                                                                                                                                                                ENT_QUOTES,
                                                                                                                                                                'UTF-8'
                                                                                                                                                            ) ?>">
                                            <?= htmlspecialchars(
                                                $statutLabel($statut),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    </div>


                                    <p
                                        class="mt-2 font-['DM_Sans'] text-[13px] text-[#777777]">

                                        <i
                                            class="fa-regular fa-calendar mr-1"></i>

                                        <?= htmlspecialchars(
                                            $formatDate($date),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </p>

                                </div>

                            </div>



                            <!-- =================================================
                                 TOTAL + ACTIONS
                            ================================================== -->

                            <div
                                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-8">


                                <!-- TOTAL -->

                                <div class="sm:text-right">

                                    <p
                                        class="font-['DM_Sans'] text-[11px] font-bold uppercase tracking-wide text-[#999999]">
                                        Total
                                    </p>


                                    <p
                                        class="font-['Inter'] text-[22px] font-black text-[#e96510]">
                                        <?= htmlspecialchars(
                                            $formatPrix((float) $montant),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </p>

                                </div>



                                <!-- ACTIONS -->

                                <div class="flex items-center gap-3">


                                    <!-- SUIVI -->

                                    <?php if (
                                        in_array(
                                            $statut,
                                            [
                                                'EN_ATTENTE',
                                                'EN_PREPARATION',
                                                'PRETE'
                                            ],
                                            true
                                        )
                                    ): ?>

                                        <a
                                            href="/commande/suivi/<?= (int) $id ?>"
                                            class="inline-flex h-[44px] items-center justify-center gap-2 rounded-[14px] bg-[#f1f1f1] px-5 font-['DM_Sans'] text-[12px] font-bold text-[#333333] transition hover:bg-[#e5e5e5]">

                                            <i
                                                class="fa-solid fa-location-dot"></i>

                                            Suivi

                                        </a>

                                    <?php endif; ?>



                                    <!-- DÉTAIL -->

                                    <a
                                        href="/commande/detail/<?= (int) $id ?>"
                                        class="inline-flex h-[44px] items-center justify-center gap-2 rounded-[14px] bg-[#fe7518] px-5 font-['DM_Sans'] text-[13px] font-extrabold text-black transition hover:bg-[#e96810]">

                                        <span>
                                            Détail
                                        </span>

                                        <i
                                            class="fa-solid fa-arrow-right"></i>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </article>


                <?php endforeach; ?>


                <!-- =================================================
                     AUCUN RÉSULTAT FILTRE
                ================================================== -->

                <div
                    id="aucunResultat"
                    class="hidden rounded-[24px] border border-[#e5e5e5] bg-white p-10 text-center shadow-sm">

                    <div
                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-[18px] bg-stone-100 text-stone-400">

                        <i
                            class="fa-solid fa-magnifying-glass text-xl"></i>

                    </div>


                    <h2
                        class="font-['Inter'] text-[18px] font-extrabold text-[#171717]">
                        Aucune commande trouvée
                    </h2>


                    <p
                        class="mt-2 font-['DM_Sans'] text-[14px] text-[#777777]">
                        Essayez de modifier votre recherche ou votre filtre.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>



<!-- =============================================================
     JAVASCRIPT
============================================================== -->

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const recherche =
            document.getElementById('rechercheCommande');

        const filtre =
            document.getElementById('filtreStatut');

        const cartes =
            document.querySelectorAll('.commande-card');

        const aucunResultat =
            document.getElementById('aucunResultat');


        if (!recherche || !filtre || !cartes.length) {
            return;
        }


        function filtrerCommandes() {

            const texte =
                recherche.value.toLowerCase().trim();

            const statut =
                filtre.value;

            let nombreVisible = 0;


            cartes.forEach(function(carte) {

                const contenu =
                    (carte.dataset.search || '').toLowerCase();

                const carteStatut =
                    carte.dataset.statut || '';


                const correspondRecherche =
                    texte === '' ||
                    contenu.includes(texte);


                const correspondStatut =
                    statut === '' ||
                    carteStatut === statut;


                if (
                    correspondRecherche &&
                    correspondStatut
                ) {

                    carte.classList.remove('hidden');

                    nombreVisible++;

                } else {

                    carte.classList.add('hidden');

                }

            });


            if (aucunResultat) {

                if (nombreVisible === 0) {

                    aucunResultat.classList.remove('hidden');

                } else {

                    aucunResultat.classList.add('hidden');

                }

            }

        }


        recherche.addEventListener(
            'input',
            filtrerCommandes
        );


        filtre.addEventListener(
            'change',
            filtrerCommandes
        );

    });
</script>