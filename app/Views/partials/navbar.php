<?php

use App\Services\AuthService;

$utilisateurConnecte = AuthService::currentUser();

?>

<header class="bg-white border-b border-stone-100">
    <div
        class="mx-auto flex h-[64px] max-w-[1240px] items-center justify-between px-5"
    >

        <!-- =====================================================
             LOGO
        ====================================================== -->

        <a
            href="/"
            class="flex items-center gap-3"
        >

            <span
                class="flex h-[38px] w-[38px] shrink-0 items-center justify-center rounded-[9px] bg-[#ff9900] text-white shadow-sm"
            >
                <i class="fa-solid fa-utensils text-[16px]"></i>
            </span>

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


        <!-- =====================================================
             NAVIGATION
        ====================================================== -->

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


        <!-- =====================================================
             ACTIONS
        ====================================================== -->

        <div
            class="flex items-center gap-5"
        >

            <!-- Panier -->

            <a
                href="/panier"
                class="hidden items-center gap-2 font-['DM_Sans'] text-[12px] font-bold text-[#222222] transition hover:text-[#ff9900] sm:flex"
            >
                <i class="fa-solid fa-cart-shopping text-[14px] text-[#ff9900]"></i>
                <span>Panier</span>
            </a>


            <?php if ($utilisateurConnecte): ?>

                <!-- Utilisateur -->

                <span
                    class="hidden items-center gap-2 font-['DM_Sans'] text-[12px] font-medium text-[#777777] sm:flex"
                >
                    <i class="fa-solid fa-user text-[13px] text-[#ff9900]"></i>

                    Bonjour,
                    <?= htmlspecialchars($utilisateurConnecte['prenom']) ?>
                </span>


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
                    class="hidden items-center gap-2 font-['DM_Sans'] text-[12px] font-bold text-[#222222] transition hover:text-[#ff9900] sm:flex"
                >
                    <i class="fa-solid fa-user text-[13px] text-[#ff9900]"></i>
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

    </div>
</header>