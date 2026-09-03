<?php /** @var ?string $erreur */ ?>

<section class="max-w-md mx-auto px-6 py-20">
    <h1 class="text-2xl font-extrabold text-stone-900 mb-8">Créer un compte</h1>

    <?php if ($erreur): ?>
        <div class="bg-red-50 text-red-700 text-sm font-semibold px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <form action="/register" method="post" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1">Nom</label>
                <input type="text" name="nom" required
                       class="w-full border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1">Prénom</label>
                <input type="text" name="prenom" required
                       class="w-full border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-stone-700 mb-1">Email</label>
            <input type="email" name="email" required
                   class="w-full border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-stone-700 mb-1">Mot de passe</label>
            <input type="password" name="mdp" required minlength="6"
                   class="w-full border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-stone-700 mb-1">Téléphone</label>
            <input type="tel" name="telephone" required
                   class="w-full border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-stone-700 mb-1">Adresse</label>
            <input type="text" name="adresse" required
                   class="w-full border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        <button type="submit"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-full">
            Créer mon compte
        </button>
    </form>

    <p class="text-sm text-stone-500 text-center mt-6">
        Déjà un compte ?
        <a href="/login" class="text-orange-500 font-semibold">Se connecter</a>
    </p>
</section>
