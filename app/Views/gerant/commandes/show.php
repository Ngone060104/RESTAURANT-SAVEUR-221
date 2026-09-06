<?php

$commandeId = (int) $commande->getId();

$nomClient = $client !== null
    ? trim(
        $client->getPrenom()
        . ' '
        . $client->getNom()
    )
    : 'Client inconnu';

$telephoneClient = $client !== null
    ? $client->getTelephone()
    : null;

$statutCommande = $commande->getStatut();

$montantTotal = (float) $commande->getMontantTotal();

$montantPaye = $statutPaiement !== null
    ? (float) (
        $statutPaiement->montant_paye ?? 0
    )
    : 0;

$montantRestant = $statutPaiement !== null
    ? (float) (
        $statutPaiement->montant_restant
        ?? max(0, $montantTotal - $montantPaye)
    )
    : max(0, $montantTotal - $montantPaye);

$statutPaiementCode = strtoupper(
    (string) (
        $statutPaiement->statut_paiement
        ?? 'IMPAYEE'
    )
);

if (
    $montantRestant <= 0
    || $montantPaye >= $montantTotal
) {
    $statutPaiementCode = 'PAYEE';
}

$statutLabels = [
    'EN_ATTENTE' => 'En attente',
    'EN_PREPARATION' => 'En préparation',
    'PRETE' => 'Prête',
    'RETIREE' => 'Retirée',
    'ANNULEE' => 'Annulée',
];

$statutLabel =
    $statutLabels[$statutCommande]
    ?? str_replace('_', ' ', $statutCommande);

$statutClasse = match ($statutCommande) {
    'EN_ATTENTE'
        => 'bg-orange-50 text-orange-600 border-orange-200',

    'EN_PREPARATION'
        => 'bg-blue-50 text-blue-600 border-blue-200',

    'PRETE'
        => 'bg-green-50 text-green-600 border-green-200',

    'RETIREE'
        => 'bg-gray-100 text-gray-600 border-gray-200',

    'ANNULEE'
        => 'bg-red-50 text-red-500 border-red-200',

    default
        => 'bg-gray-100 text-gray-600 border-gray-200',
};
?>

<div class="space-y-6">

    <!-- Retour -->
   <!-- Retour -->
<a
    href="/gerant/commandes"
    class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-[#777777] transition hover:text-orange-500"
>
    <i class="fa-solid fa-arrow-left text-xs"></i>
    Retour aux commandes
</a>

<!-- En-tête commande -->
<div
    class="mb-6 rounded-2xl border border-[#eeeeee] bg-white px-6 py-5 shadow-sm"
>
    <div
        class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
    >

        <!-- Informations principales -->
        <div>
            <div class="flex flex-wrap items-center gap-3">

                <h1
                    class="text-2xl font-bold text-[#333333]"
                >
                    Commande #<?= $commandeId ?>
                </h1>

                <span
                    class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold <?= $statutClasse ?>"
                >
                    <span
                        class="h-1.5 w-1.5 rounded-full bg-current"
                    ></span>

                    <?= htmlspecialchars($statutLabel) ?>
                </span>

            </div>

            <p class="mt-2 text-sm text-[#999999]">
                Consultez les informations et gérez cette commande.
            </p>
        </div>

        <!-- Date / montant -->
        <div
            class="flex items-center gap-6 border-t border-[#eeeeee] pt-4 md:border-t-0 md:pt-0"
        >

            <div>
                <p class="text-xs text-[#999999]">
                    Date
                </p>

                <p class="mt-1 text-sm font-semibold text-[#444444]">
                    <?= htmlspecialchars(
                        $commande->getDateCommande()
                    ) ?>
                </p>
            </div>

            <div class="h-10 w-px bg-[#eeeeee]"></div>

            <div>
                <p class="text-xs text-[#999999]">
                    Montant
                </p>

                <p class="mt-1 text-lg font-bold text-orange-500">
                    <?= number_format(
                        (float) $commande->getMontantTotal(),
                        0,
                        ',',
                        ' '
                    ) ?> F
                </p>
            </div>

        </div>

    </div>
</div>



    </div>


    <!-- Contenu principal -->
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3 p-3">


        <!-- COLONNE PRINCIPALE -->
        <div class="space-y-6 xl:col-span-2">


            <!-- Articles -->
            <div class="overflow-hidden rounded-2xl border border-[#eeeeee] bg-white">

                <div class="flex items-center justify-between border-b border-[#eeeeee] px-6 py-5">

                    <div>
                        <h2 class="text-base font-semibold text-[#333333]">
                            Articles commandés
                        </h2>

                        <p class="mt-1 text-xs text-[#999999]">
                            Produits inclus dans cette commande
                        </p>
                    </div>

                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-50 text-orange-500"
                    >
                        <i class="fa-solid fa-basket-shopping text-sm"></i>
                    </div>

                </div>


                <div class="divide-y divide-[#eeeeee]">

                    <?php foreach ($lignes as $ligne): ?>

                        <?php
                        $quantite = (int) $ligne->getQuantite();
                        $prix = (float) $ligne->getPrixUnitaire();
                        $sousTotal = $quantite * $prix;
                        ?>

                        <div class="flex items-center justify-between gap-5 px-6 py-5">

                            <div class="flex min-w-0 items-center gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#fff7ed] text-orange-500"
                                >
                                    <i class="fa-solid fa-utensils text-sm"></i>
                                </div>

                                <div class="min-w-0">

                                    <p class="truncate text-sm font-semibold text-[#333333]">
                                        <?= htmlspecialchars(
                                            (string) $ligne->getProduitLibelle()
                                        ) ?>
                                    </p>

                                    <p class="mt-1 text-xs text-[#999999]">
                                        <?= number_format(
                                            $prix,
                                            0,
                                            ',',
                                            ' '
                                        ) ?> F × <?= $quantite ?>
                                    </p>

                                </div>

                            </div>

                            <p class="shrink-0 text-sm font-semibold text-[#333333]">
                                <?= number_format(
                                    $sousTotal,
                                    0,
                                    ',',
                                    ' '
                                ) ?> F
                            </p>

                        </div>

                    <?php endforeach; ?>

                </div>


                <!-- Total -->
                <div class="border-t border-[#eeeeee] bg-[#fafafa] px-6 py-5">

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-medium text-[#777777]">
                            Total de la commande
                        </span>

                        <span class="text-xl font-bold text-[#333333]">
                            <?= number_format(
                                $montantTotal,
                                0,
                                ',',
                                ' '
                            ) ?> F
                        </span>

                    </div>

                </div>

            </div>


            <!-- Paiement -->
            <div class="rounded-2xl border border-[#eeeeee] bg-white">

                <div class="border-b border-[#eeeeee] px-6 py-5">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-50 text-orange-500"
                        >
                            <i class="fa-solid fa-credit-card text-sm"></i>
                        </div>

                        <div>

                            <h2 class="text-base font-semibold text-[#333333]">
                                Paiement
                            </h2>

                            <p class="mt-1 text-xs text-[#999999]">
                                Situation du règlement
                            </p>

                        </div>

                    </div>

                </div>


                <div class="grid grid-cols-1 gap-px overflow-hidden bg-[#eeeeee] sm:grid-cols-3">

                    <div class="bg-white px-6 py-5">

                        <p class="text-xs text-[#999999]">
                            Montant total
                        </p>

                        <p class="mt-2 text-lg font-semibold text-[#333333]">
                            <?= number_format(
                                $montantTotal,
                                0,
                                ',',
                                ' '
                            ) ?> F
                        </p>

                    </div>


                    <div class="bg-white px-6 py-5">

                        <p class="text-xs text-[#999999]">
                            Montant payé
                        </p>

                        <p class="mt-2 text-lg font-semibold text-green-600">
                            <?= number_format(
                                $montantPaye,
                                0,
                                ',',
                                ' '
                            ) ?> F
                        </p>

                    </div>


                    <div class="bg-white px-6 py-5">

                        <p class="text-xs text-[#999999]">
                            Reste à payer
                        </p>

                        <p class="mt-2 text-lg font-semibold text-orange-500">
                            <?= number_format(
                                $montantRestant,
                                0,
                                ',',
                                ' '
                            ) ?> F
                        </p>

                    </div>

                </div>


                <div class="border-t border-[#eeeeee] px-6 py-5">

                    <?php if ($statutPaiementCode === 'PAYEE'): ?>

                        <div class="flex items-center gap-3">

                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-50 text-green-600">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-green-600">
                                    Paiement effectué
                                </p>

                                <p class="mt-0.5 text-xs text-[#999999]">
                                    La commande est entièrement payée.
                                </p>
                            </div>

                        </div>

                    <?php elseif (
                        in_array(
                            $statutPaiementCode,
                            [
                                'PARTIELLE',
                                'PARTIELLEMENT_PAYEE'
                            ],
                            true
                        )
                    ): ?>

                        <div class="flex items-center gap-3">

                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-50 text-orange-500">
                                <i class="fa-solid fa-clock text-xs"></i>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-orange-500">
                                    Paiement partiel
                                </p>

                                <p class="mt-0.5 text-xs text-[#999999]">
                                    Un montant reste à payer.
                                </p>
                            </div>

                        </div>

                    <?php else: ?>

                        <div class="flex items-center gap-3">

                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-red-50 text-red-500">
                                <i class="fa-solid fa-circle-xmark text-xs"></i>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-red-500">
                                    Paiement impayé
                                </p>

                                <p class="mt-0.5 text-xs text-[#999999]">
                                    Aucun paiement complet n'a été enregistré.
                                </p>
                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- COLONNE DROITE -->
        <div class="space-y-6">


            <!-- Client -->
            <div class="rounded-2xl border border-[#eeeeee] bg-white p-6">

                <div class="mb-5 flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-50 text-orange-500"
                    >
                        <i class="fa-solid fa-user text-sm"></i>
                    </div>

                    <div>

                        <h2 class="text-base font-semibold text-[#333333]">
                            Client
                        </h2>

                        <p class="mt-1 text-xs text-[#999999]">
                            Informations du client
                        </p>

                    </div>

                </div>


                <div>

                    <p class="text-base font-semibold text-[#333333]">
                        <?= htmlspecialchars($nomClient) ?>
                    </p>

                    <?php if ($telephoneClient !== null): ?>

                        <div class="mt-3 flex items-center gap-2 text-sm text-[#777777]">

                            <i class="fa-solid fa-phone text-xs text-orange-500"></i>

                            <span>
                                <?= htmlspecialchars(
                                    (string) $telephoneClient
                                ) ?>
                            </span>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Statut -->
            <div class="rounded-2xl border border-[#eeeeee] bg-white p-6">

                <div class="mb-5 flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-50 text-orange-500"
                    >
                        <i class="fa-solid fa-list-check text-sm"></i>
                    </div>

                    <div>

                        <h2 class="text-base font-semibold text-[#333333]">
                            Statut de la commande
                        </h2>

                        <p class="mt-1 text-xs text-[#999999]">
                            État actuel
                        </p>

                    </div>

                </div>


                <div class="rounded-xl border <?= $statutClasse ?> px-4 py-3">

                    <div class="flex items-center gap-3">

                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/70">
                            <i class="fa-solid fa-circle text-[8px]"></i>
                        </div>

                        <span class="text-sm font-semibold">
                            <?= htmlspecialchars($statutLabel) ?>
                        </span>

                    </div>

                </div>

            </div>


            <!-- Actions -->
            <div class="rounded-2xl border border-[#eeeeee] bg-white p-6">

                <h2 class="text-base font-semibold text-[#333333]">
                    Actions
                </h2>

                <p class="mt-1 text-xs text-[#999999]">
                    Gestion de la commande
                </p>


                <div class="mt-5 space-y-3">

                    <?php if ($statutCommande !== 'ANNULEE'): ?>

                        <button
                            type="button"
                            onclick="ouvrirModalStatut()"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-orange-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-orange-600"
                        >
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                            Modifier le statut
                        </button>

                    <?php endif; ?>


                    <?php if ($commande->estAnnulable()): ?>

                        <button
                            type="button"
                            onclick="ouvrirModalAnnulation()"
                            class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-3 text-sm font-semibold text-red-500 transition hover:bg-red-50"
                        >
                            <i class="fa-solid fa-ban text-xs"></i>
                            Annuler la commande
                        </button>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- MODAL MODIFICATION STATUT -->
<div
    id="modalStatut"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
    aria-hidden="true"
>

    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

        <div class="flex items-center justify-between">

            <div>

                <h3 class="text-lg font-semibold text-[#333333]">
                    Modifier le statut
                </h3>

                <p class="mt-1 text-xs text-[#999999]">
                    Commande #<?= $commandeId ?>
                </p>

            </div>

            <button
                type="button"
                onclick="fermerModalStatut()"
                class="flex h-8 w-8 items-center justify-center rounded-full text-[#999999] transition hover:bg-gray-100 hover:text-[#333333]"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <form
            action="/gerant/commandes/statut"
            method="POST"
            class="mt-6"
        >

            <input
                type="hidden"
                name="id"
                value="<?= $commandeId ?>"
            >

            <label
                for="statut"
                class="mb-2 block text-sm font-medium text-[#555555]"
            >
                Nouveau statut
            </label>

            <select
                id="statut"
                name="statut"
                required
                class="w-full rounded-xl border border-[#dddddd] bg-white px-4 py-3 text-sm text-[#333333] outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
            >

                <?php foreach ($statutLabels as $code => $label): ?>

                    <?php if ($code !== 'ANNULEE'): ?>

                        <option
                            value="<?= $code ?>"
                            <?= $statutCommande === $code ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($label) ?>
                        </option>

                    <?php endif; ?>

                <?php endforeach; ?>

            </select>


            <div class="mt-6 flex gap-3">

                <button
                    type="button"
                    onclick="fermerModalStatut()"
                    class="flex-1 rounded-xl border border-[#dddddd] px-4 py-3 text-sm font-medium text-[#666666] transition hover:bg-gray-50"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="flex-1 rounded-xl bg-orange-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-orange-600"
                >
                    Enregistrer
                </button>

            </div>

        </form>

    </div>

</div>


<!-- MODAL ANNULATION -->
<div
    id="modalAnnulation"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
    aria-hidden="true"
>

    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

        <div class="flex items-center justify-between">

            <div>

                <h3 class="text-lg font-semibold text-[#333333]">
                    Annuler la commande
                </h3>

                <p class="mt-1 text-xs text-[#999999]">
                    Commande #<?= $commandeId ?>
                </p>

            </div>

            <button
                type="button"
                onclick="fermerModalAnnulation()"
                class="flex h-8 w-8 items-center justify-center rounded-full text-[#999999] transition hover:bg-gray-100 hover:text-[#333333]"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <div class="mt-6 rounded-xl border border-red-100 bg-red-50 p-4">

            <div class="flex gap-3">

                <i class="fa-solid fa-triangle-exclamation mt-0.5 text-red-500"></i>

                <p class="text-sm leading-6 text-red-600">
                    Êtes-vous sûr de vouloir annuler cette commande ?
                    Cette action entraînera la remise en stock des produits.
                </p>

            </div>

        </div>


        <form
            action="/gerant/commandes/annuler"
            method="POST"
            class="mt-6"
        >

            <input
                type="hidden"
                name="id"
                value="<?= $commandeId ?>"
            >


            <div class="flex gap-3">

                <button
                    type="button"
                    onclick="fermerModalAnnulation()"
                    class="flex-1 rounded-xl border border-[#dddddd] px-4 py-3 text-sm font-medium text-[#666666] transition hover:bg-gray-50"
                >
                    Retour
                </button>

                <button
                    type="submit"
                    class="flex-1 rounded-xl bg-red-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-600"
                >
                    Confirmer l'annulation
                </button>

            </div>

        </form>

    </div>

</div>


<script>

function ouvrirModalStatut() {

    const modal =
        document.getElementById('modalStatut');

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
}


function fermerModalStatut() {

    const modal =
        document.getElementById('modalStatut');

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


function ouvrirModalAnnulation() {

    const modal =
        document.getElementById('modalAnnulation');

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
}


function fermerModalAnnulation() {

    const modal =
        document.getElementById('modalAnnulation');

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


document.addEventListener(
    'keydown',
    function (event) {

        if (event.key !== 'Escape') {
            return;
        }

        fermerModalStatut();
        fermerModalAnnulation();
    }
);

</script>