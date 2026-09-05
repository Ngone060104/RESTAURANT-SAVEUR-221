<?php

/**
 * Gestion des catégories — Espace Gérant
 *
 * Variables disponibles :
 * - $categories
 * - $terme
 * - $erreurs
 * - $form
 * - $editId
 * - $message
 */

$terme = trim($terme ?? ($_GET['q'] ?? ''));
$erreurs = $erreurs ?? [];
$form = $form ?? [];
$editId = $editId ?? null;
$message = $message ?? null;

$hasFormError = !empty($erreurs);
?>

<main class="min-h-[calc(100vh-79px)] bg-[#f5f5f5] px-4 py-5 sm:px-5 lg:px-[30px]">

    <div class="mx-auto max-w-[1180px]">

        <!-- =====================================================
             HERO
        ====================================================== -->

        <section
            class="mb-5 overflow-hidden rounded-[10px] bg-gradient-to-r from-[#252525] via-[#754916] to-[#ff9900] px-5 py-5 shadow-sm sm:px-6 sm:py-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div class="min-w-0">

                    <h1
                        class="font-['Inter'] text-[22px] font-extrabold tracking-tight text-white sm:text-[25px]">
                        Gestion des Catégories
                    </h1>

                    <p
                        class="mt-2 font-['DM_Sans'] text-[11px] leading-5 text-white/85 sm:text-[12px]">
                        Organisez la carte de votre restaurant par familles de plats.
                    </p>

                </div>

                <!-- NOUVELLE CATÉGORIE -->

                <button
                    type="button"
                    onclick="ouvrirModalCategorie()"
                    class="inline-flex h-[42px] shrink-0 items-center justify-center gap-2 rounded-[9px] bg-[#d97700] px-5 font-['DM_Sans'] text-[11px] font-bold text-white shadow-sm transition duration-200 hover:-translate-y-[1px] hover:bg-[#c96d00] hover:shadow-md active:translate-y-0">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    Nouvelle Catégorie
                </button>

            </div>
        </section>


        <!-- =====================================================
             MESSAGE ERREUR SUPPRESSION
        ====================================================== -->

        <?php if ($message): ?>
            <div
                id="toastSuppression"
                class="fixed top-5 right-5 z-[200] flex w-[calc(100%-40px)] max-w-[380px] translate-y-5 items-start gap-3 rounded-[10px] border border-[#f1caca] bg-white px-4 py-3 shadow-xl opacity-0 transition-all duration-300 sm:right-6 sm:w-auto">

                <span
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#fff0f0] text-[#dc5555]">
                    <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
                </span>

                <div class="min-w-0 flex-1">
                    <p class="font-['DM_Sans'] text-[11px] font-bold text-[#b33a3a]">
                        Suppression impossible
                    </p>

                    <p class="mt-1 font-['DM_Sans'] text-[10px] leading-5 text-[#777777]">
                        <?= htmlspecialchars($message) ?>
                    </p>
                </div>

                <button
                    type="button"
                    onclick="fermerToastSuppression()"
                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[#aaaaaa] transition hover:bg-[#f5f5f5] hover:text-[#333333]">
                    <i class="fa-solid fa-xmark text-[10px]"></i>
                </button>
            </div>
        <?php endif; ?>


        <!-- =====================================================
             RECHERCHE
        ====================================================== -->

        <section
            class="mb-5 rounded-[11px] border border-[#e5e5e5] bg-white p-4 shadow-sm">

            <form
                action="/gerant/categories"
                method="GET"
                class="flex flex-col gap-3 sm:flex-row">

                <div class="relative flex-1">

                    <i
                        class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[12px] text-[#ff9900]"></i>

                    <input
                        type="text"
                        name="q"
                        value="<?= htmlspecialchars($terme) ?>"
                        placeholder="Rechercher par catégorie..."
                        class="h-[42px] w-full rounded-[8px] border border-[#e4e4e4] bg-[#fafafa] pl-10 pr-4 font-['DM_Sans'] text-[11px] text-[#333333] outline-none transition focus:border-[#ff9900] focus:bg-white">

                </div>

                <button
                    type="submit"
                    class="h-[42px] rounded-[8px] bg-[#292929] px-5 font-['DM_Sans'] text-[11px] font-bold text-white transition hover:bg-[#1f1f1f]">
                    <i class="fa-solid fa-magnifying-glass mr-2 text-[10px]"></i>
                    Rechercher
                </button>

                <?php if ($terme !== ''): ?>

                    <a
                        href="/gerant/categories"
                        class="inline-flex h-[42px] items-center justify-center rounded-[8px] border border-[#dddddd] px-4 font-['DM_Sans'] text-[11px] font-semibold text-[#666666] transition hover:bg-[#f5f5f5]">
                        Réinitialiser
                    </a>

                <?php endif; ?>

            </form>

        </section>


        <!-- =====================================================
             LISTE
        ====================================================== -->

        <section
            class="overflow-hidden rounded-[11px] border border-[#e5e5e5] bg-white shadow-sm">

            <!-- HEADER -->

            <div
                class="flex items-center justify-between border-b border-[#eeeeee] px-5 py-4">

                <div>

                    <h2
                        class="font-['Inter'] text-[14px] font-extrabold text-[#333333]">
                        Liste des catégories
                    </h2>

                    <p
                        class="mt-1 font-['DM_Sans'] text-[10px] text-[#999999]">
                        <?= count($categories) ?>
                        catégorie<?= count($categories) > 1 ? 's' : '' ?>
                    </p>

                </div>

                <span
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-[#fff1df] text-[#ff9900]">
                    <i class="fa-solid fa-layer-group text-[12px]"></i>
                </span>

            </div>


            <?php if (empty($categories)): ?>

                <!-- =================================================
                     ÉTAT VIDE
                ================================================== -->

                <div class="px-6 py-14 text-center">

                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#f5f5f5] text-[#aaaaaa]">
                        <i class="fa-solid fa-folder-open text-[20px]"></i>
                    </div>

                    <h3
                        class="mt-4 font-['Inter'] text-[14px] font-extrabold text-[#444444]">
                        Aucune catégorie trouvée
                    </h3>

                    <p
                        class="mx-auto mt-2 max-w-[380px] font-['DM_Sans'] text-[11px] leading-5 text-[#999999]">
                        <?php if ($terme !== ''): ?>

                            Aucune catégorie ne correspond à votre recherche.

                        <?php else: ?>

                            Commencez par ajouter une première catégorie à votre menu.

                        <?php endif; ?>
                    </p>

                    <?php if ($terme === ''): ?>

                        <button
                            type="button"
                            onclick="ouvrirModalCategorie()"
                            class="mt-5 inline-flex items-center gap-2 rounded-[8px] bg-[#ff9900] px-4 py-2.5 font-['DM_Sans'] text-[11px] font-bold text-white transition hover:bg-[#ed8d00]">
                            <i class="fa-solid fa-plus text-[9px]"></i>
                            Ajouter une catégorie
                        </button>

                    <?php endif; ?>

                </div>

            <?php else: ?>


                <!-- =================================================
                     DESKTOP / TABLETTE
                ================================================== -->

                <div class="hidden overflow-x-auto md:block">

                    <table class="w-full border-collapse">

                        <thead>

                            <tr class="border-b border-[#eeeeee] bg-[#fafafa]">

                                <th
                                    class="px-5 py-3 text-left font-['DM_Sans'] text-[10px] font-bold uppercase tracking-wide text-[#888888]">
                                    Catégorie
                                </th>

                                <th
                                    class="px-5 py-3 text-left font-['DM_Sans'] text-[10px] font-bold uppercase tracking-wide text-[#888888]">
                                    Description
                                </th>

                                <th
                                    class="px-5 py-3 text-center font-['DM_Sans'] text-[10px] font-bold uppercase tracking-wide text-[#888888]">
                                    Plats rattachés
                                </th>

                                <th
                                    class="px-5 py-3 text-right font-['DM_Sans'] text-[10px] font-bold uppercase tracking-wide text-[#888888]">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($categories as $categorie): ?>

                                <?php
                                $id = $categorie->getId();
                                $libelle = $categorie->getLibelle();
                                $description = $categorie->getDescription();
                                $nombreProduits = $categorie->getNombreProduits();
                                ?>

                                <tr
                                    class="group border-b border-[#eeeeee] last:border-b-0 transition duration-200 hover:bg-[#fffaf4]">

                                    <!-- CATÉGORIE -->

                                    <td class="px-5 py-4">

                                        <div class="flex items-center gap-3">

                                            <span
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[9px] bg-[#fff1df] text-[#ff9900]">
                                                <i class="fa-solid fa-utensils text-[11px]"></i>
                                            </span>

                                            <div class="min-w-0">

                                                <p
                                                    class="font-['DM_Sans'] text-[12px] font-bold text-[#222222]">
                                                    <?= htmlspecialchars($libelle) ?>
                                                </p>



                                            </div>

                                        </div>

                                    </td>


                                    <!-- DESCRIPTION -->

                                    <td class="px-5 py-4">

                                        <?php if ($description): ?>

                                            <p
                                                class="max-w-[450px] truncate font-['DM_Sans'] text-[11px] leading-5 text-[#666666]"
                                                title="<?= htmlspecialchars($description) ?>">
                                                <?= htmlspecialchars($description) ?>
                                            </p>

                                        <?php else: ?>

                                            <span
                                                class="font-['DM_Sans'] text-[10px] italic text-[#aaaaaa]">
                                                Aucune description
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- PLATS RATTACHÉS -->

                                    <td class="px-5 py-4 text-center">

                                        <span
                                            class="inline-flex min-w-[54px] items-center justify-center rounded-full border border-[#ffb347] bg-[#fff1df] px-3 py-1 font-['DM_Sans'] text-[10px] font-bold text-[#8a4b00]">
                                            <?= $nombreProduits ?>
                                            <?= $nombreProduits > 1 ? 'plats' : 'plat' ?>
                                        </span>

                                    </td>


                                    <!-- ACTIONS -->

                                    <td class="px-5 py-4">

                                        <div class="flex items-center justify-end gap-2">

                                            <!-- MODIFIER -->

                                            <button
                                                type="button"
                                                onclick="ouvrirModalModification(
                                                    <?= $id ?>,
                                                    <?= htmlspecialchars(json_encode($libelle), ENT_QUOTES, 'UTF-8') ?>,
                                                    <?= htmlspecialchars(json_encode($description ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                                )"
                                                title="Modifier"
                                                class="flex h-8 w-8 items-center justify-center rounded-[7px] border border-[#e5e5e5] text-[#555555] transition hover:-translate-y-[1px] hover:border-[#ff9900] hover:bg-[#fff1df] hover:text-[#ff9900]">
                                                <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                            </button>


                                            <!-- SUPPRIMER -->

                                            <button
                                                type="button"
                                                title="Supprimer"
                                                onclick="ouvrirModalSuppression(
        <?= $id ?>,
        <?= htmlspecialchars(json_encode($libelle), ENT_QUOTES, 'UTF-8') ?>
    )"
                                                class="flex h-8 w-8 items-center justify-center rounded-[7px] border border-[#f0dddd] text-[#dc5555] transition hover:-translate-y-[1px] hover:bg-[#fff0f0]">
                                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     MOBILE
                ================================================== -->

                <div class="divide-y divide-[#eeeeee] md:hidden">

                    <?php foreach ($categories as $categorie): ?>

                        <?php
                        $id = $categorie->getId();
                        $libelle = $categorie->getLibelle();
                        $description = $categorie->getDescription();
                        $nombreProduits = $categorie->getNombreProduits();
                        ?>

                        <article
                            class="p-4 transition hover:bg-[#fffaf4]">

                            <div class="flex items-start justify-between gap-3">

                                <div class="flex min-w-0 items-center gap-3">

                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[9px] bg-[#fff1df] text-[#ff9900]">
                                        <i class="fa-solid fa-utensils text-[11px]"></i>
                                    </span>

                                    <div class="min-w-0">

                                        <h3
                                            class="truncate font-['DM_Sans'] text-[12px] font-bold text-[#333333]">
                                            <?= htmlspecialchars($libelle) ?>
                                        </h3>

                                        <p
                                            class="mt-1 font-['DM_Sans'] text-[9px] text-[#aaaaaa]">
                                            ID #<?= $id ?>
                                        </p>

                                    </div>

                                </div>


                                <!-- ACTIONS -->

                                <div class="flex shrink-0 items-center gap-2">

                                    <button
                                        type="button"
                                        onclick="ouvrirModalModification(
                                            <?= $id ?>,
                                            <?= htmlspecialchars(json_encode($libelle), ENT_QUOTES, 'UTF-8') ?>,
                                            <?= htmlspecialchars(json_encode($description ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                        )"
                                        title="Modifier"
                                        class="flex h-8 w-8 items-center justify-center rounded-[7px] border border-[#e5e5e5] text-[#555555]">
                                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                    </button>
                                    <button
                                        type="button"
                                        title="Supprimer"
                                        onclick="ouvrirModalSuppression(
        <?= $id ?>,
        <?= htmlspecialchars(json_encode($libelle), ENT_QUOTES, 'UTF-8') ?>
    )"
                                        class="flex h-8 w-8 items-center justify-center rounded-[7px] border border-[#f0dddd] text-[#dc5555]">
                                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                                    </button>

                                </div>

                            </div>


                            <!-- INFOS MOBILE -->

                            <div class="mt-3 flex flex-col gap-2 rounded-[8px] bg-[#fafafa] px-3 py-3">

                                <?php if ($description): ?>

                                    <p
                                        class="font-['DM_Sans'] text-[10px] leading-5 text-[#777777]">
                                        <?= htmlspecialchars($description) ?>
                                    </p>

                                <?php else: ?>

                                    <p
                                        class="font-['DM_Sans'] text-[10px] italic text-[#aaaaaa]">
                                        Aucune description
                                    </p>

                                <?php endif; ?>


                                <div>

                                    <span
                                        class="inline-flex items-center gap-1 rounded-full border border-[#ffb347] bg-[#fff1df] px-3 py-1 font-['DM_Sans'] text-[9px] font-bold text-[#8a4b00]">
                                        <i class="fa-solid fa-utensils text-[8px]"></i>

                                        <?= $nombreProduits ?>

                                        <?= $nombreProduits > 1 ? 'plats rattachés' : 'plat rattaché' ?>
                                    </span>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>

    </div>

</main>


<!-- =========================================================
     MODAL AJOUT / MODIFICATION
========================================================= -->

<div
    id="modalCategorie"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 px-4 backdrop-blur-[2px]">
    <div
        id="modalCategorieBox"
        class="w-full max-w-[460px] scale-95 rounded-[14px] bg-white shadow-2xl transition duration-200">

        <!-- HEADER -->

        <div
            class="flex items-center justify-between border-b border-[#eeeeee] px-5 py-4">

            <div class="flex items-center gap-3">

                <span
                    class="flex h-9 w-9 items-center justify-center rounded-[9px] bg-[#fff1df] text-[#ff9900]">
                    <i class="fa-solid fa-layer-group text-[12px]"></i>
                </span>

                <div>

                    <h3
                        id="modalTitre"
                        class="font-['Inter'] text-[14px] font-extrabold text-[#333333]">
                        Ajouter une catégorie
                    </h3>

                    <p
                        class="mt-1 font-['DM_Sans'] text-[9px] text-[#999999]">
                        Renseignez les informations de la catégorie.
                    </p>

                </div>

            </div>

            <button
                type="button"
                onclick="fermerModalCategorie()"
                class="flex h-8 w-8 items-center justify-center rounded-full text-[#999999] transition hover:bg-[#f5f5f5] hover:text-[#333333]">
                <i class="fa-solid fa-xmark text-[13px]"></i>
            </button>

        </div>


        <!-- FORMULAIRE -->

        <form
            id="formCategorie"
            action="/gerant/categories"
            method="POST"
            class="p-5"
            novalidate>

            <input
                type="hidden"
                id="categorieId"
                name="id"
                value="">


            <!-- LIBELLÉ -->

            <div>

                <label
                    for="categorieLibelle"
                    class="font-['DM_Sans'] text-[11px] font-bold text-[#444444]">
                    Libellé
                    <span class="text-[#ef4444]">*</span>
                </label>

                <input
                    id="categorieLibelle"
                    type="text"
                    name="libelle"
                    maxlength="60"
                    required
                    placeholder="Ex. Entrées"
                    value="<?= htmlspecialchars($form['libelle'] ?? '') ?>"
                    class="mt-2 h-[40px] w-full rounded-[8px] border <?= !empty($erreurs['libelle']) ? 'border-[#dc5555]' : 'border-[#dddddd]' ?> bg-[#fafafa] px-3 font-['DM_Sans'] text-[11px] text-[#333333] outline-none transition focus:border-[#ff9900] focus:bg-white">

                <?php if (!empty($erreurs['libelle'])): ?>

                    <p class="mt-1 font-['DM_Sans'] text-[9px] text-[#dc5555]">
                        <?= htmlspecialchars($erreurs['libelle']) ?>
                    </p>

                <?php endif; ?>

            </div>


            <!-- DESCRIPTION -->

            <div class="mt-4">

                <label
                    for="categorieDescription"
                    class="font-['DM_Sans'] text-[11px] font-bold text-[#444444]">
                    Description
                </label>

                <textarea
                    id="categorieDescription"
                    name="description"
                    maxlength="255"
                    rows="4"
                    placeholder="Décrivez brièvement cette catégorie..."
                    class="mt-2 w-full resize-none rounded-[8px] border <?= !empty($erreurs['description']) ? 'border-[#dc5555]' : 'border-[#dddddd]' ?> bg-[#fafafa] px-3 py-3 font-['DM_Sans'] text-[11px] leading-5 text-[#333333] outline-none transition focus:border-[#ff9900] focus:bg-white"><?= htmlspecialchars($form['description'] ?? '') ?></textarea>

                <?php if (!empty($erreurs['description'])): ?>

                    <p class="mt-1 font-['DM_Sans'] text-[9px] text-[#dc5555]">
                        <?= htmlspecialchars($erreurs['description']) ?>
                    </p>

                <?php endif; ?>

            </div>


            <!-- BOUTONS -->

            <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    onclick="fermerModalCategorie()"
                    class="h-[40px] rounded-[8px] border border-[#dddddd] px-5 font-['DM_Sans'] text-[11px] font-semibold text-[#666666] transition hover:bg-[#f5f5f5]">
                    Annuler
                </button>

                <button
                    id="btnSubmitCategorie"
                    type="submit"
                    class="h-[40px] rounded-[8px] bg-[#ff9900] px-5 font-['DM_Sans'] text-[11px] font-bold text-white transition hover:bg-[#ed8d00]">
                    Ajouter
                </button>

            </div>

        </form>

    </div>
</div>


<!-- =========================================================
     MODAL CONFIRMATION SUPPRESSION
========================================================= -->

<div
    id="modalSuppression"
    class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/40 px-4 backdrop-blur-[2px]">
    <div
        id="modalSuppressionBox"
        class="w-full max-w-[400px] scale-95 rounded-[14px] bg-white shadow-2xl transition duration-200">

        <!-- HEADER -->
        <div class="flex items-center justify-between border-b border-[#eeeeee] px-5 py-4">

            <div class="flex items-center gap-3">

                <span
                    class="flex h-9 w-9 items-center justify-center rounded-[9px] bg-[#fff0f0] text-[#dc5555]">
                    <i class="fa-solid fa-trash-can text-[12px]"></i>
                </span>

                <div>
                    <h3
                        class="font-['Inter'] text-[14px] font-extrabold text-[#333333]">
                        Supprimer la catégorie
                    </h3>

                    <p
                        class="mt-1 font-['DM_Sans'] text-[9px] text-[#999999]">
                        Confirmation de suppression
                    </p>
                </div>

            </div>

            <button
                type="button"
                onclick="fermerModalSuppression()"
                class="flex h-8 w-8 items-center justify-center rounded-full text-[#999999] transition hover:bg-[#f5f5f5] hover:text-[#333333]">
                <i class="fa-solid fa-xmark text-[13px]"></i>
            </button>

        </div>


        <!-- CONTENU -->
        <div class="px-5 py-5">

            <div
                class="rounded-[9px] border border-[#f3dddd] bg-[#fff7f7] px-4 py-3">
                <div class="flex items-start gap-3">

                    <i
                        class="fa-solid fa-triangle-exclamation mt-[2px] text-[12px] text-[#dc5555]"></i>

                    <div>
                        <p
                            class="font-['DM_Sans'] text-[11px] font-semibold leading-5 text-[#555555]">
                            Voulez-vous vraiment supprimer cette catégorie ?
                        </p>

                        <p
                            id="suppressionLibelle"
                            class="mt-1 font-['DM_Sans'] text-[11px] font-bold text-[#dc5555]"></p>

                        <p
                            class="mt-2 font-['DM_Sans'] text-[9px] leading-4 text-[#999999]">
                            Cette action est irréversible. Une catégorie contenant
                            encore des plats ne pourra pas être supprimée.
                        </p>
                    </div>

                </div>
            </div>


            <!-- FORMULAIRE SUPPRESSION -->
            <form
                id="formSuppression"
                action="/gerant/categories/delete"
                method="POST"
                class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">

                <input
                    type="hidden"
                    id="suppressionId"
                    name="id"
                    value="">

                <button
                    type="button"
                    onclick="fermerModalSuppression()"
                    class="h-[40px] rounded-[8px] border border-[#dddddd] px-5 font-['DM_Sans'] text-[11px] font-semibold text-[#666666] transition hover:bg-[#f5f5f5]">
                    Annuler
                </button>

                <button
                    type="submit"
                    class="h-[40px] rounded-[8px] bg-[#dc5555] px-5 font-['DM_Sans'] text-[11px] font-bold text-white transition hover:bg-[#c74747] hover:shadow-md">
                    <i class="fa-solid fa-trash-can mr-2 text-[10px]"></i>
                    Supprimer
                </button>

            </form>

        </div>

    </div>
</div>

<script>
    const modalCategorie = document.getElementById('modalCategorie');
    const modalCategorieBox = document.getElementById('modalCategorieBox');
    const formCategorie = document.getElementById('formCategorie');
    const modalTitre = document.getElementById('modalTitre');
    const btnSubmitCategorie = document.getElementById('btnSubmitCategorie');
    const categorieId = document.getElementById('categorieId');
    const categorieLibelle = document.getElementById('categorieLibelle');
    const categorieDescription = document.getElementById('categorieDescription');

    function ouvrirModalCategorie() {

        categorieId.value = '';
        categorieLibelle.value = '';
        categorieDescription.value = '';

        modalTitre.textContent = 'Ajouter une catégorie';
        btnSubmitCategorie.textContent = 'Ajouter';

        formCategorie.action = '/gerant/categories';

        modalCategorie.classList.remove('hidden');
        modalCategorie.classList.add('flex');

        requestAnimationFrame(() => {
            modalCategorieBox.classList.remove('scale-95');
            modalCategorieBox.classList.add('scale-100');
        });

        setTimeout(() => categorieLibelle.focus(), 100);
    }


    function ouvrirModalModification(id, libelle, description) {

        categorieId.value = id;
        categorieLibelle.value = libelle;
        categorieDescription.value = description || '';

        modalTitre.textContent = 'Modifier la catégorie';
        btnSubmitCategorie.textContent = 'Enregistrer';

        formCategorie.action = '/gerant/categories/update';

        modalCategorie.classList.remove('hidden');
        modalCategorie.classList.add('flex');

        requestAnimationFrame(() => {
            modalCategorieBox.classList.remove('scale-95');
            modalCategorieBox.classList.add('scale-100');
        });

        setTimeout(() => categorieLibelle.focus(), 100);
    }


    function fermerModalCategorie() {

        modalCategorieBox.classList.remove('scale-100');
        modalCategorieBox.classList.add('scale-95');

        setTimeout(() => {

            modalCategorie.classList.remove('flex');
            modalCategorie.classList.add('hidden');

        }, 150);
    }


    const modalSuppression = document.getElementById('modalSuppression');
    const modalSuppressionBox = document.getElementById('modalSuppressionBox');
    const formSuppression = document.getElementById('formSuppression');
    const suppressionId = document.getElementById('suppressionId');
    const suppressionLibelle = document.getElementById('suppressionLibelle');

    function ouvrirModalSuppression(id, libelle) {

        suppressionId.value = id;
        suppressionLibelle.textContent = '« ' + libelle + ' »';

        modalSuppression.classList.remove('hidden');
        modalSuppression.classList.add('flex');

        requestAnimationFrame(() => {
            modalSuppressionBox.classList.remove('scale-95');
            modalSuppressionBox.classList.add('scale-100');
        });
    }

    function fermerModalSuppression() {

        modalSuppressionBox.classList.remove('scale-100');
        modalSuppressionBox.classList.add('scale-95');

        setTimeout(() => {
            modalSuppression.classList.remove('flex');
            modalSuppression.classList.add('hidden');
        }, 150);
    }


    modalCategorie.addEventListener('click', function(event) {

        if (event.target === modalCategorie) {
            fermerModalCategorie();
        }

    });

    modalSuppression.addEventListener('click', function(event) {
        if (event.target === modalSuppression) {
            fermerModalSuppression();
        }
    });


    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            fermerModalCategorie();
            fermerModalSuppression();
        }
    });


    <?php if ($hasFormError): ?>

        document.addEventListener('DOMContentLoaded', function() {

            <?php if ($editId): ?>

                ouvrirModalModification(
                    <?= (int) $editId ?>,
                    <?= htmlspecialchars(json_encode($form['libelle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>,
                    <?= htmlspecialchars(json_encode($form['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                );

            <?php else: ?>

                ouvrirModalCategorie();

            <?php endif; ?>

        });

    <?php endif; ?>

    <?php if ($message): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toastSuppression');

            if (!toast) return;

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-[-20px]', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });

            setTimeout(() => {
                fermerToastSuppression();
            }, 5000);
        });

        function fermerToastSuppression() {
            const toast = document.getElementById('toastSuppression');

            if (!toast) return;

            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-[-20px]', 'opacity-0');

            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    <?php endif; ?>
</script>