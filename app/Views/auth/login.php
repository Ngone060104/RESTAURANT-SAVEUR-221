<?php

$erreurs = $erreurs ?? [];

$email = $email ?? ($_POST['email'] ?? '');

?>

<section class="min-h-[560px] bg-[#faf9f7] flex items-start justify-center px-6 pt-[32px] pb-[40px]">

    <!-- Carte de connexion -->
    <div class="w-full max-w-[550px] bg-white border border-stone-200 rounded-[30px] shadow-[0_3px_5px_rgba(0,0,0,0.22)] px-[46px] pt-[32px] pb-[24px]">

        <!-- Logo -->
        <div class="flex justify-center mb-[14px]">
            <div class="w-[56px] h-[56px] rounded-[15px] bg-[#ff9900] flex items-center justify-center shadow-[0_3px_5px_rgba(0,0,0,0.20)]">

                <i class="fa-solid fa-utensils text-white text-[28px]"></i>

            </div>
        </div>

        <!-- Titre -->
        <h1 class="text-center text-[25px] leading-[32px] font-extrabold text-[#111111]">
            Connexion à Saveur 221
        </h1>

        <!-- Sous-titre -->
        <p class="text-center text-[15px] leading-[20px] text-[#222222] mt-[10px] mb-[10px]">
            Accédez à votre espace client ou à votre tableau de<br>
            bord restaurant
        </p>

        <!-- Formulaire -->
        <form action="/login" method="post" novalidate class="mt-[4px]">

            <!-- Email -->
            <div class="mb-[18px]">

                <label
                    for="email"
                    class="block text-[14px] font-extrabold uppercase text-[#111111] mb-[9px]">
                    Adresse email
                    <span class="text-[#ff9900] ml-[8px]">*</span>
                </label>

                <div class="relative">

                    <!-- Icône Font Awesome -->
                    <i class="fa-regular fa-envelope absolute left-[14px] top-1/2 -translate-y-1/2 text-[19px] text-stone-400"></i>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="email"
                        placeholder="Ex : MichelDiop@gmail.com"
                        value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                        class="w-full h-[46px] bg-[#fafafa] border border-[#e8e8e8] rounded-[10px] pl-[45px] pr-[16px] text-[13px] text-[#111111] placeholder:text-[#999999] outline-none transition focus:border-[#ff9900] focus:ring-1 focus:ring-[#ff9900]">

                </div>

                <!-- Erreur email -->
                <?php if (!empty($erreurs['email'])): ?>
                    <p class="mt-[5px] text-[11px] font-semibold text-red-500">
                        <?= htmlspecialchars($erreurs['email'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>

            </div>


            <!-- Mot de passe -->
            <div class="mb-[6px]">

                <label
                    for="mdp"
                    class="block text-[14px] font-extrabold uppercase text-[#111111] mb-[9px]">
                    Mot de passe
                    <span class="text-[#ff9900] ml-[8px]">*</span>
                </label>

                <div class="relative">

                    <!-- Icône Font Awesome -->
                    <i class="fa-solid fa-key absolute left-[14px] top-1/2 -translate-y-1/2 text-[19px] text-stone-400"></i>

                    <input
                        id="mdp"
                        type="password"
                        name="mdp"
                        autocomplete="current-password"
                        placeholder="Entrez votre mot de passe"
                        class="w-full h-[46px] bg-[#fafafa] border border-[#e8e8e8] rounded-[10px] pl-[45px] pr-[16px] text-[13px] text-[#111111] placeholder:text-[#999999] outline-none transition focus:border-[#ff9900] focus:ring-1 focus:ring-[#ff9900]">

                </div>

                <!-- Erreur mot de passe -->
                <?php if (!empty($erreurs['mdp'])): ?>
                    <p class="mt-[5px] text-[11px] font-semibold text-red-500">
                        <?= htmlspecialchars($erreurs['mdp'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>

            </div>


            <!-- Mot de passe oublié -->
            <div class="flex justify-end mb-[13px]">

                <a
                    href="#"
                    class="text-[13px] font-medium text-[#ff9900] underline underline-offset-2 hover:text-[#e68a00]">
                    Mot de passe oublié ?
                </a>

            </div>


            <!-- Bouton connexion -->
            <button
                type="submit"
                class="w-full h-[41px] bg-[#ff9900] hover:bg-[#ed8d00] rounded-[9px] text-[13px] font-extrabold text-[#111111] transition duration-200">
                Se connecter
            </button>

        </form>


        <!-- Séparation -->
        <div class="border-t border-[#eeeeee] mt-[16px] pt-[13px]">

            <p class="text-center text-[13px] text-[#222222]">

                Vous n'avez pas encore de compte client ?

                <a
                    href="/register"
                    class="ml-[8px] font-bold text-[#ff9900] underline underline-offset-2 hover:text-[#e68a00]">
                    Créer un compte
                </a>

            </p>

        </div>

    </div>

</section>