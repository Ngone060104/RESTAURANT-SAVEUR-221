<?php

$message = $message ?? "Vous n'avez pas les autorisations nécessaires pour accéder à cette page.";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Accès interdit | Saveur 221</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .font-inter {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[#faf9f7]">

    <main class="min-h-screen flex items-center justify-center px-4 py-8">

        <div class="w-full max-w-[680px]">

            <!-- =========================
                 LOGO
            ========================== -->
            <div class="flex items-center justify-center gap-3 mb-6">

                <div
                    class="w-11 h-11 flex items-center justify-center rounded-xl bg-[#ff9500] text-white shadow-md"
                >
                    <i class="fa-solid fa-utensils text-lg"></i>
                </div>

                <div class="font-inter text-[24px] font-black tracking-tight">
                    <span class="text-[#222222]">Saveur</span>
                    <span class="text-[#ff9500]">221</span>
                </div>

            </div>


            <!-- =========================
                 CARTE
            ========================== -->
            <section
                class="relative overflow-hidden rounded-2xl border border-[#e8e5e1] bg-white px-6 py-9 text-center shadow-[0_12px_35px_rgba(0,0,0,0.06)] sm:px-10 sm:py-10"
            >

                <!-- Barre orange -->
                <div
                    class="absolute top-0 left-1/2 h-1 w-20 -translate-x-1/2 rounded-b-full bg-[#ff9500]"
                ></div>


                <!-- =========================
                     BADGE
                ========================== -->
                <div
                    class="inline-flex items-center gap-2 rounded-full bg-[#fff3df] px-3 py-1.5 text-xs font-semibold text-[#e98200]"
                >
                    <i class="fa-solid fa-shield-halved text-[10px]"></i>
                    Accès restreint
                </div>


                <!-- =========================
                     CODE 403
                ========================== -->
                <div
                    class="font-inter mt-4 text-[82px] font-black leading-none tracking-[-5px] text-[#ff9500] sm:text-[92px]"
                >
                    403
                </div>


                <!-- =========================
                     ICÔNE
                ========================== -->
                <div
                    class="mx-auto mt-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#fff0d8] text-[#ff9500]"
                >
                    <i class="fa-solid fa-lock text-xl"></i>
                </div>


                <!-- =========================
                     TITRE
                ========================== -->
                <h1
                    class="font-inter mt-5 text-[27px] font-extrabold tracking-tight text-[#181818] sm:text-[30px]"
                >
                    Accès interdit
                </h1>


                <!-- =========================
                     MESSAGE
                ========================== -->
                <p
                    class="mx-auto mt-3 max-w-[500px] text-sm leading-6 text-[#737373] sm:text-[15px]"
                >
                    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                </p>


                <!-- =========================
                     ACTIONS
                ========================== -->
                <div
                    class="mt-7 flex flex-col justify-center gap-3 sm:flex-row"
                >

                    <!-- Accueil -->
                    <a
                        href="/"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-[#ff9500] px-6 text-sm font-bold text-white shadow-[0_4px_12px_rgba(255,149,0,0.18)] transition duration-200 hover:bg-[#e98200] hover:-translate-y-0.5"
                    >
                        <i class="fa-solid fa-house text-xs"></i>
                        Retour à l'accueil
                    </a>


                    <!-- Retour -->
                    <button
                        type="button"
                        onclick="history.back()"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-[#292929] px-6 text-sm font-bold text-white transition duration-200 hover:bg-[#1d1d1d] hover:-translate-y-0.5"
                    >
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Retour en arrière
                    </button>

                </div>

            </section>


            <!-- =========================
                 FOOTER
            ========================== -->
            <p class="mt-4 text-center text-xs text-[#999999]">
                © <?= date('Y') ?> Saveur 221 · Cuisine sénégalaise & étrangère
            </p>

        </div>

    </main>

</body>

</html>