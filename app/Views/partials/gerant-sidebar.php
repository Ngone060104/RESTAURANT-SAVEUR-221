<?php

$currentPath = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
);


function gerantSidebarActive(string $path): bool
{
    global $currentPath;

    return $currentPath === $path;
}


function gerantSidebarClass(string $path): string
{
    return gerantSidebarActive($path)
        ? 'bg-[#2a2a2a] text-white'
        : 'text-[#d8d8d8] hover:bg-[#242424] hover:text-white';
}

?>


<!-- =========================================================
     OVERLAY MOBILE / TABLETTE
========================================================= -->

<div
    id="gerant-sidebar-overlay"
    class="
        fixed
        inset-0
        z-40
        hidden
        bg-black/50
        backdrop-blur-[1px]
        lg:hidden
    "
    onclick="closeGerantSidebar()"
></div>


<!-- =========================================================
     SIDEBAR
========================================================= -->

<aside
    id="gerant-sidebar"
    class="
        fixed
        inset-y-0
        left-0
        z-50
        flex
        w-[250px]
        flex-col
        bg-[#171717]
        text-white
        shadow-2xl
        transition-transform
        duration-300
        ease-in-out
        -translate-x-full
        lg:translate-x-0
    "
>

    <!-- =====================================================
         HEADER SIDEBAR
    ====================================================== -->

    <div
        class="
            flex
            h-[92px]
            shrink-0
            items-center
            px-[22px]
        "
    >

        <!-- LOGO -->

        <a
            href="/gerant/dashboard"
            onclick="closeGerantSidebar()"
            class="flex min-w-0 items-center gap-3.5"
        >

            <span
                class="
                    flex
                    h-[45px]
                    w-[45px]
                    shrink-0
                    items-center
                    justify-center
                    rounded-[13px]
                    bg-[#ff9900]
                    text-white
                    shadow-md
                "
            >
                <i class="fa-solid fa-utensils text-[18px]"></i>
            </span>


            <span
                class="
                    whitespace-nowrap
                    font-['Inter']
                    text-[18px]
                    font-extrabold
                    tracking-tight
                "
            >
                Saveur
                <span class="text-[#ff9900]">221</span>
            </span>

        </a>


        <!-- =================================================
             BOUTON FERMER
        ================================================== -->

        <button
            type="button"
            onclick="closeGerantSidebar()"
            class="
                ml-auto
                flex
                h-8
                w-8
                shrink-0
                items-center
                justify-center
                rounded-[8px]
                text-[#aaaaaa]
                transition
                hover:bg-[#242424]
                hover:text-white
                lg:hidden
            "
            aria-label="Fermer le menu"
        >
            <i class="fa-solid fa-xmark text-[17px]"></i>
        </button>

    </div>


    <!-- =====================================================
         NAVIGATION
    ====================================================== -->

    <nav
        class="
            flex-1
            overflow-y-auto
            px-[17px]
            pb-4
        "
    >

        <div class="space-y-1.5">


            <!-- =================================================
                 DASHBOARD
            ================================================== -->

            <a
                href="/gerant/dashboard"
                onclick="closeGerantSidebar()"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-medium
                    transition
                    <?= gerantSidebarClass('/gerant/dashboard') ?>
                "
            >

                <i
                    class="
                        fa-solid
                        fa-table-cells-large
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Dashboard</span>

            </a>


            <!-- =================================================
                 CATÉGORIES
            ================================================== -->

            <a
                href="/gerant/categories"
                onclick="closeGerantSidebar()"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-medium
                    transition
                    <?= gerantSidebarClass('/gerant/categories') ?>
                "
            >

                <i
                    class="
                        fa-regular
                        fa-square
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Catégories</span>

            </a>


            <!-- =================================================
                 PRODUITS
            ================================================== -->

            <a
                href="/gerant/produits"
                onclick="closeGerantSidebar()"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-semibold
                    transition
                    <?= gerantSidebarClass('/gerant/produits') ?>
                "
            >

                <i
                    class="
                        fa-solid
                        fa-utensils
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Produits &amp; Menu</span>

            </a>


            <!-- =================================================
                 STOCKS
            ================================================== -->

            <a
                href="/gerant/produits"
                onclick="closeGerantSidebar()"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-medium
                    text-[#d8d8d8]
                    transition
                    hover:bg-[#242424]
                    hover:text-white
                "
            >

                <i
                    class="
                        fa-solid
                        fa-box
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Gestion des stocks</span>

            </a>


            <!-- =================================================
                 COMMANDES
            ================================================== -->

            <a
                href="/gerant/commandes"
                onclick="closeGerantSidebar()"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-semibold
                    transition
                    <?= gerantSidebarClass('/gerant/commandes') ?>
                "
            >

                <i
                    class="
                        fa-solid
                        fa-cart-shopping
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Commandes</span>

            </a>


            <!-- =================================================
                 PAIEMENTS
            ================================================== -->

            <a
                href="/gerant/paiements"
                onclick="closeGerantSidebar()"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-medium
                    transition
                    <?= gerantSidebarClass('/gerant/paiements') ?>
                "
            >

                <i
                    class="
                        fa-regular
                        fa-credit-card
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Paiements &amp; Caisses</span>

            </a>


            <!-- =================================================
                 STATISTIQUES
            ================================================== -->

            <a
                href="#"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-medium
                    text-[#777777]
                "
                title="Disponible prochainement"
            >

                <i
                    class="
                        fa-solid
                        fa-chart-line
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Statistiques &amp; Ventes</span>

            </a>


            <!-- =================================================
                 UTILISATEURS
            ================================================== -->

            <a
                href="#"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-medium
                    text-[#777777]
                "
                title="Disponible prochainement"
            >

                <i
                    class="
                        fa-solid
                        fa-users
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Utilisateurs &amp; Rôles</span>

            </a>


            <!-- =================================================
                 FICHIERS CLIENTS
            ================================================== -->

            <a
                href="#"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-medium
                    text-[#777777]
                "
                title="Disponible prochainement"
            >

                <i
                    class="
                        fa-regular
                        fa-user
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Fichiers Clients</span>

            </a>


            <!-- =================================================
                 MODÉRATION
            ================================================== -->

            <a
                href="#"
                class="
                    flex
                    h-[46px]
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    font-['DM_Sans']
                    text-[13px]
                    font-medium
                    text-[#777777]
                "
                title="Disponible prochainement"
            >

                <i
                    class="
                        fa-regular
                        fa-star
                        w-4
                        text-center
                        text-[15px]
                    "
                ></i>

                <span>Modérations &amp; Avis</span>

            </a>

        </div>

    </nav>


    <!-- =====================================================
         DÉCONNEXION
    ====================================================== -->

    <div
        class="
            shrink-0
            border-t
            border-[#292929]
            px-[17px]
            py-4
        "
    >

        <form
            action="/logout"
            method="POST"
        >

            <button
                type="submit"
                class="
                    flex
                    w-full
                    items-center
                    gap-4
                    rounded-[9px]
                    px-3
                    py-3
                    font-['DM_Sans']
                    text-[13px]
                    font-semibold
                    text-[#bdbdbd]
                    transition
                    hover:bg-[#242424]
                    hover:text-white
                "
            >

                <i
                    class="
                        fa-solid
                        fa-arrow-right-from-bracket
                        w-4
                        text-center
                    "
                ></i>

                Déconnexion

            </button>

        </form>

    </div>

</aside>


<!-- =========================================================
     BOUTON BURGER
     IMPORTANT :
     z-[60] > navbar z-[30]
========================================================= -->

<button
    id="gerant-menu-button"
    type="button"
    onclick="openGerantSidebar()"
    class="
        fixed
        left-3
        top-[18px]
        z-[60]
        flex
        h-10
        w-10
        items-center
        justify-center
        rounded-[9px]
        bg-[#171717]
        text-white
        shadow-lg
        transition
        hover:bg-[#242424]
        lg:hidden
    "
    aria-label="Ouvrir le menu"
    aria-controls="gerant-sidebar"
    aria-expanded="false"
>
    <i
        class="fa-solid fa-bars text-[17px]"
    ></i>
</button>


<!-- =========================================================
     JAVASCRIPT SIDEBAR
========================================================= -->

<script>

function openGerantSidebar()
{
    const sidebar = document.getElementById('gerant-sidebar');
    const overlay = document.getElementById('gerant-sidebar-overlay');
    const button = document.getElementById('gerant-menu-button');

    if (!sidebar || !overlay) {
        return;
    }

    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');

    document.body.classList.add('overflow-hidden');

    if (button) {
        button.setAttribute('aria-expanded', 'true');
    }
}


function closeGerantSidebar()
{
    const sidebar = document.getElementById('gerant-sidebar');
    const overlay = document.getElementById('gerant-sidebar-overlay');
    const button = document.getElementById('gerant-menu-button');

    if (!sidebar || !overlay) {
        return;
    }

    if (window.innerWidth < 1024) {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

        if (button) {
            button.setAttribute('aria-expanded', 'false');
        }
    }
}


function toggleGerantSidebar()
{
    const sidebar = document.getElementById('gerant-sidebar');

    if (!sidebar) {
        return;
    }

    const isClosed =
        sidebar.classList.contains('-translate-x-full');

    if (isClosed) {
        openGerantSidebar();
    } else {
        closeGerantSidebar();
    }
}


/* Fermer avec la touche Échap */

document.addEventListener('keydown', function (event)
{
    if (event.key === 'Escape') {
        closeGerantSidebar();
    }
});


/* Synchronisation avec le redimensionnement */

window.addEventListener('resize', function ()
{
    const sidebar = document.getElementById('gerant-sidebar');
    const overlay = document.getElementById('gerant-sidebar-overlay');
    const button = document.getElementById('gerant-menu-button');

    if (!sidebar || !overlay) {
        return;
    }

    if (window.innerWidth >= 1024) {

        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

        if (button) {
            button.setAttribute('aria-expanded', 'false');
        }

    }
});

</script>