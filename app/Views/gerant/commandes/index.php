<?php

$commandes = $commandes ?? [];
$clients = $clients ?? [];
$paiements = $paiements ?? [];
$statutActif = $statutActif ?? '';

/*
|--------------------------------------------------------------------------
| Statuts des commandes
|--------------------------------------------------------------------------
*/

$statuts = [
    'EN_ATTENTE' => [
        'label' => 'En attente',
        'classe' => 'border-orange-300 bg-orange-50 text-orange-600',
        'icone' => 'fa-regular fa-clock',
    ],
    'EN_PREPARATION' => [
        'label' => 'En préparation',
        'classe' => 'border-blue-300 bg-blue-50 text-blue-600',
        'icone' => 'fa-solid fa-utensils',
    ],
    'PRETE' => [
        'label' => 'Prête au comptoir',
        'classe' => 'border-purple-300 bg-purple-50 text-purple-600',
        'icone' => 'fa-solid fa-store',
    ],
    'RETIREE' => [
        'label' => 'Retirée',
        'classe' => 'border-green-300 bg-green-50 text-green-600',
        'icone' => 'fa-regular fa-circle-check',
    ],
    'ANNULEE' => [
        'label' => 'Annulée',
        'classe' => 'border-red-300 bg-red-50 text-red-600',
        'icone' => 'fa-regular fa-circle-xmark',
    ],
];

/*
|--------------------------------------------------------------------------
| Formatage du montant
|--------------------------------------------------------------------------
*/

$formatMontant = static function (float $montant): string {
    return number_format(
        $montant,
        0,
        ',',
        ' '
    ) . ' F';
};

/*
|--------------------------------------------------------------------------
| Date
|--------------------------------------------------------------------------
*/

$formatDate = static function (string $date): string {
    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return htmlspecialchars(
            $date,
            ENT_QUOTES,
            'UTF-8'
        );
    }

    return date(
        'd/m/Y H:i',
        $timestamp
    );
};

?>

<main class="min-h-screen bg-[#f7f7f6]">

    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->

    <div class="mx-auto max-w-[1100px] sm:px-6 lg:px-8">

        <!-- =================================================
             EN-TÊTE
        ================================================== -->





        <section
            class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-orange-900 shadow-sm">


            <div
                class="flex flex-col gap-5 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <div class="flex items-center gap-2 text-orange-500">

                        <i class="fa-solid fa-cart-shopping text-sm"></i>

                        <span
                            class="font-['DM_Sans'] text-[11px] font-bold uppercase tracking-wider">
                            Espace gérant
                        </span>

                    </div>

                    <h1
                        class="text-2xl font-bold text-white sm:text-3xl">
                        Gestion des Commandes
                    </h1>

                    <p
                        class="mt-2 max-w-2xl text-sm text-gray-300">
                        Traitez le flux de cuisine, le comptoir et les encaissements en direct.
                    </p>
                </div>




            </div>
        </section>


        <!-- =================================================
             FILTRES
        ================================================== -->

        <section
            class="mb-5 rounded-[14px] border border-[#eeeeee] bg-white p-4 shadow-sm">

            <div
                class="flex flex-col gap-3 md:flex-row md:items-center">

                <!-- Recherche -->

                <div class="relative flex-1">

                    <i
                        class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[13px] text-[#999999]"></i>

                    <input
                        type="search"
                        id="rechercheCommande"
                        placeholder="Nom, Client ou Téléphone..."
                        class="h-[46px] w-full rounded-[10px] border border-[#e5e5e5] bg-white pl-10 pr-4 font-['DM_Sans'] text-[13px] text-[#333333] outline-none transition placeholder:text-[#999999] focus:border-[#ff9900] focus:ring-2 focus:ring-[#ff9900]/10">

                </div>


                <!-- Filtre statut -->

                <div class="relative w-full md:w-[240px]">

                    <select
                        id="filtreStatut"
                        class="h-[46px] w-full appearance-none rounded-[10px] border border-[#e5e5e5] bg-white px-4 pr-10 font-['DM_Sans'] text-[13px] text-[#333333] outline-none transition focus:border-[#ff9900] focus:ring-2 focus:ring-[#ff9900]/10">

                        <option value="">
                            Tous les statuts
                        </option>

                        <?php foreach ($statuts as $code => $statut): ?>

                            <option
                                value="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>"
                                <?= $statutActif === $code ? 'selected' : '' ?>>
                                <?= htmlspecialchars($statut['label'], ENT_QUOTES, 'UTF-8') ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <i
                        class="fa-solid fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-[#555555]"></i>

                </div>

            </div>

        </section>


        <!-- =================================================
             MESSAGE
        ================================================== -->

        <?php if (!empty($message)): ?>

            <div
                class="mb-5 flex items-start gap-3 rounded-[10px] border border-red-100 bg-red-50 px-4 py-3 font-['DM_Sans'] text-[13px] text-red-700">

                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                <span>
                    <?= htmlspecialchars(
                        $message,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>

            </div>

        <?php endif; ?>


        <!-- =================================================
             TABLEAU
        ================================================== -->

        <section
            class="overflow-hidden rounded-[16px] border border-[#eeeeee] bg-white shadow-[0_4px_14px_rgba(0,0,0,0.08)]">

            <!-- En-tête du tableau -->

            <div
                class="border-b border-[#eeeeee] px-5 py-5">

                <div class="flex items-center justify-between">

                    <div>

                        <h2
                            class="font-['Inter'] text-[17px] font-extrabold text-[#222222]">
                            Liste des commandes
                        </h2>

                        <p
                            class="mt-1 font-['DM_Sans'] text-[12px] text-[#888888]">
                            Consultez et gérez les commandes en temps réel.
                        </p>

                    </div>

                    <div
                        class="hidden items-center gap-2 rounded-full bg-[#f8f8f8] px-3 py-1.5 font-['DM_Sans'] text-[11px] font-semibold text-[#777777] sm:flex">

                        <i class="fa-solid fa-receipt text-orange-500"></i>

                        <?= count($commandes) ?>
                        commande<?= count($commandes) > 1 ? 's' : '' ?>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 DESKTOP
            ================================================== -->

            <div class="hidden overflow-x-auto lg:block">

                <table class="w-full min-w-[900px]">

                    <thead>

                        <tr
                            class="border-b border-[#eeeeee] bg-[#fafafa]">

                            <th
                                class="px-5 py-4 text-left font-['DM_Sans'] text-[11px] font-bold uppercase tracking-wider text-[#777777]">
                                N° / Date
                            </th>

                            <th
                                class="px-5 py-4 text-left font-['DM_Sans'] text-[11px] font-bold uppercase tracking-wider text-[#777777]">
                                Client
                            </th>

                            <th
                                class="px-5 py-4 text-left font-['DM_Sans'] text-[11px] font-bold uppercase tracking-wider text-[#777777]">
                                Montant
                            </th>

                            <th
                                class="px-5 py-4 text-left font-['DM_Sans'] text-[11px] font-bold uppercase tracking-wider text-[#777777]">
                                Statut commande
                            </th>

                            <th
                                class="px-5 py-4 text-left font-['DM_Sans'] text-[11px] font-bold uppercase tracking-wider text-[#777777]">
                                Paiement
                            </th>

                            <th
                                class="px-5 py-4 text-right font-['DM_Sans'] text-[11px] font-bold uppercase tracking-wider text-[#777777]">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        id="tableauCommandes"
                        class="divide-y divide-[#eeeeee]">

                        <?php if (empty($commandes)): ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-16 text-center">

                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#f5f5f5] text-[#aaaaaa]">
                                        <i class="fa-solid fa-receipt text-xl"></i>
                                    </div>

                                    <p
                                        class="mt-4 font-['DM_Sans'] text-[14px] font-semibold text-[#555555]">
                                        Aucune commande trouvée
                                    </p>

                                    <p
                                        class="mt-1 font-['DM_Sans'] text-[12px] text-[#999999]">
                                        Les commandes apparaîtront ici.
                                    </p>

                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($commandes as $commande): ?>

                                <?php

                                $commandeId =
                                    (int) $commande->getId();

                                $clientId =
                                    (int) $commande->getClientId();

                                $client =
                                    $clients[$clientId] ?? null;

                                $statut =
                                    $commande->getStatut();

                                $statutInfo =
                                    $statuts[$statut]
                                    ?? [
                                        'label' => $statut,
                                        'classe' => 'border-gray-300 bg-gray-50 text-gray-600',
                                        'icone' => 'fa-regular fa-circle',
                                    ];

                                $paiement =
                                    $paiements[$commandeId] ?? null;

                                $montantTotal =
                                    (float) $commande->getMontantTotal();

                                $montantPaye =
                                    $paiement !== null
                                    ? (float) ($paiement->montant_paye ?? 0)
                                    : 0;

                                $montantRestant =
                                    $paiement !== null
                                    ? (float) (
                                        $paiement->montant_restant
                                        ?? max(
                                            0,
                                            $montantTotal - $montantPaye
                                        )
                                    )
                                    : max(
                                        0,
                                        $montantTotal - $montantPaye
                                    );

                                $statutPaiement = strtoupper(
                                    (string) (
                                        $paiement->statut_paiement
                                        ?? 'IMPAYEE'
                                    )
                                );

                                /*
 * Si le montant restant est nul,
 * la commande est considérée comme payée.
 */
                                if (
                                    $montantRestant <= 0
                                    || $montantPaye >= $montantTotal
                                ) {
                                    $statutPaiement = 'PAYEE';
                                }




                                if ($statutPaiement === 'PAYEE') {

                                    $paiementLabel =
                                        'Payée ('
                                        . number_format(
                                            $montantPaye,
                                            0,
                                            ',',
                                            ' '
                                        )
                                        . ' F)';

                                    $paiementClasse =
                                        'border-green-300 bg-green-50 text-green-600';

                                    $paiementIcone =
                                        'fa-regular fa-circle-check';
                                } elseif (
                                    in_array(
                                        $statutPaiement,
                                        [
                                            'PARTIELLE',
                                            'PARTIELLEMENT_PAYEE'
                                        ],
                                        true
                                    )
                                ) {

                                    $paiementLabel =
                                        'Partiel ('
                                        . number_format(
                                            $montantPaye,
                                            0,
                                            ',',
                                            ' '
                                        )
                                        . ' / '
                                        . number_format(
                                            $montantTotal,
                                            0,
                                            ',',
                                            ' '
                                        )
                                        . ' F)';

                                    $paiementClasse =
                                        'border-orange-300 bg-orange-50 text-orange-600';

                                    $paiementIcone =
                                        'fa-solid fa-circle-half-stroke';
                                } else {

                                    $paiementLabel =
                                        'Impayée (0 / '
                                        . number_format(
                                            $montantTotal,
                                            0,
                                            ',',
                                            ' '
                                        )
                                        . ' F)';

                                    $paiementClasse =
                                        'border-red-300 bg-red-50 text-red-500';

                                    $paiementIcone =
                                        'fa-regular fa-circle-xmark';
                                }

                                /*
                                |--------------------------------------------------------------------------
                                | Données utilisées par la recherche JS
                                |--------------------------------------------------------------------------
                                */

                                $nomClient = '';

                                if ($client !== null) {

                                    $nomClient =
                                        trim(
                                            ($client->getPrenom() ?? '')
                                                . ' '
                                                . ($client->getNom() ?? '')
                                        );
                                }

                                $telephoneClient = '';

                                if ($client !== null) {

                                    $telephoneClient =
                                        $client->getTelephone()
                                        ?? '';
                                }

                                $texteRecherche =
                                    strtolower(
                                        $nomClient
                                            . ' '
                                            . $telephoneClient
                                            . ' '
                                            . $commandeId
                                    );

                                ?>

                                <tr
                                    class="ligne-commande transition hover:bg-orange-50/30"
                                    data-recherche="<?= htmlspecialchars(
                                                        $texteRecherche,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>"
                                    data-statut="<?= htmlspecialchars(
                                                        $statut,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>">

                                    <!-- =================================
                                         COMMANDE
                                    ================================== -->

                                    <td class="px-5 py-5">

                                        <div>

                                            <p
                                                class="font-['DM_Sans'] text-[13px] font-extrabold text-[#222222]">
                                                CMD-2026-<?= str_pad(
                                                                (string) $commandeId,
                                                                3,
                                                                '0',
                                                                STR_PAD_LEFT
                                                            ) ?>
                                            </p>

                                            <p
                                                class="mt-1 font-['DM_Sans'] text-[11px] text-[#999999]">
                                                <?= htmlspecialchars(
                                                    $formatDate(
                                                        $commande->getDateCommande()
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </p>

                                        </div>

                                    </td>


                                    <!-- =================================
                                         CLIENT
                                    ================================== -->

                                    <td class="px-5 py-5">

                                        <?php if ($client !== null): ?>

                                            <p
                                                class="font-['DM_Sans'] text-[13px] font-bold text-[#222222]">
                                                <?= htmlspecialchars(
                                                    $nomClient,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </p>

                                            <?php if ($telephoneClient !== ''): ?>

                                                <p
                                                    class="mt-1 font-['DM_Sans'] text-[11px] text-[#999999]">
                                                    <?= htmlspecialchars(
                                                        $telephoneClient,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </p>

                                            <?php endif; ?>

                                        <?php else: ?>

                                            <p
                                                class="font-['DM_Sans'] text-[12px] text-[#999999]">
                                                Client #<?= $clientId ?>
                                            </p>

                                        <?php endif; ?>

                                    </td>


                                    <!-- =================================
                                         MONTANT
                                    ================================== -->

                                    <td class="px-5 py-5">

                                        <span
                                            class="font-['DM_Sans'] text-[13px] font-bold text-[#222222]">
                                            <?= $formatMontant(
                                                $montantTotal
                                            ) ?>
                                        </span>

                                    </td>


                                    <!-- =================================
                                         STATUT
                                    ================================== -->

                                    <td class="px-5 py-5">

                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 font-['DM_Sans'] text-[10px] font-semibold <?= $statutInfo['classe'] ?>">

                                            <i
                                                class="<?= $statutInfo['icone'] ?> text-[9px]"></i>

                                            <?= htmlspecialchars(
                                                $statutInfo['label'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- =================================
                                         PAIEMENT
                                    ================================== -->

                                    <td class="px-5 py-5">

                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 font-['DM_Sans'] text-[10px] font-semibold <?= $paiementClasse ?>">

                                            <i
                                                class="<?= $paiementIcone ?> text-[9px]"></i>

                                            <?= htmlspecialchars(
                                                $paiementLabel,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- =================================
                                         ACTIONS
                                    ================================== -->

                                    <td class="px-5 py-5">

                                        <div
                                            class="flex items-center justify-end gap-1">

                                            <!-- Voir -->

                                            <a
                                                href="/gerant/commande/show/<?= $commandeId ?>"
                                                title="Voir le détail"
                                                class="flex h-9 w-9 items-center justify-center rounded-full text-[#777777] transition hover:bg-orange-50 hover:text-orange-500">
                                                <i class="fa-solid fa-eye text-[13px]"></i>
                                            </a>

                                            <!-- Modifier statut -->

                                            <?php if (
                                                $statut !== 'RETIREE'
                                                && $statut !== 'ANNULEE'
                                            ): ?>

                                                <button
                                                    type="button"
                                                    title="Modifier le statut"
                                                    onclick="ouvrirStatutCommande(
                                                        <?= $commandeId ?>,
                                                        '<?= htmlspecialchars(
                                                                $statut,
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ) ?>'
                                                    )"
                                                    class="flex h-9 w-9 items-center justify-center rounded-full text-[#777777] transition hover:bg-blue-50 hover:text-blue-500">

                                                    <i
                                                        class="fa-solid fa-pen-to-square text-[13px]"></i>

                                                </button>

                                            <?php endif; ?>


                                            <!-- Annuler -->

                                            <?php if (
                                                $statut === 'EN_ATTENTE'
                                                || $statut === 'EN_PREPARATION'
                                            ): ?>

                                                <button
                                                    type="button"
                                                    title="Annuler la commande"
                                                    onclick="ouvrirAnnulationCommande(
                                                        <?= $commandeId ?>
                                                    )"
                                                    class="flex h-9 w-9 items-center justify-center rounded-full text-[#777777] transition hover:bg-red-50 hover:text-red-500">

                                                    <i
                                                        class="fa-solid fa-xmark text-[14px]"></i>

                                                </button>

                                            <?php endif; ?>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>


            <!-- =================================================
                 MOBILE
            ================================================== -->

            <div class="divide-y divide-[#eeeeee] lg:hidden">

                <?php if (empty($commandes)): ?>

                    <div class="px-5 py-16 text-center">

                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#f5f5f5] text-[#aaaaaa]">

                            <i class="fa-solid fa-receipt text-xl"></i>

                        </div>

                        <p
                            class="mt-4 font-['DM_Sans'] text-[14px] font-semibold text-[#555555]">
                            Aucune commande trouvée
                        </p>

                    </div>

                <?php else: ?>

                    <?php foreach ($commandes as $commande): ?>

                        <?php

                        $commandeId =
                            (int) $commande->getId();

                        $clientId =
                            (int) $commande->getClientId();

                        $client =
                            $clients[$clientId] ?? null;

                        $statut =
                            $commande->getStatut();

                        $statutInfo =
                            $statuts[$statut]
                            ?? [
                                'label' => $statut,
                                'classe' => 'border-gray-300 bg-gray-50 text-gray-600',
                                'icone' => 'fa-regular fa-circle',
                            ];

                        $paiement =
                            $paiements[$commandeId] ?? null;

                        $montantTotal =
                            (float) $commande->getMontantTotal();

                        $montantPaye =
                            $paiement !== null
                            ? (float) (
                                $paiement->montant_paye
                                ?? 0
                            )
                            : 0;

                        /*
                        |--------------------------------------------------------------------------
                        | CORRECTION
                        |--------------------------------------------------------------------------
                        | On vérifie d'abord que $paiement n'est pas null
                        | avant d'accéder à statut_paiement.
                        */

                        $montantPaye =
                            $paiement !== null
                            ? (float) ($paiement->montant_paye ?? 0)
                            : 0;

                        $montantRestant =
                            $paiement !== null
                            ? (float) (
                                $paiement->montant_restant
                                ?? max(
                                    0,
                                    $montantTotal - $montantPaye
                                )
                            )
                            : max(
                                0,
                                $montantTotal - $montantPaye
                            );

                        $statutPaiement = strtoupper(
                            (string) (
                                $paiement->statut_paiement
                                ?? 'IMPAYEE'
                            )
                        );

                        if (
                            $montantRestant <= 0
                            || $montantPaye >= $montantTotal
                        ) {
                            $statutPaiement = 'PAYEE';
                        }

                        if ($statutPaiement === 'PAYEE') {
                            $paiementLabel = 'Payée';
                            $paiementClasse =
                                'border-green-300 bg-green-50 text-green-600';
                        } elseif (
                            in_array(
                                $statutPaiement,
                                [
                                    'PARTIELLE',
                                    'PARTIELLEMENT_PAYEE'
                                ],
                                true
                            )
                        ) {
                            $paiementLabel = 'Partiel';
                            $paiementClasse =
                                'border-orange-300 bg-orange-50 text-orange-600';
                        } else {
                            $paiementLabel = 'Impayée';
                            $paiementClasse =
                                'border-red-300 bg-red-50 text-red-500';
                        }


                        $nomClient = '';

                        if ($client !== null) {

                            $nomClient =
                                trim(
                                    ($client->getPrenom() ?? '')
                                        . ' '
                                        . ($client->getNom() ?? '')
                                );
                        }

                        ?>

                        <div
                            class="ligne-commande p-4"
                            data-recherche="<?= htmlspecialchars(
                                                strtolower(
                                                    $nomClient
                                                        . ' '
                                                        . ($client?->getTelephone() ?? '')
                                                        . ' '
                                                        . $commandeId
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                            data-statut="<?= htmlspecialchars(
                                                $statut,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>">

                            <div
                                class="flex items-start justify-between gap-4">

                                <div class="min-w-0">

                                    <p
                                        class="font-['DM_Sans'] text-[13px] font-extrabold text-[#222222]">
                                        CMD-2026-<?= str_pad(
                                                        (string) $commandeId,
                                                        3,
                                                        '0',
                                                        STR_PAD_LEFT
                                                    ) ?>
                                    </p>

                                    <p
                                        class="mt-1 font-['DM_Sans'] text-[11px] text-[#999999]">
                                        <?= htmlspecialchars(
                                            $formatDate(
                                                $commande->getDateCommande()
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </p>

                                </div>


                                <span
                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1 font-['DM_Sans'] text-[9px] font-semibold <?= $statutInfo['classe'] ?>">

                                    <i
                                        class="<?= $statutInfo['icone'] ?>"></i>

                                    <?= htmlspecialchars(
                                        $statutInfo['label'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </div>


                            <div class="mt-4">

                                <p
                                    class="font-['DM_Sans'] text-[13px] font-bold text-[#222222]">
                                    <?= htmlspecialchars(
                                        $nomClient !== ''
                                            ? $nomClient
                                            : 'Client #' . $clientId,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </p>

                                <?php if ($client !== null && $client->getTelephone()): ?>

                                    <p
                                        class="mt-1 font-['DM_Sans'] text-[11px] text-[#999999]">
                                        <?= htmlspecialchars(
                                            $client->getTelephone(),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </p>

                                <?php endif; ?>

                            </div>


                            <div
                                class="mt-4 flex items-center justify-between">

                                <div>

                                    <p
                                        class="font-['DM_Sans'] text-[11px] text-[#999999]">
                                        Montant
                                    </p>

                                    <p
                                        class="mt-1 font-['DM_Sans'] text-[14px] font-extrabold text-[#222222]">
                                        <?= $formatMontant(
                                            $montantTotal
                                        ) ?>
                                    </p>

                                </div>


                                <span
                                    class="inline-flex items-center rounded-full border px-3 py-1.5 font-['DM_Sans'] text-[10px] font-semibold <?= $paiementClasse ?>">

                                    <?= htmlspecialchars(
                                        $paiementLabel,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </div>


                            <div
                                class="mt-4 flex justify-end gap-1">

                                <!-- Voir -->

                                <a
                                    href="/gerant/commande/show/<?= $commandeId ?>"
                                    title="Voir"
                                    class="flex h-9 w-9 items-center justify-center rounded-full text-[#777777] transition hover:bg-orange-50 hover:text-orange-500">

                                    <i class="fa-solid fa-eye"></i>

                                </a>


                                <!-- Statut -->

                                <?php if (
                                    $statut !== 'RETIREE'
                                    && $statut !== 'ANNULEE'
                                ): ?>

                                    <button
                                        type="button"
                                        title="Modifier le statut"
                                        onclick="ouvrirStatutCommande(
                                            <?= $commandeId ?>,
                                            '<?= htmlspecialchars(
                                                    $statut,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>'
                                        )"
                                        class="flex h-9 w-9 items-center justify-center rounded-full text-[#777777] transition hover:bg-blue-50 hover:text-blue-500">

                                        <i class="fa-solid fa-pen-to-square"></i>

                                    </button>

                                <?php endif; ?>


                                <!-- Annulation -->

                                <?php if (
                                    $statut === 'EN_ATTENTE'
                                    || $statut === 'EN_PREPARATION'
                                ): ?>

                                    <button
                                        type="button"
                                        title="Annuler"
                                        onclick="ouvrirAnnulationCommande(
                                            <?= $commandeId ?>
                                        )"
                                        class="flex h-9 w-9 items-center justify-center rounded-full text-[#777777] transition hover:bg-red-50 hover:text-red-500">

                                        <i class="fa-solid fa-xmark"></i>

                                    </button>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </section>

    </div>

</main>


<!-- =========================================================
     MODAL MODIFICATION STATUT
========================================================== -->

<div
    id="modalStatutCommande"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 px-4 backdrop-blur-[2px]">

    <div
        id="modalStatutCommandeBox"
        class="w-full max-w-[430px] scale-95 rounded-[16px] bg-white p-6 shadow-2xl transition-transform">

        <div class="flex items-start justify-between gap-4">

            <div>

                <h2
                    class="font-['Inter'] text-[17px] font-extrabold text-[#222222]">
                    Modifier le statut
                </h2>

                <p
                    class="mt-1 font-['DM_Sans'] text-[12px] text-[#888888]">
                    Choisissez le nouvel état de la commande.
                </p>

            </div>


            <button
                type="button"
                onclick="fermerStatutCommande()"
                class="flex h-8 w-8 items-center justify-center rounded-full text-[#888888] transition hover:bg-gray-100 hover:text-gray-700">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>


        <form
            id="formStatutCommande"
            method="POST"
            action="/gerant/commandes/statut"
            class="mt-6">

            <input
                type="hidden"
                name="id"
                id="statutCommandeId"
                value="">


            <label
                for="nouveauStatut"
                class="mb-2 block font-['DM_Sans'] text-[12px] font-semibold text-[#555555]">
                Nouveau statut
            </label>


            <div class="relative">

                <select
                    id="nouveauStatut"
                    name="statut"
                    required
                    class="h-11 w-full appearance-none rounded-[10px] border border-[#dddddd] bg-white px-4 pr-10 font-['DM_Sans'] text-[13px] text-[#333333] outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100">

                    <option value="EN_ATTENTE">
                        En attente
                    </option>

                    <option value="EN_PREPARATION">
                        En préparation
                    </option>

                    <option value="PRETE">
                        Prête au comptoir
                    </option>

                    <option value="RETIREE">
                        Retirée
                    </option>

                </select>


                <i
                    class="fa-solid fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-[#777777]"></i>

            </div>


            <div
                class="mt-6 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="fermerStatutCommande()"
                    class="h-10 rounded-[9px] border border-[#dddddd] bg-white px-5 font-['DM_Sans'] text-[12px] font-bold text-[#555555] transition hover:bg-[#f7f7f7]">
                    Annuler
                </button>


                <button
                    type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-[9px] bg-[#ff9800] px-5 font-['DM_Sans'] text-[12px] font-bold text-white transition hover:bg-[#e88900]">

                    <i class="fa-solid fa-check"></i>

                    Enregistrer

                </button>

            </div>

        </form>

    </div>

</div>


<!-- =========================================================
     MODAL ANNULATION
========================================================== -->

<div
    id="modalAnnulationCommande"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 px-4 backdrop-blur-[2px]">

    <div
        id="modalAnnulationCommandeBox"
        class="w-full max-w-[430px] scale-95 rounded-[16px] bg-white p-6 shadow-2xl transition-transform">

        <div class="flex items-start gap-4">

            <div
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-500">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <div>

                <h2
                    class="font-['Inter'] text-[17px] font-extrabold text-[#222222]">
                    Annuler la commande ?
                </h2>

                <p
                    class="mt-1 font-['DM_Sans'] text-[12px] leading-5 text-[#888888]">
                    Cette action annulera la commande et le stock des articles sera restauré.
                </p>

            </div>

        </div>


        <form
            id="formAnnulationCommande"
            method="POST"
            action="/gerant/commandes/annuler"
            class="mt-6">

            <input
                type="hidden"
                name="id"
                id="annulationCommandeId"
                value="">


            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    onclick="fermerAnnulationCommande()"
                    class="h-10 rounded-[9px] border border-[#dddddd] bg-white px-5 font-['DM_Sans'] text-[12px] font-bold text-[#555555] transition hover:bg-[#f7f7f7]">
                    Non, conserver
                </button>


                <button
                    type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-[9px] bg-red-500 px-5 font-['DM_Sans'] text-[12px] font-bold text-white transition hover:bg-red-600">

                    <i class="fa-solid fa-xmark"></i>

                    Annuler la commande

                </button>

            </div>

        </form>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        /*
        |--------------------------------------------------------------------------
        | RECHERCHE
        |--------------------------------------------------------------------------
        */

        const recherche =
            document.getElementById('rechercheCommande');

        const filtre =
            document.getElementById('filtreStatut');

        const lignes =
            document.querySelectorAll('.ligne-commande');


        function filtrerCommandes() {

            const terme =
                recherche ?
                recherche.value
                .trim()
                .toLowerCase() :
                '';

            const statut =
                filtre ?
                filtre.value :
                '';


            lignes.forEach(function(ligne) {

                const texte =
                    ligne.dataset.recherche || '';

                const statutLigne =
                    ligne.dataset.statut || '';


                const correspondRecherche =
                    texte.includes(terme);

                const correspondStatut =
                    statut === '' ||
                    statutLigne === statut;


                ligne.style.display =
                    correspondRecherche &&
                    correspondStatut ?
                    '' :
                    'none';

            });

        }


        if (recherche) {

            recherche.addEventListener(
                'input',
                filtrerCommandes
            );

        }


        if (filtre) {
            filtre.addEventListener(
                'change',
                function() {
                    const valeur =
                        this.value;

                    if (valeur === '') {
                        window.location.href =
                            '/gerant/commandes';
                        return;
                    }

                    window.location.href =
                        '/gerant/commandes/statut/' +
                        encodeURIComponent(valeur);
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FERMETURE DES MODALES AVEC ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function(event) {

                if (event.key !== 'Escape') {
                    return;
                }

                fermerStatutCommande();
                fermerAnnulationCommande();

            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | MODIFICATION DU STATUT
    |--------------------------------------------------------------------------
    */

    function ouvrirStatutCommande(
        commandeId,
        statutActuel
    ) {

        const modal =
            document.getElementById(
                'modalStatutCommande'
            );

        const box =
            document.getElementById(
                'modalStatutCommandeBox'
            );

        const input =
            document.getElementById(
                'statutCommandeId'
            );

        const select =
            document.getElementById(
                'nouveauStatut'
            );


        if (
            !modal ||
            !box ||
            !input ||
            !select
        ) {
            return;
        }


        input.value =
            commandeId;

        select.value =
            statutActuel;


        modal.classList.remove('hidden');

        modal.classList.add('flex');

        document.body.classList.add(
            'overflow-hidden'
        );


        requestAnimationFrame(function() {

            box.classList.remove(
                'scale-95'
            );

            box.classList.add(
                'scale-100'
            );

        });

    }


    function fermerStatutCommande() {

        const modal =
            document.getElementById(
                'modalStatutCommande'
            );

        const box =
            document.getElementById(
                'modalStatutCommandeBox'
            );


        if (!modal || !box) {
            return;
        }


        box.classList.remove(
            'scale-100'
        );

        box.classList.add(
            'scale-95'
        );


        setTimeout(function() {

            modal.classList.add(
                'hidden'
            );

            modal.classList.remove(
                'flex'
            );

            document.body.classList.remove(
                'overflow-hidden'
            );

        }, 150);

    }


    /*
    |--------------------------------------------------------------------------
    | ANNULATION
    |--------------------------------------------------------------------------
    */

    function ouvrirAnnulationCommande(
        commandeId
    ) {

        const modal =
            document.getElementById(
                'modalAnnulationCommande'
            );

        const box =
            document.getElementById(
                'modalAnnulationCommandeBox'
            );

        const input =
            document.getElementById(
                'annulationCommandeId'
            );


        if (
            !modal ||
            !box ||
            !input
        ) {
            return;
        }


        input.value =
            commandeId;


        modal.classList.remove(
            'hidden'
        );

        modal.classList.add(
            'flex'
        );


        document.body.classList.add(
            'overflow-hidden'
        );


        requestAnimationFrame(function() {

            box.classList.remove(
                'scale-95'
            );

            box.classList.add(
                'scale-100'
            );

        });

    }


    function fermerAnnulationCommande() {

        const modal =
            document.getElementById(
                'modalAnnulationCommande'
            );

        const box =
            document.getElementById(
                'modalAnnulationCommandeBox'
            );


        if (!modal || !box) {
            return;
        }


        box.classList.remove(
            'scale-100'
        );

        box.classList.add(
            'scale-95'
        );


        setTimeout(function() {

            modal.classList.add(
                'hidden'
            );

            modal.classList.remove(
                'flex'
            );

            document.body.classList.remove(
                'overflow-hidden'
            );

        }, 150);

    }
</script>