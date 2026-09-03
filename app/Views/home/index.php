<?php
 // Retient le flux HTML en mémoire jusqu'à la fin de l'exécution
/**
 * @var \App\Models\Categorie[] $categories
 * @var \App\Models\Produit[] $produitsVedettes
 * @var \App\Models\Avis[] $avisRecents
 */

$emojiParCategorie = static function (string $libelle): string {
    // strtolower() suffit ici : on ne teste que des mots-clés ASCII
    // ("entr", "plat"...), pas besoin de mbstring pour ça, ce qui évite
    // une erreur fatale si l'extension n'est pas activée localement.
    $libelle = strtolower($libelle);

    return match (true) {
        str_contains($libelle, 'entr') => '🥗',
        str_contains($libelle, 'plat') => '🍛',
        str_contains($libelle, 'grillade') || str_contains($libelle, 'dibi') => '🍢',
        str_contains($libelle, 'fast') || str_contains($libelle, 'pastel') => '🍔',
        str_contains($libelle, 'boisson') || str_contains($libelle, 'jus') => '🥤',
        str_contains($libelle, 'dessert') || str_contains($libelle, 'douceur') => '🍰',
        default => '🍽️',
    };
};

$prixFormate = static fn (float $prix) => number_format($prix, 0, ',', ' ') . ' FCFA';
?>

<!-- HERO -->
<section class="max-w-7xl mx-auto px-6 pt-16 pb-12 grid md:grid-cols-2 gap-12 items-center">
    <div>
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight text-stone-900">
            Les meilleures saveurs de Dakar à portée de clic avec
            <span class="text-orange-500">Saveur 221.</span>
        </h1>
        <p class="mt-6 text-stone-600 leading-relaxed">
            Dégustez notre authentique Thiéboudienne Penda Mbaye, nos Dibis braisés à point
            et nos jus de fruits locaux fraîchement pressés. Commandez en ligne, suivez votre
            commande en temps réel et retirez au restaurant.
        </p>

        <div class="mt-8 flex flex-wrap gap-4">
            <a href="/produits"
               class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-full">
                🛍️ Commandez maintenant
            </a>
            <a href="/produits"
               class="bg-stone-900 hover:bg-stone-800 text-white font-bold px-6 py-3 rounded-full">
                Consulter la carte →
            </a>
        </div>

        <div class="mt-8 flex flex-wrap gap-6 text-sm font-semibold text-stone-600">
            <span>✅ Produits 100% frais &amp; locaux</span>
            <span>💛 Véritable teranga dakaroise</span>
        </div>
    </div>

    <div class="relative">
        <div class="rounded-[2.5rem] overflow-hidden bg-stone-200 aspect-[4/3]"></div>
    </div>
</section>

<!-- CATÉGORIES -->
<section class="max-w-7xl mx-auto px-6 py-16">
    <p class="text-orange-500 font-bold text-sm tracking-wide mb-2">EXPLORER LA CARTE</p>
    <h2 class="text-3xl font-extrabold text-stone-900 mb-10">Nos Grandes Catégories Culinaires</h2>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
        <?php foreach ($categories as $categorie): ?>
            <a href="/produits?categorie=<?= $categorie->getId() ?>"
               class="bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="aspect-square bg-orange-100 flex items-center justify-center text-5xl">
                    <?= $emojiParCategorie($categorie->getLibelle()) ?>
                </div>
                <div class="p-4">
                    <p class="font-bold text-stone-900"><?= htmlspecialchars($categorie->getLibelle()) ?></p>
                    <?php if ($categorie->getDescription()): ?>
                        <p class="text-xs text-stone-400 mt-1 truncate">
                            <?= htmlspecialchars($categorie->getDescription()) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- PLATS VEDETTES -->
<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
        <div>
            <p class="text-orange-500 font-bold text-sm tracking-wide mb-2">LES INCONTOURNABLES</p>
            <h2 class="text-3xl font-extrabold text-stone-900">Plats les Plus Plébiscités</h2>
        </div>
        <p class="text-stone-500 max-w-sm">
            Des recettes mijotées dès l'aube par notre brigade sénégalaise pour vous offrir
            un goût inimitable.
        </p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        <?php foreach ($produitsVedettes as $produit): ?>
            <article class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="relative aspect-[4/3] bg-stone-200">
                    <?php if ($produit->getImage()): ?>
                        <img src="<?= htmlspecialchars($produit->getImage()) ?>"
                             alt="<?= htmlspecialchars($produit->getNom()) ?>"
                             class="w-full h-full object-cover">
                    <?php endif; ?>
                    <span class="absolute top-3 left-3 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                        ✓ Disponible
                    </span>
                    <span class="absolute top-3 right-3 bg-white/90 text-stone-700 text-xs font-bold px-3 py-1 rounded-full">
                        <?= htmlspecialchars($produit->getCategorieLibelle() ?? '') ?>
                    </span>
                </div>

                <div class="p-5">
                    <h3 class="font-extrabold text-lg text-stone-900">
                        <?= htmlspecialchars($produit->getNom()) ?>
                    </h3>
                    <p class="text-sm text-stone-500 mt-2 line-clamp-2">
                        <?= htmlspecialchars($produit->getDescription() ?? '') ?>
                    </p>

                    <div class="mt-4 pt-4 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-stone-400">Prix</p>
                            <p class="font-extrabold text-stone-900"><?= $prixFormate($produit->getPrix()) ?></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="/produit?id=<?= $produit->getId() ?>"
                               class="text-sm font-semibold text-stone-600 hover:text-orange-500">
                                Détail
                            </a>
                            <form action="/panier/ajouter" method="post">
                                <input type="hidden" name="produit_id" value="<?= $produit->getId() ?>">
                                <input type="hidden" name="quantite" value="1">
                                <button type="submit"
                                        class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold px-4 py-2 rounded-full">
                                    🛒 Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- BANDEAU PROMO -->
<section class="max-w-7xl mx-auto px-6 pb-16">
    <div class="bg-gradient-to-r from-orange-500 to-orange-700 rounded-[2rem] p-10 md:p-14 text-white flex flex-col md:flex-row items-center justify-between gap-8">
        <div>
            <span class="inline-block bg-white/20 text-xs font-bold px-3 py-1 rounded-full mb-4">
                Formule Déjeuner &amp; Dîner
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold leading-snug">
                Savourez notre Thiéboudienne avec un jus de bouye artisanal offert !
            </h2>
            <p class="mt-4 text-white/90 max-w-xl">
                Commandez en ligne et profitez d'un temps de préparation garanti de 15 à 25 minutes.
                Retrait direct à notre comptoir express.
            </p>
        </div>
        <a href="/produits"
           class="shrink-0 bg-white text-orange-600 font-bold px-6 py-3 rounded-full whitespace-nowrap">
            Découvrez la formule →
        </a>
    </div>
</section>

<!-- TÉMOIGNAGES -->
<?php if (!empty($avisRecents)): ?>
    <section class="max-w-7xl mx-auto px-6 py-16 text-center">
        <p class="text-orange-500 font-bold text-sm tracking-wide mb-2">AVIS &amp; TÉMOIGNAGES CLIENTS</p>
        <h2 class="text-3xl font-extrabold text-stone-900">La Satisfaction De Nos Clients Saveur 221</h2>
        <p class="text-stone-500 mt-3 max-w-xl mx-auto">
            Retours authentiques enregistrés après retrait de commande par nos clients vérifiés.
        </p>

        <div class="mt-10 grid md:grid-cols-2 gap-6 text-left">
            <?php foreach ($avisRecents as $avis): ?>
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center font-bold text-orange-600">
                                <?php
                                    $nomComplet = $avis->getClientNomComplet() ?? '?';
                                    // Repli sur substr() si mbstring n'est pas activé - imparfait
                                    // sur une initiale accentuée, mais ne casse jamais la page.
                                    $initiale = function_exists('mb_substr')
                                        ? mb_substr($nomComplet, 0, 1)
                                        : substr($nomComplet, 0, 1);
                                    echo htmlspecialchars($initiale);
                                ?>
                            </span>
                            <span class="font-bold text-stone-900">
                                <?= htmlspecialchars($avis->getClientNomComplet() ?? 'Client') ?>
                            </span>
                        </div>
                        <span class="text-orange-500">
                            <?= str_repeat('★', $avis->getNote()) . str_repeat('☆', 5 - $avis->getNote()) ?>
                        </span>
                    </div>

                    <p class="mt-4 text-stone-600 italic">
                        "<?= htmlspecialchars($avis->getCommentaire() ?? '') ?>"
                    </p>

                    <?php if ($avis->getProduitNom()): ?>
                        <div class="mt-4 pt-4 border-t border-stone-100 text-sm">
                            <span class="text-stone-400">Plat apprécié : </span>
                            <span class="text-orange-500 font-semibold">
                                <?= htmlspecialchars($avis->getProduitNom()) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
