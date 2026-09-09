<?php

/**
 * Page d'erreur 404 - Saveur 221
 *
 * Cette vue est un document HTML autonome.
 * Elle est rendue directement par App::afficherErreur()
 * sans passer par le layout public.
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

    <title>Page introuvable | Saveur 221</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@700;800;900&display=swap"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-[#faf9f7] text-[#222222]">

    <main class="flex min-h-screen items-center justify-center px-5 py-10">

        <section class="w-full max-w-[620px] text-center">

            <!-- LOGO / MARQUE -->
            <div class="mb-8">

                <a
                    href="/"
                    class="inline-flex items-center gap-2 font-['Inter'] text-[20px] font-black tracking-[-0.5px] text-[#222222]"
                >
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-[9px] bg-[#ff9500] text-white"
                    >
                        <i class="fa-solid fa-utensils text-[14px]"></i>
                    </span>

                    <span>
                        Saveur
                        <span class="text-[#ff9500]">221</span>
                    </span>
                </a>

            </div>


            <!-- CARTE ERREUR -->
            <div
                class="rounded-[20px] border border-[#e7e3dd] bg-white px-6 py-10 shadow-[0_4px_15px_rgba(0,0,0,0.05)] sm:px-10 sm:py-12"
            >

                <!-- CODE -->
                <div
                    class="font-['Inter'] text-[80px] font-black leading-none tracking-[-5px] text-[#ff9500] sm:text-[100px]"
                >
                    404
                </div>


                <!-- PETITE ICÔNE -->
                <div
                    class="mx-auto mt-5 flex h-14 w-14 items-center justify-center rounded-full bg-[#fff0d8] text-[#ff9000]"
                >
                    <i class="fa-solid fa-map-location-dot text-[23px]"></i>
                </div>


                <!-- TITRE -->
                <h1
                    class="mt-6 font-['Inter'] text-[24px] font-black tracking-[-0.7px] text-[#111111] sm:text-[28px]"
                >
                    Oups ! Cette page n'existe pas.
                </h1>


                <!-- DESCRIPTION -->
                <p
                    class="mx-auto mt-3 max-w-[440px] font-['DM_Sans'] text-[11px] leading-5 text-[#777777]"
                >
                    La page que vous recherchez semble avoir disparu,
                    ou l'adresse saisie est incorrecte.
                </p>


                <!-- BOUTONS -->
                <div
                    class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row"
                >

                    <a
                        href="/"
                        class="inline-flex h-[42px] w-full items-center justify-center gap-2 rounded-[6px] bg-[#ff9500] px-5 font-['DM_Sans'] text-[11px] font-bold text-white transition hover:bg-[#e98500] sm:w-auto"
                    >
                        <i class="fa-solid fa-house text-[10px]"></i>
                        Retour à l'accueil
                    </a>


                    <a
                        href="/produits"
                        class="inline-flex h-[42px] w-full items-center justify-center gap-2 rounded-[6px] bg-[#292929] px-5 font-['DM_Sans'] text-[11px] font-bold text-white transition hover:bg-[#1d1d1d] sm:w-auto"
                    >
                        Voir la carte
                        <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>

                </div>

            </div>


            <!-- MESSAGE BAS DE PAGE -->
            <p
                class="mt-6 font-['DM_Sans'] text-[9px] text-[#999999]"
            >
                Les meilleures saveurs de Dakar à portée de clic avec
                <span class="font-bold text-[#ff9000]">
                    Saveur 221
                </span>.
            </p>

        </section>

    </main>

</body>
</html>
