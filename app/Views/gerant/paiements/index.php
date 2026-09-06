<?php

$paiements = $paiements ?? [];

$commandesImpayees = $commandesImpayees ?? [];

$commandesPartielles = $commandesPartielles ?? [];

$statutFiltre = $statutFiltre ?? 'TOUS';

$termeRecherche = $termeRecherche ?? null;

$erreurPaiement = $erreurPaiement ?? null;

$commandePaiement = $commandePaiement ?? null;

$montantPaiement = $montantPaiement ?? null;

/**
 * Formate un montant en FCFA.
 */
$formatMontant = static function (float $montant): string {
    return number_format(
        $montant,
        0,
        ',',
        ' '
    ) . ' F';
};

/**
 * Retourne le libellé du statut.
 */
$libelleStatut = static function (string $statut): string {
    return match ($statut) {
        'PAYEE' => 'Payé',
        'IMPAYEE' => 'Impayé',
        'PARTIELLEMENT_PAYEE',
        'PARTIELLE' => 'Partiellement payé',
        default => 'Inconnu',
    };
};

/**
 * Retourne les classes Tailwind du statut.
 */
$classesStatut = static function (string $statut): string {
    return match ($statut) {
        'PAYEE' => 'bg-green-100 text-green-700',
        'IMPAYEE' => 'bg-red-100 text-red-700',
        'PARTIELLEMENT_PAYEE',
        'PARTIELLE' => 'bg-orange-100 text-orange-700',
        default => 'bg-gray-100 text-gray-700',
    };
};

/**
 * Détermine le statut réel à partir des montants.
 *
 * Cela évite d'afficher "Impayé" lorsqu'une commande
 * est entièrement réglée.
 */
$determinerStatut = static function (
    object $paiement
): string {
    $montantTotal =
        (float) ($paiement->montant_total ?? 0);

    $montantPaye =
        (float) ($paiement->montant_paye ?? 0);

    $montantRestant =
        (float) ($paiement->montant_restant ?? 0);

    if (
        $montantRestant <= 0.01
        || (
            $montantTotal > 0
            && $montantPaye >= $montantTotal
        )
    ) {
        return 'PAYEE';
    }

    if ($montantPaye > 0) {
        return 'PARTIELLEMENT_PAYEE';
    }

    return 'IMPAYEE';
};

?>
<!-- ENTETE -->

<section
    class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-orange-900 shadow-sm">


    <div
        class="flex flex-col gap-5 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex items-center gap-2 text-orange-500">

                <i class="fa-solid fa-coins text-sm"></i>

                <span
                    class="font-['DM_Sans'] text-[11px] font-bold uppercase tracking-wider">
                   Paiements
                </span>

            </div>

            <h1
                class="text-2xl font-bold text-white sm:text-3xl">
                Gestion des paiements
            </h1>

            <p
                class="mt-2 max-w-2xl text-sm text-gray-300">
                Consultez les paiements et suivez les règlements des commandes.
            </p>
        </div>


        <button
            type="button"
            onclick="ouvrirModalPaiement()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#ff9500] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#e68600]">
            <i class="fa-solid fa-plus"></i>
            Enregistrer un paiement
        </button>

    </div>
</section>

<div class="space-y-6">



    <!-- Cartes statistiques -->
    <?php

    $nombrePaiements = count($paiements);

    $totalEncaisse = 0;

    foreach ($paiements as $paiement) {
        $totalEncaisse +=
            (float) ($paiement->montant ?? 0);
    }

    $nombreImpayees = count($commandesImpayees);

    $nombrePartielles = count($commandesPartielles);

    ?>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <!-- Paiements -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Paiements enregistrés
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        <?= $nombrePaiements ?>
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-credit-card"></i>
                </div>

            </div>
        </div>


        <!-- Encaissé -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Total encaissé
                    </p>

                    <p class="mt-2 text-xl font-bold text-gray-900">
                        <?= htmlspecialchars(
                            $formatMontant($totalEncaisse),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>

            </div>
        </div>


        <!-- Impayées -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Commandes impayées
                    </p>

                    <p class="mt-2 text-2xl font-bold text-red-600">
                        <?= $nombreImpayees ?>
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>

            </div>
        </div>


        <!-- Partielles -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Paiements partiels
                    </p>

                    <p class="mt-2 text-2xl font-bold text-orange-500">
                        <?= $nombrePartielles ?>
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>

            </div>
        </div>

    </div>


    <!-- Filtres + recherche -->
    <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <!-- Filtres -->
            <div class="flex flex-wrap gap-2">

                <a
                    href="/gerant/paiements"
                    class="<?= $statutFiltre === 'TOUS'
                                ? 'bg-[#ff9500] text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>
                        inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                    <i class="fa-solid fa-list"></i>
                    Tous
                </a>

                <a
                    href="/gerant/paiements/statut/PAYEE"
                    class="<?= $statutFiltre === 'PAYEE'
                                ? 'bg-[#ff9500] text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>
                        inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                    <i class="fa-solid fa-circle-check"></i>
                    Payés
                </a>

                <a
                    href="/gerant/paiements/statut/IMPAYEE"
                    class="<?= $statutFiltre === 'IMPAYEE'
                                ? 'bg-[#ff9500] text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>
                        inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                    <i class="fa-solid fa-circle-xmark"></i>
                    Impayés
                </a>

                <a
                    href="/gerant/paiements/statut/PARTIELLEMENT_PAYEE"
                    class="<?= $statutFiltre === 'PARTIELLEMENT_PAYEE'
                                ? 'bg-[#ff9500] text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>
                        inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition">
                    <i class="fa-solid fa-clock"></i>
                    Partiels
                </a>

            </div>


            <!-- Recherche -->
            <form
                method="GET"
                action="/gerant/paiements"
                onsubmit="return rechercherPaiement(event)"
                class="flex w-full lg:w-80">
                <div class="relative w-full">

                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input
                        id="recherchePaiement"
                        type="text"
                        value="<?= htmlspecialchars(
                                    $termeRecherche ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                        placeholder="Rechercher un client ..."
                        class="w-full rounded-xl border border-gray-200 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-[#ff9500] focus:ring-2 focus:ring-[#ff9500]/20">

                </div>
            </form>

        </div>

    </div>


    <?php if ($statutFiltre === 'IMPAYEE'): ?>

        <!-- COMMANDES IMPAYÉES -->
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-5 py-4">

                <h2 class="font-semibold text-gray-900">
                    Commandes impayées
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Commandes pour lesquelles aucun paiement n'a encore été enregistré.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">

                        <tr>
                            <th class="px-5 py-3">Commande</th>
                            <th class="px-5 py-3">Client</th>
                            <th class="px-5 py-3">Montant total</th>
                            <th class="px-5 py-3">Payé</th>
                            <th class="px-5 py-3">Restant</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        <?php if (empty($commandesImpayees)): ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-10 text-center text-gray-500">

                                    <i class="fa-solid fa-circle-check mb-2 text-2xl text-green-500"></i>

                                    <p>
                                        Aucune commande impayée.
                                    </p>

                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($commandesImpayees as $commande): ?>

                                <tr class="hover:bg-gray-50">

                                    <td class="px-5 py-4 font-semibold text-gray-900">
                                        CMD-<?= str_pad(
                                                (string) ($commande->commande_id ?? 0),
                                                3,
                                                '0',
                                                STR_PAD_LEFT
                                            ) ?>
                                    </td>

                                    <td class="px-5 py-4 text-gray-700">
                                        <?= htmlspecialchars(
                                            $commande->client_nom_complet ?? 'Client',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4 font-medium">
                                        <?= $formatMontant(
                                            (float) ($commande->montant_total ?? 0)
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4 text-gray-600">
                                        <?= $formatMontant(
                                            (float) ($commande->montant_paye ?? 0)
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4 font-semibold text-red-600">
                                        <?= $formatMontant(
                                            (float) ($commande->montant_restant ?? 0)
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4 text-right">

                                        <button
                                            type="button"
                                            onclick="selectionnerCommande(
                                            <?= (int) ($commande->commande_id ?? 0) ?>,
                                            <?= (float) ($commande->montant_restant ?? 0) ?>
                                        )"
                                            class="inline-flex items-center gap-2 rounded-lg bg-[#ff9500] px-3 py-2 text-xs font-semibold text-white hover:bg-[#e68600]">
                                            <i class="fa-solid fa-plus"></i>
                                            Payer
                                        </button>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>


    <?php elseif ($statutFiltre === 'PARTIELLEMENT_PAYEE'): ?>

        <!-- COMMANDES PARTIELLES -->
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-5 py-4">

                <h2 class="font-semibold text-gray-900">
                    Commandes partiellement payées
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Commandes dont le règlement n'est pas encore complet.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">

                        <tr>
                            <th class="px-5 py-3">Commande</th>
                            <th class="px-5 py-3">Client</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Payé</th>
                            <th class="px-5 py-3">Restant</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        <?php if (empty($commandesPartielles)): ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-10 text-center text-gray-500">
                                    Aucune commande partiellement payée.
                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($commandesPartielles as $commande): ?>

                                <tr class="hover:bg-gray-50">

                                    <td class="px-5 py-4 font-semibold text-gray-900">
                                        CMD-<?= str_pad(
                                                (string) ($commande->commande_id ?? 0),
                                                3,
                                                '0',
                                                STR_PAD_LEFT
                                            ) ?>
                                    </td>

                                    <td class="px-5 py-4 text-gray-700">
                                        <?= htmlspecialchars(
                                            $commande->client_nom_complet ?? 'Client',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <?= $formatMontant(
                                            (float) ($commande->montant_total ?? 0)
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4 font-medium text-green-600">
                                        <?= $formatMontant(
                                            (float) ($commande->montant_paye ?? 0)
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4 font-semibold text-orange-600">
                                        <?= $formatMontant(
                                            (float) ($commande->montant_restant ?? 0)
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4 text-right">

                                        <button
                                            type="button"
                                            onclick="selectionnerCommande(
                                            <?= (int) ($commande->commande_id ?? 0) ?>,
                                            <?= (float) ($commande->montant_restant ?? 0) ?>
                                        )"
                                            class="inline-flex items-center gap-2 rounded-lg bg-[#ff9500] px-3 py-2 text-xs font-semibold text-white hover:bg-[#e68600]">
                                            <i class="fa-solid fa-plus"></i>
                                            Compléter
                                        </button>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>


    <?php else: ?>

        <!-- HISTORIQUE DES PAIEMENTS -->
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-5 py-4">

                <h2 class="font-semibold text-gray-900">
                    Historique des paiements
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Liste des règlements enregistrés.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">

                        <tr>
                            <th class="px-5 py-3">Commande</th>
                            <th class="px-5 py-3">Client</th>
                            <th class="px-5 py-3">Montant</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Statut</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        <?php if (empty($paiements)): ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-12 text-center text-gray-500">

                                    <i class="fa-solid fa-receipt mb-3 text-3xl text-gray-300"></i>

                                    <p class="font-medium">
                                        Aucun paiement enregistré.
                                    </p>

                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($paiements as $paiement): ?>

                                <?php
                                $statut = $determinerStatut($paiement);
                                ?>

                                <tr class="hover:bg-gray-50">

                                    <td class="px-5 py-4">

                                        <a
                                            href="/gerant/commande/show/<?= (int) ($paiement->commande_id ?? 0) ?>"
                                            class="font-semibold text-gray-900 hover:text-[#ff9500]">
                                            CMD-<?= str_pad(
                                                    (string) ($paiement->commande_id ?? 0),
                                                    3,
                                                    '0',
                                                    STR_PAD_LEFT
                                                ) ?>
                                        </a>

                                    </td>

                                    <td class="px-5 py-4 text-gray-700">
                                        <?= htmlspecialchars(
                                            $paiement->client_nom_complet ?? 'Client',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4 font-semibold text-gray-900">
                                        <?= $formatMontant(
                                            (float) ($paiement->montant ?? 0)
                                        ) ?>
                                    </td>

                                    <td class="px-5 py-4 text-gray-500">
                                        <?= $paiement->date_paiement
                                            ? date(
                                                'd/m/Y H:i',
                                                strtotime($paiement->date_paiement)
                                            )
                                            : '' ?>
                                    </td>

                                    <td class="px-5 py-4">

                                        <span
                                            class="<?= $classesStatut($statut) ?>
                                        inline-flex rounded-full px-3 py-1 text-xs font-semibold">
                                            <?= htmlspecialchars(
                                                $libelleStatut($statut),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    </td>

                                    <td class="px-5 py-4 text-right">

                                        <a
                                            href="/gerant/commande/show/<?= (int) ($paiement->commande_id ?? 0) ?>"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-600 transition hover:bg-gray-200"
                                            title="Voir la commande">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>


</div>


<!-- MODAL PAIEMENT -->
<div
    id="modalPaiement"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
    aria-hidden="true">

    <div
        class="w-full max-w-md rounded-2xl bg-white shadow-xl"
        onclick="event.stopPropagation()">

        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">

            <div>

                <h2 class="text-lg font-bold text-gray-900">
                    Enregistrer un paiement
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Saisissez les informations du règlement.
                </p>

            </div>

            <button
                type="button"
                onclick="fermerModalPaiement()"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <form
            method="POST"
            action="/gerant/paiements"
            class="space-y-5 p-6">

            <!-- Numéro de commande -->
            <div>

                <label
                    for="commande_id"
                    class="mb-2 block text-sm font-medium text-gray-700">
                    Numéro de commande
                </label>

                <input
                    id="commande_id"
                    name="commande_id"
                    type="number"
                    min="1"
                    required
                    placeholder="Ex : 1"
                    value="<?= $commandePaiement !== null
                                ? (int) $commandePaiement
                                : '' ?>"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#ff9500] focus:ring-2 focus:ring-[#ff9500]/20">

            </div>


            <!-- Montant -->
            <div>

                <label
                    for="montant"
                    class="mb-2 block text-sm font-medium text-gray-700">
                    Montant du paiement
                </label>

                <div class="relative">

                    <input
                        id="montant"
                        name="montant"
                        type="number"
                        min="1"
                        step="1"
                        required
                        placeholder="Ex : 8500"
                        value="<?= $montantPaiement !== null
                                    ? htmlspecialchars(
                                        (string) $montantPaiement,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )
                                    : '' ?>"
                        class="w-full rounded-xl border <?= $erreurPaiement
                                                            ? 'border-red-400'
                                                            : 'border-gray-200' ?> px-4 py-3 pr-14 text-sm outline-none focus:border-[#ff9500] focus:ring-2 focus:ring-[#ff9500]/20">

                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-medium text-gray-400">
                        FCFA
                    </span>

                </div>


                <!-- Message d'erreur -->
                <?php if ($erreurPaiement): ?>

                    <p class="mt-1.5 text-sm font-medium text-red-600">

                        <i class="fa-solid fa-circle-exclamation mr-1"></i>

                        <?= htmlspecialchars(
                            $erreurPaiement,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </p>

                <?php endif; ?>

            </div>


            <!-- Boutons -->
            <div class="flex justify-end gap-3 pt-2">

                <button
                    type="button"
                    onclick="fermerModalPaiement()"
                    class="rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-200">
                    Annuler
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#ff9500] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#e68600]">
                    <i class="fa-solid fa-check mr-1"></i>
                    Enregistrer
                </button>

            </div>

        </form>

    </div>

</div>


<script>
    function ouvrirModalPaiement() {

        const modal =
            document.getElementById('modalPaiement');

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');

        modal.classList.add('flex');

        modal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'overflow-hidden'
        );

        document
            .getElementById('commande_id')
            ?.focus();
    }


    function fermerModalPaiement() {

        const modal =
            document.getElementById('modalPaiement');

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');

        modal.classList.remove('flex');

        modal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );
    }


    function selectionnerCommande(
        id,
        restant
    ) {

        ouvrirModalPaiement();

        const commandeInput =
            document.getElementById('commande_id');

        const montantInput =
            document.getElementById('montant');

        if (commandeInput) {
            commandeInput.value = id;
        }

        if (montantInput) {
            montantInput.value =
                Math.floor(restant);
        }

        montantInput?.focus();
    }


    function rechercherPaiement(event) {

        event.preventDefault();

        const input =
            document.getElementById(
                'recherchePaiement'
            );

        if (!input) {
            return false;
        }

        const terme =
            input.value.trim();

        if (terme === '') {

            window.location.href =
                '/gerant/paiements';

            return false;
        }

        window.location.href =
            '/gerant/paiements/recherche/' +
            encodeURIComponent(terme);

        return false;
    }


    document
        .getElementById('modalPaiement')
        ?.addEventListener(
            'click',
            function() {
                fermerModalPaiement();
            }
        );


    document.addEventListener(
        'keydown',
        function(event) {

            if (event.key === 'Escape') {
                fermerModalPaiement();
            }

        }
    );
</script>


<?php if ($erreurPaiement): ?>

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                ouvrirModalPaiement();

                const commandeInput =
                    document.getElementById(
                        'commande_id'
                    );

                if (commandeInput) {

                    commandeInput.value =
                        <?= (int) $commandePaiement ?>;

                }

                const montantInput =
                    document.getElementById(
                        'montant'
                    );

                if (montantInput) {

                    montantInput.value =
                        <?= json_encode(
                            (string) $montantPaiement
                        ) ?>;

                    montantInput.focus();

                }

            }
        );
    </script>

<?php endif; ?>