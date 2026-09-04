<?php

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

$statutLabel = static function (string $statut): string {
    return match ($statut) {
        'EN_ATTENTE' => 'En attente',
        'EN_PREPARATION' => 'En préparation',
        'PRETE' => 'Prête',
        'RETIREE' => 'Retirée',
        'ANNULEE' => 'Annulée',
        default => $statut,
    };
};

$statutClass = static function (string $statut): string {
    return match ($statut) {
        'EN_ATTENTE' => 'bg-amber-100 text-amber-700',
        'EN_PREPARATION' => 'bg-blue-100 text-blue-700',
        'PRETE' => 'bg-emerald-100 text-emerald-700',
        'RETIREE' => 'bg-green-100 text-green-700',
        'ANNULEE' => 'bg-red-100 text-red-700',
        default => 'bg-stone-100 text-stone-700',
    };
};
?>

<div class="min-h-screen bg-stone-50 py-10">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- En-tête -->
        <div class="mb-8">

            <a
                href="/"
                class="inline-flex items-center gap-2 text-stone-600 hover:text-orange-600 font-semibold mb-5 transition">
                <i class="fa-solid fa-arrow-left"></i>
                Retour à l'accueil
            </a>

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-stone-900">
                        Historique de mes Commandes
                    </h1>

                    <p class="mt-2 text-stone-500">
                        Retrouvez toutes vos commandes passées et leur statut.
                    </p>
                </div>

                <a
                    href="/panier"
                    class="inline-flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-stone-900 font-extrabold px-5 py-3 rounded-2xl transition shadow-sm">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Mon panier
                </a>

            </div>
        </div>

        <!-- Recherche + filtre -->
        <section class="bg-white rounded-3xl border border-stone-200 shadow-sm p-5 md:p-6 mb-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Recherche -->
                <div class="md:col-span-2">

                    <label
                        for="rechercheCommande"
                        class="block text-sm font-bold text-stone-700 mb-2">
                        Rechercher une commande
                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-stone-400"></i>

                        <input
                            id="rechercheCommande"
                            type="search"
                            placeholder="Rechercher par numéro de commande..."
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-stone-200 bg-stone-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition">

                    </div>

                </div>

                <!-- Statut -->
                <div>

                    <label
                        for="filtreStatut"
                        class="block text-sm font-bold text-stone-700 mb-2">
                        Filtrer par statut
                    </label>

                    <select
                        id="filtreStatut"
                        class="w-full px-4 py-3 rounded-2xl border border-stone-200 bg-stone-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition">
                        <option value="">Tous les statuts</option>
                        <option value="EN_ATTENTE">En attente</option>
                        <option value="EN_PREPARATION">En préparation</option>
                        <option value="PRETE">Prête</option>
                        <option value="RETIREE">Retirée</option>
                        <option value="ANNULEE">Annulée</option>
                    </select>

                </div>

            </div>

        </section>

        <!-- Liste des commandes -->
        <div
            id="listeCommandes"
            class="space-y-5">

            <?php if (empty($commandes)): ?>

                <!-- Aucun résultat -->
                <div
                    id="aucuneCommande"
                    class="bg-white rounded-3xl border border-stone-200 shadow-sm p-10 text-center">

                    <div class="w-16 h-16 mx-auto rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center mb-5">
                        <i class="fa-solid fa-receipt text-2xl"></i>
                    </div>

                    <h2 class="text-xl font-extrabold text-stone-900">
                        Aucune commande
                    </h2>

                    <p class="text-stone-500 mt-2 max-w-md mx-auto">
                        Vous n'avez pas encore passé de commande.
                    </p>

                    <a
                        href="/catalogue"
                        class="inline-flex items-center gap-2 mt-6 bg-orange-500 hover:bg-orange-600 text-stone-900 font-extrabold px-6 py-3 rounded-2xl transition">
                        <i class="fa-solid fa-utensils"></i>
                        Découvrir le menu
                    </a>

                </div>

            <?php else: ?>

                <?php foreach ($commandes as $commande): ?>

                    <?php
                    $id = $commande->getId();
                    $statut = $commande->getStatut();
                    $montant = $commande->getMontantTotal();
                    $date = $commande->getDateCommande();
                    ?>

                    <article
                        class="commande-card bg-white rounded-3xl border border-stone-200 shadow-sm hover:shadow-md transition p-5 md:p-6"
                        data-id="<?= $id ?>"
                        data-statut="<?= htmlspecialchars($statut, ENT_QUOTES, 'UTF-8') ?>"
                        data-search="commande #<?= $id ?>">

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                            <!-- Informations -->
                            <div class="flex items-start gap-4">

                                <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>

                                <div>

                                    <div class="flex flex-wrap items-center gap-3">

                                        <h2 class="text-lg font-extrabold text-stone-900">
                                            Commande #<?= $id ?>
                                        </h2>

                                        <span
                                            class="statut-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold <?= $statutClass($statut) ?>">
                                            <?= htmlspecialchars(
                                                $statutLabel($statut),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    </div>

                                    <p class="text-sm text-stone-500 mt-2">
                                        <i class="fa-regular fa-calendar mr-1"></i>
                                        <?= htmlspecialchars(
                                            $formatDate($date),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </p>

                                </div>

                            </div>

                            <!-- Total + actions -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 lg:gap-8">

                                <div class="sm:text-right">

                                    <p class="text-xs font-semibold text-stone-400 uppercase tracking-wide">
                                        Total
                                    </p>

                                    <p class="text-xl font-black text-orange-600">
                                        <?= $formatPrix($montant) ?>
                                    </p>

                                </div>

                                <div class="flex items-center gap-3">

                                    <!-- Suivi -->
                                    <?php if (
                                        in_array(
                                            $statut,
                                            ['EN_ATTENTE', 'EN_PREPARATION', 'PRETE'],
                                            true
                                        )
                                    ): ?>

                                        <a
                                            href="/commande/detail/<?= $id ?>"
                                            class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-stone-100 hover:bg-stone-200 text-stone-800 font-bold transition">
                                            <i class="fa-solid fa-location-dot"></i>
                                            Suivi
                                        </a>

                                    <?php endif; ?>

                                    <!-- Détail -->
                                    <a
                                        href="/commande/detail/<?= $id ?>"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-orange-500 hover:bg-orange-600 text-stone-900 font-extrabold transition">
                                        <span>Détail</span>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>

                                </div>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

                <!-- Message aucun résultat après filtrage -->
                <div
                    id="aucunResultat"
                    class="hidden bg-white rounded-3xl border border-stone-200 shadow-sm p-10 text-center">

                    <div class="w-14 h-14 mx-auto rounded-2xl bg-stone-100 text-stone-400 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-magnifying-glass text-xl"></i>
                    </div>

                    <h2 class="text-lg font-extrabold text-stone-900">
                        Aucune commande trouvée
                    </h2>

                    <p class="text-stone-500 mt-2">
                        Essayez de modifier votre recherche ou votre filtre.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const recherche = document.getElementById('rechercheCommande');
        const filtre = document.getElementById('filtreStatut');
        const cartes = document.querySelectorAll('.commande-card');
        const aucunResultat = document.getElementById('aucunResultat');

        if (!recherche || !filtre || !cartes.length) {
            return;
        }

        function filtrerCommandes() {

            const texte = recherche.value.toLowerCase().trim();
            const statut = filtre.value;

            let nombreVisible = 0;

            cartes.forEach(function(carte) {

                const contenu = carte.dataset.search.toLowerCase();
                const carteStatut = carte.dataset.statut;

                const correspondRecherche =
                    texte === '' || contenu.includes(texte);

                const correspondStatut =
                    statut === '' || carteStatut === statut;

                if (correspondRecherche && correspondStatut) {
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

        recherche.addEventListener('input', filtrerCommandes);
        filtre.addEventListener('change', filtrerCommandes);

    });
</script>