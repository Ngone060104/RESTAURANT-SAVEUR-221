<?php

/**
 * Vue : Suivi d'une commande
 *
 * Variables reçues :
 * - $commande
 * - $lignes
 * - $paiements
 * - $statutPaiement
 */

$commande = $commande ?? null;

if ($commande === null) {
    http_response_code(404);
    return;
}

/* =========================================================
   FORMATAGE
========================================================= */

$formatPrix = static function (float $prix): string {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
};

/* =========================================================
   DONNÉES COMMANDE
========================================================= */

$idCommande = $commande->getId();

$numeroCommande = 'CMD-' . date('Y') . '-' . str_pad(
    (string) $idCommande,
    3,
    '0',
    STR_PAD_LEFT
);

$statutActuel = $commande->getStatut();

$total = (float) $commande->getMontantTotal();

$dateCommande = $commande->getDateCommande();

try {
    $dateObjet = new DateTime($dateCommande);

    $dateFormatee = $dateObjet->format('d/m/Y');
    $heureFormatee = $dateObjet->format('H:i');

} catch (Throwable $e) {

    $dateFormatee = htmlspecialchars(
        $dateCommande,
        ENT_QUOTES,
        'UTF-8'
    );

    $heureFormatee = '';
}

/* =========================================================
   STATUTS
========================================================= */

$etapes = [

    'EN_ATTENTE' => [
        'label' => 'En attente',
        'description' => 'Commande enregistrée, attente de prise en charge',
        'icone' => 'fa-regular fa-clock',
    ],

    'EN_PREPARATION' => [
        'label' => 'En préparation',
        'description' => 'Cuisson et dressage en cuisine par nos chefs',
        'icone' => 'fa-solid fa-utensils',
    ],

    'PRETE' => [
        'label' => 'Prête au comptoir',
        'description' => 'Votre repas chaud est prêt à être récupéré',
        'icone' => 'fa-solid fa-store',
    ],

    'RETIREE' => [
        'label' => 'Retirée',
        'description' => 'Commande remise au client. Bon appétit !',
        'icone' => 'fa-regular fa-circle-check',
    ],

];

/* =========================================================
   ORDRE OFFICIEL DES ÉTAPES
========================================================= */

$ordreEtapes = [
    'EN_ATTENTE',
    'EN_PREPARATION',
    'PRETE',
    'RETIREE',
];

$indexActuel = array_search(
    $statutActuel,
    $ordreEtapes,
    true
);

if ($indexActuel === false) {
    $indexActuel = -1;
}

/* =========================================================
   BADGE STATUT
========================================================= */

$badgeClasses = match ($statutActuel) {

    'EN_ATTENTE' =>
        'bg-orange-50 text-orange-600 border-orange-300',

    'EN_PREPARATION' =>
        'bg-blue-50 text-blue-600 border-blue-300',

    'PRETE' =>
        'bg-emerald-50 text-emerald-600 border-emerald-300',

    'RETIREE' =>
        'bg-green-50 text-green-600 border-green-300',

    'ANNULEE' =>
        'bg-red-50 text-red-600 border-red-300',

    default =>
        'bg-stone-50 text-stone-600 border-stone-300',
};

$statutLabel = match ($statutActuel) {

    'EN_ATTENTE' =>
        'En attente',

    'EN_PREPARATION' =>
        'En préparation',

    'PRETE' =>
        'Prête au comptoir',

    'RETIREE' =>
        'Retirée',

    'ANNULEE' =>
        'Annulée',

    default =>
        $statutActuel,
};

?>

<main class="min-h-screen bg-[#faf9f7]">

    <!-- =====================================================
         RETOUR
    ====================================================== -->

    <section class="mx-auto max-w-[1150px] px-6 pt-7">

        <div class="flex items-center justify-between gap-6">

            <!-- RETOUR -->

            <a
                href="/mes-commandes"
                class="inline-flex items-center gap-3 font-['DM_Sans'] text-[14px] text-[#333333] transition hover:text-[#fe9a00]"
            >

                <span class="text-[23px] leading-none">
                    ←
                </span>

                <span>
                    Retour à la liste de mes commandes
                </span>

            </a>


            <!-- STATUT -->

            <span
                id="statutBadge"
                class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-[11px] font-semibold <?= $badgeClasses ?>"
            >

                <i
                    id="statutBadgeIcon"
                    class="<?= $statutActuel === 'ANNULEE'
                        ? 'fa-regular fa-circle-xmark'
                        : 'fa-regular fa-clock' ?>"
                ></i>

                <span id="statutBadgeLabel">
                    <?= htmlspecialchars(
                        $statutLabel,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>

            </span>

        </div>

    </section>


    <!-- =====================================================
         CARTE PRINCIPALE
    ====================================================== -->

    <section class="mx-auto max-w-[1150px] px-6 pb-10 pt-6">

        <div
            class="overflow-hidden rounded-[16px] border border-[#dddddd] bg-white shadow-[0_1px_3px_rgba(0,0,0,0.03)]"
        >

            <!-- =================================================
                 EN-TÊTE COMMANDE
            ================================================== -->

            <div class="px-7 py-6 md:px-10 md:py-7">

                <div
                    class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between"
                >

                    <!-- INFORMATIONS -->

                    <div>

                        <p
                            class="mb-3 font-['DM_Sans'] text-[12px] font-bold uppercase text-[#fe7900]"
                        >
                            SUIVI EN DIRECT
                        </p>


                        <h1
                            class="font-['Inter'] text-[30px] font-black leading-tight text-[#252525] md:text-[38px]"
                        >

                            Commande

                            <?= htmlspecialchars(
                                $numeroCommande,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </h1>


                        <p
                            class="mt-3 font-['DM_Sans'] text-[12px] text-[#333333]"
                        >

                            Passée le

                            <?= htmlspecialchars(
                                $dateFormatee,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                            <?php if ($heureFormatee !== ''): ?>

                                à

                                <?= htmlspecialchars(
                                    $heureFormatee,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            <?php endif; ?>

                            • Mode : À emporter

                        </p>

                    </div>


                    <!-- MONTANT -->

                    <div class="text-left md:text-right">

                        <p
                            class="font-['DM_Sans'] text-[12px] font-bold uppercase text-[#333333]"
                        >
                            MONTANT
                        </p>

                        <p
                            class="mt-2 font-['Inter'] text-[28px] font-black text-[#c96500] md:text-[34px]"
                        >
                            <?= $formatPrix($total) ?>
                        </p>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 SÉPARATION
            ================================================== -->

            <div class="h-px bg-[#dedede]"></div>


            <!-- =================================================
                 AFFICHAGE COMMANDE ANNULÉE / ÉTAPES
            ================================================== -->

            <div id="suiviEtatContainer">

                <?php if ($statutActuel === 'ANNULEE'): ?>

                    <!-- =================================================
                         COMMANDE ANNULÉE
                    ================================================== -->

                    <div
                        id="commandeAnnulee"
                        class="px-7 py-7 md:px-10 md:py-8"
                    >

                        <div
                            class="flex items-center gap-5 rounded-[16px] border border-red-500 bg-red-100 px-5 py-5"
                        >

                            <!-- ICÔNE -->

                            <div
                                class="flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-full border-[3px] border-red-600"
                            >

                                <i
                                    class="fa-solid fa-xmark text-[21px] text-red-600"
                                ></i>

                            </div>


                            <!-- TEXTE -->

                            <div>

                                <h2
                                    class="font-['Inter'] text-[16px] font-bold text-red-700"
                                >
                                    Commande Annulée
                                </h2>

                                <p
                                    class="mt-2 font-['DM_Sans'] text-[13px] leading-6 text-red-600"
                                >
                                    Cette commande a été annulée.
                                    Conformément à la
                                    <strong>Règle métier #8</strong>,
                                    l'ensemble des articles commandés a été
                                    réinjecté dans le stock disponible de la cuisine.
                                </p>

                            </div>

                        </div>

                    </div>

                <?php else: ?>

                    <!-- =================================================
                         ÉTAPES DE LA COMMANDE
                    ================================================== -->

                    <div
                        id="etapesCommande"
                        class="px-6 py-6 md:px-10 md:py-7"
                    >

                        <div
                            class="grid grid-cols-1 gap-7 md:grid-cols-[1fr_auto_1fr_auto_1fr_auto_1fr] md:items-start"
                        >

                            <?php foreach ($ordreEtapes as $index => $code): ?>

                                <?php

                                $etape = $etapes[$code];

                                $estAtteinte =
                                    $indexActuel >= $index;

                                $estActuelle =
                                    $indexActuel === $index;

                                ?>

                                <!-- ÉTAPE -->

                                <div
                                    class="text-center"
                                    data-step="<?= $code ?>"
                                >

                                    <div class="flex justify-center">

                                        <div
                                            data-step-icon="<?= $code ?>"
                                            class="
                                                flex h-[64px] w-[64px]
                                                items-center justify-center
                                                rounded-[21px]
                                                transition-all duration-300
                                                <?= $estAtteinte
                                                    ? 'bg-[#ff9800] text-black shadow-[0_0_0_4px_#e3e3e3]'
                                                    : 'bg-[#f5f5f5] text-[#bdbdbd]' ?>
                                            "
                                        >

                                            <i
                                                data-step-icon-element="<?= $code ?>"
                                                class="<?= $etape['icone'] ?> text-[25px]"
                                            ></i>

                                        </div>

                                    </div>


                                    <h3
                                        data-step-label="<?= $code ?>"
                                        class="
                                            mt-3 font-['DM_Sans']
                                            text-[13px] font-bold
                                            transition-colors duration-300
                                            <?= $estAtteinte
                                                ? 'text-[#c96500]'
                                                : 'text-[#333333]' ?>
                                        "
                                    >

                                        <?= htmlspecialchars(
                                            $etape['label'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </h3>


                                    <p
                                        class="mx-auto mt-1 max-w-[160px] font-['DM_Sans'] text-[10px] leading-4 text-[#888888]"
                                    >

                                        <?= htmlspecialchars(
                                            $etape['description'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </p>

                                </div>


                                <!-- CONNECTEUR -->

                                <?php if (
                                    $index < count($ordreEtapes) - 1
                                ): ?>

                                    <div
                                        data-connector="<?= $index ?>"
                                        class="hidden h-[3px] w-[55px] self-center bg-[#dedede] transition-colors duration-300 md:block"
                                    ></div>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        </div>

                    </div>

                <?php endif; ?>

            </div>


            <!-- =================================================
                 SÉPARATION
            ================================================== -->

            <div class="mx-7 h-px bg-[#dedede] md:mx-10"></div>


            <!-- =================================================
                 HISTORIQUE DES ÉTAPES
            ================================================== -->

            <div class="px-7 py-6 md:px-10">

                <h2
                    class="font-['Inter'] text-[17px] font-medium uppercase text-[#222222]"
                >
                    HISTORIQUE DES ÉTAPES DE LA COMMANDE
                </h2>


                <div
                    id="historiqueCommande"
                    class="mt-5 space-y-4"
                >

                    <!-- =================================================
                         COMMANDE EN ATTENTE
                    ================================================== -->

                    <div
                        class="flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4"
                    >

                        <!-- BADGE -->

                        <span
                            class="inline-flex shrink-0 items-center gap-2 rounded-full border border-orange-300 bg-orange-50 px-3 py-1 text-[10px] font-semibold text-orange-600"
                        >

                            <i class="fa-regular fa-clock"></i>

                            En attente

                        </span>


                        <!-- TEXTE -->

                        <p
                            class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]"
                        >
                            Commande enregistrée avec succès via le site web Saveur 221
                        </p>


                        <!-- HEURE -->

                        <span
                            class="shrink-0 font-['DM_Sans'] text-[14px] font-medium text-[#333333]"
                        >
                            <?= htmlspecialchars(
                                $heureFormatee,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>

                    </div>


                    <?php if ($statutActuel === 'ANNULEE'): ?>

                        <!-- =================================================
                             COMMANDE ANNULÉE
                        ================================================== -->

                        <div
                            id="historiqueAnnulation"
                            class="flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4"
                        >

                            <!-- BADGE -->

                            <span
                                class="inline-flex shrink-0 items-center gap-2 rounded-full border border-red-400 bg-red-50 px-3 py-1 text-[10px] font-semibold text-red-600"
                            >

                                <i class="fa-regular fa-circle-xmark"></i>

                                Annulée

                            </span>


                            <!-- TEXTE -->

                            <p
                                class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]"
                            >
                                Annulée par le client - Stock restitué
                            </p>


                            <!-- HEURE -->

                            <span
                                class="shrink-0 font-['DM_Sans'] text-[14px] font-medium text-[#333333]"
                            >
                                <?= date('H:i') ?>
                            </span>

                        </div>

                    <?php else: ?>

                        <!-- =================================================
                             HISTORIQUE DU STATUT ACTUEL
                        ================================================== -->

                        <?php if ($statutActuel !== 'EN_ATTENTE'): ?>

                            <div
                                id="historiqueStatutActuel"
                                class="flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4"
                            >

                                <!-- BADGE -->

                                <span
                                    id="historiqueBadge"
                                    class="inline-flex shrink-0 items-center gap-2 rounded-full border px-3 py-1 text-[10px] font-semibold <?= $badgeClasses ?>"
                                >

                                    <i
                                        id="historiqueBadgeIcon"
                                        class="<?= $etapes[$statutActuel]['icone'] ?? 'fa-regular fa-clock' ?>"
                                    ></i>

                                    <span id="historiqueBadgeLabel">
                                        <?= htmlspecialchars(
                                            $statutLabel,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </span>


                                <!-- TEXTE -->

                                <p
                                    id="historiqueMessage"
                                    class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]"
                                >

                                    <?php if ($statutActuel === 'EN_PREPARATION'): ?>

                                        Votre commande est actuellement en préparation dans notre cuisine

                                    <?php elseif ($statutActuel === 'PRETE'): ?>

                                        Votre commande est prête au comptoir et attend votre retrait

                                    <?php elseif ($statutActuel === 'RETIREE'): ?>

                                        Commande remise au client. Bon appétit !

                                    <?php endif; ?>

                                </p>


                                <!-- HEURE -->

                                <span
                                    id="historiqueHeure"
                                    class="shrink-0 font-['DM_Sans'] text-[14px] font-medium text-[#333333]"
                                >

                                    <?= htmlspecialchars(
                                        $heureFormatee,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </div>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>

            </div>


            <!-- =================================================
                 ACTIONS
            ================================================== -->

            <div
                class="border-t border-[#dedede] px-7 py-5 md:px-10"
            >

                <div
                    class="flex items-center justify-end gap-4"
                >

                    <!-- ANNULER -->

                    <div id="annulationContainer">

                        <?php if (
                            in_array(
                                $statutActuel,
                                [
                                    'EN_ATTENTE',
                                    'EN_PREPARATION'
                                ],
                                true
                            )
                        ): ?>

                            <button
                                type="button"
                                id="openCancelModal"
                                class="inline-flex items-center justify-center gap-3 rounded-[9px] border border-red-500 px-5 py-2.5 font-['DM_Sans'] text-[13px] font-medium text-red-500 transition hover:bg-red-50"
                            >

                                <i class="fa-regular fa-circle-xmark"></i>

                                Annuler cette commande

                            </button>

                        <?php endif; ?>

                    </div>


                    <!-- DÉTAIL COMPLET -->

                    <a
                        href="/commande/detail/<?= $idCommande ?>"
                        class="inline-flex items-center justify-center gap-3 rounded-[9px] border border-[#dddddd] px-5 py-2.5 font-['DM_Sans'] text-[12px] font-bold text-[#333333] transition hover:bg-[#f8f8f8]"
                    >

                        <i class="fa-regular fa-file-lines"></i>

                        Voir la facture / détail complet

                    </a>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         MODAL ANNULATION COMMANDE
    ========================================================== -->

    <div
        id="cancelModal"
        class="fixed inset-0 z-[9999] hidden items-start justify-center bg-black/50 px-4 pt-[10vh]"
        aria-hidden="true"
    >

        <div
            id="cancelModalContent"
            class="relative w-full max-w-[460px] overflow-hidden rounded-[18px] bg-white shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="cancelModalTitle"
        >

            <!-- HEADER -->

            <div
                class="flex items-center justify-between border-b border-[#eeeeee] px-5 py-4"
            >

                <h2
                    id="cancelModalTitle"
                    class="font-['Inter'] text-[19px] font-bold text-[#111111]"
                >
                    Annuler la commande ?
                </h2>


                <button
                    type="button"
                    id="closeCancelModal"
                    class="flex h-8 w-8 items-center justify-center text-[28px] leading-none text-[#111111] transition hover:text-[#fe7900]"
                    aria-label="Fermer"
                >
                    ×
                </button>

            </div>


            <!-- CONTENU -->

            <div class="px-5 py-5 text-center">

                <!-- ICÔNE AVERTISSEMENT -->

                <div class="flex justify-center">

                    <div
                        class="flex h-[54px] w-[54px] items-center justify-center rounded-[16px] bg-red-100"
                    >

                        <i
                            class="fa-solid fa-triangle-exclamation text-[28px] text-red-600"
                        ></i>

                    </div>

                </div>


                <!-- MESSAGE -->

                <p
                    class="mx-auto mt-4 max-w-[390px] font-['DM_Sans'] text-[13px] leading-5 text-[#777777]"
                >
                    Êtes-vous sûr de vouloir annuler cette commande ?

                    Les articles seront automatiquement restitués au stock
                    du restaurant (Règle métier #8).
                </p>


                <!-- BOUTONS -->

                <div
                    class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2"
                >

                    <!-- ANNULER -->

                    <button
                        type="button"
                        id="cancelModalButton"
                        class="inline-flex h-[42px] items-center justify-center rounded-[10px] border border-[#dddddd] bg-white px-5 font-['DM_Sans'] text-[13px] font-bold text-[#333333] transition hover:bg-[#f7f7f7]"
                    >
                        Annuler
                    </button>


                    <!-- CONFIRMER -->

                    <form
                        method="POST"
                        action="/commandes/<?= $idCommande ?>/annuler"
                        class="w-full"
                    >

                        <button
                            type="submit"
                            class="inline-flex h-[42px] w-full items-center justify-center rounded-[10px] bg-[#ff4545] px-5 font-['DM_Sans'] text-[13px] font-bold text-white transition hover:bg-[#e93636]"
                        >
                            Oui, annuler
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         JAVASCRIPT
         1. MODAL
         2. SUIVI EN TEMPS RÉEL
    ========================================================== -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            /* =====================================================
               CONFIGURATION
            ===================================================== */

            const commandeId = <?= (int) $idCommande ?>;

            const urlEtat =
                `/commande/suivi/${commandeId}/etat`;

            const intervalle = 3000;

            let statutPrecedent =
                <?= json_encode($statutActuel) ?>;


            /* =====================================================
               DONNÉES DES STATUTS
            ===================================================== */

            const statuts = {

                EN_ATTENTE: {
                    label: 'En attente',
                    message: 'Commande enregistrée avec succès via le site web Saveur 221',
                    icon: 'fa-regular fa-clock',
                    badge:
                        'bg-orange-50 text-orange-600 border-orange-300'
                },

                EN_PREPARATION: {
                    label: 'En préparation',
                    message: 'Votre commande est actuellement en préparation dans notre cuisine',
                    icon: 'fa-solid fa-utensils',
                    badge:
                        'bg-blue-50 text-blue-600 border-blue-300'
                },

                PRETE: {
                    label: 'Prête au comptoir',
                    message: 'Votre commande est prête au comptoir et attend votre retrait',
                    icon: 'fa-solid fa-store',
                    badge:
                        'bg-emerald-50 text-emerald-600 border-emerald-300'
                },

                RETIREE: {
                    label: 'Retirée',
                    message: 'Commande remise au client. Bon appétit !',
                    icon: 'fa-regular fa-circle-check',
                    badge:
                        'bg-green-50 text-green-600 border-green-300'
                },

                ANNULEE: {
                    label: 'Annulée',
                    message: 'Annulée par le client - Stock restitué',
                    icon: 'fa-regular fa-circle-xmark',
                    badge:
                        'bg-red-50 text-red-600 border-red-300'
                }

            };


            /* =====================================================
               ORDRE DES ÉTAPES
            ===================================================== */

            const ordreEtapes = [
                'EN_ATTENTE',
                'EN_PREPARATION',
                'PRETE',
                'RETIREE'
            ];


            /* =====================================================
               ÉLÉMENTS DOM
            ===================================================== */

            const statutBadge =
                document.getElementById('statutBadge');

            const statutBadgeIcon =
                document.getElementById('statutBadgeIcon');

            const statutBadgeLabel =
                document.getElementById('statutBadgeLabel');

            const etapesCommande =
                document.getElementById('etapesCommande');

            const historiqueCommande =
                document.getElementById('historiqueCommande');

            const annulationContainer =
                document.getElementById('annulationContainer');


            /* =====================================================
               MISE À JOUR DU BADGE PRINCIPAL
            ===================================================== */

            function mettreAJourBadge(statut) {

                const info = statuts[statut];

                if (!info) {
                    return;
                }

                if (!statutBadge ||
                    !statutBadgeIcon ||
                    !statutBadgeLabel) {
                    return;
                }

                /* Supprimer les anciennes classes */

                statutBadge.className =
                    'inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-[11px] font-semibold ' +
                    info.badge;

                statutBadgeIcon.className =
                    info.icon;

                statutBadgeLabel.textContent =
                    info.label;
            }


            /* =====================================================
               MISE À JOUR DES ÉTAPES
            ===================================================== */

            function mettreAJourEtapes(statut) {

                if (!etapesCommande) {
                    return;
                }

                const indexActuel =
                    ordreEtapes.indexOf(statut);

                if (indexActuel === -1) {
                    return;
                }


                ordreEtapes.forEach(function (code, index) {

                    const iconeContainer =
                        document.querySelector(
                            `[data-step-icon="${code}"]`
                        );

                    const label =
                        document.querySelector(
                            `[data-step-label="${code}"]`
                        );

                    const icone =
                        document.querySelector(
                            `[data-step-icon-element="${code}"]`
                        );


                    if (!iconeContainer) {
                        return;
                    }


                    const atteinte =
                        indexActuel >= index;


                    const actuelle =
                        indexActuel === index;


                    /* =================================================
                       ÉTAPE ATTEINTE
                    ================================================== */

                    if (atteinte) {

                        iconeContainer.className =
                            'flex h-[64px] w-[64px] items-center justify-center rounded-[21px] transition-all duration-300 bg-[#ff9800] text-black shadow-[0_0_0_4px_#e3e3e3]';

                        label?.classList.remove(
                            'text-[#333333]'
                        );

                        label?.classList.add(
                            'text-[#c96500]'
                        );

                    } else {

                        iconeContainer.className =
                            'flex h-[64px] w-[64px] items-center justify-center rounded-[21px] transition-all duration-300 bg-[#f5f5f5] text-[#bdbdbd]';

                        label?.classList.remove(
                            'text-[#c96500]'
                        );

                        label?.classList.add(
                            'text-[#333333]'
                        );

                    }


                    /* =================================================
                       ICÔNE
                    ================================================== */

                    if (icone && statuts[code]) {

                        icone.className =
                            statuts[code].icon +
                            ' text-[25px]';

                    }


                    /* =================================================
                       ANIMATION POUR L'ÉTAPE ACTUELLE
                    ================================================== */

                    if (actuelle) {

                        iconeContainer.classList.add(
                            'scale-105'
                        );

                    } else {

                        iconeContainer.classList.remove(
                            'scale-105'
                        );

                    }

                });


                /* =====================================================
                   CONNECTEURS
                ===================================================== */

                const connecteurs =
                    document.querySelectorAll(
                        '[data-connector]'
                    );

                connecteurs.forEach(
                    function (connecteur, index) {

                        if (indexActuel > index) {

                            connecteur.classList.remove(
                                'bg-[#dedede]'
                            );

                            connecteur.classList.add(
                                'bg-[#ff9800]'
                            );

                        } else {

                            connecteur.classList.remove(
                                'bg-[#ff9800]'
                            );

                            connecteur.classList.add(
                                'bg-[#dedede]'
                            );

                        }

                    }
                );

            }


            /* =====================================================
               CRÉER LE BLOC "COMMANDE ANNULÉE"
            ===================================================== */

            function afficherCommandeAnnulee() {

                if (etapesCommande) {

                    etapesCommande.outerHTML = `

                        <div
                            id="commandeAnnulee"
                            class="px-7 py-7 md:px-10 md:py-8"
                        >

                            <div
                                class="flex items-center gap-5 rounded-[16px] border border-red-500 bg-red-100 px-5 py-5"
                            >

                                <div
                                    class="flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-full border-[3px] border-red-600"
                                >

                                    <i
                                        class="fa-solid fa-xmark text-[21px] text-red-600"
                                    ></i>

                                </div>

                                <div>

                                    <h2
                                        class="font-['Inter'] text-[16px] font-bold text-red-700"
                                    >
                                        Commande Annulée
                                    </h2>

                                    <p
                                        class="mt-2 font-['DM_Sans'] text-[13px] leading-6 text-red-600"
                                    >
                                        Cette commande a été annulée.
                                        Conformément à la
                                        <strong>Règle métier #8</strong>,
                                        l'ensemble des articles commandés a été
                                        réinjecté dans le stock disponible de la cuisine.
                                    </p>

                                </div>

                            </div>

                        </div>

                    `;

                }


                /* =====================================================
                   SUPPRIMER LE BOUTON ANNULATION
                ===================================================== */

                if (annulationContainer) {

                    annulationContainer.innerHTML = '';

                }


                /* =====================================================
                   AJOUTER L'HISTORIQUE ANNULATION
                ===================================================== */

                if (historiqueCommande) {

                    const ancienneLigne =
                        document.getElementById(
                            'historiqueStatutActuel'
                        );

                    if (ancienneLigne) {
                        ancienneLigne.remove();
                    }


                    if (
                        !document.getElementById(
                            'historiqueAnnulation'
                        )
                    ) {

                        historiqueCommande.insertAdjacentHTML(
                            'beforeend',
                            `

                            <div
                                id="historiqueAnnulation"
                                class="flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4"
                            >

                                <span
                                    class="inline-flex shrink-0 items-center gap-2 rounded-full border border-red-400 bg-red-50 px-3 py-1 text-[10px] font-semibold text-red-600"
                                >

                                    <i class="fa-regular fa-circle-xmark"></i>

                                    Annulée

                                </span>


                                <p
                                    class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]"
                                >
                                    Annulée par le client - Stock restitué
                                </p>


                                <span
                                    class="shrink-0 font-['DM_Sans'] text-[14px] font-medium text-[#333333]"
                                >
                                    ${new Date().toLocaleTimeString(
                                        'fr-FR',
                                        {
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        }
                                    )}

                                </span>

                            </div>

                            `
                        );

                    }

                }

            }


            /* =====================================================
               MISE À JOUR DE L'HISTORIQUE
            ===================================================== */

            function mettreAJourHistorique(statut) {

                if (!historiqueCommande) {
                    return;
                }

                if (statut === 'EN_ATTENTE') {
                    return;
                }


                const info =
                    statuts[statut];

                if (!info) {
                    return;
                }


                let ligne =
                    document.getElementById(
                        'historiqueStatutActuel'
                    );


                /* =================================================
                   CRÉATION SI ELLE N'EXISTE PAS
                ================================================== */

                if (!ligne) {

                    historiqueCommande.insertAdjacentHTML(
                        'beforeend',
                        `

                        <div
                            id="historiqueStatutActuel"
                            class="flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4"
                        >

                            <span
                                id="historiqueBadge"
                                class="inline-flex shrink-0 items-center gap-2 rounded-full border px-3 py-1 text-[10px] font-semibold"
                            >

                                <i id="historiqueBadgeIcon"></i>

                                <span id="historiqueBadgeLabel"></span>

                            </span>


                            <p
                                id="historiqueMessage"
                                class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]"
                            ></p>


                            <span
                                id="historiqueHeure"
                                class="shrink-0 font-['DM_Sans'] text-[14px] font-medium text-[#333333]"
                            ></span>

                        </div>

                        `
                    );

                    ligne =
                        document.getElementById(
                            'historiqueStatutActuel'
                        );

                }


                const badge =
                    document.getElementById(
                        'historiqueBadge'
                    );

                const badgeIcon =
                    document.getElementById(
                        'historiqueBadgeIcon'
                    );

                const badgeLabel =
                    document.getElementById(
                        'historiqueBadgeLabel'
                    );

                const message =
                    document.getElementById(
                        'historiqueMessage'
                    );

                const heure =
                    document.getElementById(
                        'historiqueHeure'
                    );


                if (badge) {

                    badge.className =
                        'inline-flex shrink-0 items-center gap-2 rounded-full border px-3 py-1 text-[10px] font-semibold ' +
                        info.badge;

                }


                if (badgeIcon) {

                    badgeIcon.className =
                        info.icon;

                }


                if (badgeLabel) {

                    badgeLabel.textContent =
                        info.label;

                }


                if (message) {

                    message.textContent =
                        info.message;

                }


                if (heure) {

                    heure.textContent =
                        new Date().toLocaleTimeString(
                            'fr-FR',
                            {
                                hour: '2-digit',
                                minute: '2-digit'
                            }
                        );

                }

            }


            /* =====================================================
               MISE À JOUR GÉNÉRALE
            ===================================================== */

            function mettreAJourInterface(statut) {

                if (!statuts[statut]) {
                    return;
                }


                /* Badge principal */

                mettreAJourBadge(statut);


                /* Commande annulée */

                if (statut === 'ANNULEE') {

                    afficherCommandeAnnulee();

                    return;

                }


                /* Étapes */

                mettreAJourEtapes(statut);


                /* Historique */

                mettreAJourHistorique(statut);


                /* =================================================
                   BOUTON ANNULATION
                ================================================== */

                if (annulationContainer) {

                    if (
                        statut === 'EN_ATTENTE' ||
                        statut === 'EN_PREPARATION'
                    ) {

                        if (
                            !document.getElementById(
                                'openCancelModal'
                            )
                        ) {

                            annulationContainer.innerHTML = `

                                <button
                                    type="button"
                                    id="openCancelModal"
                                    class="inline-flex items-center justify-center gap-3 rounded-[9px] border border-red-500 px-5 py-2.5 font-['DM_Sans'] text-[13px] font-medium text-red-500 transition hover:bg-red-50"
                                >

                                    <i class="fa-regular fa-circle-xmark"></i>

                                    Annuler cette commande

                                </button>

                            `;

                            initialiserModal();

                        }

                    } else {

                        annulationContainer.innerHTML = '';

                    }

                }

            }


            /* =====================================================
               RÉCUPÉRATION DE L'ÉTAT
            ===================================================== */

            async function recupererEtat() {

                try {

                    const response =
                        await fetch(
                            urlEtat,
                            {
                                method: 'GET',
                                headers: {
                                    'Accept':
                                        'application/json'
                                },
                                cache: 'no-store'
                            }
                        );


                    if (!response.ok) {

                        console.error(
                            'Erreur lors de la récupération du statut.'
                        );

                        return;

                    }


                    const data =
                        await response.json();


                    if (
                        !data.success ||
                        !data.commande ||
                        !data.commande.statut
                    ) {

                        return;

                    }


                    const nouveauStatut =
                        data.commande.statut;


                    /* =================================================
                       LE STATUT A CHANGÉ
                    ================================================== */

                    if (
                        nouveauStatut !== statutPrecedent
                    ) {

                        mettreAJourInterface(
                            nouveauStatut
                        );

                        statutPrecedent =
                            nouveauStatut;

                    }

                } catch (error) {

                    console.error(
                        'Impossible de récupérer le statut de la commande.',
                        error
                    );

                }

            }


            /* =====================================================
               DÉMARRER LE SUIVI
            ===================================================== */

            let intervalleSuivi = null;


            function demarrerSuivi() {

                /* Premier appel immédiatement */

                recupererEtat();


                /* Puis toutes les 3 secondes */

                intervalleSuivi =
                    setInterval(
                        recupererEtat,
                        intervalle
                    );

            }


            /* =====================================================
               ARRÊTER LE SUIVI
               Quand la commande est terminée
            ===================================================== */

            function arreterSuiviSiNecessaire() {

                if (
                    statutPrecedent === 'RETIREE' ||
                    statutPrecedent === 'ANNULEE'
                ) {

                    if (intervalleSuivi) {

                        clearInterval(
                            intervalleSuivi
                        );

                        intervalleSuivi = null;

                    }

                }

            }


            /* =====================================================
               MODAL ANNULATION
            ===================================================== */

            function initialiserModal() {

                const modal =
                    document.getElementById(
                        'cancelModal'
                    );

                const openButton =
                    document.getElementById(
                        'openCancelModal'
                    );

                const closeButton =
                    document.getElementById(
                        'closeCancelModal'
                    );

                const cancelButton =
                    document.getElementById(
                        'cancelModalButton'
                    );


                if (!modal || !openButton) {
                    return;
                }


                /* Éviter d'attacher plusieurs événements */

                if (
                    openButton.dataset.modalInitialized ===
                    'true'
                ) {
                    return;
                }


                openButton.dataset.modalInitialized =
                    'true';


                /* =================================================
                   OUVRIR
                ================================================== */

                openButton.addEventListener(
                    'click',
                    function () {

                        modal.classList.remove(
                            'hidden'
                        );

                        modal.classList.add(
                            'flex'
                        );

                        modal.setAttribute(
                            'aria-hidden',
                            'false'
                        );

                        document.body.classList.add(
                            'overflow-hidden'
                        );

                        closeButton?.focus();

                    }
                );


                /* =================================================
                   FERMER
                ================================================== */

                function closeModal() {

                    modal.classList.add(
                        'hidden'
                    );

                    modal.classList.remove(
                        'flex'
                    );

                    modal.setAttribute(
                        'aria-hidden',
                        'true'
                    );

                    document.body.classList.remove(
                        'overflow-hidden'
                    );

                    openButton.focus();

                }


                closeButton?.addEventListener(
                    'click',
                    closeModal
                );


                cancelButton?.addEventListener(
                    'click',
                    closeModal
                );


                modal.addEventListener(
                    'click',
                    function (event) {

                        if (
                            event.target === modal
                        ) {

                            closeModal();

                        }

                    }
                );


                document.addEventListener(
                    'keydown',
                    function (event) {

                        if (
                            event.key === 'Escape' &&
                            !modal.classList.contains(
                                'hidden'
                            )
                        ) {

                            closeModal();

                        }

                    }
                );

            }


            /* =====================================================
               INITIALISATION
            ===================================================== */

            initialiserModal();

            demarrerSuivi();


            /* =====================================================
               VÉRIFIER SI LE SUIVI DOIT ÊTRE ARRÊTÉ
            ===================================================== */

            setInterval(
                arreterSuiviSiNecessaire,
                1000
            );

        });

    </script>

</main>