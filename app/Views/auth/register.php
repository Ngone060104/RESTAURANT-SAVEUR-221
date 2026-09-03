<?php

/** @var array $erreurs */

$erreurs = $erreurs ?? [];

$prenom = $_POST['prenom'] ?? '';
$nom = $_POST['nom'] ?? '';
$email = $_POST['email'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$adresse = $_POST['adresse'] ?? '';

?>

<section class="bg-[#faf9f7] px-6 py-6 lg:px-14 lg:py-6">

    <div class="mx-auto max-w-[1128px] overflow-hidden rounded-[30px] border border-stone-100 bg-white shadow-sm">

        <div class="grid min-h-[654px] lg:grid-cols-[1.03fr_0.97fr]">

            <!-- ================================================= -->
            <!-- IMAGE -->
            <!-- ================================================= -->
            <div class="relative hidden min-h-[654px] lg:block">

                <img
                    src="/assets/images/register-food.jpg"
                    alt="Cuisine sénégalaise"
                    class="absolute inset-0 h-full w-full object-cover">

            </div>


            <!-- ================================================= -->
            <!-- FORMULAIRE -->
            <!-- ================================================= -->
            <div class="flex flex-col justify-center px-8 py-10 sm:px-12 lg:px-14">

                <!-- Logo utilisateur -->
                <div class="mb-4 flex justify-center">

                    <div class="flex h-[50px] w-[50px] items-center justify-center rounded-[13px] bg-orange-500">
                        <i class="fa-solid fa-user text-white text-[24px]"></i>
                    </div>
                </div>


                <!-- ================================================= -->
                <!-- TITRE -->
                <!-- ================================================= -->
                <h1 class="text-center text-[25px] font-extrabold leading-tight text-stone-900">
                    Créer un compte Client Saveur 221
                </h1>


                <!-- ================================================= -->
                <!-- SOUS-TITRE -->
                <!-- ================================================= -->
                <p class="mx-auto mt-2 max-w-[500px] text-center text-[13px] leading-5 text-stone-500">
                    Enregistrez vos coordonnées pour commander facilement
                    et suivre vos repas
                </p>


                <!-- ================================================= -->
                <!-- FORMULAIRE -->
                <!-- ================================================= -->
                <form
                    action="/register"
                    method="post"
                    class="mt-6 space-y-4"
                    novalidate>

                    <!-- ================================================= -->
                    <!-- PRÉNOM / NOM -->
                    <!-- ================================================= -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <!-- PRÉNOM -->
                        <div>

                            <label
                                for="prenom"
                                class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.03em] text-stone-700">
                                PRENOM
                                <span class="text-orange-500">*</span>
                            </label>

                            <div class="relative">

                                <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                    <i class="fa-regular fa-user text-[16px]"></i>
                                </div>

                                <input
                                    id="prenom"
                                    type="text"
                                    name="prenom"
                                    value="<?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Ex : Michel"
                                    autocomplete="given-name"
                                    required
                                    class="h-[40px] w-full rounded-[10px] border border-stone-200 bg-[#fafafa] pl-10 pr-3 text-[12px] text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100">

                            </div>

                            <!-- Erreur prénom -->
                            <?php if (!empty($erreurs['prenom'])): ?>

                                <p class="mt-1 text-[10px] font-semibold text-red-500">
                                    <?= htmlspecialchars($erreurs['prenom'], ENT_QUOTES, 'UTF-8') ?>
                                </p>

                            <?php endif; ?>

                        </div>


                        <!-- NOM -->
                        <div>

                            <label
                                for="nom"
                                class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.03em] text-stone-700">
                                NOM
                                <span class="text-orange-500">*</span>
                            </label>

                            <div class="relative">

                                <!-- Icône -->


                                <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                    <i class="fa-regular fa-user text-[16px]"></i>
                                </div>

                                <input
                                    id="nom"
                                    type="text"
                                    name="nom"
                                    value="<?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Ex : Diop"
                                    autocomplete="family-name"
                                    required
                                    class="h-[40px] w-full rounded-[10px] border border-stone-200 bg-[#fafafa] pl-10 pr-3 text-[12px] text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100">

                            </div>

                            <!-- Erreur nom -->
                            <?php if (!empty($erreurs['nom'])): ?>

                                <p class="mt-1 text-[10px] font-semibold text-red-500">
                                    <?= htmlspecialchars($erreurs['nom'], ENT_QUOTES, 'UTF-8') ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- EMAIL -->
                    <!-- ================================================= -->
                    <div>

                        <label
                            for="email"
                            class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.03em] text-stone-700">
                            ADRESSE EMAIL (IDENTIFIANT UNIQUE)
                            <span class="text-orange-500">*</span>
                        </label>

                        <div class="relative">

                            <!-- Icône -->
                            <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                <i class="fa-regular fa-envelope text-[16px]"></i>
                            </div>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Ex : MichelDiop@gmail.com"
                                autocomplete="email"
                                required
                                class="h-[40px] w-full rounded-[10px] border border-stone-200 bg-[#fafafa] pl-10 pr-3 text-[12px] text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100">

                        </div>

                        <!-- Erreur email -->
                        <?php if (!empty($erreurs['email'])): ?>

                            <p class="mt-1 text-[10px] font-semibold text-red-500">
                                <?= htmlspecialchars($erreurs['email'], ENT_QUOTES, 'UTF-8') ?>
                            </p>

                        <?php endif; ?>

                    </div>


                    <!-- ================================================= -->
                    <!-- TÉLÉPHONE / ADRESSE -->
                    <!-- ================================================= -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <!-- TÉLÉPHONE -->
                        <div>

                            <label
                                for="telephone"
                                class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.03em] text-stone-700">
                                TÉLÉPHONE (SÉNÉGAL)
                                <span class="text-orange-500">*</span>
                            </label>

                            <div class="relative">

                                <!-- Icône -->
                                <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                    <i class="fas fa-phone text-[16px]"></i>
                                </div>



                                <input
                                    id="telephone"
                                    type="tel"
                                    name="telephone"
                                    value="<?= htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Ex : 77 123 45 67"
                                    autocomplete="tel"
                                    required
                                    class="h-[40px] w-full rounded-[10px] border border-stone-200 bg-[#fafafa] pl-10 pr-3 text-[12px] text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100">

                            </div>

                            <!-- Erreur téléphone -->
                            <?php if (!empty($erreurs['telephone'])): ?>

                                <p class="mt-1 text-[10px] font-semibold text-red-500">
                                    <?= htmlspecialchars($erreurs['telephone'], ENT_QUOTES, 'UTF-8') ?>
                                </p>

                            <?php endif; ?>

                        </div>


                        <!-- ADRESSE -->
                        <div>

                            <label
                                for="adresse"
                                class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.03em] text-stone-700">
                                ADRESSE DE LIVRAISON
                                <span class="text-orange-500">*</span>
                            </label>

                            <div class="relative">

                                <!-- Icône -->
                                <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                    <i class="fa-solid fa-location-dot text-[16px]"></i>
                                </div>


                                <input
                                    id="adresse"
                                    type="text"
                                    name="adresse"
                                    value="<?= htmlspecialchars($adresse, ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Ex : Michel"
                                    autocomplete="street-address"
                                    required
                                    class="h-[40px] w-full rounded-[10px] border border-stone-200 bg-[#fafafa] pl-10 pr-3 text-[12px] text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100">

                            </div>

                            <!-- Erreur adresse -->
                            <?php if (!empty($erreurs['adresse'])): ?>

                                <p class="mt-1 text-[10px] font-semibold text-red-500">
                                    <?= htmlspecialchars($erreurs['adresse'], ENT_QUOTES, 'UTF-8') ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- MOT DE PASSE / CONFIRMATION -->
                    <!-- ================================================= -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <!-- MOT DE PASSE -->
                        <div>

                            <label
                                for="mdp"
                                class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.03em] text-stone-700">
                                MOT DE PASSE (MIN. 6 CARACTÈRES)
                                <span class="text-orange-500">*</span>
                            </label>

                            <div class="relative">

                                <!-- Icône -->
                                <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                    <i class="fa-solid fa-lock text-[15px]"></i>
                                </div>

                                <input
                                    id="mdp"
                                    type="password"
                                    name="mdp"
                                    placeholder="Ex : Michel"
                                    minlength="6"
                                    autocomplete="new-password"
                                    required
                                    class="h-[40px] w-full rounded-[10px] border border-stone-200 bg-[#fafafa] pl-10 pr-3 text-[12px] text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100">

                            </div>

                            <!-- Erreur mot de passe -->
                            <?php if (!empty($erreurs['mdp'])): ?>

                                <p class="mt-1 text-[10px] font-semibold text-red-500">
                                    <?= htmlspecialchars($erreurs['mdp'], ENT_QUOTES, 'UTF-8') ?>
                                </p>

                            <?php endif; ?>

                        </div>


                        <!-- CONFIRMATION -->
                        <div>

                            <label
                                for="confirmation_mdp"
                                class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.03em] text-stone-700">
                                CONFIRMATION DU MOT DE PASSE
                                <span class="text-orange-500">*</span>
                            </label>

                            <div class="relative">

                                <!-- Icône -->
                                <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                    <i class="fa-solid fa-lock text-[15px]"></i>
                                </div>

                                <input
                                    id="confirmation_mdp"
                                    type="password"
                                    name="confirmation_mdp"
                                    placeholder="Ex : Michel"
                                    minlength="6"
                                    autocomplete="new-password"
                                    required
                                    class="h-[40px] w-full rounded-[10px] border border-stone-200 bg-[#fafafa] pl-10 pr-3 text-[12px] text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100">

                            </div>

                            <!-- Erreur confirmation -->
                            <?php if (!empty($erreurs['confirmation_mdp'])): ?>

                                <p class="mt-1 text-[10px] font-semibold text-red-500">
                                    <?= htmlspecialchars($erreurs['confirmation_mdp'], ENT_QUOTES, 'UTF-8') ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- BOUTON -->
                    <!-- ================================================= -->
                    <button
                        type="submit"
                        class="mt-2 flex h-[48px] w-full items-center justify-center gap-2 rounded-full bg-orange-500 px-5 text-[13px] font-extrabold text-white transition hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:ring-offset-2">
                        <i class="fa-solid fa-user-plus text-[16px]"></i>
                        Créer mon compte client
                    </button>

                </form>


                <!-- ================================================= -->
                <!-- CONNEXION -->
                <!-- ================================================= -->
                <div class="mt-5 text-center text-[12px] text-stone-500">

                    Vous avez déjà un compte ?

                    <a
                        href="/login"
                        class="ml-1 font-bold text-orange-500 underline decoration-orange-300 underline-offset-2 transition hover:text-orange-600">
                        Se connecter
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>