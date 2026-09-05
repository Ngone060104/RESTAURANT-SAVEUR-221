<?php

/**
 * Layout de l'espace gérant.
 *
 * Variables disponibles :
 * - $titre
 * - $content
 * - $utilisateur
 */

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($titre ?? 'Dashboard') ?> - Saveur 221
    </title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

</head>


<body class="min-h-screen bg-[#f5f5f5] text-[#222222]">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <?php require __DIR__ . '/../partials/gerant-sidebar.php'; ?>


    <!-- =====================================================
         CONTENEUR PRINCIPAL
    ====================================================== -->

    <div
        class="
            min-h-screen
            transition-all
            duration-300
            lg:ml-[250px]
        "
    >

        <!-- =================================================
             NAVBAR
        ================================================== -->

        <?php require __DIR__ . '/../partials/gerant-navbar.php'; ?>


        <!-- =================================================
             CONTENU
        ================================================== -->

        <main
            class="
                min-h-[calc(100vh-79px)]
                px-3
                pb-6
                pt-[99px]
                sm:px-5
                lg:px-[30px]
            "
        >

            <?= $content ?? '' ?>

        </main>

    </div>


    <!-- =====================================================
         FERMETURE AUTOMATIQUE DU MENU SUR CHANGEMENT
         DE TAILLE D'ÉCRAN
    ====================================================== -->

    <script>

        function resetGerantSidebarOnDesktop()
        {
            if (window.innerWidth >= 1024) {

                const sidebar = document.getElementById('gerant-sidebar');
                const overlay = document.getElementById('gerant-sidebar-overlay');

                if (sidebar) {
                    sidebar.classList.remove('-translate-x-full');
                }

                if (overlay) {
                    overlay.classList.add('hidden');
                }

                document.body.classList.remove('overflow-hidden');
            }
        }


        window.addEventListener(
            'resize',
            resetGerantSidebarOnDesktop
        );

    </script>

</body>

</html>