<?php
/**
 * Vue : Suivi d'une commande
 *
 * Variables reçues :
 * - $commande
 * - $lignes
 * - $paiements
 * - $statutPaiement
 *
 * Suivi en temps réel :
 * - le JavaScript interroge /commande/suivi/{id}/etat
 * - le statut affiché vient toujours de la base de données
 * - aucune transition n'est simulée côté navigateur
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

$idCommande = (int) $commande->getId();

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
        (string) $dateCommande,
        ENT_QUOTES,
        'UTF-8'
    );
    $heureFormatee = '';
}

/* =========================================================
   STATUTS OFFICIELS
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

$ordreEtapes = [
    'EN_ATTENTE',
    'EN_PREPARATION',
    'PRETE',
    'RETIREE',
];

$indexActuel = array_search($statutActuel, $ordreEtapes, true);
$indexActuel = $indexActuel === false ? -1 : $indexActuel;

$badgeClasses = match ($statutActuel) {
    'EN_ATTENTE' => 'bg-orange-50 text-orange-600 border-orange-300',
    'EN_PREPARATION' => 'bg-blue-50 text-blue-600 border-blue-300',
    'PRETE' => 'bg-emerald-50 text-emerald-600 border-emerald-300',
    'RETIREE' => 'bg-green-50 text-green-600 border-green-300',
    'ANNULEE' => 'bg-red-50 text-red-600 border-red-300',
    default => 'bg-stone-50 text-stone-600 border-stone-300',
};

$statutLabel = match ($statutActuel) {
    'EN_ATTENTE' => 'En attente',
    'EN_PREPARATION' => 'En préparation',
    'PRETE' => 'Prête au comptoir',
    'RETIREE' => 'Retirée',
    'ANNULEE' => 'Annulée',
    default => $statutActuel,
};

$statutIcone = match ($statutActuel) {
    'EN_PREPARATION' => 'fa-solid fa-utensils',
    'PRETE' => 'fa-solid fa-store',
    'RETIREE' => 'fa-regular fa-circle-check',
    'ANNULEE' => 'fa-regular fa-circle-xmark',
    default => 'fa-regular fa-clock',
};

/* =========================================================
   MESSAGES DE STATUT
========================================================= */

$messageStatut = [
    'EN_ATTENTE' => 'Votre commande est enregistrée et attend sa prise en charge.',
    'EN_PREPARATION' => 'Votre commande est actuellement en préparation dans notre cuisine.',
    'PRETE' => 'Votre commande est prête au comptoir et attend votre retrait.',
    'RETIREE' => 'Commande remise au client. Bon appétit !',
    'ANNULEE' => 'Cette commande a été annulée.',
];

$messageActuel = $messageStatut[$statutActuel] ?? 'Statut de commande mis à jour.';

?>

<main class="min-h-screen bg-[#faf9f7]" id="suiviCommande" data-commande-id="<?= $idCommande ?>">

    <!-- =====================================================
         RETOUR + STATUT
    ====================================================== -->

    <section class="mx-auto max-w-[1150px] px-6 pt-7">
        <div class="flex items-center justify-between gap-6">

            <a
                href="/mes-commandes"
                class="inline-flex items-center gap-3 font-['DM_Sans'] text-[14px] text-[#333333] transition hover:text-[#fe9a00]"
            >
                <i class="fa-solid fa-arrow-left text-[14px]"></i>
                <span>Retour à la liste de mes commandes</span>
            </a>

            <span
                id="statutBadge"
                class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-[11px] font-semibold <?= $badgeClasses ?>"
            >
                <i id="statutBadgeIcon" class="<?= $statutIcone ?>"></i>
                <span id="statutBadgeLabel"><?= htmlspecialchars($statutLabel, ENT_QUOTES, 'UTF-8') ?></span>
            </span>

        </div>
    </section>

    <!-- =====================================================
         CARTE PRINCIPALE
    ====================================================== -->

    <section class="mx-auto max-w-[1150px] px-6 pb-10 pt-6">
        <div class="overflow-hidden rounded-[16px] border border-[#dddddd] bg-white shadow-[0_1px_3px_rgba(0,0,0,0.03)]">

            <!-- EN-TÊTE COMMANDE -->
            <div class="px-7 py-6 md:px-10 md:py-7">
                <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">

                    <div>
                        <p class="mb-3 font-['DM_Sans'] text-[12px] font-bold uppercase text-[#fe7900]">
                            SUIVI EN DIRECT
                        </p>

                        <h1 class="font-['Inter'] text-[30px] font-black leading-tight text-[#252525] md:text-[38px]">
                            Commande
                            <?= htmlspecialchars($numeroCommande, ENT_QUOTES, 'UTF-8') ?>
                        </h1>

                        <p class="mt-3 font-['DM_Sans'] text-[12px] text-[#333333]">
                            Passée le
                            <?= htmlspecialchars($dateFormatee, ENT_QUOTES, 'UTF-8') ?>

                            <?php if ($heureFormatee !== ''): ?>
                                à <?= htmlspecialchars($heureFormatee, ENT_QUOTES, 'UTF-8') ?>
                            <?php endif; ?>

                            • Mode : À emporter
                        </p>
                    </div>

                    <div class="text-left md:text-right">
                        <p class="font-['DM_Sans'] text-[12px] font-bold uppercase text-[#333333]">
                            MONTANT
                        </p>

                        <p class="mt-2 font-['Inter'] text-[28px] font-black text-[#c96500] md:text-[34px]">
                            <?= $formatPrix($total) ?>
                        </p>
                    </div>

                </div>
            </div>

            <div class="h-px bg-[#dedede]"></div>

            <!-- =================================================
                 ZONE STATUT
            ================================================== -->

            <div id="zoneStatutCommande">

                <?php if ($statutActuel === 'ANNULEE'): ?>

                    <div class="px-7 py-7 md:px-10 md:py-8">
                        <div class="flex items-center gap-5 rounded-[16px] border border-red-500 bg-red-100 px-5 py-5">

                            <div class="flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-full border-[3px] border-red-600">
                                <i class="fa-solid fa-xmark text-[21px] text-red-600"></i>
                            </div>

                            <div>
                                <h2 class="font-['Inter'] text-[16px] font-bold text-red-700">
                                    Commande annulée
                                </h2>

                                <p class="mt-2 font-['DM_Sans'] text-[13px] leading-6 text-red-600">
                                    Cette commande a été annulée.
                                    Conformément à la <strong>Règle métier #8</strong>,
                                    l'ensemble des articles commandés a été réinjecté
                                    dans le stock disponible de la cuisine.
                                </p>
                            </div>

                        </div>
                    </div>

                <?php else: ?>

                    <!-- ÉTAPES DE LA COMMANDE -->
                    <div class="px-6 py-6 md:px-10 md:py-7">
                        <div
                            id="etapesCommande"
                            class="grid grid-cols-1 gap-7 md:grid-cols-[1fr_auto_1fr_auto_1fr_auto_1fr] md:items-start"
                        >

                            <?php foreach ($ordreEtapes as $index => $code): ?>
                                <?php
                                $etape = $etapes[$code];
                                $estAtteinte = $indexActuel >= $index;
                                $estActuelle = $indexActuel === $index;
                                ?>

                                <div class="text-center" data-step="<?= $code ?>">
                                    <div class="flex justify-center">
                                        <div
                                            data-step-icon
                                            class="flex h-[64px] w-[64px] items-center justify-center rounded-[21px] <?= $estAtteinte ? 'bg-[#ff9800] text-black shadow-[0_0_0_4px_#e3e3e3]' : 'bg-[#f5f5f5] text-[#bdbdbd]' ?>"
                                        >
                                            <i class="<?= $etape['icone'] ?> text-[25px]"></i>
                                        </div>
                                    </div>

                                    <h3
                                        data-step-label
                                        class="mt-3 font-['DM_Sans'] text-[13px] font-bold <?= $estAtteinte ? 'text-[#c96500]' : 'text-[#333333]' ?>"
                                    >
                                        <?= htmlspecialchars($etape['label'], ENT_QUOTES, 'UTF-8') ?>
                                    </h3>

                                    <p class="mx-auto mt-1 max-w-[160px] font-['DM_Sans'] text-[10px] leading-4 text-[#888888]">
                                        <?= htmlspecialchars($etape['description'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                </div>

                                <?php if ($index < count($ordreEtapes) - 1): ?>
                                    <div
                                        data-step-connector="<?= $index ?>"
                                        class="hidden h-[3px] w-[55px] self-center md:block <?= $indexActuel > $index ? 'bg-[#ff9800]' : 'bg-[#dedede]' ?>"
                                    ></div>
                                <?php endif; ?>

                            <?php endforeach; ?>

                        </div>
                    </div>

                <?php endif; ?>

            </div>

            <div class="mx-7 h-px bg-[#dedede] md:mx-10"></div>

            <!-- =================================================
                 HISTORIQUE / DERNIER ÉTAT CONNU
            ================================================== -->

            <div class="px-7 py-6 md:px-10">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-['Inter'] text-[17px] font-medium uppercase text-[#222222]">
                        HISTORIQUE DES ÉTAPES DE LA COMMANDE
                    </h2>

                    <span
                        id="suiviConnexion"
                        class="inline-flex items-center gap-2 font-['DM_Sans'] text-[10px] text-[#888888]"
                    >
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Suivi actif
                    </span>
                </div>

                <div id="historiqueCommande" class="mt-5 space-y-4">

                    <!-- ÉTAT INITIAL : date réelle de création de la commande -->
                    <div
                        class="flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4"
                        data-history-status="EN_ATTENTE"
                    >
                        <span class="inline-flex shrink-0 items-center gap-2 rounded-full border border-orange-300 bg-orange-50 px-3 py-1 text-[10px] font-semibold text-orange-600">
                            <i class="fa-regular fa-clock"></i>
                            En attente
                        </span>

                        <p class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]">
                            Commande enregistrée avec succès via le site web Saveur 221
                        </p>

                        <span class="shrink-0 font-['DM_Sans'] text-[14px] font-medium text-[#333333]">
                            <?= htmlspecialchars($heureFormatee, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>

                    <!-- Le statut courant est mis à jour par JavaScript à partir de la BDD. -->
                    <div
                        id="historiqueStatutCourant"
                        class="<?= $statutActuel === 'EN_ATTENTE' ? 'hidden' : '' ?> flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4"
                    >
                        <span
                            id="historiqueStatutBadge"
                            class="inline-flex shrink-0 items-center gap-2 rounded-full border px-3 py-1 text-[10px] font-semibold <?= $badgeClasses ?>"
                        >
                            <i id="historiqueStatutIcon" class="<?= $statutIcone ?>"></i>
                            <span id="historiqueStatutLabel">
                                <?= htmlspecialchars($statutLabel, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </span>

                        <p
                            id="historiqueStatutMessage"
                            class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]"
                        >
                            <?= htmlspecialchars($messageActuel, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <span
                            id="historiqueStatutHeure"
                            class="shrink-0 font-['DM_Sans'] text-[11px] font-medium text-[#777777]"
                        >
                            Statut actuel
                        </span>
                    </div>

                    <?php if ($statutActuel === 'ANNULEE'): ?>
                        <div
                            id="historiqueAnnulation"
                            class="flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4"
                        >
                            <span class="inline-flex shrink-0 items-center gap-2 rounded-full border border-red-400 bg-red-50 px-3 py-1 text-[10px] font-semibold text-red-600">
                                <i class="fa-regular fa-circle-xmark"></i>
                                Annulée
                            </span>

                            <p class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]">
                                Annulée par le client - Stock restitué
                            </p>

                            <span class="shrink-0 font-['DM_Sans'] text-[11px] font-medium text-[#777777]">
                                Statut actuel
                            </span>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- =================================================
                 ACTIONS
            ================================================== -->

            <div class="border-t border-[#dedede] px-7 py-5 md:px-10">
                <div id="actionsContainer" class="flex flex-wrap items-center justify-end gap-4">

                    <?php if (in_array($statutActuel, ['EN_ATTENTE', 'EN_PREPARATION'], true)): ?>
                        <button
                            type="button"
                            id="openCancelModal"
                            class="inline-flex items-center justify-center gap-3 rounded-[9px] border border-red-500 px-5 py-2.5 font-['DM_Sans'] text-[13px] font-medium text-red-500 transition hover:bg-red-50"
                        >
                            <i class="fa-regular fa-circle-xmark"></i>
                            Annuler cette commande
                        </button>
                    <?php endif; ?>

                    <?php if ($statutActuel === 'RETIREE'): ?>
                        <button
                            type="button"
                            id="openAvisModal"
                            class="inline-flex items-center justify-center gap-3 rounded-[9px] bg-[#ff9800] px-5 py-2.5 font-['DM_Sans'] text-[13px] font-bold text-white transition hover:bg-[#e88900]"
                        >
                            <i class="fa-regular fa-star"></i>
                            Donner mon avis pour ce repas
                        </button>
                    <?php endif; ?>

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
         MODAL ANNULATION
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
            <div class="flex items-center justify-between border-b border-[#eeeeee] px-5 py-4">
                <h2 id="cancelModalTitle" class="font-['Inter'] text-[19px] font-bold text-[#111111]">
                    Annuler la commande ?
                </h2>

                <button
                    type="button"
                    id="closeCancelModal"
                    class="flex h-8 w-8 items-center justify-center text-[20px] leading-none text-[#111111] transition hover:text-[#fe7900]"
                    aria-label="Fermer"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="px-5 py-5 text-center">
                <div class="flex justify-center">
                    <div class="flex h-[54px] w-[54px] items-center justify-center rounded-[16px] bg-red-100">
                        <i class="fa-solid fa-triangle-exclamation text-[28px] text-red-600"></i>
                    </div>
                </div>

                <p class="mx-auto mt-4 max-w-[390px] font-['DM_Sans'] text-[13px] leading-5 text-[#777777]">
                    Êtes-vous sûr de vouloir annuler cette commande ?
                    Les articles seront automatiquement restitués au stock
                    du restaurant (Règle métier #8).
                </p>

                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button
                        type="button"
                        id="cancelModalButton"
                        class="inline-flex h-[42px] items-center justify-center rounded-[10px] border border-[#dddddd] bg-white px-5 font-['DM_Sans'] text-[13px] font-bold text-[#333333] transition hover:bg-[#f7f7f7]"
                    >
                        Annuler
                    </button>

                    <form method="POST" action="/commandes/<?= $idCommande ?>/annuler" class="w-full">
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
         MODAL AVIS

         IMPORTANT : le backend AvisController existe déjà et
         attend POST /avis avec commande_id, note et commentaire.
    ========================================================== -->

    <div
        id="avisModal"
        class="fixed inset-0 z-[9999] hidden items-start justify-center bg-black/50 px-4 pt-[8vh]"
        aria-hidden="true"
    >
        <div
            class="relative w-full max-w-[520px] overflow-hidden rounded-[18px] bg-white shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="avisModalTitle"
        >
            <div class="flex items-center justify-between border-b border-[#eeeeee] px-6 py-4">
                <div>
                    <p class="font-['DM_Sans'] text-[10px] font-bold uppercase text-[#fe7900]">
                        VOTRE EXPÉRIENCE
                    </p>
                    <h2
                        id="avisModalTitle"
                        class="mt-1 font-['Inter'] text-[19px] font-bold text-[#111111]"
                    >
                        Donnez votre avis
                    </h2>
                </div>

                <button
                    type="button"
                    id="closeAvisModal"
                    class="flex h-8 w-8 items-center justify-center text-[20px] leading-none text-[#111111] transition hover:text-[#fe7900]"
                    aria-label="Fermer"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="avisForm" method="POST" action="/avis" class="px-6 py-6">
                <input type="hidden" name="commande_id" value="<?= $idCommande ?>">

                <div>
                    <label class="font-['DM_Sans'] text-[13px] font-bold text-[#333333]">
                        Note
                    </label>

                    <div class="mt-3 flex items-center gap-2" id="avisStars">
                        <?php for ($note = 1; $note <= 5; $note++): ?>
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="note"
                                    value="<?= $note ?>"
                                    class="sr-only"
                                    <?= $note === 5 ? '' : '' ?>
                                >
                                <i
                                    data-star="<?= $note ?>"
                                    class="fa-regular fa-star text-[27px] text-[#cfcfcf] transition"
                                ></i>
                            </label>
                        <?php endfor; ?>
                    </div>

                    <p id="avisNoteErreur" class="mt-2 hidden font-['DM_Sans'] text-[12px] text-red-600">
                        Veuillez sélectionner une note entre 1 et 5.
                    </p>
                </div>

                <div class="mt-5">
                    <label
                        for="avisCommentaire"
                        class="font-['DM_Sans'] text-[13px] font-bold text-[#333333]"
                    >
                        Votre commentaire
                    </label>

                    <textarea
                        id="avisCommentaire"
                        name="commentaire"
                        rows="5"
                        required
                        class="mt-2 w-full resize-none rounded-[10px] border border-[#dddddd] px-4 py-3 font-['DM_Sans'] text-[13px] text-[#333333] outline-none transition placeholder:text-[#aaaaaa] focus:border-[#ff9800] focus:ring-1 focus:ring-[#ff9800]"
                        placeholder="Dites-nous ce que vous avez pensé de votre repas..."
                    ></textarea>

                    <p id="avisCommentaireErreur" class="mt-2 hidden font-['DM_Sans'] text-[12px] text-red-600">
                        Veuillez saisir un commentaire.
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button
                        type="button"
                        id="cancelAvisModal"
                        class="inline-flex h-[42px] items-center justify-center rounded-[10px] border border-[#dddddd] bg-white px-5 font-['DM_Sans'] text-[13px] font-bold text-[#333333] transition hover:bg-[#f7f7f7]"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="inline-flex h-[42px] items-center justify-center gap-2 rounded-[10px] bg-[#ff9800] px-5 font-['DM_Sans'] text-[13px] font-bold text-white transition hover:bg-[#e88900]"
                    >
                        <i class="fa-regular fa-paper-plane"></i>
                        Envoyer mon avis
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const page = document.getElementById('suiviCommande');

        if (!page) {
            return;
        }

        const commandeId = Number(page.dataset.commandeId || 0);

        if (!commandeId) {
            return;
        }

        /* =====================================================
           DONNÉES STATUTS
        ===================================================== */

        const statuts = {
            EN_ATTENTE: {
                label: 'En attente',
                message: 'Commande enregistrée avec succès via le site web Saveur 221',
                icon: 'fa-regular fa-clock',
                badge: 'bg-orange-50 text-orange-600 border-orange-300',
                stepIcon: 'fa-regular fa-clock'
            },
            EN_PREPARATION: {
                label: 'En préparation',
                message: 'Votre commande est actuellement en préparation dans notre cuisine',
                icon: 'fa-solid fa-utensils',
                badge: 'bg-blue-50 text-blue-600 border-blue-300',
                stepIcon: 'fa-solid fa-utensils'
            },
            PRETE: {
                label: 'Prête au comptoir',
                message: 'Votre commande est prête au comptoir et attend votre retrait',
                icon: 'fa-solid fa-store',
                badge: 'bg-emerald-50 text-emerald-600 border-emerald-300',
                stepIcon: 'fa-solid fa-store'
            },
            RETIREE: {
                label: 'Retirée',
                message: 'Commande remise au client. Bon appétit !',
                icon: 'fa-regular fa-circle-check',
                badge: 'bg-green-50 text-green-600 border-green-300',
                stepIcon: 'fa-regular fa-circle-check'
            },
            ANNULEE: {
                label: 'Annulée',
                message: 'Cette commande a été annulée.',
                icon: 'fa-regular fa-circle-xmark',
                badge: 'bg-red-50 text-red-600 border-red-300',
                stepIcon: 'fa-solid fa-xmark'
            }
        };

        const ordreEtapes = [
            'EN_ATTENTE',
            'EN_PREPARATION',
            'PRETE',
            'RETIREE'
        ];

        let statutPrecedent = <?= json_encode($statutActuel, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        let intervalleSuivi = null;
        let requeteEnCours = false;

        /* =====================================================
           OUTILS
        ===================================================== */

        function estStatutTerminal(statut) {
            return statut === 'RETIREE' || statut === 'ANNULEE';
        }

        function mettreClasse(element, anciennes, nouvelle) {
            if (!element) {
                return;
            }

            anciennes.forEach(function (classe) {
                element.classList.remove(...classe.split(' '));
            });

            element.classList.add(...nouvelle.split(' '));
        }

        function mettreAJourBadge(statut) {
            const data = statuts[statut];
            const badge = document.getElementById('statutBadge');
            const icon = document.getElementById('statutBadgeIcon');
            const label = document.getElementById('statutBadgeLabel');

            if (!data || !badge || !icon || !label) {
                return;
            }

            const toutesClassesBadge = [
                'bg-orange-50 text-orange-600 border-orange-300',
                'bg-blue-50 text-blue-600 border-blue-300',
                'bg-emerald-50 text-emerald-600 border-emerald-300',
                'bg-green-50 text-green-600 border-green-300',
                'bg-red-50 text-red-600 border-red-300',
                'bg-stone-50 text-stone-600 border-stone-300'
            ];

            mettreClasse(badge, toutesClassesBadge, data.badge);
            icon.className = data.icon;
            label.textContent = data.label;
        }

        function mettreAJourEtapes(statut) {
            const zone = document.getElementById('etapesCommande');

            if (!zone) {
                return;
            }

            const indexActuel = ordreEtapes.indexOf(statut);

            zone.querySelectorAll('[data-step]').forEach(function (step) {
                const code = step.dataset.step;
                const index = ordreEtapes.indexOf(code);
                const icon = step.querySelector('[data-step-icon]');
                const label = step.querySelector('[data-step-label]');
                const data = statuts[code];

                if (!icon || !label || !data) {
                    return;
                }

                const atteinte = indexActuel >= index && indexActuel !== -1;
                const actuelle = indexActuel === index;

                icon.classList.remove(
                    'bg-[#ff9800]',
                    'text-black',
                    'shadow-[0_0_0_4px_#e3e3e3]',
                    'bg-[#f5f5f5]',
                    'text-[#bdbdbd]'
                );

                label.classList.remove(
                    'text-[#c96500]',
                    'text-[#333333]'
                );

                if (atteinte) {
                    icon.classList.add(
                        'bg-[#ff9800]',
                        'text-black',
                        'shadow-[0_0_0_4px_#e3e3e3]'
                    );
                    label.classList.add('text-[#c96500]');
                } else {
                    icon.classList.add(
                        'bg-[#f5f5f5]',
                        'text-[#bdbdbd]'
                    );
                    label.classList.add('text-[#333333]');
                }

                icon.querySelector('i').className = data.stepIcon + ' text-[25px]';

                if (actuelle) {
                    icon.setAttribute('aria-current', 'step');
                } else {
                    icon.removeAttribute('aria-current');
                }
            });

            zone.querySelectorAll('[data-step-connector]').forEach(function (connector) {
                const connectorIndex = Number(connector.dataset.stepConnector);

                connector.classList.remove(
                    'bg-[#ff9800]',
                    'bg-[#dedede]'
                );

                connector.classList.add(
                    indexActuel > connectorIndex
                        ? 'bg-[#ff9800]'
                        : 'bg-[#dedede]'
                );
            });
        }

        function afficherZoneAnnulee() {
            const zone = document.getElementById('zoneStatutCommande');

            if (!zone) {
                return;
            }

            zone.innerHTML = `
                <div class="px-7 py-7 md:px-10 md:py-8">
                    <div class="flex items-center gap-5 rounded-[16px] border border-red-500 bg-red-100 px-5 py-5">
                        <div class="flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-full border-[3px] border-red-600">
                            <i class="fa-solid fa-xmark text-[21px] text-red-600"></i>
                        </div>
                        <div>
                            <h2 class="font-['Inter'] text-[16px] font-bold text-red-700">
                                Commande annulée
                            </h2>
                            <p class="mt-2 font-['DM_Sans'] text-[13px] leading-6 text-red-600">
                                Cette commande a été annulée.
                                Conformément à la <strong>Règle métier #8</strong>,
                                l'ensemble des articles commandés a été réinjecté
                                dans le stock disponible de la cuisine.
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }

        function afficherZoneEtapes(statut) {
            const zone = document.getElementById('zoneStatutCommande');

            if (!zone || !statuts[statut]) {
                return;
            }

            if (document.getElementById('etapesCommande')) {
                mettreAJourEtapes(statut);
                return;
            }

            zone.innerHTML = `
                <div class="px-6 py-6 md:px-10 md:py-7">
                    <div id="etapesCommande" class="grid grid-cols-1 gap-7 md:grid-cols-[1fr_auto_1fr_auto_1fr_auto_1fr] md:items-start">
                        <div class="text-center" data-step="EN_ATTENTE">
                            <div class="flex justify-center">
                                <div data-step-icon class="flex h-[64px] w-[64px] items-center justify-center rounded-[21px] bg-[#f5f5f5] text-[#bdbdbd]">
                                    <i class="fa-regular fa-clock text-[25px]"></i>
                                </div>
                            </div>
                            <h3 data-step-label class="mt-3 font-['DM_Sans'] text-[13px] font-bold text-[#333333]">En attente</h3>
                            <p class="mx-auto mt-1 max-w-[160px] font-['DM_Sans'] text-[10px] leading-4 text-[#888888]">Commande enregistrée, attente de prise en charge</p>
                        </div>
                        <div data-step-connector="0" class="hidden h-[3px] w-[55px] self-center bg-[#dedede] md:block"></div>
                        <div class="text-center" data-step="EN_PREPARATION">
                            <div class="flex justify-center">
                                <div data-step-icon class="flex h-[64px] w-[64px] items-center justify-center rounded-[21px] bg-[#f5f5f5] text-[#bdbdbd]">
                                    <i class="fa-solid fa-utensils text-[25px]"></i>
                                </div>
                            </div>
                            <h3 data-step-label class="mt-3 font-['DM_Sans'] text-[13px] font-bold text-[#333333]">En préparation</h3>
                            <p class="mx-auto mt-1 max-w-[160px] font-['DM_Sans'] text-[10px] leading-4 text-[#888888]">Cuisson et dressage en cuisine par nos chefs</p>
                        </div>
                        <div data-step-connector="1" class="hidden h-[3px] w-[55px] self-center bg-[#dedede] md:block"></div>
                        <div class="text-center" data-step="PRETE">
                            <div class="flex justify-center">
                                <div data-step-icon class="flex h-[64px] w-[64px] items-center justify-center rounded-[21px] bg-[#f5f5f5] text-[#bdbdbd]">
                                    <i class="fa-solid fa-store text-[25px]"></i>
                                </div>
                            </div>
                            <h3 data-step-label class="mt-3 font-['DM_Sans'] text-[13px] font-bold text-[#333333]">Prête au comptoir</h3>
                            <p class="mx-auto mt-1 max-w-[160px] font-['DM_Sans'] text-[10px] leading-4 text-[#888888]">Votre repas chaud est prêt à être récupéré</p>
                        </div>
                        <div data-step-connector="2" class="hidden h-[3px] w-[55px] self-center bg-[#dedede] md:block"></div>
                        <div class="text-center" data-step="RETIREE">
                            <div class="flex justify-center">
                                <div data-step-icon class="flex h-[64px] w-[64px] items-center justify-center rounded-[21px] bg-[#f5f5f5] text-[#bdbdbd]">
                                    <i class="fa-regular fa-circle-check text-[25px]"></i>
                                </div>
                            </div>
                            <h3 data-step-label class="mt-3 font-['DM_Sans'] text-[13px] font-bold text-[#333333]">Retirée</h3>
                            <p class="mx-auto mt-1 max-w-[160px] font-['DM_Sans'] text-[10px] leading-4 text-[#888888]">Commande remise au client. Bon appétit !</p>
                        </div>
                    </div>
                </div>
            `;

            mettreAJourEtapes(statut);
        }

        function mettreAJourHistorique(statut) {
            const data = statuts[statut];
            const historique = document.getElementById('historiqueCommande');
            const ligneCourante = document.getElementById('historiqueStatutCourant');

            if (!data || !historique) {
                return;
            }

            if (statut === 'EN_ATTENTE') {
                if (ligneCourante) {
                    ligneCourante.classList.add('hidden');
                }
                return;
            }

            if (statut === 'ANNULEE') {
                if (ligneCourante) {
                    ligneCourante.classList.add('hidden');
                }

                let ligneAnnulation = document.getElementById('historiqueAnnulation');

                if (!ligneAnnulation) {
                    ligneAnnulation = document.createElement('div');
                    ligneAnnulation.id = 'historiqueAnnulation';
                    ligneAnnulation.className = 'flex items-center justify-between gap-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4';
                    historique.appendChild(ligneAnnulation);
                }

                ligneAnnulation.innerHTML = `
                    <span class="inline-flex shrink-0 items-center gap-2 rounded-full border border-red-400 bg-red-50 px-3 py-1 text-[10px] font-semibold text-red-600">
                        <i class="fa-regular fa-circle-xmark"></i>
                        Annulée
                    </span>
                    <p class="flex-1 text-center font-['DM_Sans'] text-[14px] text-[#333333]">
                        Annulée par le client - Stock restitué
                    </p>
                    <span class="shrink-0 font-['DM_Sans'] text-[11px] font-medium text-[#777777]">
                        Statut actuel
                    </span>
                `;

                return;
            }

            const annulation = document.getElementById('historiqueAnnulation');
            annulation?.remove();

            if (!ligneCourante) {
                return;
            }

            const badge = document.getElementById('historiqueStatutBadge');
            const icon = document.getElementById('historiqueStatutIcon');
            const label = document.getElementById('historiqueStatutLabel');
            const message = document.getElementById('historiqueStatutMessage');

            ligneCourante.classList.remove('hidden');

            if (badge) {
                const toutesClassesBadge = [
                    'bg-orange-50 text-orange-600 border-orange-300',
                    'bg-blue-50 text-blue-600 border-blue-300',
                    'bg-emerald-50 text-emerald-600 border-emerald-300',
                    'bg-green-50 text-green-600 border-green-300',
                    'bg-red-50 text-red-600 border-red-300',
                    'bg-stone-50 text-stone-600 border-stone-300'
                ];
                mettreClasse(badge, toutesClassesBadge, data.badge);
            }

            if (icon) {
                icon.className = data.icon;
            }

            if (label) {
                label.textContent = data.label;
            }

            if (message) {
                message.textContent = data.message;
            }
        }

        function creerBoutonAvis() {
            if (document.getElementById('openAvisModal')) {
                return;
            }

            const actions = document.getElementById('actionsContainer');
            const detail = actions?.querySelector('a[href^="/commande/detail/"]');

            if (!actions || !detail) {
                return;
            }

            const bouton = document.createElement('button');
            bouton.type = 'button';
            bouton.id = 'openAvisModal';
            bouton.className = 'inline-flex items-center justify-center gap-3 rounded-[9px] bg-[#ff9800] px-5 py-2.5 font-[\'DM_Sans\'] text-[13px] font-bold text-white transition hover:bg-[#e88900]';
            bouton.innerHTML = '<i class="fa-regular fa-star"></i> Donner mon avis pour ce repas';

            actions.insertBefore(bouton, detail);
        }

        function supprimerBoutonAvis() {
            document.getElementById('openAvisModal')?.remove();
        }

        function creerBoutonAnnulation() {
            if (document.getElementById('openCancelModal')) {
                return;
            }

            const actions = document.getElementById('actionsContainer');
            const detail = actions?.querySelector('a[href^="/commande/detail/"]');

            if (!actions || !detail) {
                return;
            }

            const bouton = document.createElement('button');
            bouton.type = 'button';
            bouton.id = 'openCancelModal';
            bouton.className = 'inline-flex items-center justify-center gap-3 rounded-[9px] border border-red-500 px-5 py-2.5 font-[\'DM_Sans\'] text-[13px] font-medium text-red-500 transition hover:bg-red-50';
            bouton.innerHTML = '<i class="fa-regular fa-circle-xmark"></i> Annuler cette commande';

            actions.insertBefore(bouton, detail);
        }

        function supprimerBoutonAnnulation() {
            document.getElementById('openCancelModal')?.remove();
        }

        function mettreAJourActions(statut) {
            if (statut === 'EN_ATTENTE' || statut === 'EN_PREPARATION') {
                creerBoutonAnnulation();
            } else {
                supprimerBoutonAnnulation();
            }

            if (statut === 'RETIREE') {
                creerBoutonAvis();
            } else {
                supprimerBoutonAvis();
            }
        }

        function mettreAJourInterface(statut) {
            if (!statuts[statut]) {
                return;
            }

            mettreAJourBadge(statut);
            mettreAJourHistorique(statut);
            mettreAJourActions(statut);

            if (statut === 'ANNULEE') {
                afficherZoneAnnulee();
            } else {
                afficherZoneEtapes(statut);
            }
        }

        function afficherEtatConnexion(actif) {
            const element = document.getElementById('suiviConnexion');

            if (!element) {
                return;
            }

            if (actif) {
                element.innerHTML = `
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Suivi actif
                `;
                element.classList.remove('text-red-500');
                element.classList.add('text-[#888888]');
            } else {
                element.innerHTML = `
                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                    Reconnexion...
                `;
                element.classList.remove('text-[#888888]');
                element.classList.add('text-red-500');
            }
        }

        /* =====================================================
           POLLING : LE STATUT VIENT DE LA BASE DE DONNÉES
        ===================================================== */

        async function recupererEtat() {
            if (requeteEnCours) {
                return;
            }

            requeteEnCours = true;

            try {
                const response = await fetch(
                    '/commande/suivi/' + commandeId + '/etat',
                    {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Cache-Control': 'no-cache'
                        },
                        cache: 'no-store'
                    }
                );

                if (!response.ok) {
                    throw new Error('Erreur HTTP ' + response.status);
                }

                const data = await response.json();

                if (!data.success || !data.commande || !data.commande.statut) {
                    throw new Error(data.message || 'Réponse invalide.');
                }

                const nouveauStatut = data.commande.statut;

                if (!statuts[nouveauStatut]) {
                    throw new Error('Statut inconnu : ' + nouveauStatut);
                }

                afficherEtatConnexion(true);

                if (nouveauStatut !== statutPrecedent) {
                    mettreAJourInterface(nouveauStatut);
                    statutPrecedent = nouveauStatut;
                } else {
                    /*
                     * On rafraîchit quand même l'interface au premier appel,
                     * notamment après un rechargement ou un changement effectué
                     * juste avant l'ouverture de la page.
                     */
                    mettreAJourInterface(nouveauStatut);
                }

                /*
                 * Une commande RETIREE ou ANNULEE n'a plus besoin d'être interrogée.
                 */
                if (estStatutTerminal(nouveauStatut)) {
                    arreterSuivi();
                }
            } catch (error) {
                console.error('Suivi commande :', error);
                afficherEtatConnexion(false);
            } finally {
                requeteEnCours = false;
            }
        }

        function arreterSuivi() {
            if (intervalleSuivi !== null) {
                clearInterval(intervalleSuivi);
                intervalleSuivi = null;
            }
        }

        function demarrerSuivi() {
            /*
             * Ne pas lancer de polling inutile si la commande est déjà
             * dans un état terminal.
             */
            if (estStatutTerminal(statutPrecedent)) {
                return;
            }

            recupererEtat();

            intervalleSuivi = setInterval(
                recupererEtat,
                3000
            );
        }

        /* =====================================================
           MODAL ANNULATION

           Délégation d'événement : le bouton peut être ajouté ou
           supprimé dynamiquement lorsque le statut change.
        ===================================================== */

        const cancelModal = document.getElementById('cancelModal');
        const closeCancelModalButton = document.getElementById('closeCancelModal');
        const cancelModalButton = document.getElementById('cancelModalButton');

        function ouvrirModalAnnulation() {
            if (!cancelModal) {
                return;
            }

            cancelModal.classList.remove('hidden');
            cancelModal.classList.add('flex');
            cancelModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        }

        function fermerModalAnnulation() {
            if (!cancelModal) {
                return;
            }

            cancelModal.classList.add('hidden');
            cancelModal.classList.remove('flex');
            cancelModal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('click', function (event) {
            const bouton = event.target.closest('#openCancelModal');

            if (bouton) {
                ouvrirModalAnnulation();
            }
        });

        closeCancelModalButton?.addEventListener('click', fermerModalAnnulation);
        cancelModalButton?.addEventListener('click', fermerModalAnnulation);

        cancelModal?.addEventListener('click', function (event) {
            if (event.target === cancelModal) {
                fermerModalAnnulation();
            }
        });

        /* =====================================================
           MODAL AVIS
        ===================================================== */

        const avisModal = document.getElementById('avisModal');
        const closeAvisModalButton = document.getElementById('closeAvisModal');
        const cancelAvisModalButton = document.getElementById('cancelAvisModal');
        const avisForm = document.getElementById('avisForm');
        const avisStars = document.getElementById('avisStars');
        const avisNoteErreur = document.getElementById('avisNoteErreur');
        const avisCommentaireErreur = document.getElementById('avisCommentaireErreur');
        const avisCommentaire = document.getElementById('avisCommentaire');

        function ouvrirModalAvis() {
            if (!avisModal) {
                return;
            }

            avisModal.classList.remove('hidden');
            avisModal.classList.add('flex');
            avisModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        }

        function fermerModalAvis() {
            if (!avisModal) {
                return;
            }

            avisModal.classList.add('hidden');
            avisModal.classList.remove('flex');
            avisModal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('click', function (event) {
            const bouton = event.target.closest('#openAvisModal');

            if (bouton) {
                ouvrirModalAvis();
            }
        });

        closeAvisModalButton?.addEventListener('click', fermerModalAvis);
        cancelAvisModalButton?.addEventListener('click', fermerModalAvis);

        avisModal?.addEventListener('click', function (event) {
            if (event.target === avisModal) {
                fermerModalAvis();
            }
        });

        /* Étoiles de notation */
        avisStars?.addEventListener('change', function (event) {
            const radio = event.target.closest('input[name="note"]');

            if (!radio) {
                return;
            }

            const note = Number(radio.value);

            avisStars.querySelectorAll('[data-star]').forEach(function (star) {
                const valeur = Number(star.dataset.star);

                star.classList.remove(
                    'fa-regular',
                    'fa-solid',
                    'text-[#cfcfcf]',
                    'text-[#ff9800]'
                );

                if (valeur <= note) {
                    star.classList.add('fa-solid', 'text-[#ff9800]');
                } else {
                    star.classList.add('fa-regular', 'text-[#cfcfcf]');
                }
            });

            avisNoteErreur?.classList.add('hidden');
        });

        avisForm?.addEventListener('submit', function (event) {
            const noteInput = avisForm.querySelector('input[name="note"]:checked');
            const note = Number(noteInput?.value || 0);
            const commentaire = avisCommentaire?.value.trim() || '';

            let valide = true;

            if (note < 1 || note > 5) {
                avisNoteErreur?.classList.remove('hidden');
                valide = false;
            } else {
                avisNoteErreur?.classList.add('hidden');
            }

            if (commentaire.length === 0) {
                avisCommentaireErreur?.classList.remove('hidden');
                valide = false;
            } else {
                avisCommentaireErreur?.classList.add('hidden');
            }

            if (!valide) {
                event.preventDefault();
                return;
            }

            /*
             * IMPORTANT : ne pas faire preventDefault ici.
             * Le formulaire est réellement envoyé vers POST /avis.
             * AvisController -> AvisService -> AvisRepository enregistrent l'avis.
             */
        });

        avisCommentaire?.addEventListener('input', function () {
            if (avisCommentaire.value.trim() !== '') {
                avisCommentaireErreur?.classList.add('hidden');
            }
        });

        /* =====================================================
           ÉCHAP / FERMETURE
        ===================================================== */

        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape') {
                return;
            }

            fermerModalAnnulation();
            fermerModalAvis();
        });

        /* =====================================================
           INITIALISATION
        ===================================================== */

        mettreAJourInterface(statutPrecedent);
        demarrerSuivi();

        window.addEventListener('beforeunload', function () {
            arreterSuivi();
        });
    });
</script>
