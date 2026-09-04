<?php

$client = $client ?? null;
$utilisateur = $utilisateur ?? [];
$erreurs = $erreurs ?? [];
$form = $form ?? [];
$success = $success ?? null;


/*
|--------------------------------------------------------------------------
| RÉCUPÉRATION DES INFORMATIONS
|--------------------------------------------------------------------------
|
| Après une erreur, on privilégie les données saisies dans le formulaire.
| Sinon, on utilise les données de session puis celles du Client.
|
*/

$nomBrut = $form['nom']
    ?? ($utilisateur['nom'] ?? null)
    ?? ($client !== null ? $client->getNom() : '');

$prenomBrut = $form['prenom']
    ?? ($utilisateur['prenom'] ?? null)
    ?? ($client !== null ? $client->getPrenom() : '');

$emailBrut = $form['email']
    ?? ($utilisateur['email'] ?? null)
    ?? ($client !== null ? $client->getEmail() : '');

$telephoneBrut = $form['telephone']
    ?? ($client !== null ? $client->getTelephone() : '');

$adresseBrut = $form['adresse']
    ?? ($client !== null ? $client->getAdresse() : '');


/*
|--------------------------------------------------------------------------
| NETTOYAGE
|--------------------------------------------------------------------------
*/

$nomBrut = trim((string) $nomBrut);
$prenomBrut = trim((string) $prenomBrut);
$emailBrut = trim((string) $emailBrut);
$telephoneBrut = trim((string) $telephoneBrut);
$adresseBrut = trim((string) $adresseBrut);


/*
|--------------------------------------------------------------------------
| PROTECTION HTML
|--------------------------------------------------------------------------
*/

$nom = htmlspecialchars(
    $nomBrut,
    ENT_QUOTES,
    'UTF-8'
);

$prenom = htmlspecialchars(
    $prenomBrut,
    ENT_QUOTES,
    'UTF-8'
);

$email = htmlspecialchars(
    $emailBrut,
    ENT_QUOTES,
    'UTF-8'
);

$telephone = htmlspecialchars(
    $telephoneBrut,
    ENT_QUOTES,
    'UTF-8'
);

$adresse = htmlspecialchars(
    $adresseBrut,
    ENT_QUOTES,
    'UTF-8'
);


/*
|--------------------------------------------------------------------------
| INITIALES
|--------------------------------------------------------------------------
*/

$initiales = '';

if ($prenomBrut !== '') {
    $initiales .= strtoupper(
        mb_substr($prenomBrut, 0, 1)
    );
}

if ($nomBrut !== '') {
    $initiales .= strtoupper(
        mb_substr($nomBrut, 0, 1)
    );
}

if ($initiales === '') {
    $initiales = 'CL';
}


/*
|--------------------------------------------------------------------------
| AFFICHER UNE ERREUR SOUS UN CHAMP
|--------------------------------------------------------------------------
*/

function afficherErreur(array $erreurs, string $champ): void
{
    if (empty($erreurs[$champ])) {
        return;
    }

    echo '
        <p class="mt-1.5 flex items-center gap-1.5 font-[\'DM_Sans\'] text-[10px] font-medium leading-tight text-red-600">
            <i class="fa-solid fa-circle-exclamation text-[9px]"></i>
            <span>'
            . htmlspecialchars(
                (string) $erreurs[$champ],
                ENT_QUOTES,
                'UTF-8'
            )
            . '</span>
        </p>
    ';
}

?>

<div class="min-h-[calc(100vh-64px)] bg-[#faf9f7]">

    <!-- =========================================================
         EN-TÊTE DU PROFIL
    ========================================================== -->

    <section class="px-5 pb-7 pt-10">
        <div class="mx-auto max-w-[840px]">

            <div
                class="flex flex-col gap-5 rounded-[18px] border border-stone-200 bg-white px-7 py-6 shadow-sm sm:flex-row sm:items-center sm:justify-between"
            >

                <!-- PROFIL -->
                <div class="flex items-center gap-4">

                    <!-- INITIALES -->
                    <div
                        class="flex h-[62px] w-[62px] shrink-0 items-center justify-center rounded-[10px] bg-[#ff9900] font-['Inter'] text-[21px] font-extrabold text-black shadow-sm"
                    >
                        <?= htmlspecialchars(
                            $initiales,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                    <!-- NOM + EMAIL -->
                    <div>

                        <div class="flex flex-wrap items-center gap-3">

                            <h1
                                class="font-['Inter'] text-[20px] font-extrabold leading-tight text-black"
                            >
                                <?= $prenom ?>
                                <?= $nom ?>
                            </h1>

                            <span
                                class="inline-flex items-center gap-1.5 rounded-[5px] bg-[#dff8f1] px-3 py-1 font-['DM_Sans'] text-[9px] font-extrabold uppercase tracking-wide text-[#18a77a]"
                            >
                                <i class="fa-solid fa-user text-[9px]"></i>
                                Client
                            </span>

                        </div>

                        <p
                            class="mt-1 font-['DM_Sans'] text-[11px] text-[#777777]"
                        >
                            <?= $email ?>
                        </p>

                    </div>

                </div>


                <!-- BOUTON COMMANDES -->
                <a
                    href="/mes-commandes"
                    class="inline-flex h-[38px] shrink-0 items-center justify-center gap-2 rounded-full bg-[#f1f1f1] px-5 font-['DM_Sans'] text-[11px] font-bold text-[#222222] transition hover:bg-[#e7e7e7]"
                >
                    <i class="fa-solid fa-list text-[11px]"></i>
                    Mes commandes
                </a>

            </div>

        </div>
    </section>


    <!-- =========================================================
         CONTENU
    ========================================================== -->

    <main class="px-5 pb-14">
        <div class="mx-auto max-w-[840px]">


            <!-- =================================================
                 MESSAGE DE SUCCÈS UNIQUEMENT
            ================================================== -->

            <?php if (!empty($success)): ?>

                <div
                    class="mb-6 flex items-center gap-3 rounded-[10px] border border-green-200 bg-green-50 px-4 py-3 font-['DM_Sans'] text-[12px] font-medium text-green-700"
                >

                    <i class="fa-solid fa-circle-check"></i>

                    <?php if ($success === 'password'): ?>

                        Votre mot de passe a été modifié avec succès.

                    <?php else: ?>

                        Vos informations ont été mises à jour avec succès.

                    <?php endif; ?>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 DEUX CARTES
            ================================================== -->

            <div class="grid gap-6 md:grid-cols-2">


                <!-- =================================================
                     INFORMATIONS PERSONNELLES
                ================================================== -->

                <section
                    class="rounded-[17px] border border-stone-200 bg-white p-7 shadow-sm"
                >

                    <!-- TITRE -->
                    <div class="mb-6">

                        <div class="flex items-center gap-3">

                            <i
                                class="fa-regular fa-user text-[18px] text-[#ff9900]"
                            ></i>

                            <h2
                                class="font-['Inter'] text-[14px] font-extrabold text-black"
                            >
                                Informations Personnelles
                            </h2>

                        </div>

                        <p
                            class="mt-2 font-['DM_Sans'] text-[12px] text-[#777777]"
                        >
                            Mettez à jour vos coordonnées de livraison
                        </p>

                    </div>


                    <!-- FORMULAIRE INFORMATIONS -->

                    <form
                        action="/profil"
                        method="POST"
                        novalidate
                        class="space-y-4"
                    >


                        <!-- PRÉNOM / NOM -->

                        <div class="grid grid-cols-2 gap-4">


                            <!-- PRÉNOM -->

                            <div>

                                <label
                                    for="prenom"
                                    class="mb-1.5 block font-['DM_Sans'] text-[10px] font-extrabold uppercase text-[#222222]"
                                >
                                    Prénom
                                </label>

                                <input
                                    type="text"
                                    id="prenom"
                                    name="prenom"
                                    value="<?= $prenom ?>"
                                    placeholder="Prénom"
                                    class="h-[36px] w-full rounded-[8px] border <?= !empty($erreurs['prenom']) ? 'border-red-400 bg-red-50' : 'border-stone-200 bg-[#fafafa]' ?> px-3 font-['DM_Sans'] text-[11px] text-[#222222] outline-none transition placeholder:text-[#999999] focus:border-[#ff9900] focus:bg-white focus:ring-1 focus:ring-[#ff9900]/20"
                                >

                                <?php afficherErreur($erreurs, 'prenom'); ?>

                            </div>


                            <!-- NOM -->

                            <div>

                                <label
                                    for="nom"
                                    class="mb-1.5 block font-['DM_Sans'] text-[10px] font-extrabold uppercase text-[#222222]"
                                >
                                    Nom
                                </label>

                                <input
                                    type="text"
                                    id="nom"
                                    name="nom"
                                    value="<?= $nom ?>"
                                    placeholder="Nom"
                                    class="h-[36px] w-full rounded-[8px] border <?= !empty($erreurs['nom']) ? 'border-red-400 bg-red-50' : 'border-stone-200 bg-[#fafafa]' ?> px-3 font-['DM_Sans'] text-[11px] text-[#222222] outline-none transition placeholder:text-[#999999] focus:border-[#ff9900] focus:bg-white focus:ring-1 focus:ring-[#ff9900]/20"
                                >

                                <?php afficherErreur($erreurs, 'nom'); ?>

                            </div>

                        </div>


                        <!-- EMAIL -->

                        <div>

                            <label
                                for="email"
                                class="mb-1.5 block font-['DM_Sans'] text-[10px] font-extrabold uppercase text-[#222222]"
                            >
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?= $email ?>"
                                placeholder="exemple@email.com"
                                class="h-[36px] w-full rounded-[8px] border <?= !empty($erreurs['email']) ? 'border-red-400 bg-red-50' : 'border-stone-200 bg-[#fafafa]' ?> px-3 font-['DM_Sans'] text-[11px] text-[#222222] outline-none transition placeholder:text-[#999999] focus:border-[#ff9900] focus:bg-white focus:ring-1 focus:ring-[#ff9900]/20"
                            >

                            <!-- ERREUR EMAIL -->
                            <?php afficherErreur($erreurs, 'email'); ?>

                        </div>


                        <!-- TÉLÉPHONE -->

                        <div>

                            <label
                                for="telephone"
                                class="mb-1.5 block font-['DM_Sans'] text-[10px] font-extrabold uppercase text-[#222222]"
                            >
                                Téléphone
                            </label>

                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                value="<?= $telephone ?>"
                                placeholder="+221 77 123 45 67"
                                class="h-[36px] w-full rounded-[8px] border <?= !empty($erreurs['telephone']) ? 'border-red-400 bg-red-50' : 'border-stone-200 bg-[#fafafa]' ?> px-3 font-['DM_Sans'] text-[11px] text-[#222222] outline-none transition placeholder:text-[#999999] focus:border-[#ff9900] focus:bg-white focus:ring-1 focus:ring-[#ff9900]/20"
                            >

                            <!-- ERREUR TÉLÉPHONE -->
                            <?php afficherErreur($erreurs, 'telephone'); ?>

                        </div>


                        <!-- ADRESSE -->

                        <div>

                            <label
                                for="adresse"
                                class="mb-1.5 block font-['DM_Sans'] text-[10px] font-extrabold uppercase text-[#222222]"
                            >
                                Adresse de livraison
                            </label>

                            <input
                                type="text"
                                id="adresse"
                                name="adresse"
                                value="<?= $adresse ?>"
                                placeholder="Almadies, Dakar"
                                class="h-[36px] w-full rounded-[8px] border <?= !empty($erreurs['adresse']) ? 'border-red-400 bg-red-50' : 'border-stone-200 bg-[#fafafa]' ?> px-3 font-['DM_Sans'] text-[11px] text-[#222222] outline-none transition placeholder:text-[#999999] focus:border-[#ff9900] focus:bg-white focus:ring-1 focus:ring-[#ff9900]/20"
                            >

                            <?php afficherErreur($erreurs, 'adresse'); ?>

                        </div>


                        <!-- BOUTON -->

                        <button
                            type="submit"
                            class="mt-2 flex h-[40px] w-full items-center justify-center gap-2 rounded-[8px] bg-[#ff9900] font-['DM_Sans'] text-[11px] font-extrabold text-black transition hover:bg-[#e88c00]"
                        >

                            <i class="fa-regular fa-floppy-disk text-[12px]"></i>

                            Enregistrer les modifications

                        </button>

                    </form>

                </section>


                <!-- =================================================
                     SÉCURITÉ
                ================================================== -->

                <section
                    class="rounded-[17px] border border-stone-200 bg-white p-7 shadow-sm"
                >

                    <!-- TITRE -->

                    <div class="mb-6">

                        <div class="flex items-center gap-3">

                            <i
                                class="fa-solid fa-key text-[18px] text-[#ff9900]"
                            ></i>

                            <h2
                                class="font-['Inter'] text-[14px] font-extrabold text-black"
                            >
                                Sécurité du Compte
                            </h2>

                        </div>

                        <p
                            class="mt-2 font-['DM_Sans'] text-[12px] text-[#777777]"
                        >
                            Changer votre mot de passe (min. 6 caractères)
                        </p>

                    </div>


                    <!-- FORMULAIRE MOT DE PASSE -->

                    <form
                        action="/profil/mot-de-passe"
                        method="POST"
                        id="password-form"
                        novalidate
                        class="space-y-4"
                    >


                        <!-- =================================================
                             ANCIEN MOT DE PASSE
                        ================================================== -->

                        <div>

                            <label
                                for="ancien_mdp"
                                class="mb-1.5 block font-['DM_Sans'] text-[10px] font-extrabold uppercase text-[#222222]"
                            >
                                Mot de passe actuel
                            </label>

                            <div class="relative">

                                <input
                                    type="password"
                                    id="ancien_mdp"
                                    name="ancien_mdp"
                                    class="h-[36px] w-full rounded-[8px] border <?= !empty($erreurs['ancien_mdp']) ? 'border-red-400 bg-red-50' : 'border-stone-200 bg-[#fafafa]' ?> px-3 pr-10 font-['DM_Sans'] text-[11px] text-[#222222] outline-none transition focus:border-[#ff9900] focus:bg-white focus:ring-1 focus:ring-[#ff9900]/20"
                                >

                                <button
                                    type="button"
                                    class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#777777] transition hover:text-[#ff9900]"
                                    data-target="ancien_mdp"
                                    title="Afficher le mot de passe"
                                >
                                    <i class="fa-regular fa-eye text-[11px]"></i>
                                </button>

                            </div>

                            <?php afficherErreur($erreurs, 'ancien_mdp'); ?>

                        </div>


                        <!-- =================================================
                             NOUVEAU MOT DE PASSE
                        ================================================== -->

                        <div>

                            <label
                                for="nouveau_mdp"
                                class="mb-1.5 block font-['DM_Sans'] text-[10px] font-extrabold uppercase text-[#222222]"
                            >
                                Nouveau mot de passe
                            </label>

                            <div class="relative">

                                <input
                                    type="password"
                                    id="nouveau_mdp"
                                    name="nouveau_mdp"
                                    placeholder="Min. 6 caractères"
                                    class="h-[36px] w-full rounded-[8px] border <?= !empty($erreurs['nouveau_mdp']) ? 'border-red-400 bg-red-50' : 'border-stone-200 bg-[#fafafa]' ?> px-3 pr-10 font-['DM_Sans'] text-[11px] text-[#222222] outline-none transition placeholder:text-[#999999] focus:border-[#ff9900] focus:bg-white focus:ring-1 focus:ring-[#ff9900]/20"
                                >

                                <button
                                    type="button"
                                    class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#777777] transition hover:text-[#ff9900]"
                                    data-target="nouveau_mdp"
                                    title="Afficher le mot de passe"
                                >
                                    <i class="fa-regular fa-eye text-[11px]"></i>
                                </button>

                            </div>

                            <?php afficherErreur($erreurs, 'nouveau_mdp'); ?>

                        </div>


                        <!-- =================================================
                             CONFIRMATION
                        ================================================== -->

                        <div>

                            <label
                                for="confirmation_mdp"
                                class="mb-1.5 block font-['DM_Sans'] text-[10px] font-extrabold uppercase text-[#222222]"
                            >
                                Confirmer le nouveau mot de passe
                            </label>

                            <div class="relative">

                                <input
                                    type="password"
                                    id="confirmation_mdp"
                                    name="confirmation_mdp"
                                    placeholder="Confirmer le mot de passe"
                                    class="h-[36px] w-full rounded-[8px] border <?= !empty($erreurs['confirmation_mdp']) ? 'border-red-400 bg-red-50' : 'border-stone-200 bg-[#fafafa]' ?> px-3 pr-10 font-['DM_Sans'] text-[11px] text-[#222222] outline-none transition placeholder:text-[#999999] focus:border-[#ff9900] focus:bg-white focus:ring-1 focus:ring-[#ff9900]/20"
                                >

                                <button
                                    type="button"
                                    class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#777777] transition hover:text-[#ff9900]"
                                    data-target="confirmation_mdp"
                                    title="Afficher le mot de passe"
                                >
                                    <i class="fa-regular fa-eye text-[11px]"></i>
                                </button>

                            </div>

                            <?php afficherErreur($erreurs, 'confirmation_mdp'); ?>

                        </div>


                        <!-- =================================================
                             BOUTON MOT DE PASSE
                        ================================================== -->

                        <button
                            type="submit"
                            class="mt-3 flex h-[40px] w-full items-center justify-center gap-2 rounded-[8px] bg-black font-['DM_Sans'] text-[11px] font-extrabold text-white transition hover:bg-[#222222]"
                        >

                            <i class="fa-solid fa-lock text-[11px]"></i>

                            Mettre à jour le mot de passe

                        </button>

                    </form>

                </section>

            </div>

        </div>
    </main>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | AFFICHER / MASQUER LES MOTS DE PASSE
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.password-toggle').forEach(function (button) {

        button.addEventListener('click', function () {

            const targetId = button.dataset.target;

            const input = document.getElementById(targetId);

            const icon = button.querySelector('i');

            if (!input || !icon) {
                return;
            }

            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove('fa-eye');

                icon.classList.add('fa-eye-slash');

                button.title = 'Masquer le mot de passe';

            } else {

                input.type = 'password';

                icon.classList.remove('fa-eye-slash');

                icon.classList.add('fa-eye');

                button.title = 'Afficher le mot de passe';
            }

        });

    });

});

</script>