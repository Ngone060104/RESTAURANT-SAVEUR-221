<?php

/**
 * Page de succès après création de la commande.
 *
 * Données reçues :
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

$formatPrix = static function (float $prix): string {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
};

$numeroCommande = 'CMD-' . date('Y') . '-' . str_pad(
    (string) $commande->getId(),
    3,
    '0',
    STR_PAD_LEFT
);

$dateCommande = $commande->getDateCommande();

try {
    $dateFormatee = (new DateTime($dateCommande))->format('d/m/Y à H:i');
} catch (Throwable $e) {
    $dateFormatee = htmlspecialchars(
        $dateCommande,
        ENT_QUOTES,
        'UTF-8'
    );
}

$statut = $commande->getStatut();

$statutLabels = [
    'EN_ATTENTE' => 'En attente',
    'EN_PREPARATION' => 'En préparation',
    'PRETE' => 'Prête',
    'RETIREE' => 'Retirée',
    'ANNULEE' => 'Annulée',
];

$statutLabel = $statutLabels[$statut] ?? $statut;

$total = (float) $commande->getMontantTotal();

$lignes = $lignes ?? [];

?>

<div class="min-h-screen bg-stone-50 py-10">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div
            class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">

            <div class="px-6 py-12 md:px-12 md:py-16 text-center">

                <!-- =================================================
                     ICÔNE SUCCÈS
                ================================================== -->

                <div class="flex justify-center mb-6">

                    <div
                        class="w-24 h-24 rounded-full bg-emerald-100 flex items-center justify-center">

                        <div
                            class="w-14 h-14 rounded-full border-4 border-emerald-500 flex items-center justify-center">

                            <i
                                class="fa-solid fa-check text-2xl text-emerald-500"></i>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     BADGE
                ================================================== -->

                <div class="mb-5">

                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full border border-emerald-400 bg-emerald-50 text-emerald-600 text-sm font-semibold">
                        Commande enregistrée avec succès
                    </span>

                </div>


                <!-- =================================================
                     TITRE
                ================================================== -->

                <h1
                    class="text-3xl md:text-4xl font-black text-stone-900">
                    Merci Pour Votre Commande !
                </h1>


                <p
                    class="max-w-2xl mx-auto mt-5 text-base md:text-lg text-stone-500 leading-relaxed">
                    Votre commande
                    <strong class="text-stone-800">
                        <?= htmlspecialchars(
                            $numeroCommande,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>
                    est maintenant en cours de traitement<br class="hidden md:block">
                    par notre brigade.
                </p>


                <!-- =================================================
                     INFORMATIONS COMMANDE
                ================================================== -->

                <div
                    class="max-w-5xl mx-auto mt-10 rounded-2xl border border-stone-200 bg-stone-50 p-6 md:p-8">

                    <div
                        class="grid grid-cols-1 md:grid-cols-4 gap-6 text-left">

                        <!-- Numéro -->

                        <div>

                            <p
                                class="text-xs font-bold uppercase text-stone-500 mb-2">
                                N° COMMANDE
                            </p>

                            <p
                                class="text-lg font-extrabold text-stone-800">
                                <?= htmlspecialchars(
                                    $numeroCommande,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </p>

                        </div>


                        <!-- Date -->

                        <div>

                            <p
                                class="text-xs font-bold uppercase text-stone-500 mb-2">
                                DATE & HEURE
                            </p>

                            <p
                                class="text-lg font-extrabold text-stone-800">
                                <?= $dateFormatee ?>
                            </p>

                        </div>


                        <!-- Statut -->

                        <div>

                            <p
                                class="text-xs font-bold uppercase text-stone-500 mb-2">
                                STATUT ACTUEL
                            </p>

                            <?php

                            $statutClasses = match ($statut) {

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

                            ?>

                            <span
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full border text-xs font-bold <?= $statutClasses ?>">

                                <i class="fa-regular fa-clock"></i>

                                <?= htmlspecialchars(
                                    $statutLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </span>

                        </div>


                        <!-- Total -->

                        <div>

                            <p
                                class="text-xs font-bold uppercase text-stone-500 mb-2">
                                MONTANT TOTAL
                            </p>

                            <p
                                class="text-lg font-black text-orange-600">
                                <?= $formatPrix($total) ?>
                            </p>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     DÉTAIL DES PLATS
                ================================================== -->

                <div class="max-w-5xl mx-auto mt-8 text-left">

                    <h2
                        class="text-lg font-extrabold text-stone-800 mb-4">
                        DÉTAIL DES PLATS (<?= count($lignes) ?>)
                    </h2>


                    <div class="border border-stone-200 rounded-2xl bg-white overflow-hidden">

                        <?php if (empty($lignes)): ?>

                            <div class="p-6 text-center text-stone-500">
                                Aucun article trouvé pour cette commande.
                            </div>

                        <?php else: ?>

                            <?php foreach ($lignes as $ligne): ?>

                                <?php

                                $nomProduit = $ligne->getProduitLibelle()
                                    ?? 'Produit';

                                $nomProduit = htmlspecialchars(
                                    $nomProduit,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                $quantite = $ligne->getQuantite();

                                $prixUnitaire = $ligne->getPrixUnitaire();

                                $montantLigne = $ligne->getMontantLigne();

                                $image = $ligne->getProduitImage();

                                $imageUrl = null;

                                if (!empty($image)) {
                                    $imageUrl = '/images/' . ltrim(
                                        $image,
                                        '/'
                                    );
                                }

                                ?>

                                <div
                                    class="flex items-center gap-5 p-5 md:p-6">

                                    <!-- IMAGE -->

                                    <div
                                        class="w-20 h-20 rounded-2xl overflow-hidden bg-stone-100 border border-stone-200 shrink-0">

                                        <?php if ($imageUrl !== null): ?>

                                            <img
                                                src="<?= htmlspecialchars(
                                                            $imageUrl,
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>"
                                                alt="<?= $nomProduit ?>"
                                                class="w-full h-full object-cover">

                                        <?php else: ?>

                                            <div
                                                class="w-full h-full flex items-center justify-center text-stone-400">
                                                <i class="fa-solid fa-utensils text-xl"></i>
                                            </div>

                                        <?php endif; ?>

                                    </div>


                                    <!-- INFOS -->

                                    <div class="flex-1 min-w-0">

                                        <h3
                                            class="font-extrabold text-stone-900">
                                            <?= $nomProduit ?>
                                        </h3>

                                        <p class="text-sm text-stone-500 mt-1">
                                            Qté : <?= $quantite ?>
                                            ×
                                            <?= $formatPrix($prixUnitaire) ?>
                                        </p>

                                    </div>


                                    <!-- PRIX -->

                                    <div class="text-right">

                                        <p
                                            class="text-lg font-black text-stone-900">
                                            <?= $formatPrix($montantLigne) ?>
                                        </p>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- =================================================
                     SÉPARATION
                ================================================== -->

                <div class="max-w-5xl mx-auto border-t border-stone-200 mt-8 pt-8">

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-3">

                        <!-- Suivre -->

                        <a
                            href="/commande/suivi/<?= $commande->getId() ?>"
                            class="inline-flex items-center justify-center gap-3 bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-4 px-6 rounded-2xl transition">
                            <i class="fa-regular fa-clock"></i>

                            Suivre l'avancement en direct
                        </a>


                        <!-- Historique -->

                        <a
                            href="/mes-commandes"
                            class="inline-flex items-center justify-center gap-3 border border-stone-300 hover:bg-stone-50 text-stone-800 font-extrabold py-4 px-6 rounded-2xl transition">

                            <i class="fa-solid fa-book-open"></i>

                            Voir mon historique de commandes

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>