<?php

use App\Services\AuthService;

$utilisateurConnecte = AuthService::currentUser();
?>
<header class="bg-white border-b border-stone-100">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3">
            <span class="w-11 h-11 rounded-xl bg-orange-500 flex items-center justify-center text-white text-xl">
                🍴
            </span>
            <span>
                <span class="block text-lg font-extrabold text-stone-900 leading-none">
                    Saveur <span class="text-orange-500">221</span>
                </span>
                <span class="block text-[11px] font-semibold tracking-wide text-stone-400">
                    CUISINE SÉNÉGALAISE &amp; TÉRANGA
                </span>
            </span>
        </a>

        <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-stone-700">
            <a href="/" class="hover:text-orange-500">Accueil</a>
            <a href="/produits" class="hover:text-orange-500">Menu</a>
            <a href="/mes-commandes" class="hover:text-orange-500">Mes Commandes</a>
        </nav>

        <div class="flex items-center gap-6 text-sm font-semibold text-stone-700">
            <a href="/panier" class="hidden sm:flex items-center gap-2 hover:text-orange-500">
                <span>🛒</span> Panier
            </a>

            <?php if ($utilisateurConnecte): ?>
                <span class="hidden sm:inline text-stone-500">
                    Bonjour, <?= htmlspecialchars($utilisateurConnecte['prenom']) ?>
                </span>
                <form action="/logout" method="post" class="inline">
                    <button type="submit" class="hover:text-orange-500">Déconnexion</button>
                </form>
            <?php else: ?>
                <a href="/login" class="hidden sm:flex items-center gap-2 hover:text-orange-500">
                    <span>👤</span> Connexion
                </a>
                <a href="/register"
                   class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-5 py-2.5 rounded-full">
                    S'inscrire
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
