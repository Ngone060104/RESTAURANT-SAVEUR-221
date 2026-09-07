<?php

$utilisateurs = $utilisateurs ?? [];
$erreurs =
    $erreurs ?? [];

$typeFormulaire =
    $typeFormulaire ?? 'ajout';

$donneesFormulaire =
    $donneesFormulaire ?? [];

$ouvrirModal =
    $ouvrirModal ?? false;

$erreurNom =
    $erreurs['nom'] ?? null;

$erreurPrenom =
    $erreurs['prenom'] ?? null;

$erreurEmail =
    $erreurs['email'] ?? null;

$erreurMdp =
    $erreurs['mdp'] ?? null;

$erreurRole =
    $erreurs['role_id'] ?? null;
?>

<div class="space-y-6">

    <!-- ===================================================== -->
    <!-- EN-TÊTE -->
    <!-- ===================================================== -->


    <section
        class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-orange-900 shadow-sm">


        <div
            class="flex flex-col gap-5 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <div class="flex items-center gap-2">
                    <div class="h-1 w-8 rounded-full bg-[#ff9500]"></div>

                    <span class="text-xs font-semibold uppercase tracking-wider text-[#ff9500]">
                        Administration
                    </span>
                </div>


                <h1
                    class="text-2xl font-bold text-white sm:text-3xl">
                    Utilisateurs &amp; Rôles
                </h1>

                <p
                    class="mt-2 max-w-2xl text-sm text-gray-300">
                    Gérez les comptes des administrateurs et des gérants.
                </p>
            </div>


            <button
                type="button"
                onclick="ouvrirModalAjout()"
                class="inline-flex h-11 items-center justify-center gap-2 rounded-xl
                   bg-[#ff9500] px-5 text-sm font-semibold text-white shadow-sm
                   transition-all duration-300
                   hover:bg-[#e88700] hover:-translate-y-0.5 hover:shadow-lg
                   active:translate-y-0">
                <i class="fa-solid fa-user-plus"></i>
                Ajouter un utilisateur
            </button>

        </div>
    </section>



    <!-- ===================================================== -->
    <!-- RECHERCHE -->
    <!-- ===================================================== -->

    <?php
    $termeRecherche = $termeRecherche ?? '';
    ?>

    <section
        class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
        <form
            id="formRechercheUtilisateur"
            class="flex flex-col gap-3 md:flex-row">

            <div class="relative flex-1">

                <i
                    class="fa-solid fa-magnifying-glass
                       absolute left-4 top-1/2 -translate-y-1/2
                       text-sm text-gray-400"></i>

                <input
                    id="champRechercheUtilisateur"
                    type="text"
                    value="<?= htmlspecialchars(
                                $termeRecherche,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                    placeholder="Rechercher par nom, prénom ou email..."
                    class="h-11 w-full rounded-xl border border-gray-200
                       bg-gray-50 pl-11 pr-4 text-sm text-gray-700
                       outline-none transition
                       placeholder:text-gray-400
                       focus:border-[#ff9500]
                       focus:bg-white
                       focus:ring-4 focus:ring-orange-100">

            </div>


            <button
                type="submit"
                class="inline-flex h-11 items-center justify-center
                   gap-2 rounded-xl border border-gray-200
                   bg-white px-5 text-sm font-semibold
                   text-gray-700 transition
                   hover:border-[#ff9500]
                   hover:bg-orange-50
                   hover:text-[#ff9500]">
                <i class="fa-solid fa-search"></i>
                Rechercher
            </button>


            <?php if ($termeRecherche !== ''): ?>

                <a
                    href="/admin/utilisateurs"
                    class="inline-flex h-11 items-center
                       justify-center gap-2 rounded-xl
                       border border-gray-200 px-5
                       text-sm font-semibold text-gray-500
                       transition hover:bg-gray-50
                       hover:text-gray-700">
                    <i class="fa-solid fa-xmark"></i>
                    Réinitialiser
                </a>

            <?php endif; ?>

        </form>
    </section>


    <!-- ===================================================== -->
    <!-- STATISTIQUES RAPIDES -->
    <!-- ===================================================== -->

    <?php
    $nombreTotal = count($utilisateurs);

    $nombreAdmins = 0;
    $nombreGerants = 0;
    $nombreActifs = 0;

    foreach ($utilisateurs as $utilisateur) {

        if ($utilisateur->isAdmin()) {
            $nombreAdmins++;
        }

        if ($utilisateur->isGerant()) {
            $nombreGerants++;
        }

        if ($utilisateur->isActif()) {
            $nombreActifs++;
        }
    }
    ?>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        <!-- TOTAL -->

        <div
            class="group relative overflow-hidden rounded-2xl border
                   border-gray-100 bg-white p-5 shadow-sm
                   transition-all duration-300
                   hover:-translate-y-1 hover:shadow-xl">
            <div
                class="absolute left-0 top-0 h-1 w-0 bg-gray-700
                       transition-all duration-300 group-hover:w-full"></div>

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Total
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        <?= $nombreTotal ?>
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Utilisateurs internes
                    </p>
                </div>

                <div
                    class="flex h-12 w-12 items-center justify-center
                           rounded-xl bg-gray-100 text-gray-600
                           transition-all duration-300
                           group-hover:scale-110">
                    <i class="fa-solid fa-users"></i>
                </div>

            </div>
        </div>


        <!-- ADMIN -->

        <div
            class="group relative overflow-hidden rounded-2xl border
                   border-gray-100 bg-white p-5 shadow-sm
                   transition-all duration-300
                   hover:-translate-y-1 hover:shadow-xl">
            <div
                class="absolute left-0 top-0 h-1 w-0 bg-purple-500
                       transition-all duration-300 group-hover:w-full"></div>

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Administrateurs
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        <?= $nombreAdmins ?>
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Accès complet
                    </p>
                </div>

                <div
                    class="flex h-12 w-12 items-center justify-center
                           rounded-xl bg-purple-50 text-purple-600
                           transition-all duration-300
                           group-hover:scale-110 group-hover:rotate-3">
                    <i class="fa-solid fa-user-shield"></i>
                </div>

            </div>
        </div>


        <!-- GERANTS -->

        <div
            class="group relative overflow-hidden rounded-2xl border
                   border-gray-100 bg-white p-5 shadow-sm
                   transition-all duration-300
                   hover:-translate-y-1 hover:shadow-xl">
            <div
                class="absolute left-0 top-0 h-1 w-0 bg-orange-500
                       transition-all duration-300 group-hover:w-full"></div>

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Gérants
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        <?= $nombreGerants ?>
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Gestion opérationnelle
                    </p>
                </div>

                <div
                    class="flex h-12 w-12 items-center justify-center
                           rounded-xl bg-orange-50 text-[#ff9500]
                           transition-all duration-300
                           group-hover:scale-110 group-hover:rotate-3">
                    <i class="fa-solid fa-user-tie"></i>
                </div>

            </div>
        </div>

    </div>


    <!-- ===================================================== -->
    <!-- TABLEAU -->
    <!-- ===================================================== -->

    <section
        class="overflow-hidden rounded-2xl border border-gray-100
               bg-white shadow-sm">

        <!-- En-tête -->
        <div
            class="flex flex-col gap-2 border-b border-gray-100
                   bg-gray-50/70 px-5 py-4 md:flex-row md:items-center
                   md:justify-between">

            <div>
                <h2 class="text-sm font-bold text-gray-800">
                    Liste des utilisateurs
                </h2>

                <p class="mt-1 text-xs text-gray-400">
                    <?= $nombreTotal ?>
                    utilisateur<?= $nombreTotal > 1 ? 's' : '' ?>
                </p>
            </div>

            <div class="flex items-center gap-2 text-xs text-gray-400">
                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                <?= $nombreActifs ?> actif<?= $nombreActifs > 1 ? 's' : '' ?>
            </div>

        </div>


        <!-- ================================================= -->
        <!-- DESKTOP -->
        <!-- ================================================= -->

        <div class="hidden overflow-x-auto lg:block">

            <table class="w-full min-w-[900px]">

                <thead>

                    <tr class="border-b border-gray-100 bg-white">

                        <th
                            class="px-5 py-4 text-left text-[11px]
                                   font-bold uppercase tracking-wider
                                   text-gray-400">
                            Utilisateur
                        </th>

                        <th
                            class="px-5 py-4 text-left text-[11px]
                                   font-bold uppercase tracking-wider
                                   text-gray-400">
                            Email
                        </th>

                        <th
                            class="px-5 py-4 text-left text-[11px]
                                   font-bold uppercase tracking-wider
                                   text-gray-400">
                            Rôle
                        </th>

                        <th
                            class="px-5 py-4 text-left text-[11px]
                                   font-bold uppercase tracking-wider
                                   text-gray-400">
                            Statut
                        </th>

                        <th
                            class="px-5 py-4 text-left text-[11px]
                                   font-bold uppercase tracking-wider
                                   text-gray-400">
                            Créé le
                        </th>

                        <th
                            class="px-5 py-4 text-right text-[11px]
                                   font-bold uppercase tracking-wider
                                   text-gray-400">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    <?php if (empty($utilisateurs)): ?>

                        <tr>

                            <td colspan="6" class="px-5 py-16 text-center">

                                <div
                                    class="mx-auto flex h-14 w-14 items-center
                                       justify-center rounded-full bg-gray-100
                                       text-gray-400">
                                    <i class="fa-solid fa-users text-xl"></i>
                                </div>

                                <p class="mt-4 text-sm font-semibold text-gray-700">
                                    Aucun utilisateur trouvé
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Essayez de modifier votre recherche.
                                </p>

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($utilisateurs as $utilisateur): ?>

                            <?php
                            $initiale = mb_strtoupper(
                                mb_substr(
                                    $utilisateur->getPrenom(),
                                    0,
                                    1
                                )
                            );

                            $role = $utilisateur->getRole();

                            $roleClasse = match ($role) {
                                'ADMIN' =>
                                'bg-purple-50 text-purple-600',
                                'GERANT' =>
                                'bg-orange-50 text-orange-600',
                                default =>
                                'bg-gray-100 text-gray-600',
                            };

                            $roleIcone = match ($role) {
                                'ADMIN' => 'fa-user-shield',
                                'GERANT' => 'fa-user-tie',
                                default => 'fa-user',
                            };
                            ?>


                            <tr class="group transition hover:bg-gray-50/70">

                                <!-- Utilisateur -->

                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-xl bg-orange-50
                                               text-sm font-bold
                                               text-[#ff9500]">
                                            <?= htmlspecialchars(
                                                $initiale,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                        <div class="min-w-0">

                                            <p
                                                class="truncate text-sm
                                                   font-semibold text-gray-800">
                                                <?= htmlspecialchars(
                                                    $utilisateur->getNomComplet(),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-400">
                                                ID #<?= (int) $utilisateur->getId() ?>
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                <!-- Email -->

                                <td class="px-5 py-4">

                                    <span class="text-sm text-gray-600">
                                        <?= htmlspecialchars(
                                            $utilisateur->getEmail(),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <!-- Rôle -->

                                <td class="px-5 py-4">

                                    <span
                                        class="inline-flex items-center gap-2
                                           rounded-full px-3 py-1.5
                                           text-xs font-semibold
                                           <?= $roleClasse ?>">
                                        <i class="fa-solid <?= $roleIcone ?>"></i>

                                        <?= $role === 'GERANT'
                                            ? 'Gérant'
                                            : 'Administrateur'
                                        ?>
                                    </span>

                                </td>


                                <!-- Statut -->

                                <td class="px-5 py-4">

                                    <?php if ($utilisateur->isActif()): ?>

                                        <span
                                            class="inline-flex items-center gap-2
                                               rounded-full bg-green-50
                                               px-3 py-1.5 text-xs
                                               font-semibold text-green-600">
                                            <span
                                                class="h-1.5 w-1.5 rounded-full
                                                   bg-green-500"></span>

                                            Actif
                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="inline-flex items-center gap-2
                                               rounded-full bg-red-50
                                               px-3 py-1.5 text-xs
                                               font-semibold text-red-600">
                                            <span
                                                class="h-1.5 w-1.5 rounded-full
                                                   bg-red-500"></span>

                                            Inactif
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- Date -->

                                <td class="px-5 py-4">

                                    <span class="text-sm text-gray-500">

                                        <?php
                                        $dateCreation =
                                            $utilisateur->getDateCreation();
                                        ?>

                                        <?= $dateCreation
                                            ? date(
                                                'd/m/Y',
                                                strtotime($dateCreation)
                                            )
                                            : '-'
                                        ?>

                                    </span>

                                </td>


                                <!-- Actions -->

                                <td class="px-5 py-4">

                                    <div class="flex justify-end gap-1">

                                        <!-- Modifier -->

                                        <button
                                            type="button"
                                            onclick="ouvrirModalModification(
                                            <?= (int) $utilisateur->getId() ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $utilisateur->getNom()
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $utilisateur->getPrenom()
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $utilisateur->getEmail()
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $role
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                        )"
                                            title="Modifier"
                                            class="flex h-9 w-9 items-center
                                               justify-center rounded-lg
                                               text-gray-500 transition
                                               hover:bg-orange-50
                                               hover:text-[#ff9500]">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>


                                        <!-- Activer / Désactiver -->

                                        <?php if ($utilisateur->isActif()): ?>

                                            <form
                                                method="POST"
                                                action="/admin/utilisateurs/desactiver"
                                                class="inline">
                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int) $utilisateur->getId() ?>">

                                                <button
                                                    type="submit"
                                                    title="Désactiver"
                                                    class="flex h-9 w-9
                                                       items-center
                                                       justify-center
                                                       rounded-lg
                                                       text-gray-400
                                                       transition
                                                       hover:bg-red-50
                                                       hover:text-red-500">
                                                    <i class="fa-solid fa-user-slash"></i>
                                                </button>
                                            </form>

                                        <?php else: ?>

                                            <form
                                                method="POST"
                                                action="/admin/utilisateurs/activer"
                                                class="inline">
                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int) $utilisateur->getId() ?>">

                                                <button
                                                    type="submit"
                                                    title="Activer"
                                                    class="flex h-9 w-9
                                                       items-center
                                                       justify-center
                                                       rounded-lg
                                                       text-gray-400
                                                       transition
                                                       hover:bg-green-50
                                                       hover:text-green-500">
                                                    <i class="fa-solid fa-user-check"></i>
                                                </button>
                                            </form>

                                        <?php endif; ?>


                                        <!-- Supprimer -->

                                        <button
                                            type="button"
                                            onclick="ouvrirModalSuppression(
                                            <?= (int) $utilisateur->getId() ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $utilisateur->getNomComplet()
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        )"
                                            title="Supprimer"
                                            class="flex h-9 w-9 items-center
                                               justify-center rounded-lg
                                               text-gray-400 transition
                                               hover:bg-red-50
                                               hover:text-red-500">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>


        <!-- ================================================= -->
        <!-- MOBILE -->
        <!-- ================================================= -->

        <div class="divide-y divide-gray-100 lg:hidden">

            <?php if (empty($utilisateurs)): ?>

                <div class="px-5 py-16 text-center">

                    <div
                        class="mx-auto flex h-14 w-14 items-center
                               justify-center rounded-full bg-gray-100
                               text-gray-400">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>

                    <p class="mt-4 text-sm font-semibold text-gray-700">
                        Aucun utilisateur trouvé
                    </p>

                </div>

            <?php else: ?>

                <?php foreach ($utilisateurs as $utilisateur): ?>

                    <?php
                    $role = $utilisateur->getRole();

                    $initiale = mb_strtoupper(
                        mb_substr(
                            $utilisateur->getPrenom(),
                            0,
                            1
                        )
                    );
                    ?>

                    <div class="p-4">

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-11 w-11 shrink-0
                                       items-center justify-center
                                       rounded-xl bg-orange-50
                                       text-sm font-bold
                                       text-[#ff9500]">
                                <?= htmlspecialchars(
                                    $initiale,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>


                            <div class="min-w-0 flex-1">

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0">

                                        <p class="truncate text-sm font-semibold text-gray-800">
                                            <?= htmlspecialchars(
                                                $utilisateur->getNomComplet(),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </p>

                                        <p class="mt-1 truncate text-xs text-gray-400">
                                            <?= htmlspecialchars(
                                                $utilisateur->getEmail(),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </p>

                                    </div>

                                </div>


                                <div class="mt-3 flex flex-wrap items-center gap-2">

                                    <?php if ($role === 'ADMIN'): ?>

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full bg-purple-50
                                                   px-2.5 py-1 text-[10px]
                                                   font-semibold text-purple-600">
                                            <i class="fa-solid fa-user-shield"></i>
                                            Administrateur
                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full bg-orange-50
                                                   px-2.5 py-1 text-[10px]
                                                   font-semibold text-orange-600">
                                            <i class="fa-solid fa-user-tie"></i>
                                            Gérant
                                        </span>

                                    <?php endif; ?>


                                    <?php if ($utilisateur->isActif()): ?>

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full bg-green-50
                                                   px-2.5 py-1 text-[10px]
                                                   font-semibold text-green-600">
                                            <span
                                                class="h-1.5 w-1.5 rounded-full
                                                       bg-green-500"></span>
                                            Actif
                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full bg-red-50
                                                   px-2.5 py-1 text-[10px]
                                                   font-semibold text-red-600">
                                            <span
                                                class="h-1.5 w-1.5 rounded-full
                                                       bg-red-500"></span>
                                            Inactif
                                        </span>

                                    <?php endif; ?>

                                </div>


                                <!-- Actions mobile -->

                                <div class="mt-4 flex gap-2">

                                    <button
                                        type="button"
                                        onclick="ouvrirModalModification(
                                            <?= (int) $utilisateur->getId() ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $utilisateur->getNom()
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $utilisateur->getPrenom()
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $utilisateur->getEmail()
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $role
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                        )"
                                        class="inline-flex h-9 flex-1
                                               items-center justify-center
                                               gap-2 rounded-lg
                                               border border-gray-200
                                               text-xs font-semibold
                                               text-gray-600 transition
                                               hover:border-orange-200
                                               hover:bg-orange-50
                                               hover:text-[#ff9500]">
                                        <i class="fa-solid fa-pen"></i>
                                        Modifier
                                    </button>


                                    <button
                                        type="button"
                                        onclick="ouvrirModalSuppression(
                                            <?= (int) $utilisateur->getId() ?>,
                                            <?= htmlspecialchars(
                                                json_encode(
                                                    $utilisateur->getNomComplet()
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        )"
                                        class="inline-flex h-9 flex-1
                                               items-center justify-center
                                               gap-2 rounded-lg
                                               bg-red-50 text-xs
                                               font-semibold text-red-600
                                               transition hover:bg-red-100">
                                        <i class="fa-solid fa-trash"></i>
                                        Supprimer
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </section>

</div>


<!-- ========================================================= -->
<!-- MODAL AJOUT / MODIFICATION -->
<!-- ========================================================= -->

<div
    id="modalUtilisateur"
    class="fixed inset-0 z-[120] hidden items-start
           justify-center overflow-y-auto bg-gray-900/60 p-4
           backdrop-blur-sm sm:items-center"
    aria-hidden="true">

    <div
        id="contenuModalUtilisateur"
        class="my-4 w-full max-w-lg max-h-[90vh] overflow-y-auto
               overscroll-contain rounded-2xl bg-white shadow-2xl">

        <!-- Header -->

        <div
            class="flex items-center justify-between
                   border-b border-gray-100 px-6 py-4">

            <div>

                <p
                    id="modalUtilisateurSurTitre"
                    class="text-[11px] font-bold uppercase
                           tracking-wider text-[#ff9500]">
                    Nouveau compte
                </p>

                <h2
                    id="modalUtilisateurTitre"
                    class="mt-1 text-lg font-bold text-gray-900">
                    Ajouter un utilisateur
                </h2>

            </div>

            <button
                type="button"
                onclick="fermerModalUtilisateur()"
                class="flex h-9 w-9 items-center justify-center
                       rounded-lg text-gray-400 transition
                       hover:bg-gray-100 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <!-- Formulaire -->

        <form
            id="formUtilisateur"
            method="POST"
            action="/admin/utilisateurs"
            class="px-6 py-5"
            novalidate>


            <input
                type="hidden"
                id="utilisateurId"
                name="id"
                value="">


            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <!-- Nom -->

                <div>
                    <label
                        for="nom"
                        class="mb-1.5 block text-xs font-semibold text-gray-600">
                        Nom
                    </label>

                    <input
                        id="nom"
                        name="nom"
                        type="text"
                        value="<?= htmlspecialchars(
                                    $donneesFormulaire['nom'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"

                        class="h-11 w-full rounded-xl border
               <?= $erreurNom
                    ? 'border-red-300 bg-red-50'
                    : 'border-gray-200'
                ?>
               px-4 text-sm text-gray-700 outline-none transition
               focus:border-[#ff9500]
               focus:ring-4 focus:ring-orange-100">

                    <?php if ($erreurNom): ?>

                        <p class="mt-1.5 flex items-start gap-1.5 text-xs leading-5 text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= htmlspecialchars(
                                $erreurNom,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                    <?php endif; ?>
                </div>


                <!-- Prénom -->

                <div>
                    <label
                        for="prenom"
                        class="mb-1.5 block text-xs font-semibold text-gray-600">
                        Prénom
                    </label>

                    <input
                        id="prenom"
                        name="prenom"
                        type="text"
                        value="<?= htmlspecialchars(
                                    $donneesFormulaire['prenom'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                        class="h-11 w-full rounded-xl border
               <?= $erreurPrenom
                    ? 'border-red-300 bg-red-50'
                    : 'border-gray-200'
                ?>
               px-4 text-sm text-gray-700 outline-none transition
               focus:border-[#ff9500]
               focus:ring-4 focus:ring-orange-100">

                    <?php if ($erreurPrenom): ?>

                        <p class="mt-1.5 flex items-start gap-1.5 text-xs leading-5 text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= htmlspecialchars(
                                $erreurPrenom,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                    <?php endif; ?>
                </div>


                <!-- Email -->

                <div>

                    <label
                        for="email"
                        class="mb-1.5 block text-xs font-semibold text-gray-600">
                        Adresse email
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="<?= htmlspecialchars(
                                    $donneesFormulaire['email'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                        class="h-11 w-full rounded-xl border
               <?= $erreurEmail
                    ? 'border-red-300 bg-red-50'
                    : 'border-gray-200'
                ?>
               px-4 text-sm text-gray-700 outline-none transition
               focus:border-[#ff9500]
               focus:ring-4 focus:ring-orange-100">

                    <?php if ($erreurEmail): ?>

                        <p class="mt-1.5 flex items-start gap-1.5 text-xs leading-5 text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= htmlspecialchars(
                                $erreurEmail,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                    <?php endif; ?>

                </div>


                <!-- Mot de passe -->

                <div
                    id="blocMotDePasse">
                    <label
                        for="mdp"
                        class="mb-1.5 block text-xs font-semibold text-gray-600">
                        Mot de passe
                    </label>

                    <input
                        id="mdp"
                        name="mdp"
                        type="password"
                        minlength="6"
                        <?= $typeFormulaire === 'ajout'
                            ? 'required'
                            : ''
                        ?>
                        class="h-11 w-full rounded-xl border
               <?= $erreurMdp
                    ? 'border-red-300 bg-red-50'
                    : 'border-gray-200'
                ?>
               px-4 text-sm text-gray-700 outline-none transition
               focus:border-[#ff9500]
               focus:ring-4 focus:ring-orange-100">

                    <p class="mt-1.5 text-[11px] text-gray-400">
                        Minimum 6 caractères.
                    </p>

                    <?php if ($erreurMdp): ?>

                        <p class="mt-1.5 flex items-start gap-1.5 text-xs leading-5 text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= htmlspecialchars(
                                $erreurMdp,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                    <?php endif; ?>

                </div>


                <!-- Rôle -->

                <div class="sm:col-span-2">

                    <label
                        for="role_id"
                        class="mb-1.5 block text-xs font-semibold text-gray-600">
                        Rôle
                    </label>

                    <select
                        id="role_id"
                        name="role_id"
                        class="h-11 w-full rounded-xl border
               <?= $erreurRole
                    ? 'border-red-300 bg-red-50'
                    : 'border-gray-200'
                ?>
               bg-white px-4 text-sm text-gray-700
               outline-none transition
               focus:border-[#ff9500]
               focus:ring-4 focus:ring-orange-100">
                        <option value="">
                            Sélectionner un rôle
                        </option>

                        <option
                            value="1"
                            <?= (int) ($donneesFormulaire['role_id'] ?? 0) === 1
                                ? 'selected'
                                : ''
                            ?>>
                            Administrateur
                        </option>

                        <option
                            value="2"
                            <?= (int) ($donneesFormulaire['role_id'] ?? 0) === 2
                                ? 'selected'
                                : ''
                            ?>>
                            Gérant
                        </option>

                    </select>

                    <?php if ($erreurRole): ?>

                        <p class="mt-1.5 flex items-start gap-1.5 text-xs leading-5 text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= htmlspecialchars(
                                $erreurRole,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                    <?php endif; ?>

                </div>


            </div>


            <!-- Boutons -->

            <div class="mt-6 grid grid-cols-1 gap-3 pb-1 sm:grid-cols-2">

                <button
                    type="button"
                    onclick="fermerModalUtilisateur()"
                    class="inline-flex h-11 items-center
                           justify-center rounded-xl border
                           border-gray-200 px-4 text-sm
                           font-semibold text-gray-600
                           transition hover:bg-gray-50">
                    Annuler
                </button>

                <button
                    type="submit"
                    id="boutonUtilisateur"
                    class="inline-flex h-11 items-center
                           justify-center gap-2 rounded-xl
                           bg-[#ff9500] px-4 text-sm font-semibold
                           text-white transition
                           hover:bg-[#e88700]">
                    <i class="fa-solid fa-user-plus"></i>
                    Ajouter
                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- MODAL SUPPRESSION -->
<!-- ========================================================= -->

<div
    id="modalSuppressionUtilisateur"
    class="fixed inset-0 z-[120] hidden items-center
           justify-center bg-gray-900/60 p-4 backdrop-blur-sm"
    aria-hidden="true">

    <div
        class="w-full max-w-md overflow-hidden rounded-2xl
               bg-white shadow-2xl">

        <div class="p-6 text-center">

            <div
                class="mx-auto flex h-14 w-14 items-center
                       justify-center rounded-full bg-red-50
                       text-red-500">
                <i class="fa-solid fa-trash text-xl"></i>
            </div>

            <h2 class="mt-4 text-lg font-bold text-gray-900">
                Supprimer cet utilisateur ?
            </h2>

            <p
                id="texteSuppressionUtilisateur"
                class="mx-auto mt-2 max-w-sm text-sm
                       leading-6 text-gray-500">
                Cette action est irréversible.
            </p>


            <div class="mt-6 grid grid-cols-2 gap-3">

                <button
                    type="button"
                    onclick="fermerModalSuppression()"
                    class="inline-flex h-11 items-center
                           justify-center rounded-xl border
                           border-gray-200 text-sm font-semibold
                           text-gray-600 transition hover:bg-gray-50">
                    Annuler
                </button>


                <form
                    method="POST"
                    action="/admin/utilisateurs/delete">

                    <input
                        type="hidden"
                        id="suppressionId"
                        name="id"
                        value="">

                    <button
                        type="submit"
                        class="inline-flex h-11 w-full
                               items-center justify-center gap-2
                               rounded-xl bg-red-500 px-4
                               text-sm font-semibold text-white
                               transition hover:bg-red-600">
                        <i class="fa-solid fa-trash"></i>
                        Supprimer
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<script>
    const modalUtilisateur =
        document.getElementById('modalUtilisateur');

    const modalSuppressionUtilisateur =
        document.getElementById(
            'modalSuppressionUtilisateur'
        );

    const formUtilisateur =
        document.getElementById('formUtilisateur');


    function ouvrirModalAjout() {

        if (!modalUtilisateur || !formUtilisateur) {
            return;
        }

        formUtilisateur.reset();

        document.getElementById('utilisateurId').value = '';

        formUtilisateur.action =
            '/admin/utilisateurs';

        document.getElementById('modalUtilisateurSurTitre')
            .textContent = 'Nouveau compte';

        document.getElementById('modalUtilisateurTitre')
            .textContent = 'Ajouter un utilisateur';

        document.getElementById('boutonUtilisateur')
            .innerHTML =
            '<i class="fa-solid fa-user-plus"></i> Ajouter';

        document.getElementById('blocMotDePasse')
            .classList.remove('hidden');

        document.getElementById('mdp')
            .required = true;

        modalUtilisateur.classList.remove('hidden');
        modalUtilisateur.classList.add('flex');

        modalUtilisateur.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add('overflow-hidden');

    }


    function ouvrirModalModification(
        id,
        nom,
        prenom,
        email,
        role
    ) {
        if (!modalUtilisateur || !formUtilisateur) {
            return;
        }

        document.getElementById('utilisateurId').value = id;
        document.getElementById('nom').value = nom;
        document.getElementById('prenom').value = prenom;
        document.getElementById('email').value = email;

        document.getElementById('role_id').value =
            role === 'ADMIN' ? '1' : '2';

        document.getElementById('mdp').value = '';

        formUtilisateur.action =
            '/admin/utilisateurs/update';

        document.getElementById(
            'modalUtilisateurSurTitre'
        ).textContent = 'Modification';

        document.getElementById(
            'modalUtilisateurTitre'
        ).textContent = 'Modifier un utilisateur';

        document.getElementById(
                'boutonUtilisateur'
            ).innerHTML =
            '<i class="fa-solid fa-floppy-disk"></i> Enregistrer';

        document.getElementById(
            'blocMotDePasse'
        ).classList.add('hidden');

        document.getElementById('mdp').required = false;

        modalUtilisateur.classList.remove('hidden');
        modalUtilisateur.classList.add('flex');

        modalUtilisateur.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add('overflow-hidden');
    }


    function fermerModalUtilisateur() {

        if (!modalUtilisateur) {
            return;
        }

        modalUtilisateur.classList.add('hidden');
        modalUtilisateur.classList.remove('flex');

        modalUtilisateur.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove('overflow-hidden');

        if (
            window.location.pathname ===
            '/admin/utilisateurs/update'
        ) {
            window.location.href =
                '/admin/utilisateurs';
        }
    }


    function ouvrirModalSuppression(id, nom) {

        if (!modalSuppressionUtilisateur) {
            return;
        }

        document.getElementById('suppressionId')
            .value = id;

        document.getElementById(
                'texteSuppressionUtilisateur'
            ).innerHTML =
            'Vous êtes sur le point de supprimer ' +
            '<span class="font-semibold text-gray-700">« ' +
            escapeHtml(nom) +
            ' »</span>. Cette action est irréversible.';

        modalSuppressionUtilisateur.classList.remove('hidden');
        modalSuppressionUtilisateur.classList.add('flex');

        modalSuppressionUtilisateur.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add('overflow-hidden');

    }


    function fermerModalSuppression() {

        if (!modalSuppressionUtilisateur) {
            return;
        }

        modalSuppressionUtilisateur.classList.add('hidden');
        modalSuppressionUtilisateur.classList.remove('flex');

        modalSuppressionUtilisateur.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove('overflow-hidden');

    }


    function escapeHtml(value) {

        const div = document.createElement('div');

        div.textContent = value;

        return div.innerHTML;

    }


    // Fermeture en cliquant sur le fond

    if (modalUtilisateur) {

        modalUtilisateur.addEventListener(
            'click',
            function(event) {

                if (
                    event.target === modalUtilisateur
                ) {
                    fermerModalUtilisateur();
                }

            }
        );

    }


    if (modalSuppressionUtilisateur) {

        modalSuppressionUtilisateur.addEventListener(
            'click',
            function(event) {

                if (
                    event.target ===
                    modalSuppressionUtilisateur
                ) {
                    fermerModalSuppression();
                }

            }
        );

    }


    // Touche Echap

    document.addEventListener(
        'keydown',
        function(event) {

            if (event.key !== 'Escape') {
                return;
            }

            fermerModalUtilisateur();
            fermerModalSuppression();

        }
    );

    const formRechercheUtilisateur =
        document.getElementById(
            'formRechercheUtilisateur'
        );

    const champRechercheUtilisateur =
        document.getElementById(
            'champRechercheUtilisateur'
        );


    if (
        formRechercheUtilisateur &&
        champRechercheUtilisateur
    ) {

        formRechercheUtilisateur.addEventListener(
            'submit',
            function(event) {

                event.preventDefault();

                const terme =
                    champRechercheUtilisateur.value.trim();

                if (terme === '') {
                    window.location.href =
                        '/admin/utilisateurs';

                    return;
                }

                window.location.href =
                    '/admin/utilisateurs/recherche/' +
                    encodeURIComponent(terme);
            }
        );

    }
</script>


<script>
    const doitOuvrirModalUtilisateur =
        <?= $ouvrirModal ? 'true' : 'false' ?>;

    if (doitOuvrirModalUtilisateur) {

        const modal =
            document.getElementById(
                'modalUtilisateur'
            );

        if (modal) {

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            modal.setAttribute(
                'aria-hidden',
                'false'
            );

            document.body.classList.add(
                'overflow-hidden'
            );

            <?php if ($typeFormulaire === 'modification'): ?>

                document.getElementById(
                    'modalUtilisateurSurTitre'
                ).textContent = 'Modification';

                document.getElementById(
                        'modalUtilisateurTitre'
                    ).textContent =
                    'Modifier un utilisateur';

                document.getElementById(
                        'boutonUtilisateur'
                    ).innerHTML =
                    '<i class="fa-solid fa-floppy-disk"></i> Enregistrer';

                document.getElementById(
                        'formUtilisateur'
                    ).action =
                    '/admin/utilisateurs/update';

                document.getElementById(
                        'utilisateurId'
                    ).value =
                    <?= isset($utilisateurEdition)
                        && $utilisateurEdition !== null
                        ? (int) $utilisateurEdition->getId()
                        : 0 ?>

                document.getElementById(
                    'blocMotDePasse'
                ).classList.add('hidden');

                document.getElementById(
                    'mdp'
                ).required = false;

            <?php else: ?>

                document.getElementById(
                    'modalUtilisateurSurTitre'
                ).textContent = 'Nouveau compte';

                document.getElementById(
                        'modalUtilisateurTitre'
                    ).textContent =
                    'Ajouter un utilisateur';

                document.getElementById(
                        'boutonUtilisateur'
                    ).innerHTML =
                    '<i class="fa-solid fa-user-plus"></i> Ajouter';

                document.getElementById(
                        'formUtilisateur'
                    ).action =
                    '/admin/utilisateurs';

            <?php endif; ?>

        }

    }
</script>