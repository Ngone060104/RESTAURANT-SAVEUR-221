<?php

use App\Services\AuthService;

$utilisateurConnecte = AuthService::currentUser();

?>

<header class="relative z-50 border-b border-stone-100 bg-white">

    <!-- =====================================================
         NAVBAR PRINCIPALE
    ====================================================== -->

    <div
        class="mx-auto flex h-[64px] max-w-[1240px] items-center justify-between px-5"
    >

        <!-- =================================================
             LOGO
        ================================================== -->

        <a
            href="/"
            class="flex shrink-0 items-center gap-3"
        >

            <!-- Icône -->
            <span
                class="flex h-[38px] w-[38px] items-center justify-center rounded-[9px] bg-[#ff9900] text-white shadow-sm"
            >
                <i class="fa-solid fa-utensils text-[16px]"></i>
            </span>

            <!-- Texte -->
            <span class="leading-none">

                <span
                    class="block font-['Inter'] text-[16px] font-extrabold text-black"
                >
                    Saveur
                    <span class="text-[#ff9900]">221</span>
                </span>

                <span
                    class="mt-1 block font-['DM_Sans'] text-[8px] font-semibold tracking-[0.04em] text-[#777777]"
                >
                    CUISINE SÉNÉGALAISE &amp; TÉRANGA
                </span>

            </span>

        </a>


        <!-- =================================================
             NAVIGATION DESKTOP
        ================================================== -->

        <nav
            class="hidden items-center gap-9 md:flex"
        >

            <a
                href="/"
                class="font-['DM_Sans'] text-[13px] font-bold text-[#222222] transition hover:text-[#ff9900]"
            >
                Accueil
            </a>

            <a
                href="/produits"
                class="font-['DM_Sans'] text-[13px] font-bold text-[#222222] transition hover:text-[#ff9900]"
            >
                Menu
            </a>

            <a
                href="/mes-commandes"
                class="font-['DM_Sans'] text-[13px] font-bold text-[#222222] transition hover:text-[#ff9900]"
            >
                Mes Commandes
            </a>

        </nav>


        <!-- =================================================
             ACTIONS DESKTOP
        ================================================== -->

        <div
            class="hidden items-center gap-5 md:flex"
        >

            <!-- Panier -->
            <a
                href="/panier"
                class="flex items-center gap-2 font-['DM_Sans'] text-[12px] font-bold text-[#222222] transition hover:text-[#ff9900]"
            >
                <i
                    class="fa-solid fa-cart-shopping text-[14px] text-[#ff9900]"
                ></i>

                <span>Panier</span>
            </a>


            <?php if ($utilisateurConnecte): ?>

                <!-- Utilisateur -->
                <span
                    class="flex items-center gap-2 font-['DM_Sans'] text-[12px] font-medium text-[#777777]"
                >
                    <i
                        class="fa-solid fa-user text-[13px] text-[#ff9900]"
                    ></i>

                    Bonjour,
                    <?= htmlspecialchars($utilisateurConnecte['prenom']) ?>
                </span>


                <!-- Profil -->
                <a
                    href="/profil"
                    title="Profil & Sécurité"
                    class="flex items-center gap-2 font-['DM_Sans'] text-[12px] font-bold text-[#222222] transition hover:text-[#ff9900]"
                >
                    <i
                        class="fa-solid fa-user-shield text-[14px] text-[#ff9900]"
                    ></i>

                    <span>Profil</span>
                </a>


                <!-- Déconnexion -->
                <form
                    action="/logout"
                    method="post"
                    class="inline"
                >
                    <button
                        type="submit"
                        title="Déconnexion"
                        class="flex items-center justify-center text-[#222222] transition hover:text-[#ff9900]"
                    >
                        <i class="fa-solid fa-right-from-bracket text-[14px]"></i>
                    </button>
                </form>


            <?php else: ?>

                <!-- Connexion -->
                <a
                    href="/login"
                    class="flex items-center gap-2 font-['DM_Sans'] text-[12px] font-bold text-[#222222] transition hover:text-[#ff9900]"
                >
                    <i
                        class="fa-solid fa-user text-[13px] text-[#ff9900]"
                    ></i>

                    <span>Connexion</span>
                </a>


                <!-- Inscription -->
                <a
                    href="/register"
                    class="inline-flex h-[34px] items-center justify-center rounded-full bg-[#ff9900] px-5 font-['DM_Sans'] text-[11px] font-extrabold text-white transition hover:bg-[#e88900]"
                >
                    S'inscrire
                </a>

            <?php endif; ?>

        </div>


        <!-- =================================================
             BOUTON BURGER MOBILE
        ================================================== -->

        <button
            id="mobile-menu-button"
            type="button"
            aria-label="Ouvrir le menu"
            aria-expanded="false"
            class="flex h-10 w-10 items-center justify-center rounded-lg text-[#222222] transition hover:bg-[#fff4e5] hover:text-[#ff9900] md:hidden"
        >

            <i
                id="mobile-menu-icon"
                class="fa-solid fa-bars text-[20px]"
            ></i>

        </button>

    </div>


    <!-- =====================================================
         MENU MOBILE
    ====================================================== -->

    <div
        id="mobile-menu"
        class="hidden border-t border-stone-100 bg-white shadow-[0_12px_25px_rgba(0,0,0,0.06)] md:hidden"
    >

        <div class="px-5 py-4">


            <!-- =============================================
                 NAVIGATION
            ============================================== -->

            <nav class="space-y-1">

                <!-- Accueil -->
                <a
                    href="/"
                    class="mobile-menu-link flex items-center gap-4 rounded-xl px-4 py-3.5 font-['DM_Sans'] text-[14px] font-bold text-[#222222] transition hover:bg-[#fff7ed] hover:text-[#ff9900]"
                >
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#fff3df] text-[#ff9900]"
                    >
                        <i class="fa-solid fa-house text-[13px]"></i>
                    </span>

                    <span>Accueil</span>
                </a>


                <!-- Menu -->
                <a
                    href="/produits"
                    class="mobile-menu-link flex items-center gap-4 rounded-xl px-4 py-3.5 font-['DM_Sans'] text-[14px] font-bold text-[#222222] transition hover:bg-[#fff7ed] hover:text-[#ff9900]"
                >
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#fff3df] text-[#ff9900]"
                    >
                        <i class="fa-solid fa-utensils text-[13px]"></i>
                    </span>

                    <span>Menu</span>
                </a>


                <!-- Mes commandes -->
                <a
                    href="/mes-commandes"
                    class="mobile-menu-link flex items-center gap-4 rounded-xl px-4 py-3.5 font-['DM_Sans'] text-[14px] font-bold text-[#222222] transition hover:bg-[#fff7ed] hover:text-[#ff9900]"
                >
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#fff3df] text-[#ff9900]"
                    >
                        <i class="fa-solid fa-clipboard-list text-[13px]"></i>
                    </span>

                    <span>Mes Commandes</span>
                </a>


                <!-- Panier -->
                <a
                    href="/panier"
                    class="mobile-menu-link flex items-center gap-4 rounded-xl px-4 py-3.5 font-['DM_Sans'] text-[14px] font-bold text-[#222222] transition hover:bg-[#fff7ed] hover:text-[#ff9900]"
                >
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#fff3df] text-[#ff9900]"
                    >
                        <i class="fa-solid fa-cart-shopping text-[13px]"></i>
                    </span>

                    <span>Panier</span>
                </a>

            </nav>


            <!-- =============================================
                 SÉPARATEUR
            ============================================== -->

            <div class="my-3 border-t border-stone-100"></div>


            <?php if ($utilisateurConnecte): ?>

                <!-- =========================================
                     UTILISATEUR CONNECTÉ
                ========================================== -->

                <div
                    class="mb-3 flex items-center gap-3 rounded-xl bg-[#faf9f7] px-4 py-3"
                >

                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-[#fff0d8] text-[#ff9900]"
                    >
                        <i class="fa-solid fa-user text-[13px]"></i>
                    </span>

                    <div class="min-w-0">

                        <p class="text-[11px] text-[#999999]">
                            Connecté en tant que
                        </p>

                        <p
                            class="truncate font-['DM_Sans'] text-[13px] font-bold text-[#222222]"
                        >
                            <?= htmlspecialchars($utilisateurConnecte['prenom']) ?>
                        </p>

                    </div>

                </div>


                <!-- Profil -->
                <a
                    href="/profil"
                    class="mobile-menu-link flex items-center gap-4 rounded-xl px-4 py-3.5 font-['DM_Sans'] text-[14px] font-bold text-[#222222] transition hover:bg-[#fff7ed] hover:text-[#ff9900]"
                >

                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#fff3df] text-[#ff9900]"
                    >
                        <i class="fa-solid fa-user-shield text-[13px]"></i>
                    </span>

                    <span>Profil &amp; Sécurité</span>

                </a>


                <!-- Déconnexion -->
                <form
                    action="/logout"
                    method="post"
                    class="mt-1"
                >

                    <button
                        type="submit"
                        class="flex w-full items-center gap-4 rounded-xl px-4 py-3.5 text-left font-['DM_Sans'] text-[14px] font-bold text-[#222222] transition hover:bg-red-50 hover:text-red-500"
                    >

                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-stone-100"
                        >
                            <i class="fa-solid fa-right-from-bracket text-[13px]"></i>
                        </span>

                        <span>Déconnexion</span>

                    </button>

                </form>


            <?php else: ?>

                <!-- =========================================
                     UTILISATEUR NON CONNECTÉ
                ========================================== -->

                <!-- Connexion -->
                <a
                    href="/login"
                    class="mobile-menu-link flex items-center gap-4 rounded-xl px-4 py-3.5 font-['DM_Sans'] text-[14px] font-bold text-[#222222] transition hover:bg-[#fff7ed] hover:text-[#ff9900]"
                >

                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#fff3df] text-[#ff9900]"
                    >
                        <i class="fa-solid fa-user text-[13px]"></i>
                    </span>

                    <span>Connexion</span>

                </a>


                <!-- Inscription -->
                <a
                    href="/register"
                    class="mobile-menu-link mt-2 flex h-11 items-center justify-center rounded-xl bg-[#ff9900] font-['DM_Sans'] text-[13px] font-extrabold text-white shadow-[0_4px_12px_rgba(255,153,0,0.18)] transition hover:bg-[#e88900]"
                >
                    <i class="fa-solid fa-user-plus mr-2 text-[12px]"></i>
                    S'inscrire
                </a>

            <?php endif; ?>

        </div>

    </div>

</header>


<!-- =========================================================
     JAVASCRIPT MENU MOBILE
========================================================== -->

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const button = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('mobile-menu-icon');
        const links = document.querySelectorAll('.mobile-menu-link');

        if (!button || !menu || !icon) {
            return;
        }


        /* ============================================
           OUVRIR / FERMER LE MENU
        ============================================= */

        button.addEventListener('click', function () {

            const isOpen = !menu.classList.contains('hidden');

            if (isOpen) {

                // Fermer
                menu.classList.add('hidden');

                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');

                button.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-label', 'Ouvrir le menu');

            } else {

                // Ouvrir
                menu.classList.remove('hidden');

                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');

                button.setAttribute('aria-expanded', 'true');
                button.setAttribute('aria-label', 'Fermer le menu');

            }

        });


        /* ============================================
           FERMER APRÈS UN CLIC SUR UN LIEN
        ============================================= */

        links.forEach(function (link) {

            link.addEventListener('click', function () {

                menu.classList.add('hidden');

                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');

                button.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-label', 'Ouvrir le menu');

            });

        });


        /* ============================================
           FERMER SI ON REPASSE EN DESKTOP
        ============================================= */

        window.addEventListener('resize', function () {

            if (window.innerWidth >= 768) {

                menu.classList.add('hidden');

                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');

                button.setAttribute('aria-expanded', 'false');

            }

        });

    });

</script>