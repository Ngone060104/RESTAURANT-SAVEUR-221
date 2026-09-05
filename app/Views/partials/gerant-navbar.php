<?php

use App\Services\AuthService;

$utilisateur = AuthService::currentUser();

$prenom = $utilisateur['prenom'] ?? 'Gérant';
$nom = $utilisateur['nom'] ?? '';
$role = $utilisateur['role'] ?? 'GERANT';

$initiales = '';

if ($prenom !== '') {
    $initiales .= mb_strtoupper(
        mb_substr($prenom, 0, 1)
    );
}

if ($nom !== '') {
    $initiales .= mb_strtoupper(
        mb_substr($nom, 0, 1)
    );
}

$initiales = $initiales ?: 'G';

?>


<!-- =========================================================
     NAVBAR GÉRANT
========================================================= -->

<header
    class="
        fixed
        left-0
        right-0
        top-0
        z-30
        h-[79px]
        border-b
        border-[#dddddd]
        bg-white
        lg:left-[250px]
    "
>

    <div
        class="
            flex
            h-full
            items-center
            justify-between
            gap-3
            px-3
            sm:px-5
            lg:px-[30px]
            xl:px-[44px]
        "
    >


        <!-- =================================================
             TITRE
        ================================================== -->

        <div
            class="
                ml-[52px]
                min-w-0
                shrink-0
                lg:ml-0
            "
        >

            <h1
                class="
                    truncate
                    font-['Inter']
                    text-[18px]
                    font-extrabold
                    text-[#293241]
                    sm:text-[20px]
                "
            >
                Dashboard
            </h1>

        </div>


        <!-- =================================================
             RECHERCHE
        ================================================== -->

        <div
            class="
                hidden
                w-full
                max-w-[485px]
                lg:block
                lg:flex-1
                xl:mx-8
            "
        >

            <div class="relative">

                <i
                    class="
                        fa-solid
                        fa-magnifying-glass
                        absolute
                        left-[18px]
                        top-1/2
                        -translate-y-1/2
                        text-[14px]
                        text-[#ff9900]
                    "
                ></i>


                <input
                    type="text"
                    placeholder="-- rechercher --"
                    class="
                        h-[37px]
                        w-full
                        rounded-[11px]
                        border
                        border-[#f1d8b2]
                        bg-white
                        pl-[42px]
                        pr-4
                        font-['DM_Sans']
                        text-[12px]
                        text-[#444444]
                        outline-none
                        placeholder:text-[#ff9900]
                        focus:border-[#ff9900]
                    "
                >

            </div>

        </div>


        <!-- =================================================
             UTILISATEUR CONNECTÉ
        ================================================== -->

        <div
            class="
                flex
                shrink-0
                items-center
                gap-2.5
                sm:gap-3
            "
        >

            <!-- Initiales -->

            <div
                class="
                    flex
                    h-[40px]
                    w-[40px]
                    shrink-0
                    items-center
                    justify-center
                    overflow-hidden
                    rounded-full
                    bg-[#ff9900]
                    font-['Inter']
                    text-[12px]
                    font-extrabold
                    text-white
                    shadow-sm
                    sm:h-[42px]
                    sm:w-[42px]
                    sm:text-[13px]
                "
            >
                <?= htmlspecialchars($initiales) ?>
            </div>


            <!-- =================================================
                 INFORMATIONS UTILISATEUR
            ================================================== -->

            <div
                class="
                    hidden
                    leading-tight
                    sm:block
                "
            >

                <p
                    class="
                        max-w-[140px]
                        truncate
                        font-['DM_Sans']
                        text-[12px]
                        font-semibold
                        text-[#222222]
                    "
                >
                    <?= htmlspecialchars(
                        trim($prenom . ' ' . $nom)
                    ) ?>
                </p>


                <p
                    class="
                        mt-[3px]
                        font-['DM_Sans']
                        text-[9px]
                        font-medium
                        uppercase
                        text-[#ff9900]
                    "
                >
                    Espace <?= htmlspecialchars($role) ?>
                </p>

            </div>

        </div>

    </div>

</header>