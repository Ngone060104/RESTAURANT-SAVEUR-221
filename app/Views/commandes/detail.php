<?php

$commande = $commande ?? null;
$lignes = $lignes ?? [];
$paiements = $paiements ?? [];
$statutPaiement = $statutPaiement ?? null;

if ($commande === null) {
    return;
}

/*
|--------------------------------------------------------------------------
| Helpers
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

$escape = static function ($value): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
};

/*
|--------------------------------------------------------------------------
| Informations commande
|--------------------------------------------------------------------------
*/

$statut = $commande->getStatut();
$commandeId = $commande->getId();
$total = $commande->getMontantTotal();

/*
|--------------------------------------------------------------------------
| Statut commande
|--------------------------------------------------------------------------
*/

$statutLabel = match ($statut) {
    'EN_ATTENTE' => 'En attente',
    'EN_PREPARATION' => 'En préparation',
    'PRETE' => 'Prête',
    'RETIREE' => 'Retirée',
    'ANNULEE' => 'Annulée',
    default => $statut,
};

$statutClass = match ($statut) {
    'EN_ATTENTE' =>
        'bg-amber-50 text-amber-700 border-amber-200',

    'EN_PREPARATION' =>
        'bg-blue-50 text-blue-700 border-blue-200',

    'PRETE' =>
        'bg-emerald-50 text-emerald-700 border-emerald-200',

    'RETIREE' =>
        'bg-emerald-50 text-emerald-700 border-emerald-200',

    'ANNULEE' =>
        'bg-red-50 text-red-600 border-red-200',

    default =>
        'bg-stone-50 text-stone-700 border-stone-200',
};

$statutIcon = match ($statut) {
    'ANNULEE' => 'fa-circle-xmark',
    'RETIREE' => 'fa-circle-check',
    'PRETE' => 'fa-bag-shopping',
    'EN_PREPARATION' => 'fa-fire-burner',
    default => 'fa-clock',
};

/*
|--------------------------------------------------------------------------
| Paiement
|--------------------------------------------------------------------------
*/

$montantPaye = 0.0;
$montantRestant = $total;
$statutPaiementLabel = 'Impayée';

/*
 * La vue_statut_paiement fournit normalement :
 * - montant_paye
 * - montant_restant
 * - statut_paiement
 */
if ($statutPaiement !== null) {
    $montantPaye = (float) (
        $statutPaiement->montant_paye
        ?? 0
    );

    $montantRestant = (float) (
        $statutPaiement->montant_restant
        ?? max(0, $total - $montantPaye)
    );

    $statutPaiementCode = strtoupper(
        (string) (
            $statutPaiement->statut_paiement
            ?? 'IMPAYEE'
        )
    );

    $statutPaiementLabel = match ($statutPaiementCode) {
        'PAYEE' => 'Payé',
        'PARTIELLE' => 'Paiement partiel',
        'IMPAYEE' => 'Impayée',
        default => ucfirst(
            strtolower(
                str_replace('_', ' ', $statutPaiementCode)
            )
        ),
    };
}

/*
 * Protection contre les éventuels petits écarts
 * de calcul décimal.
 */
if ($montantRestant < 0) {
    $montantRestant = 0;
}

if ($montantPaye > $total) {
    $montantPaye = $total;
}

$commandePayee = $montantRestant <= 0;

/*
|--------------------------------------------------------------------------
| Client
|--------------------------------------------------------------------------
|
| Si ton contrôleur envoie $client, on l'utilise.
| Sinon, on évite de provoquer une erreur PHP.
|--------------------------------------------------------------------------
*/

$client = $client ?? null;

$nomClient = '';
$telephoneClient = '';
$emailClient = '';
$adresseClient = '';

if ($client !== null) {
    $nomClient = method_exists($client, 'getNomComplet')
        ? $client->getNomComplet()
        : trim(
            ($client->getPrenom() ?? '') . ' ' .
            ($client->getNom() ?? '')
        );

    if (method_exists($client, 'getTelephone')) {
        $telephoneClient = $client->getTelephone();
    }

    if (method_exists($client, 'getEmail')) {
        $emailClient = $client->getEmail();
    }

    if (method_exists($client, 'getAdresse')) {
        $adresseClient = $client->getAdresse();
    }
}

?>

<div class="min-h-screen bg-stone-50 py-8 sm:py-10">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- =====================================================
             RETOUR + STATUT
        ====================================================== -->

        <div class="flex items-center justify-between gap-4 mb-6">

            <a
                href="/mes-commandes"
                class="inline-flex items-center gap-3 text-stone-600 hover:text-orange-600 font-semibold transition"
            >
                <i class="fa-solid fa-arrow-left"></i>

                <span>
                    Retour à mes commandes
                </span>
            </a>

            <span
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full border text-xs sm:text-sm font-extrabold <?= $statutClass ?>"
            >
                <i class="fa-solid <?= $statutIcon ?>"></i>

                <?= $escape($statutLabel) ?>
            </span>

        </div>


        <!-- =====================================================
             CARTE PRINCIPALE
        ====================================================== -->

        <section
            class="bg-white rounded-3xl border border-stone-200 shadow-sm p-5 sm:p-7 md:p-8"
        >

            <!-- =================================================
                 EN-TÊTE COMMANDE
            ================================================== -->

            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 pb-7 border-b border-stone-200"
            >

                <div>

                    <div class="flex flex-wrap items-center gap-4">

                        <h1
                            class="text-3xl md:text-4xl font-black text-stone-900"
                        >
                            Commande #<?= $commandeId ?>
                        </h1>

                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full border text-xs sm:text-sm font-extrabold <?= $statutClass ?>"
                        >
                            <i class="fa-solid <?= $statutIcon ?>"></i>

                            <?= $escape($statutLabel) ?>
                        </span>

                    </div>

                    <p class="mt-4 text-sm text-stone-500">

                        Date :

                        <span class="font-semibold text-stone-700">
                            <?= $escape(
                                $formatDate(
                                    $commande->getDateCommande()
                                )
                            ) ?>
                        </span>

                    </p>

                    <!--
                        Le mode de retrait sera affiché ici
                        lorsque le champ sera disponible dans
                        le modèle Commande.
                    -->

                </div>


                <!-- Montant -->

                <div class="md:text-right">

                    <p
                        class="text-sm font-bold text-stone-500 uppercase tracking-wide"
                    >
                        Montant
                    </p>

                    <p
                        class="text-3xl md:text-4xl font-black text-orange-600 mt-1"
                    >
                        <?= $formatPrix($total) ?>
                    </p>

                </div>

            </div>


            <!-- =================================================
                 INFORMATIONS CLIENT
            ================================================== -->

            <?php if ($client !== null): ?>

                <div class="mt-7">

                    <div
                        class="rounded-2xl bg-stone-50 p-5 sm:p-6"
                    >

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-6"
                        >

                            <!-- Coordonnées client -->

                            <div>

                                <p
                                    class="text-xs font-extrabold uppercase tracking-wide text-stone-400"
                                >
                                    Coordonnées client
                                </p>

                                <h2
                                    class="mt-2 text-base sm:text-lg font-extrabold text-stone-900"
                                >
                                    <?= $escape($nomClient) ?>
                                </h2>

                                <?php if ($telephoneClient !== ''): ?>

                                    <p class="mt-1 text-sm text-stone-500">
                                        <?= $escape($telephoneClient) ?>
                                    </p>

                                <?php endif; ?>

                                <?php if ($emailClient !== ''): ?>

                                    <p class="mt-1 text-sm text-stone-500">
                                        <?= $escape($emailClient) ?>
                                    </p>

                                <?php endif; ?>

                            </div>


                            <!-- Lieu de retrait -->

                            <div>

                                <p
                                    class="text-xs font-extrabold uppercase tracking-wide text-stone-400"
                                >
                                    Lieu de retrait & notes
                                </p>

                                <h2
                                    class="mt-2 text-base font-extrabold text-stone-900"
                                >
                                    Restaurant Saveur 221 (Dakar)
                                </h2>

                                <?php if ($adresseClient !== ''): ?>

                                    <p class="mt-1 text-sm text-stone-500">
                                        Adresse client :
                                        <?= $escape($adresseClient) ?>
                                    </p>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 ARTICLES
            ================================================== -->

            <div class="border-t border-stone-200 mt-7 pt-7">

                <h2
                    class="text-sm font-extrabold uppercase tracking-wide text-stone-700 mb-5"
                >
                    Articles & lignes de la commande
                </h2>


                <div class="space-y-4">

                    <?php if (empty($lignes)): ?>

                        <div
                            class="rounded-2xl bg-stone-50 border border-stone-200 p-6 text-center"
                        >
                            <p class="text-stone-500">
                                Aucun article trouvé pour cette commande.
                            </p>
                        </div>

                    <?php else: ?>

                        <?php foreach ($lignes as $ligne): ?>

                            <?php

                            $nomProduit =
                                $ligne->getProduitLibelle()
                                ?? 'Produit';

                            $quantite =
                                $ligne->getQuantite();

                            $prixUnitaire =
                                $ligne->getPrixUnitaire();

                            $montantLigne =
                                $ligne->getMontantLigne();

                            $imageProduit = null;

                            if (
                                method_exists(
                                    $ligne,
                                    'getProduitImage'
                                )
                            ) {
                                $imageProduit =
                                    $ligne->getProduitImage();
                            }

                            /*
                             * IMPORTANT :
                             * On utilise directement le chemin
                             * enregistré en base.
                             */
                            $imageUrl = null;

                            if (
                                !empty($imageProduit)
                            ) {
                                $imageUrl =
                                    '/' .
                                    ltrim(
                                        $imageProduit,
                                        '/'
                                    );
                            }

                            ?>

                            <div
                                class="flex items-center gap-4 rounded-2xl border border-stone-200 p-4"
                            >

                                <!-- IMAGE PRODUIT -->

                                <div
                                    class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden bg-stone-100 shrink-0"
                                >

                                    <?php if ($imageUrl !== null): ?>

                                        <img
                                            src="<?= $escape($imageUrl) ?>"
                                            alt="<?= $escape($nomProduit) ?>"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                        >

                                    <?php else: ?>

                                        <div
                                            class="w-full h-full flex items-center justify-center bg-orange-50 text-orange-500"
                                        >
                                            <i
                                                class="fa-solid fa-utensils text-xl"
                                            ></i>
                                        </div>

                                    <?php endif; ?>

                                </div>


                                <!-- PRODUIT -->

                                <div
                                    class="flex-1 min-w-0"
                                >

                                    <h3
                                        class="font-extrabold text-stone-900"
                                    >
                                        <?= $escape($nomProduit) ?>
                                    </h3>

                                    <p
                                        class="text-sm text-stone-500 mt-1"
                                    >
                                        Prix unitaire :
                                        <?= $formatPrix($prixUnitaire) ?>
                                    </p>

                                </div>


                                <!-- QUANTITÉ + TOTAL LIGNE -->

                                <div
                                    class="text-right shrink-0"
                                >

                                    <p
                                        class="text-sm text-stone-400 font-semibold"
                                    >
                                        <?= $quantite ?> ×
                                    </p>

                                    <p
                                        class="text-lg font-black text-stone-900 mt-1"
                                    >
                                        <?= $formatPrix($montantLigne) ?>
                                    </p>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </div>


            <!-- =================================================
                 PAIEMENTS
            ================================================== -->

            <div
                class="border-t border-stone-200 mt-8 pt-8"
            >

                <h2
                    class="flex items-center gap-3 text-sm font-extrabold uppercase tracking-wide text-stone-700 mb-5"
                >
                    <i
                        class="fa-solid fa-wallet text-red-500"
                    ></i>

                    Paiements enregistrés
                    <?php if (!empty($paiements)): ?>
                        (<?= count($paiements) ?>)
                    <?php endif; ?>
                </h2>


                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-6"
                >

                    <!-- Liste des paiements -->

                    <div
                        class="rounded-2xl bg-stone-50 p-5"
                    >

                        <?php if (empty($paiements)): ?>

                            <h3
                                class="text-lg font-extrabold text-stone-900"
                            >
                                Paiement de la commande
                            </h3>

                            <p
                                class="mt-3 text-sm text-stone-500"
                            >
                                Aucun paiement détaillé n'est actuellement
                                associé à cette commande.
                            </p>

                        <?php else: ?>

                            <div class="space-y-4">

                                <?php foreach ($paiements as $paiement): ?>

                                    <?php

                                    $montantPaiement =
                                        method_exists(
                                            $paiement,
                                            'getMontant'
                                        )
                                            ? $paiement->getMontant()
                                            : 0;

                                    $datePaiement =
                                        method_exists(
                                            $paiement,
                                            'getDatePaiement'
                                        )
                                            ? $paiement->getDatePaiement()
                                            : null;

                                    ?>

                                    <div
                                        class="flex items-center justify-between gap-4"
                                    >

                                        <div>

                                            <p
                                                class="font-extrabold text-stone-900"
                                            >
                                                Paiement de la commande
                                            </p>

                                            <?php if ($datePaiement): ?>

                                                <p
                                                    class="mt-1 text-sm text-stone-500"
                                                >
                                                    <?= $escape(
                                                        $formatDate(
                                                            $datePaiement
                                                        )
                                                    ) ?>
                                                </p>

                                            <?php endif; ?>

                                        </div>

                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-600 text-xs font-extrabold whitespace-nowrap"
                                        >
                                            <i
                                                class="fa-solid fa-circle-check"
                                            ></i>

                                            <?= $formatPrix(
                                                (float) $montantPaiement
                                            ) ?>
                                        </span>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- Résumé paiement -->

                    <div
                        class="rounded-2xl bg-stone-50 p-5 sm:p-6"
                    >

                        <!-- Total -->

                        <div
                            class="flex items-center justify-between gap-4"
                        >

                            <span class="text-stone-600">
                                Montant total de la commande :
                            </span>

                            <span
                                class="font-black text-stone-900 whitespace-nowrap"
                            >
                                <?= $formatPrix($total) ?>
                            </span>

                        </div>


                        <!-- Payé -->

                        <div
                            class="flex items-center justify-between gap-4 mt-4 pt-4 border-t border-stone-200"
                        >

                            <span class="text-emerald-600">
                                Montant total payé :
                            </span>

                            <span
                                class="font-black text-emerald-600 whitespace-nowrap"
                            >
                                <?= $formatPrix($montantPaye) ?>
                            </span>

                        </div>


                        <!-- Restant -->

                        <div
                            class="flex items-center justify-between gap-4 mt-4 pt-4 border-t border-stone-200"
                        >

                            <span
                                class="<?= $commandePayee
                                    ? 'text-emerald-600'
                                    : 'text-orange-600' ?>"
                            >
                                Solde restant à régler :
                            </span>

                            <span
                                class="font-black <?= $commandePayee
                                    ? 'text-emerald-600'
                                    : 'text-orange-600' ?> whitespace-nowrap"
                            >
                                <?= $formatPrix($montantRestant) ?>
                            </span>

                        </div>


                        <!-- Statut paiement -->

                        <div class="mt-5">

                            <?php if ($commandePayee): ?>

                                <span
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-600 text-xs font-extrabold"
                                >
                                    <i
                                        class="fa-solid fa-circle-check"
                                    ></i>

                                    Payé
                                </span>

                            <?php elseif ($montantPaye > 0): ?>

                                <span
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-amber-200 bg-amber-50 text-amber-700 text-xs font-extrabold"
                                >
                                    <i
                                        class="fa-solid fa-clock"
                                    ></i>

                                    Paiement partiel
                                </span>

                            <?php else: ?>

                                <span
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-red-200 bg-red-50 text-red-600 text-xs font-extrabold"
                                >
                                    <i
                                        class="fa-solid fa-circle-exclamation"
                                    ></i>

                                    <?= $escape(
                                        $statutPaiementLabel
                                    ) ?>
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 AVIS
                 UNIQUEMENT SI RETIREE
            ================================================== -->

            <?php if ($statut === 'RETIREE'): ?>

                <div
                    class="mt-8 rounded-2xl border-2 border-orange-300 bg-orange-50 p-5 sm:p-6"
                >

                    <div
                        class="flex flex-col md:flex-row md:items-center md:justify-between gap-5"
                    >

                        <div
                            class="flex items-start gap-4"
                        >

                            <div
                                class="w-11 h-11 rounded-xl bg-orange-500 text-white flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="fa-solid fa-star"
                                ></i>
                            </div>

                            <div>

                                <h2
                                    class="font-extrabold text-orange-800 text-lg"
                                >
                                    Votre avis compte beaucoup
                                    pour Saveur 221 !
                                </h2>

                                <p
                                    class="text-sm text-orange-700 mt-1"
                                >
                                    Commande retirée avec succès.
                                    Partagez votre expérience culinaire.
                                </p>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-stone-200 text-stone-500 font-bold cursor-not-allowed"
                            disabled
                        >
                            <span>Avis</span>

                            <i
                                class="fa-solid fa-star"
                            ></i>
                        </button>

                    </div>

                </div>

            <?php endif; ?>

        </section>


        <!-- =====================================================
             ACTIONS
        ====================================================== -->

        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6"
        >

            


            <?php if ($statut === 'RETIREE'): ?>

                <a
                    href="/"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-orange-500 hover:bg-orange-600 text-stone-900 font-extrabold transition"
                >
                    <i class="fa-solid fa-utensils"></i>

                    Commander à nouveau
                </a>

            <?php elseif ($statut !== 'ANNULEE'): ?>

              <a
                href="/mes-commandes"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 font-bold transition"
            >
                <i class="fa-solid fa-arrow-left"></i>

                Mes commandes
            </a>

            <?php endif; ?>

        </div>

    </div>

</div>