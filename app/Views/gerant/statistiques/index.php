<?php

$formatMontant = static function (float $montant): string {
    return number_format(
        $montant,
        0,
        ',',
        ' '
    ) . ' F CFA';
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
            'bg-amber-50 text-amber-700 ring-1 ring-amber-200',

        'EN_PREPARATION' =>
            'bg-blue-50 text-blue-700 ring-1 ring-blue-200',

        'PRETE' =>
            'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',

        'RETIREE' =>
            'bg-violet-50 text-violet-700 ring-1 ring-violet-200',

        'ANNULEE' =>
            'bg-rose-50 text-rose-700 ring-1 ring-rose-200',

        default =>
            'bg-gray-50 text-gray-600 ring-1 ring-gray-200',
    };
};

$iconeStatut = static function (string $statut): string {
    return match ($statut) {
        'EN_ATTENTE' => 'fa-hourglass-half',
        'EN_PREPARATION' => 'fa-kitchen-set',
        'PRETE' => 'fa-circle-check',
        'RETIREE' => 'fa-bag-shopping',
        'ANNULEE' => 'fa-circle-xmark',
        default => 'fa-circle',
    };
};

$couleurStatut = static function (string $statut): string {
    return match ($statut) {
        'EN_ATTENTE' =>
            'bg-gradient-to-br from-amber-400 to-orange-500',

        'EN_PREPARATION' =>
            'bg-gradient-to-br from-blue-500 to-indigo-600',

        'PRETE' =>
            'bg-gradient-to-br from-emerald-400 to-green-600',

        'RETIREE' =>
            'bg-gradient-to-br from-violet-500 to-purple-600',

        'ANNULEE' =>
            'bg-gradient-to-br from-rose-400 to-red-600',

        default =>
            'bg-gradient-to-br from-gray-400 to-gray-600',
    };
};

$caJour = $caJour ?? 0;
$caSemaine = $caSemaine ?? 0;
$caMois = $caMois ?? 0;

$nombreCommandes = $nombreCommandes ?? 0;
$commandesEnCours = $commandesEnCours ?? 0;

$commandesParStatut = $commandesParStatut ?? [];
$produitPlusVendu = $produitPlusVendu ?? null;
$top3Produits = $top3Produits ?? [];

$totalStatuts = array_sum(
    array_map(
        static fn(object $statut): int => (int) $statut->nombre,
        $commandesParStatut
    )
);
?>

<div class="space-y-8">

    <!-- ====================================================== -->
    <!-- EN-TÊTE -->
    <!-- ====================================================== -->

    <div class="relative overflow-hidden rounded-3xl border border-orange-100 bg-gradient-to-br from-white via-orange-50/50 to-amber-50/70 px-6 py-7 shadow-sm md:px-8">

        <!-- Décoration -->
        <div class="pointer-events-none absolute -right-16 -top-20 h-52 w-52 rounded-full bg-orange-200/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-40 w-40 rounded-full bg-amber-200/30 blur-3xl"></div>

        <div class="relative flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

            <div>

                <div class="mb-2 flex items-center gap-2">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100">
                        <i class="fa-solid fa-chart-line text-sm text-[#ff9500]"></i>
                    </span>

                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#ff9500]">
                        Tableau de bord
                    </p>

                </div>

                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 md:text-4xl">
                    Statistiques &amp; Ventes
                </h1>

                <p class="mt-2 max-w-xl text-sm text-gray-500">
                    Vue d’ensemble de l’activité et des performances
                    de votre restaurant.
                </p>

            </div>

            <div class="hidden h-20 w-20 items-center justify-center rounded-3xl bg-white shadow-md ring-1 ring-orange-100 md:flex">
                <i class="fa-solid fa-chart-pie text-3xl text-[#ff9500]"></i>
            </div>

        </div>

    </div>


    <!-- ====================================================== -->
    <!-- CHIFFRE D'AFFAIRES -->
    <!-- ====================================================== -->

    <section>

        <div class="mb-5 flex items-center gap-3">

            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100">
                <i class="fa-solid fa-coins text-sm text-[#ff9500]"></i>
            </div>

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Chiffre d'affaires
                </h2>

                <p class="text-xs text-gray-400">
                    Revenus encaissés
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">


            <!-- CA JOUR -->

            <div
                class="group relative cursor-pointer overflow-hidden rounded-3xl border border-orange-100 bg-gradient-to-br from-white to-orange-50/70 p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl active:-translate-y-3 active:shadow-2xl"
            >

                <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-orange-100/70 transition-transform duration-500 group-hover:scale-150"></div>

                <div class="relative">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-semibold text-gray-500">
                                CA du jour
                            </p>

                            <p class="mt-3 text-2xl font-extrabold tracking-tight text-gray-900">
                                <?= $formatMontant((float) $caJour) ?>
                            </p>

                            <div class="mt-3 flex items-center gap-2">

                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100">
                                    <i class="fa-solid fa-arrow-trend-up text-[10px] text-emerald-600"></i>
                                </span>

                                <span class="text-xs font-semibold text-emerald-600">
                                    Aujourd'hui
                                </span>

                            </div>

                        </div>


                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-[#ff9500] shadow-lg shadow-orange-200 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">

                            <i class="fa-solid fa-calendar-day text-xl text-white"></i>

                        </div>

                    </div>

                    <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-orange-100">
                        <div class="h-full w-3/4 rounded-full bg-gradient-to-r from-orange-300 to-[#ff9500]"></div>
                    </div>

                </div>

            </div>


            <!-- CA SEMAINE -->

            <div
                class="group relative cursor-pointer overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-white to-blue-50/70 p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl active:-translate-y-3 active:shadow-2xl"
            >

                <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-blue-100/70 transition-transform duration-500 group-hover:scale-150"></div>

                <div class="relative">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-semibold text-gray-500">
                                CA de la semaine
                            </p>

                            <p class="mt-3 text-2xl font-extrabold tracking-tight text-gray-900">
                                <?= $formatMontant((float) $caSemaine) ?>
                            </p>

                            <div class="mt-3 flex items-center gap-2">

                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100">
                                    <i class="fa-solid fa-chart-line text-[10px] text-blue-600"></i>
                                </span>

                                <span class="text-xs font-semibold text-blue-600">
                                    Semaine en cours
                                </span>

                            </div>

                        </div>


                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-600 shadow-lg shadow-blue-200 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">

                            <i class="fa-solid fa-chart-line text-xl text-white"></i>

                        </div>

                    </div>

                    <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-blue-100">
                        <div class="h-full w-2/3 rounded-full bg-gradient-to-r from-blue-400 to-indigo-600"></div>
                    </div>

                </div>

            </div>


            <!-- CA MOIS -->

            <div
                class="group relative cursor-pointer overflow-hidden rounded-3xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50/70 p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl active:-translate-y-3 active:shadow-2xl"
            >

                <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-emerald-100/70 transition-transform duration-500 group-hover:scale-150"></div>

                <div class="relative">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-semibold text-gray-500">
                                CA du mois
                            </p>

                            <p class="mt-3 text-2xl font-extrabold tracking-tight text-gray-900">
                                <?= $formatMontant((float) $caMois) ?>
                            </p>

                            <div class="mt-3 flex items-center gap-2">

                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100">
                                    <i class="fa-solid fa-coins text-[10px] text-emerald-600"></i>
                                </span>

                                <span class="text-xs font-semibold text-emerald-600">
                                    Mois en cours
                                </span>

                            </div>

                        </div>


                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-green-600 shadow-lg shadow-emerald-200 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">

                            <i class="fa-solid fa-coins text-xl text-white"></i>

                        </div>

                    </div>

                    <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-emerald-100">
                        <div class="h-full w-4/5 rounded-full bg-gradient-to-r from-emerald-400 to-green-600"></div>
                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- COMMANDES -->
    <!-- ====================================================== -->

    <section>

        <div class="mb-5 flex items-center gap-3">

            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100">
                <i class="fa-solid fa-receipt text-sm text-blue-600"></i>
            </div>

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Commandes
                </h2>

                <p class="text-xs text-gray-400">
                    Activité des commandes
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


            <!-- NOMBRE COMMANDES -->

            <div
                class="group relative cursor-pointer overflow-hidden rounded-3xl border border-indigo-100 bg-gradient-to-br from-white to-indigo-50/70 p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl active:-translate-y-3 active:shadow-2xl"
            >

                <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-indigo-100/60 transition-transform duration-500 group-hover:scale-150"></div>

                <div class="relative flex items-center justify-between">

                    <div>

                        <p class="text-sm font-semibold text-gray-500">
                            Nombre de commandes
                        </p>

                        <p class="mt-3 text-4xl font-extrabold tracking-tight text-gray-900">
                            <?= (int) $nombreCommandes ?>
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Commandes enregistrées
                        </p>

                    </div>


                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-400 to-blue-600 shadow-lg shadow-indigo-200 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">

                        <i class="fa-solid fa-receipt text-2xl text-white"></i>

                    </div>

                </div>

            </div>


            <!-- COMMANDES EN COURS -->

            <div
                class="group relative cursor-pointer overflow-hidden rounded-3xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50/70 p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl active:-translate-y-3 active:shadow-2xl"
            >

                <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-emerald-100/60 transition-transform duration-500 group-hover:scale-150"></div>

                <div class="relative flex items-center justify-between">

                    <div>

                        <p class="text-sm font-semibold text-gray-500">
                            Commandes en cours
                        </p>

                        <p class="mt-3 text-4xl font-extrabold tracking-tight text-gray-900">
                            <?= (int) $commandesEnCours ?>
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            En attente · Préparation · Prête
                        </p>

                    </div>


                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-green-600 shadow-lg shadow-emerald-200 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">

                        <i class="fa-solid fa-clock text-2xl text-white"></i>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- COMMANDES PAR STATUT -->
    <!-- ====================================================== -->

    <section
        class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
    >

        <div class="border-b border-gray-100 bg-gradient-to-r from-white to-gray-50 px-6 py-6">

            <div class="flex items-center justify-between">

                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-[#ff9500] shadow-md shadow-orange-200">

                        <i class="fa-solid fa-chart-pie text-white"></i>

                    </div>

                    <div>

                        <h2 class="font-bold text-gray-900">
                            Commandes par statut
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Répartition des commandes enregistrées.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <?php if (empty($commandesParStatut)): ?>

            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100">

                    <i class="fa-solid fa-chart-column text-2xl text-gray-400"></i>

                </div>

                <p class="mt-4 text-sm font-medium text-gray-500">
                    Aucune commande disponible.
                </p>

            </div>

        <?php else: ?>

            <div class="grid grid-cols-1 divide-y divide-gray-100 md:grid-cols-5 md:divide-x md:divide-y-0">

                <?php foreach ($commandesParStatut as $statut): ?>

                    <?php
                    $codeStatut = (string) $statut->statut;
                    $nombre = (int) $statut->nombre;

                    $pourcentage = $totalStatuts > 0
                        ? round(($nombre / $totalStatuts) * 100)
                        : 0;
                    ?>

                    <div
                        class="group cursor-pointer px-5 py-7 text-center transition-all duration-300 hover:-translate-y-2 hover:bg-gray-50"
                    >

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl <?= $couleurStatut($codeStatut) ?> shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">

                            <i class="fa-solid <?= $iconeStatut($codeStatut) ?> text-lg text-white"></i>

                        </div>


                        <p class="mt-4 text-xs font-bold text-gray-700">
                            <?= htmlspecialchars(
                                $libelleStatut($codeStatut),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>


                        <p class="mt-2 text-3xl font-extrabold text-gray-900 transition-transform duration-300 group-hover:scale-110">

                            <?= $nombre ?>

                        </p>


                        <p class="mt-1 text-[11px] text-gray-400">
                            commande(s)
                        </p>


                        <div class="mx-auto mt-4 h-1.5 max-w-[90px] overflow-hidden rounded-full bg-gray-100">

                            <div
                                class="h-full rounded-full <?= $couleurStatut($codeStatut) ?>"
                                style="width: <?= $pourcentage ?>%;"
                            ></div>

                        </div>


                        <span
                            class="mt-3 inline-flex rounded-full px-3 py-1 text-[10px] font-bold <?= $classesStatut($codeStatut) ?>"
                        >

                            <?= $pourcentage ?> %

                        </span>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>


    <!-- ====================================================== -->
    <!-- VENTES PRODUITS -->
    <!-- ====================================================== -->

    <section>

        <div class="mb-5 flex items-center gap-3">

            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100">

                <i class="fa-solid fa-burger text-sm text-[#ff9500]"></i>

            </div>

            <div>

                <h2 class="text-lg font-bold text-gray-900">
                    Ventes produits
                </h2>

                <p class="text-xs text-gray-400">
                    Performance de votre menu
                </p>

            </div>

        </div>


        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">


            <!-- PRODUIT LE PLUS VENDU -->

            <div
                class="group relative cursor-pointer overflow-hidden rounded-3xl border border-orange-100 bg-gradient-to-br from-white via-orange-50/50 to-amber-50 p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl active:-translate-y-3 active:shadow-2xl"
            >

                <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-orange-200/30 transition-transform duration-500 group-hover:scale-150"></div>

                <div class="relative">

                    <div class="flex items-start justify-between">

                        <div>

                            <div class="flex items-center gap-2">

                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-100">

                                    <i class="fa-solid fa-trophy text-sm text-[#ff9500]"></i>

                                </span>

                                <h2 class="font-bold text-gray-900">
                                    Produit le plus vendu
                                </h2>

                            </div>

                            <p class="mt-2 text-xs text-gray-500">
                                Meilleure performance produit.
                            </p>

                        </div>


                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-orange-100 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">

                            <i class="fa-solid fa-crown text-[#ff9500]"></i>

                        </div>

                    </div>


                    <?php if ($produitPlusVendu === null): ?>

                        <div class="py-12 text-center">

                            <i class="fa-solid fa-chart-column text-3xl text-gray-300"></i>

                            <p class="mt-3 text-sm text-gray-500">
                                Aucune vente disponible.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="mt-7 rounded-2xl bg-white/80 p-5 shadow-sm ring-1 ring-orange-100">

                            <div class="flex items-center gap-4">

                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-[#ff9500] shadow-lg shadow-orange-200 transition-transform duration-300 group-hover:scale-110">

                                    <i class="fa-solid fa-crown text-2xl text-white"></i>

                                </div>


                                <div class="min-w-0">

                                    <p class="truncate text-xl font-extrabold text-gray-900">

                                        <?= htmlspecialchars(
                                            $produitPlusVendu->nom,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">

                                        <span class="font-bold text-[#ff9500]">
                                            <?= (int) $produitPlusVendu->quantite_vendue ?>
                                        </span>

                                        unité(s) vendue(s)

                                    </p>

                                </div>

                            </div>


                            <div class="mt-5 flex items-center gap-2 rounded-xl bg-orange-50 px-4 py-3">

                                <i class="fa-solid fa-arrow-trend-up text-[#ff9500]"></i>

                                <span class="text-xs font-semibold text-orange-700">
                                    Produit le plus populaire de votre menu
                                </span>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- TOP 3 -->

            <div
                class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
            >

                <div class="border-b border-gray-100 bg-gradient-to-r from-white to-gray-50 px-6 py-5">

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50">

                                <i class="fa-solid fa-ranking-star text-[#ff9500]"></i>

                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900">
                                    Top 3 des produits
                                </h2>

                                <p class="mt-1 text-xs text-gray-500">
                                    Les produits les plus vendus.
                                </p>

                            </div>

                        </div>


                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50">

                            <i class="fa-solid fa-chart-simple text-gray-500"></i>

                        </div>

                    </div>

                </div>


                <div class="divide-y divide-gray-100">

                    <?php if (empty($top3Produits)): ?>

                        <div class="px-6 py-14 text-center">

                            <i class="fa-solid fa-chart-column text-3xl text-gray-300"></i>

                            <p class="mt-3 text-sm text-gray-500">
                                Aucune vente disponible.
                            </p>

                        </div>

                    <?php else: ?>

                        <?php foreach ($top3Produits as $index => $produit): ?>

                            <?php
                            $rang = $index + 1;

                            $couleurRang = match ($rang) {
                                1 => 'bg-gradient-to-br from-amber-400 to-orange-500',
                                2 => 'bg-gradient-to-br from-gray-300 to-gray-500',
                                3 => 'bg-gradient-to-br from-orange-300 to-amber-600',
                                default => 'bg-gray-100',
                            };

                            $maxVente = !empty($top3Produits)
                                ? max(
                                    array_map(
                                        static fn(object $p): int =>
                                            (int) $p->quantite_vendue,
                                        $top3Produits
                                    )
                                )
                                : 1;

                            $progression = $maxVente > 0
                                ? round(
                                    ((int) $produit->quantite_vendue / $maxVente) * 100
                                )
                                : 0;
                            ?>


                            <div
                                class="group flex cursor-pointer items-center gap-4 px-6 py-5 transition-all duration-300 hover:bg-gray-50 hover:px-7"
                            >

                                <!-- RANG -->

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl <?= $couleurRang ?> text-sm font-extrabold text-white shadow-sm transition-transform duration-300 group-hover:scale-110">

                                    <?= $rang ?>

                                </div>


                                <!-- PRODUIT -->

                                <div class="min-w-0 flex-1">

                                    <p class="truncate font-bold text-gray-900">

                                        <?= htmlspecialchars(
                                            $produit->nom,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">

                                        <?= (int) $produit->quantite_vendue ?>
                                        unité(s)

                                    </p>


                                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-gray-100">

                                        <div
                                            class="h-full rounded-full bg-gradient-to-r from-orange-400 to-[#ff9500] transition-all duration-700"
                                            style="width: <?= $progression ?>%;"
                                        ></div>

                                    </div>

                                </div>


                                <!-- QUANTITÉ -->

                                <div class="text-right">

                                    <p class="text-lg font-extrabold text-gray-900">

                                        <?= (int) $produit->quantite_vendue ?>

                                    </p>

                                    <p class="text-[10px] font-medium uppercase tracking-wide text-gray-400">
                                        ventes
                                    </p>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>

</div>