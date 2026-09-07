<?php

$commandes = $commandes ?? [];
$lignesParCommande = $lignesParCommande ?? [];

$formatPrix = static function (float $prix): string {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
};

$formatDate = static function (string $date): string {
    try {
        return (new DateTime($date))->format('d/m/Y à H:i');
    } catch (Throwable $e) {
        return $date;
    }
};

$libelleStatut = static function (string $statut): string {
    return match ($statut) {
        'EN_ATTENTE' => 'En attente',
        'EN_PREPARATION' => 'En préparation',
        'PRETE' => 'Prête',
        'RETIREE' => 'Retirée',
        'ANNULEE' => 'Annulée',
        default => $statut,
    };
};

$classesStatut = static function (string $statut): string {
    return match ($statut) {
        'EN_ATTENTE' =>
            'bg-orange-50 text-orange-600 border-orange-100',
        'EN_PREPARATION' =>
            'bg-blue-50 text-blue-600 border-blue-100',
        'PRETE' =>
            'bg-emerald-50 text-emerald-600 border-emerald-100',
        'RETIREE' =>
            'bg-green-50 text-green-600 border-green-100',
        'ANNULEE' =>
            'bg-red-50 text-red-600 border-red-100',
        default =>
            'bg-gray-50 text-gray-600 border-gray-100',
    };
};

$imagePlat = static function ($ligne): string {
    $image = trim((string) ($ligne->getProduitImage() ?? ''));

    if ($image === '') {
        return 'https://placehold.co/120x120/f3f4f6/9ca3af?text=Plat';
    }

    if (
        str_starts_with($image, 'http://')
        || str_starts_with($image, 'https://')
        || str_starts_with($image, '/')
    ) {
        return $image;
    }

    return '/' . ltrim($image, '/');
};

?>

<div class="space-y-6">

    <!-- =========================================================
         RETOUR
    ========================================================== -->
    <div>
        <a
            href="/admin/clients"
            class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 transition hover:text-[#ff9500]"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Retour aux clients
        </a>
    </div>


    <!-- =========================================================
         EN-TÊTE CLIENT
    ========================================================== -->
    <section
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-950 via-gray-900 to-gray-800 px-6 py-7 shadow-lg"
    >

        <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-orange-500/10"></div>
        <div class="absolute -bottom-20 left-1/3 h-48 w-48 rounded-full bg-orange-500/5"></div>

        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex min-w-0 items-center gap-4">

                <div
                    class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-orange-500 text-2xl font-extrabold text-white shadow-lg shadow-orange-500/20"
                >
                    <?= htmlspecialchars(
                        mb_strtoupper(
                            mb_substr(
                                $client->getPrenom(),
                                0,
                                1
                            )
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>

                <div class="min-w-0">

                    <div class="flex flex-wrap items-center gap-2">

                        <p class="text-xs font-bold uppercase tracking-widest text-orange-400">
                            Fiche client
                        </p>

                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[10px] font-semibold text-gray-300">
                            #<?= (int) $client->getId() ?>
                        </span>

                    </div>

                    <h1 class="mt-1 truncate text-2xl font-extrabold text-white sm:text-3xl">
                        <?= htmlspecialchars(
                            $client->getNomComplet(),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </h1>

                    <p class="mt-1 text-sm text-gray-400">
                        Client Saveur 221
                    </p>

                </div>

            </div>

            <div
                class="inline-flex w-fit items-center gap-2 rounded-full border border-green-400/20 bg-green-400/10 px-3 py-2 text-xs font-semibold text-green-300"
            >
                <span class="h-2 w-2 rounded-full bg-green-400"></span>
                Compte actif
            </div>

        </div>

    </section>


    <!-- =========================================================
         INFORMATIONS CLIENT
    ========================================================== -->
    <section
        class="rounded-2xl border border-gray-100 bg-white shadow-sm"
    >

        <div class="border-b border-gray-100 px-5 py-5">

            <div class="flex items-center gap-3">

                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-500"
                >
                    <i class="fa-solid fa-user"></i>
                </div>

                <div>
                    <h2 class="font-bold text-gray-800">
                        Informations du client
                    </h2>

                    <p class="mt-0.5 text-xs text-gray-400">
                        Coordonnées enregistrées
                    </p>
                </div>

            </div>

        </div>


        <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">

            <!-- Email -->
            <div
                class="rounded-xl border border-gray-100 bg-gray-50/70 p-4"
            >
                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-gray-400 shadow-sm">
                        <i class="fa-solid fa-envelope"></i>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            Email
                        </p>

                        <p class="mt-1 truncate text-sm font-semibold text-gray-800">
                            <?= htmlspecialchars(
                                $client->getEmail(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>
                    </div>

                </div>
            </div>


            <!-- Téléphone -->
            <div
                class="rounded-xl border border-gray-100 bg-gray-50/70 p-4"
            >
                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-gray-400 shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            Téléphone
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            <?= htmlspecialchars(
                                $client->getTelephone() ?? '-',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>
                    </div>

                </div>
            </div>


            <!-- Adresse -->
            <div
                class="rounded-xl border border-gray-100 bg-gray-50/70 p-4"
            >
                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-gray-400 shadow-sm">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            Adresse
                        </p>

                        <p class="mt-1 truncate text-sm font-semibold text-gray-800">
                            <?= htmlspecialchars(
                                $client->getAdresse(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>
                    </div>

                </div>
            </div>


            <!-- Date d'inscription -->
            <div
                class="rounded-xl border border-gray-100 bg-gray-50/70 p-4"
            >
                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-gray-400 shadow-sm">
                        <i class="fa-solid fa-calendar"></i>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            Client depuis
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            <?= htmlspecialchars(
                                $client->getDateCreation() ?? '-',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </section>


    <!-- =========================================================
         STATISTIQUES
    ========================================================== -->

    <?php
    $nombreCommandes = count($commandes);

    $totalDepense = 0;

    foreach ($commandes as $commande) {
        $totalDepense += $commande->getMontantTotal();
    }
    ?>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold text-gray-400">
                        Commandes
                    </p>

                    <p class="mt-1 text-2xl font-extrabold text-gray-800">
                        <?= $nombreCommandes ?>
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>

            </div>
        </div>


        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold text-gray-400">
                        Total dépensé
                    </p>

                    <p class="mt-1 text-xl font-extrabold text-gray-800">
                        <?= $formatPrix($totalDepense) ?>
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-500">
                    <i class="fa-solid fa-wallet"></i>
                </div>

            </div>
        </div>


        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-semibold text-gray-400">
                        Dernière commande
                    </p>

                    <p class="mt-1 text-sm font-extrabold text-gray-800">
                        <?php if (!empty($commandes)): ?>
                            <?= htmlspecialchars(
                                $formatDate(
                                    $commandes[0]->getDateCommande()
                                ),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        <?php else: ?>
                            Aucune
                        <?php endif; ?>
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-500">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>

            </div>
        </div>

    </section>


    <!-- =========================================================
         HISTORIQUE
    ========================================================== -->
    <section>

        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <p class="text-xs font-bold uppercase tracking-widest text-orange-500">
                    Activité
                </p>

                <h2 class="mt-1 text-xl font-extrabold text-gray-800">
                    Historique des commandes
                </h2>

                <p class="mt-1 text-sm text-gray-400">
                    Les plats commandés par ce client.
                </p>

            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-orange-50 px-3 py-1.5 text-xs font-bold text-orange-600">
                <i class="fa-solid fa-receipt"></i>
                <?= $nombreCommandes ?>
                <?= $nombreCommandes > 1 ? 'commandes' : 'commande' ?>
            </span>

        </div>


        <?php if (empty($commandes)): ?>

            <!-- Aucun historique -->
            <div
                class="rounded-2xl border border-dashed border-gray-200 bg-white px-5 py-16 text-center"
            >

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">
                    <i class="fa-solid fa-receipt text-2xl"></i>
                </div>

                <h3 class="mt-5 font-bold text-gray-700">
                    Aucune commande
                </h3>

                <p class="mt-1 text-sm text-gray-400">
                    Ce client n'a encore passé aucune commande.
                </p>

            </div>

        <?php else: ?>

            <div class="space-y-5">

                <?php foreach ($commandes as $commande): ?>

                    <?php
                    $commandeId = $commande->getId();
                    $statut = $commande->getStatut();
                    $lignes = $lignesParCommande[$commandeId] ?? [];
                    ?>

                    <article
                        class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md"
                    >

                        <!-- Entête commande -->
                        <div class="border-b border-gray-100 bg-gray-50/60 px-5 py-4">

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>

                                    <div>

                                        <div class="flex items-center gap-2">

                                            <h3 class="text-sm font-extrabold text-gray-800">
                                                Commande #<?= $commandeId ?>
                                            </h3>

                                            <span
                                                class="rounded-full border px-2.5 py-1 text-[10px] font-bold <?= $classesStatut($statut) ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    $libelleStatut($statut),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>

                                        </div>

                                        <p class="mt-1 text-xs text-gray-400">
                                            <?= htmlspecialchars(
                                                $formatDate(
                                                    $commande->getDateCommande()
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </p>

                                    </div>

                                </div>


                                <div class="flex items-center justify-between gap-4 sm:justify-end">

                                    <div class="text-right">
                                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                                            Total
                                        </p>

                                        <p class="mt-0.5 text-sm font-extrabold text-gray-800">
                                            <?= $formatPrix(
                                                $commande->getMontantTotal()
                                            ) ?>
                                        </p>
                                    </div>

                                    <a
                                        href="/commande/detail/<?= $commandeId ?>"
                                        title="Voir la commande"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-500"
                                    >
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>

                                </div>

                            </div>

                        </div>


                        <!-- Plats -->
                        <div class="p-5">

                            <?php if (empty($lignes)): ?>

                                <p class="text-sm text-gray-400">
                                    Aucun détail de plat disponible pour cette commande.
                                </p>

                            <?php else: ?>

                                <div class="space-y-3">

                                    <?php foreach ($lignes as $ligne): ?>

                                        <div
                                            class="flex items-center gap-4 rounded-xl border border-gray-100 bg-white p-3 transition hover:bg-gray-50"
                                        >

                                            <!-- Image -->
                                            <img
                                                src="<?= htmlspecialchars(
                                                    $imagePlat($ligne),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                alt="<?= htmlspecialchars(
                                                    $ligne->getProduitLibelle() ?? 'Plat',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                class="h-16 w-16 shrink-0 rounded-xl object-cover ring-1 ring-gray-100"
                                            >


                                            <!-- Infos plat -->
                                            <div class="min-w-0 flex-1">

                                                <p class="truncate text-sm font-bold text-gray-800">
                                                    <?= htmlspecialchars(
                                                        $ligne->getProduitLibelle() ?? 'Plat',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </p>

                                                <p class="mt-1 text-xs text-gray-400">
                                                    <?= $ligne->getQuantite() ?>
                                                    ×
                                                    <?= $formatPrix(
                                                        $ligne->getPrixUnitaire()
                                                    ) ?>
                                                </p>

                                            </div>


                                            <!-- Montant ligne -->
                                            <div class="text-right">

                                                <p class="text-sm font-extrabold text-gray-800">
                                                    <?= $formatPrix(
                                                        $ligne->getMontantLigne()
                                                    ) ?>
                                                </p>

                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</div>