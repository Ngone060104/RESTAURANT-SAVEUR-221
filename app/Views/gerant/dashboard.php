<?php
?>

<main class="min-h-[calc(100vh-79px)] bg-[#f5f5f5] px-[30px] py-[20px]">
    <div class="mx-auto max-w-[1180px]">

        <!-- =================================================
             BANNIÈRE PRINCIPALE
        ================================================== -->

        <section
            class="relative overflow-hidden rounded-[12px] bg-gradient-to-r from-[#292929] via-[#9b5510] to-[#ff9900] px-[22px] py-[34px] shadow-[0_8px_24px_rgba(0,0,0,0.08)]"
        >
            <div class="relative z-10">
                <h2
                    class="font-['Inter'] text-[28px] font-extrabold tracking-tight text-white"
                >
                    Tableau de Bord Opérationnel
                </h2>

                <p
                    class="mt-[8px] font-['DM_Sans'] text-[15px] font-medium text-white"
                >
                    Supervision du service, commandes en cours,
                    encaissements et suivi des stocks en temps réel.
                </p>
            </div>
        </section>


        <!-- =================================================
             STATISTIQUES
        ================================================== -->

        <section class="mt-[22px]">
            <div class="grid grid-cols-1 gap-[15px] sm:grid-cols-2 xl:grid-cols-5">


                <!-- =================================================
                     COMMANDES EN ATTENTE
                ================================================== -->

                <div
                    class="group relative cursor-pointer overflow-hidden rounded-[12px] border border-[#e8e8e8] bg-white px-[18px] py-[18px]
                    shadow-[0_4px_12px_rgba(0,0,0,0.05)]
                    transition-all duration-200 ease-out
                    hover:-translate-y-1 hover:border-[#ffd9a8]
                    hover:shadow-[0_10px_24px_rgba(255,153,0,0.13)]
                    active:-translate-y-2 active:scale-[0.99]
                    focus-within:-translate-y-1"
                >

                    <!-- petit accent supérieur -->
                    <div
                        class="absolute left-0 right-0 top-0 h-[3px] bg-[#ff9900]"
                    ></div>

                    <div class="flex items-start justify-between">

                        <div>
                            <p
                                class="font-['DM_Sans'] text-[11px] font-semibold text-[#777777]"
                            >
                                Commandes en attente
                            </p>

                            <p
                                class="mt-[10px] font-['Inter'] text-[30px] font-extrabold leading-none text-[#222222]"
                            >
                                <?= (int) $commandesEnAttente ?>
                            </p>
                        </div>

                        <span
                            class="flex h-[40px] w-[40px] items-center justify-center rounded-[10px]
                            bg-[#fff1df] text-[#ff9900]
                            transition-transform duration-200
                            group-hover:scale-110"
                        >
                            <i class="fa-regular fa-clock text-[15px]"></i>
                        </span>

                    </div>

                    <div class="mt-[17px] h-[3px] w-full overflow-hidden rounded-full bg-[#fff1df]">
                        <div
                            class="h-full w-full origin-left rounded-full bg-[#ff9900] transition-transform duration-300 group-hover:scale-x-105"
                        ></div>
                    </div>

                </div>


                <!-- =================================================
                     EN PRÉPARATION
                ================================================== -->

                <div
                    class="group relative cursor-pointer overflow-hidden rounded-[12px] border border-[#e8e8e8] bg-white px-[18px] py-[18px]
                    shadow-[0_4px_12px_rgba(0,0,0,0.05)]
                    transition-all duration-200 ease-out
                    hover:-translate-y-1 hover:border-[#cfe0ff]
                    hover:shadow-[0_10px_24px_rgba(59,130,246,0.12)]
                    active:-translate-y-2 active:scale-[0.99]
                    focus-within:-translate-y-1"
                >

                    <div
                        class="absolute left-0 right-0 top-0 h-[3px] bg-[#3b82f6]"
                    ></div>

                    <div class="flex items-start justify-between">

                        <div>
                            <p
                                class="font-['DM_Sans'] text-[11px] font-semibold text-[#777777]"
                            >
                                En préparation
                            </p>

                            <p
                                class="mt-[10px] font-['Inter'] text-[30px] font-extrabold leading-none text-[#222222]"
                            >
                                <?= (int) $commandesEnPreparation ?>
                            </p>
                        </div>

                        <span
                            class="flex h-[40px] w-[40px] items-center justify-center rounded-[10px]
                            bg-[#eaf3ff] text-[#3b82f6]
                            transition-transform duration-200
                            group-hover:scale-110"
                        >
                            <i class="fa-solid fa-utensils text-[14px]"></i>
                        </span>

                    </div>

                    <div class="mt-[17px] h-[3px] w-full overflow-hidden rounded-full bg-[#eaf3ff]">
                        <div
                            class="h-full w-full rounded-full bg-[#3b82f6] transition-transform duration-300 group-hover:scale-x-105"
                        ></div>
                    </div>

                </div>


                <!-- =================================================
                     COMMANDES PRÊTES
                ================================================== -->

                <div
                    class="group relative cursor-pointer overflow-hidden rounded-[12px] border border-[#e8e8e8] bg-white px-[18px] py-[18px]
                    shadow-[0_4px_12px_rgba(0,0,0,0.05)]
                    transition-all duration-200 ease-out
                    hover:-translate-y-1 hover:border-[#bdebd3]
                    hover:shadow-[0_10px_24px_rgba(24,167,107,0.12)]
                    active:-translate-y-2 active:scale-[0.99]
                    focus-within:-translate-y-1"
                >

                    <div
                        class="absolute left-0 right-0 top-0 h-[3px] bg-[#18a76b]"
                    ></div>

                    <div class="flex items-start justify-between">

                        <div>
                            <p
                                class="font-['DM_Sans'] text-[11px] font-semibold text-[#777777]"
                            >
                                Commandes prêtes
                            </p>

                            <p
                                class="mt-[10px] font-['Inter'] text-[30px] font-extrabold leading-none text-[#222222]"
                            >
                                <?= (int) $commandesPretes ?>
                            </p>
                        </div>

                        <span
                            class="flex h-[40px] w-[40px] items-center justify-center rounded-[10px]
                            bg-[#e8f8ef] text-[#18a76b]
                            transition-transform duration-200
                            group-hover:scale-110"
                        >
                            <i class="fa-solid fa-circle-check text-[15px]"></i>
                        </span>

                    </div>

                    <div class="mt-[17px] h-[3px] w-full overflow-hidden rounded-full bg-[#e8f8ef]">
                        <div
                            class="h-full w-full rounded-full bg-[#18a76b] transition-transform duration-300 group-hover:scale-x-105"
                        ></div>
                    </div>

                </div>


                <!-- =================================================
                     STOCK FAIBLE
                ================================================== -->

                <div
                    class="group relative cursor-pointer overflow-hidden rounded-[12px] border border-[#e8e8e8] bg-white px-[18px] py-[18px]
                    shadow-[0_4px_12px_rgba(0,0,0,0.05)]
                    transition-all duration-200 ease-out
                    hover:-translate-y-1 hover:border-[#ffe0a8]
                    hover:shadow-[0_10px_24px_rgba(245,158,11,0.13)]
                    active:-translate-y-2 active:scale-[0.99]
                    focus-within:-translate-y-1"
                >

                    <div
                        class="absolute left-0 right-0 top-0 h-[3px] bg-[#f59e0b]"
                    ></div>

                    <div class="flex items-start justify-between">

                        <div>
                            <p
                                class="font-['DM_Sans'] text-[11px] font-semibold text-[#777777]"
                            >
                                Stock faible
                            </p>

                            <p
                                class="mt-[10px] font-['Inter'] text-[30px] font-extrabold leading-none text-[#222222]"
                            >
                                <?= (int) $nombreStockFaible ?>
                            </p>
                        </div>

                        <span
                            class="flex h-[40px] w-[40px] items-center justify-center rounded-[10px]
                            bg-[#fff5df] text-[#f59e0b]
                            transition-transform duration-200
                            group-hover:scale-110"
                        >
                            <i class="fa-solid fa-box-open text-[15px]"></i>
                        </span>

                    </div>

                    <div class="mt-[17px] h-[3px] w-full overflow-hidden rounded-full bg-[#fff5df]">
                        <div
                            class="h-full w-full rounded-full bg-[#f59e0b] transition-transform duration-300 group-hover:scale-x-105"
                        ></div>
                    </div>

                </div>


                <!-- =================================================
                     PRODUITS EN RUPTURE
                ================================================== -->

                <div
                    class="group relative cursor-pointer overflow-hidden rounded-[12px] border border-[#e8e8e8] bg-white px-[18px] py-[18px]
                    shadow-[0_4px_12px_rgba(0,0,0,0.05)]
                    transition-all duration-200 ease-out
                    hover:-translate-y-1 hover:border-[#ffcaca]
                    hover:shadow-[0_10px_24px_rgba(239,68,68,0.12)]
                    active:-translate-y-2 active:scale-[0.99]
                    focus-within:-translate-y-1"
                >

                    <div
                        class="absolute left-0 right-0 top-0 h-[3px] bg-[#ef4444]"
                    ></div>

                    <div class="flex items-start justify-between">

                        <div>
                            <p
                                class="font-['DM_Sans'] text-[11px] font-semibold text-[#777777]"
                            >
                                Produits en rupture
                            </p>

                            <p
                                class="mt-[10px] font-['Inter'] text-[30px] font-extrabold leading-none text-[#222222]"
                            >
                                <?= (int) $nombreEnRupture ?>
                            </p>
                        </div>

                        <span
                            class="flex h-[40px] w-[40px] items-center justify-center rounded-[10px]
                            bg-[#ffe8e8] text-[#ef4444]
                            transition-transform duration-200
                            group-hover:scale-110"
                        >
                            <i class="fa-solid fa-triangle-exclamation text-[14px]"></i>
                        </span>

                    </div>

                    <div class="mt-[17px] h-[3px] w-full overflow-hidden rounded-full bg-[#ffe8e8]">
                        <div
                            class="h-full w-full rounded-full bg-[#ef4444] transition-transform duration-300 group-hover:scale-x-105"
                        ></div>
                    </div>

                </div>

            </div>
        </section>


        <!-- =================================================
             DÉTAIL DES ALERTES
        ================================================== -->

        <section class="mt-[20px] grid grid-cols-1 gap-[15px] lg:grid-cols-2">


            <!-- =================================================
                 STOCK FAIBLE
            ================================================== -->

            <div
                class="rounded-[12px] border border-[#e5e5e5] bg-white
                shadow-[0_4px_14px_rgba(0,0,0,0.04)]
                transition-all duration-200
                hover:-translate-y-[2px]
                hover:shadow-[0_8px_20px_rgba(0,0,0,0.07)]"
            >

                <div
                    class="flex items-center justify-between border-b border-[#eeeeee] px-[18px] py-[15px]"
                >

                    <div class="flex items-center gap-3">

                        <span
                            class="flex h-[34px] w-[34px] items-center justify-center rounded-[9px] bg-[#fff5df] text-[#f59e0b]"
                        >
                            <i class="fa-solid fa-box-open text-[12px]"></i>
                        </span>

                        <div>

                            <h3
                                class="font-['Inter'] text-[14px] font-extrabold text-[#333333]"
                            >
                                Produits à surveiller
                            </h3>

                            <p
                                class="font-['DM_Sans'] text-[10px] text-[#999999]"
                            >
                                Stock inférieur ou égal à 5 unités
                            </p>

                        </div>

                    </div>

                    <span
                        class="rounded-full bg-[#fff5df] px-2.5 py-1 font-['DM_Sans'] text-[10px] font-bold text-[#d97706]"
                    >
                        <?= (int) $nombreStockFaible ?>
                    </span>

                </div>


                <div class="p-[15px]">

                    <?php if (empty($produitsStockFaible)): ?>

                        <div class="py-[25px] text-center">

                            <i
                                class="fa-solid fa-circle-check text-[20px] text-[#18a76b]"
                            ></i>

                            <p
                                class="mt-2 font-['DM_Sans'] text-[11px] text-[#777777]"
                            >
                                Aucun produit avec un stock faible.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="space-y-2">

                            <?php foreach ($produitsStockFaible as $produit): ?>

                                <div
                                    class="group flex cursor-pointer items-center justify-between rounded-[9px] bg-[#fafafa] px-3 py-2.5
                                    transition-all duration-200
                                    hover:-translate-y-[1px]
                                    hover:bg-[#fffaf2]
                                    hover:shadow-sm
                                    active:-translate-y-[2px]"
                                >

                                    <div class="flex min-w-0 items-center gap-3">

                                        <span
                                            class="flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-full bg-[#fff5df] text-[#f59e0b]
                                            transition-transform duration-200
                                            group-hover:scale-110"
                                        >
                                            <i class="fa-solid fa-utensils text-[9px]"></i>
                                        </span>

                                        <span
                                            class="truncate font-['DM_Sans'] text-[11px] font-semibold text-[#444444]"
                                        >
                                            <?= htmlspecialchars($produit->getNom()) ?>
                                        </span>

                                    </div>

                                    <span
                                        class="ml-3 shrink-0 rounded-full bg-[#fff5df] px-2 py-1 font-['DM_Sans'] text-[9px] font-extrabold text-[#d97706]"
                                    >
                                        <?= (int) $produit->getStock() ?> restant(s)
                                    </span>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- =================================================
                 RUPTURE
            ================================================== -->

            <div
                class="rounded-[12px] border border-[#e5e5e5] bg-white
                shadow-[0_4px_14px_rgba(0,0,0,0.04)]
                transition-all duration-200
                hover:-translate-y-[2px]
                hover:shadow-[0_8px_20px_rgba(0,0,0,0.07)]"
            >

                <div
                    class="flex items-center justify-between border-b border-[#eeeeee] px-[18px] py-[15px]"
                >

                    <div class="flex items-center gap-3">

                        <span
                            class="flex h-[34px] w-[34px] items-center justify-center rounded-[9px] bg-[#ffe8e8] text-[#ef4444]"
                        >
                            <i class="fa-solid fa-triangle-exclamation text-[12px]"></i>
                        </span>

                        <div>

                            <h3
                                class="font-['Inter'] text-[14px] font-extrabold text-[#333333]"
                            >
                                Produits en rupture
                            </h3>

                            <p
                                class="font-['DM_Sans'] text-[10px] text-[#999999]"
                            >
                                Produits actuellement indisponibles
                            </p>

                        </div>

                    </div>

                    <span
                        class="rounded-full bg-[#ffe8e8] px-2.5 py-1 font-['DM_Sans'] text-[10px] font-bold text-[#dc2626]"
                    >
                        <?= (int) $nombreEnRupture ?>
                    </span>

                </div>


                <div class="p-[15px]">

                    <?php if (empty($produitsEnRupture)): ?>

                        <div class="py-[25px] text-center">

                            <i
                                class="fa-solid fa-circle-check text-[20px] text-[#18a76b]"
                            ></i>

                            <p
                                class="mt-2 font-['DM_Sans'] text-[11px] text-[#777777]"
                            >
                                Aucun produit en rupture.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="space-y-2">

                            <?php foreach ($produitsEnRupture as $produit): ?>

                                <div
                                    class="group flex cursor-pointer items-center justify-between rounded-[9px] bg-[#fafafa] px-3 py-2.5
                                    transition-all duration-200
                                    hover:-translate-y-[1px]
                                    hover:bg-[#fff7f7]
                                    hover:shadow-sm
                                    active:-translate-y-[2px]"
                                >

                                    <div class="flex min-w-0 items-center gap-3">

                                        <span
                                            class="flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-full bg-[#ffe8e8] text-[#ef4444]
                                            transition-transform duration-200
                                            group-hover:scale-110"
                                        >
                                            <i class="fa-solid fa-utensils text-[9px]"></i>
                                        </span>

                                        <span
                                            class="truncate font-['DM_Sans'] text-[11px] font-semibold text-[#444444]"
                                        >
                                            <?= htmlspecialchars($produit->getNom()) ?>
                                        </span>

                                    </div>

                                    <span
                                        class="ml-3 shrink-0 rounded-full bg-[#ffe8e8] px-2 py-1 font-['DM_Sans'] text-[9px] font-extrabold text-[#dc2626]"
                                    >
                                        Rupture
                                    </span>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </section>

    </div>
</main>