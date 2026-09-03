<?php /** @var ?string $erreur */ ?>

<section class="max-w-md mx-auto px-6 py-20">
    <h1 class="text-2xl font-extrabold text-stone-900 mb-8">Connexion</h1>

    <?php if ($erreur): ?>
        <div class="bg-red-50 text-red-700 text-sm font-semibold px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="post" class="space-y-4">
        <div>
            <label class="block text-sm font-semibold text-stone-700 mb-1">Email</label>
            <input type="email" name="email" required
                   class="w-full border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-stone-700 mb-1">Mot de passe</label>
            <input type="password" name="mdp" required
                   class="w-full border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        <button type="submit"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-full">
            Se connecter
        </button>
    </form>

    <p class="text-sm text-stone-500 text-center mt-6">
        Pas encore de compte ?
        <a href="/register" class="text-orange-500 font-semibold">S'inscrire</a>
    </p>
</section>
