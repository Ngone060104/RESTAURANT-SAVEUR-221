<?php
$titre = $titre ?? 'Espace Administrateur';
?>

<div class="space-y-6">

    <!-- EN-TÊTE -->

    <section
        class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-orange-900 shadow-sm">


        <div
            class="flex flex-col gap-5 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <div class="flex items-center gap-2 text-orange-500">

                    <i class="fa-solid fa-unlock-keyhole text-sm"></i>

                    <div class="flex items-center gap-2">


                        <span class="text-xs font-semibold uppercase tracking-wider text-[#ff9500]">
                            Administration
                        </span>
                    </div>

                </div>

                <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    <?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>
                </h1>

                <p class="mt-2 text-sm text-gray-300">
                    Gérez l'ensemble de la plateforme Saveur 221.
                </p>
            </div>




        </div>
    </section>




    <!-- CARTES ADMIN -->
    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

        <!-- UTILISATEURS -->
        <a
            href="/admin/utilisateurs"
            class="group relative overflow-hidden rounded-2xl border border-gray-100
                   bg-white p-6 shadow-sm transition-all duration-300
                   hover:-translate-y-1.5 hover:shadow-xl">
            <div
                class="absolute left-0 top-0 h-1 w-0 rounded-r-full bg-blue-500
                       transition-all duration-300 group-hover:w-full"></div>

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Utilisateurs & rôles
                    </p>

                    <p class="mt-3 text-lg font-bold text-gray-900">
                        Gestion des utilisateurs
                    </p>

                    <p class="mt-2 text-xs leading-5 text-gray-400">
                        Créer, modifier, activer ou désactiver les utilisateurs internes.
                    </p>
                </div>

                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center
                           rounded-2xl bg-blue-50 transition-all duration-300
                           group-hover:scale-110 group-hover:rotate-3">
                    <i class="fa-solid fa-users text-xl text-blue-600"></i>
                </div>

            </div>

            <div class="mt-5 flex items-center gap-2 text-xs font-semibold text-blue-600">
                Accéder
                <i
                    class="fa-solid fa-arrow-right transition-transform duration-300
                           group-hover:translate-x-1"></i>
            </div>
        </a>

        <!-- CLIENTS -->
        <a
            href="/admin/clients"
            class="group relative overflow-hidden rounded-2xl border border-gray-100
                   bg-white p-6 shadow-sm transition-all duration-300
                   hover:-translate-y-1.5 hover:shadow-xl">
            <div
                class="absolute left-0 top-0 h-1 w-0 rounded-r-full bg-green-500
                       transition-all duration-300 group-hover:w-full"></div>

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Fichiers clients
                    </p>

                    <p class="mt-3 text-lg font-bold text-gray-900">
                        Gestion des clients
                    </p>

                    <p class="mt-2 text-xs leading-5 text-gray-400">
                        Consulter les clients et leur historique de commandes.
                    </p>
                </div>

                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center
                           rounded-2xl bg-green-50 transition-all duration-300
                           group-hover:scale-110 group-hover:rotate-3">
                    <i class="fa-solid fa-user-group text-xl text-green-600"></i>
                </div>

            </div>

            <div class="mt-5 flex items-center gap-2 text-xs font-semibold text-green-600">
                Accéder
                <i
                    class="fa-solid fa-arrow-right transition-transform duration-300
                           group-hover:translate-x-1"></i>
            </div>
        </a>

        <!-- AVIS -->
        <a
            href="/admin/avis"
            class="group relative overflow-hidden rounded-2xl border border-gray-100
                   bg-white p-6 shadow-sm transition-all duration-300
                   hover:-translate-y-1.5 hover:shadow-xl">
            <div
                class="absolute left-0 top-0 h-1 w-0 rounded-r-full bg-[#ff9500]
                       transition-all duration-300 group-hover:w-full"></div>

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Modérations & avis
                    </p>

                    <p class="mt-3 text-lg font-bold text-gray-900">
                        Gestion des avis
                    </p>

                    <p class="mt-2 text-xs leading-5 text-gray-400">
                        Consulter les évaluations et supprimer les avis inappropriés.
                    </p>
                </div>

                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center
                           rounded-2xl bg-orange-50 transition-all duration-300
                           group-hover:scale-110 group-hover:rotate-3">
                    <i class="fa-solid fa-star text-xl text-[#ff9500]"></i>
                </div>

            </div>

            <div class="mt-5 flex items-center gap-2 text-xs font-semibold text-[#ff9500]">
                Accéder
                <i
                    class="fa-solid fa-arrow-right transition-transform duration-300
                           group-hover:translate-x-1"></i>
            </div>
        </a>

    </div>

    <!-- RAPPEL -->
    <div class="rounded-2xl border border-orange-100 bg-orange-50 p-5">
        <div class="flex items-start gap-3">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center
                       rounded-xl bg-white">
                <i class="fa-solid fa-shield-halved text-[#ff9500]"></i>
            </div>

            <div>
                <p class="text-sm font-semibold text-gray-900">
                    Droits administrateur
                </p>

                <p class="mt-1 text-xs leading-5 text-gray-500">
                    L'administrateur possède tous les droits du gérant
                    et peut également gérer les utilisateurs internes,
                    les clients et les avis.
                </p>
            </div>
        </div>
    </div>

</div>