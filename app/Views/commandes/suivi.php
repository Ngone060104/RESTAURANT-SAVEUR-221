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

/* Ordre officiel des étapes */

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

    'EN_ATTENTE' => 'En attente',

    'EN_PREPARATION' => 'En préparation',

    'PRETE' => 'Prête au comptoir',

    'RETIREE' => 'Retirée',

    'ANNULEE' => 'Annulée',

    default => $statutActuel,
};

?>

<main class="min-h-screen bg-[#faf9f7]">

    <!-- =====================================================
         RETOUR
    ====================================================== -->

    <section class="mx-auto max-w-[1150px] px-6 pt-7">

        <div class="flex items-center justify-between gap-6">

            <a
                href="/mes-commandes"
                class="inline-flex items-center gap-3 font-['DM_Sans'] text-[14px] text-[#333333] transition hover:text-[#fe9a00]">

                <span class="text-[23px] leading-none">
                    ←
                </span>

                <span>
                    Retour à la liste de mes commandes
                </span>

            </a>

            <!-- STATUT -->

            <span
                class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-[11px] font-semibold <?= $badgeClasses ?>">

                <i class="fa-regular fa-clock"></i>

                <?= htmlspecialchars(
                    $statutLabel,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </span>

        </div>

    </section>


    <!-- =====================================================
         CARTE PRINCIPALE
    ====================================================== -->

    <section class="mx-auto max-w-[1150px] px-6 pb-10 pt-6">

        <div
            class="overflow-hidden rounded-[16px] border border-[#dddddd] bg-white shadow-[0_1px_3px_rgba(0,0,0,0.03)]">

            <!-- =================================================
                 EN-TÊTE COMMANDE
            ================================================== -->

            <div class="px-7 py-6 md:px-10 md:py-7">

                <div
                    class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">

                    <div>

                        <p
                            class="mb-3 font-['DM_Sans'] text-[12px] font-bold uppercase text-[#fe7900]">
                            SUIVI EN DIRECT
                        </p>

                        <h1
                            class="font-['Inter'] text-[30px] font-black leading-tight text-[#252525] md:text-[38px]">
                            Commande
                            <?= htmlspecialchars(
                                $numeroCommande,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h1>

                        <p
                            class="mt-3 font-['DM_Sans'] text-[12px] text-[#333333]">

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
                            class="font-['DM_Sans'] text-[12px] font-bold uppercase text-[#333333]">
                            MONTANT
                        </p>

                        <p
                            class="mt-2 font-['Inter'] text-[28px] font-black text-[#c96500] md:text-[34px]">
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
                 ÉTAPES
            ================================================== -->

            <div class="px-6 py-6 md:px-10 md:py-7">

                <div
                    class="grid grid-cols-1 gap-7 md:grid-cols-[1fr_auto_1fr_auto_1fr_auto_1fr] md:items-start">

                    <?php foreach ($ordreEtapes as $index => $code): ?>

                        <?php

                        $etape = $etapes[$code];

                        $estAtteinte =
                            $indexActuel >= $index;

                        $estActuelle =
                            $indexActuel === $index;

                        ?>

                        <!-- ÉTAPE -->

                        <div class="text-center">

                            <div class="flex justify-center">

                                <div
                                    class="
                                        flex h-[64px] w-[64px] items-center justify-center rounded-[21px]
                                        <?= $estAtteinte
                                            ? 'bg-[#ff9800] text-black shadow-[0_0_0_4px_#e3e3e3]'
                                            : 'bg-[#f5f5f5] text-[#bdbdbd]' ?>
                                    ">

                                    <i
                                        class="<?= $etape['icone'] ?> text-[25px]"></i>

                                </div>

                            </div>


                            <h3
                                class="
                                    mt-3 font-['DM_Sans'] text-[13px] font-bold
                                    <?= $estAtteinte
                                        ? 'text-[#c96500]'
                                        : 'text-[#333333]' ?>
                                ">

                                <?= htmlspecialchars(
                                    $etape['label'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </h3>


                            <p
                                class="mx-auto mt-1 max-w-[160px] font-['DM_Sans'] text-[10px] leading-4 text-[#888888]">

                                <?= htmlspecialchars(
                                    $etape['description'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </p>

                        </div>


                        <!-- CONNECTEUR -->

                        <?php if ($index < count($ordreEtapes) - 1): ?>

                            <div
                                class="hidden h-[3px] w-[55px] self-center bg-[#dedede] md:block"></div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

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
                    class="font-['Inter'] text-[17px] font-medium uppercase text-[#222222]">
                    HISTORIQUE DES ÉTAPES DE LA COMMANDE
                </h2>


                <div
                    class="mt-5 flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4">

                    <!-- BADGE -->

                    <span
                        class="inline-flex shrink-0 items-center gap-2 rounded-full border border-orange-300 bg-orange-50 px-3 py-1 text-[10px] font-semibold text-orange-600">

                        <i class="fa-regular fa-clock"></i>

                        <?= htmlspecialchars(
                            $statutLabel,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </span>


                    <!-- TEXTE -->

                    <p
                        class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]">

                        <?php if ($statutActuel === 'EN_ATTENTE'): ?>

                            Commande enregistrée avec succès via le site web Saveur 221

                        <?php elseif ($statutActuel === 'EN_PREPARATION'): ?>

                            Votre commande est actuellement en préparation dans notre cuisine

                        <?php elseif ($statutActuel === 'PRETE'): ?>

                            Votre commande est prête au comptoir et attend votre retrait

                        <?php elseif ($statutActuel === 'RETIREE'): ?>

                            Commande remise au client. Bon appétit !

                        <?php elseif ($statutActuel === 'ANNULEE'): ?>

                            Cette commande a été annulée.

                        <?php endif; ?>

                    </p>


                    <!-- HEURE -->

                    <span
                        class="shrink-0 font-['DM_Sans'] text-[14px] font-medium text-[#333333]">

                        <?= htmlspecialchars(
                            $heureFormatee,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </span>

                </div>

            </div>


            <!-- =================================================
                 ACTIONS
            ================================================== -->

            <div
                class="border-t border-[#dedede] px-7 py-5 md:px-10">

                <div
                    class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <!-- ANNULER -->

                    <?php if (
                        in_array(
                            $statutActuel,
                            ['EN_ATTENTE', 'EN_PREPARATION'],
                            true
                        )
                    ): ?>

                        <button
                            type="button"
                            id="openCancelModal"
                            class="inline-flex items-center justify-center gap-3 rounded-[9px] border border-red-500 px-5 py-2.5 font-['DM_Sans'] text-[13px] font-medium text-red-500 transition hover:bg-red-50">
                            <i class="fa-regular fa-circle-xmark"></i>
                            Annuler cette commande
                        </button>

                    <?php else: ?>

                        <div></div>

                    <?php endif; ?>


                    <!-- DÉTAIL COMPLET -->

                    <a
                        href="/commande/detail/<?= $idCommande ?>"
                        class="inline-flex items-center justify-center gap-3 rounded-[9px] border border-[#dddddd] px-5 py-2.5 font-['DM_Sans'] text-[12px] font-bold text-[#333333] transition hover:bg-[#f8f8f8]">

                        <i class="fa-regular fa-file-lines"></i>

                        Voir la facture / détail complet

                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- =========================================================
     MODAL ANNULATION COMMANDE
========================================================= -->
<div
    id="cancelModal"
    class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 px-4"
    aria-hidden="true"
>
    <div
        id="cancelModalContent"
        class="relative w-full max-w-[560px] overflow-hidden rounded-[24px] bg-white shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="cancelModalTitle"
    >

        <!-- HEADER -->
        <div
            class="flex items-center justify-between border-b border-[#eeeeee] px-6 py-5 md:px-7"
        >
            <h2
                id="cancelModalTitle"
                class="font-['Inter'] text-[23px] font-bold text-[#111111]"
            >
                Annuler la commande ?
            </h2>

            <button
                type="button"
                id="closeCancelModal"
                class="flex h-9 w-9 items-center justify-center text-[34px] leading-none text-[#111111] transition hover:text-[#fe7900]"
                aria-label="Fermer"
            >
                ×
            </button>
        </div>

        <!-- CONTENU -->
        <div class="px-6 py-6 text-center md:px-8 md:py-7">

            <!-- ICÔNE AVERTISSEMENT -->
            <div class="flex justify-center">
                <div
                    class="flex h-[68px] w-[68px] items-center justify-center rounded-[20px] bg-red-100"
                >
                    <i
                        class="fa-solid fa-triangle-exclamation text-[38px] text-red-600"
                    ></i>
                </div>
            </div>

            <!-- MESSAGE -->
            <p
                class="mx-auto mt-6 max-w-[470px] font-['DM_Sans'] text-[16px] leading-6 text-[#777777]"
            >
                Êtes-vous sûr de vouloir annuler cette commande ?
                Les articles seront automatiquement restitués au stock
                du restaurant (Règle métier #8).
            </p>

            <!-- BOUTONS -->
            <div
                class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-2"
            >

                <!-- ANNULER -->
                <button
                    type="button"
                    id="cancelModalButton"
                    class="inline-flex h-[48px] items-center justify-center rounded-[11px] border border-[#dddddd] bg-white px-5 font-['DM_Sans'] text-[15px] font-bold text-[#333333] transition hover:bg-[#f7f7f7]"
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
                        class="inline-flex h-[48px] w-full items-center justify-center rounded-[11px] bg-[#ff4545] px-5 font-['DM_Sans'] text-[15px] font-bold text-white transition hover:bg-[#e93636]"
                    >
                        Oui, annuler
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     JAVASCRIPT MODAL
========================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('cancelModal');
    const openButton = document.getElementById('openCancelModal');
    const closeButton = document.getElementById('closeCancelModal');
    const cancelButton = document.getElementById('cancelModalButton');

    if (!modal || !openButton) {
        return;
    }

    /* OUVRIR */
    openButton.addEventListener('click', function () {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('overflow-hidden');

        closeButton?.focus();
    });

    /* FERMER */
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('overflow-hidden');

        openButton.focus();
    }

    /* Bouton X */
    closeButton?.addEventListener('click', closeModal);

    /* Bouton Annuler */
    cancelButton?.addEventListener('click', closeModal);

    /* Clic sur le fond */
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    /* Touche Échap */
    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            !modal.classList.contains('hidden')
        ) {
            closeModal();
        }
    });

});
</script>

</main>