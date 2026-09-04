<footer class="bg-[#1c1c1c] text-[#b8b8b8]">
    <div class="mx-auto grid max-w-[1240px] grid-cols-1 gap-8 px-5 py-10 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:gap-10">

        <!-- Logo + description -->
        <div>
            <a href="/" class="mb-4 flex items-center gap-2.5">
                <span class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-[7px] bg-[#ff9900] text-white">
                    <i class="fa-solid fa-utensils text-[14px]"></i>
                </span>

                <span class="leading-none">
                    <span class="block font-['Inter'] text-[16px] font-extrabold text-white">
                        Saveur <span class="text-[#ff9900]">221</span>
                    </span>

                    <span class="mt-1 block font-['DM_Sans'] text-[8px] font-semibold tracking-[0.05em] text-[#777777]">
                        CUISINE SÉNÉGALAISE &amp; TÉRANGA
                    </span>
                </span>
            </a>

            <p class="max-w-[280px] font-['DM_Sans'] text-[13px] leading-6 text-[#999999]">
                L'excellence de la gastronomie sénégalaise alliée à l'art de la teranga.
                Des produits frais du marché, des recettes traditionnelles et une préparation soignée.
            </p>
        </div>

        <!-- Navigation -->
        <div>
            <h3 class="mb-4 font-['DM_Sans'] text-[12px] font-extrabold tracking-[0.08em] text-white">
                NAVIGATION
            </h3>

            <ul class="space-y-3 font-['DM_Sans'] text-[13px]">
                <li>
                    <a href="/" class="transition hover:text-[#ff9900]">
                        Accueil &amp; découverte
                    </a>
                </li>

                <li>
                    <a href="/produits" class="transition hover:text-[#ff9900]">
                        Carte &amp; plats sénégalais
                    </a>
                </li>

                <li>
                    <a href="/panier" class="transition hover:text-[#ff9900]">
                        Mon panier de commande
                    </a>
                </li>

                <li>
                    <a href="/mes-commandes" class="transition hover:text-[#ff9900]">
                        Mes commandes
                    </a>
                </li>
            </ul>
        </div>

        <!-- Horaires + adresse -->
        <div>
            <h3 class="mb-4 font-['DM_Sans'] text-[12px] font-extrabold tracking-[0.08em] text-white">
                HORAIRES &amp; ADRESSE
            </h3>

            <ul class="space-y-3 font-['DM_Sans'] text-[12px] leading-5 text-[#999999]">

                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-location-dot mt-0.5 w-3 shrink-0 text-[12px] text-[#ff9900]"></i>

                    <span>
                        Route des Almadies &amp; Dakar Plateau, Sénégal
                    </span>
                </li>

                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-phone mt-0.5 w-3 shrink-0 text-[11px] text-[#ff9900]"></i>

                    <span>
                        +221 33 800 02 21<br>
                        +221 77 123 45 67
                    </span>
                </li>

                <li class="flex items-start gap-2.5">
                    <i class="fa-regular fa-clock mt-0.5 w-3 shrink-0 text-[12px] text-[#ff9900]"></i>

                    <span>
                        Lundi – Dimanche :<br>
                        11h30 – 23h30
                    </span>
                </li>

            </ul>
        </div>

        <!-- Paiements -->
        <div>
            <h3 class="mb-4 font-['DM_Sans'] text-[12px] font-extrabold tracking-[0.08em] text-white">
                PAIEMENTS &amp; ESPACE PRO
            </h3>

            <p class="mb-4 font-['DM_Sans'] text-[12px] leading-5 text-[#999999]">
                Paiements sécurisés acceptés au comptoir ou par mobile money :
            </p>

            <div class="flex flex-wrap gap-2">

                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#292929] px-3 py-1.5 font-['DM_Sans'] text-[11px] font-semibold text-[#d5d5d5]">
                    <i class="fa-solid fa-mobile-screen-button text-[10px] text-[#ff9900]"></i>
                    Wave
                </span>

                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#292929] px-3 py-1.5 font-['DM_Sans'] text-[11px] font-semibold text-[#d5d5d5]">
                    <i class="fa-solid fa-mobile-screen-button text-[10px] text-[#ff9900]"></i>
                    Orange Money
                </span>

                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#292929] px-3 py-1.5 font-['DM_Sans'] text-[11px] font-semibold text-[#d5d5d5]">
                    <i class="fa-solid fa-money-bill-wave text-[10px] text-[#ff9900]"></i>
                    Espèces
                </span>

                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#292929] px-3 py-1.5 font-['DM_Sans'] text-[11px] font-semibold text-[#d5d5d5]">
                    <i class="fa-regular fa-credit-card text-[10px] text-[#ff9900]"></i>
                    CB / Visa
                </span>

            </div>
        </div>

    </div>

    <!-- Copyright -->
    <div class="border-t border-[#303030]">
        <div class="mx-auto flex max-w-[1240px] flex-col items-center justify-between gap-2 px-5 py-4 font-['DM_Sans'] text-[10px] text-[#666666] sm:px-6 md:flex-row">

            <span class="text-center md:text-left">
                &copy; <?= date('Y') ?> Saveur 221 — Prototype Module B PHP Web
            </span>

            <span class="text-center md:text-right">
                Développé pour la démonstration académique et la conception Figma
            </span>

        </div>
    </div>
</footer>