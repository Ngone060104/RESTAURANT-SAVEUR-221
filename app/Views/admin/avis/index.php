<main class="min-h-screen bg-[#f8f7f4] md:px-10">

    <!-- En-tête -->


    <section
        class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-orange-900 shadow-sm">


        <div
            class="flex flex-col gap-5 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <div class="flex items-center gap-2">
                    <div class="h-1 w-8 rounded-full bg-[#ff9500]"></div>

                    <span class="text-xs font-semibold uppercase tracking-wider text-[#ff9500]">
                        Administration
                    </span>
                </div>


                <h1
                    class="text-2xl font-bold text-white sm:text-3xl">
                    Gestion des avis
                </h1>

                <p
                    class="mt-2 max-w-2xl text-sm text-gray-300">
                    Consultez les commentaires et les notes laissés par les clients
                    et supprimez les avis inappropriés.
            </div>
            <div class="inline-flex items-center gap-2 self-start rounded-full border border-[#f1dfc6] bg-white px-4 py-2">
                <i class="fa-regular fa-star text-[#ff9800]"></i>

                <span class="font-['DM_Sans'] text-[12px] font-bold text-[#555555]">
                    <?= count($avis) ?> avis
                </span>
            </div>

        </div>
    </section>


    <?php if (empty($avis)): ?>

        <!-- Aucun avis -->
        <section class="rounded-[20px] border border-[#e9e5df] bg-white px-6 py-16 text-center shadow-[0_8px_30px_rgba(0,0,0,0.03)]">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#fff4e8]">
                <i class="fa-regular fa-star text-[24px] text-[#ff9800]"></i>
            </div>

            <h2 class="mt-5 font-['DM_Sans'] text-[18px] font-bold text-[#333333]">
                Aucun avis pour le moment
            </h2>

            <p class="mx-auto mt-2 max-w-md font-['DM_Sans'] text-[13px] leading-6 text-[#888888]">
                Les avis laissés par les clients apparaîtront ici une fois disponibles.
            </p>
        </section>

    <?php else: ?>

        <!-- Liste des avis -->
        <section class="overflow-hidden rounded-[20px] border border-[#e9e5df] bg-white shadow-[0_8px_30px_rgba(0,0,0,0.03)]">

            <!-- Barre supérieure -->
            <div class="flex flex-col gap-3 border-b border-[#eeeeee] px-6 py-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="font-['DM_Sans'] text-[16px] font-bold text-[#333333]">
                        Avis des clients
                    </h2>

                    <p class="mt-1 font-['DM_Sans'] text-[12px] text-[#999999]">
                        Liste des évaluations et commentaires publiés.
                    </p>
                </div>

                <div class="inline-flex items-center gap-2 rounded-[10px] bg-[#fafafa] px-3 py-2">
                    <i class="fa-solid fa-message text-[12px] text-[#ff9800]"></i>

                    <span class="font-['DM_Sans'] text-[11px] font-semibold text-[#666666]">
                        <?= count($avis) ?> commentaire<?= count($avis) > 1 ? 's' : '' ?>
                    </span>
                </div>
            </div>


            <!-- Cartes -->
            <div class="divide-y divide-[#eeeeee]">

                <?php foreach ($avis as $unAvis): ?>

                    <article class="px-6 py-6 transition hover:bg-[#fffaf5]">

                        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                            <!-- Contenu principal -->
                            <div class="min-w-0 flex-1">

                                <!-- Client + date -->
                                <div class="flex flex-wrap items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#fff1df]">
                                        <i class="fa-regular fa-user text-[15px] text-[#ff9800]"></i>
                                    </div>

                                    <div>
                                        <h3 class="font-['DM_Sans'] text-[14px] font-bold text-[#333333]">
                                            <?= htmlspecialchars(
                                                $unAvis->getClientNomComplet() ?? 'Client'
                                            ) ?>
                                        </h3>

                                        <p class="mt-0.5 font-['DM_Sans'] text-[11px] text-[#999999]">
                                            Avis du
                                            <?= htmlspecialchars(
                                                date('d/m/Y à H:i', strtotime($unAvis->getDateAvis()))
                                            ) ?>
                                        </p>
                                    </div>

                                </div>


                                <!-- Note -->
                                <div class="mt-5 flex flex-wrap items-center gap-3">

                                    <div class="flex items-center gap-1">

                                        <?php for ($i = 1; $i <= 5; $i++): ?>

                                            <?php if ($i <= $unAvis->getNote()): ?>
                                                <i class="fa-solid fa-star text-[15px] text-[#ff9800]"></i>
                                            <?php else: ?>
                                                <i class="fa-regular fa-star text-[15px] text-[#d8d3cd]"></i>
                                            <?php endif; ?>

                                        <?php endfor; ?>

                                    </div>

                                    <span class="rounded-full border border-[#f0dfc9] bg-[#fff8ef] px-2.5 py-1 font-['DM_Sans'] text-[10px] font-bold text-[#d97900]">
                                        <?= $unAvis->getNote() ?>/5
                                    </span>

                                </div>


                                <!-- Commentaire -->
                                <div class="mt-5 rounded-[14px] border border-[#eeeeee] bg-[#fafafa] px-5 py-4">

                                    <div class="mb-2 flex items-center gap-2">
                                        <i class="fa-regular fa-comment-dots text-[13px] text-[#999999]"></i>

                                        <span class="font-['DM_Sans'] text-[10px] font-bold uppercase tracking-[0.12em] text-[#999999]">
                                            Commentaire
                                        </span>
                                    </div>

                                    <p class="font-['DM_Sans'] text-[13px] leading-6 text-[#444444]">
                                        <?= nl2br(
                                            htmlspecialchars(
                                                $unAvis->getCommentaire() ?: 'Aucun commentaire.'
                                            )
                                        ) ?>
                                    </p>

                                </div>


                                <!-- Commande -->
                                <div class="mt-4 flex flex-wrap items-center gap-4">

                                    <div class="inline-flex items-center gap-2">
                                        <i class="fa-regular fa-file-lines text-[12px] text-[#999999]"></i>

                                        <span class="font-['DM_Sans'] text-[11px] font-medium text-[#777777]">
                                            Commande
                                            <span class="font-bold text-[#333333]">
                                                #<?= $unAvis->getCommandeId() ?>
                                            </span>
                                        </span>
                                    </div>

                                </div>

                            </div>


                            <!-- Action suppression -->
                            <div class="shrink-0 lg:pt-1">

                                <button
                                    type="button"
                                    title="Supprimer cet avis"
                                    data-delete-avis
                                    data-avis-id="<?= (int) $unAvis->getId() ?>"
                                    data-client="<?= htmlspecialchars(
                                                        $unAvis->getClientNomComplet() ?? 'Client',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>"
                                    class="inline-flex items-center justify-center gap-2 rounded-[10px] border border-red-200 bg-red-50 px-4 py-2.5 font-['DM_Sans'] text-[12px] font-bold text-red-600 transition hover:bg-red-100">
                                    <i class="fa-regular fa-trash-can"></i>
                                    Supprimer
                                </button>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>

    <?php endif; ?>

</main>

<!-- =========================================================
     MODAL CONFIRMATION SUPPRESSION AVIS
========================================================= -->

<div
    id="deleteAvisModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 px-4"
    aria-hidden="true">
    <div
        class="w-full max-w-md overflow-hidden rounded-[20px] bg-white shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="deleteAvisTitle">

        <!-- En-tête -->
        <div class="flex items-start gap-4 border-b border-[#eeeeee] px-6 py-5">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50">
                <i class="fa-regular fa-trash-can text-[17px] text-red-500"></i>
            </div>

            <div class="min-w-0">
                <h2
                    id="deleteAvisTitle"
                    class="font-['DM_Sans'] text-[17px] font-bold text-[#333333]">
                    Supprimer cet avis ?
                </h2>

                <p
                    id="deleteAvisClient"
                    class="mt-1 font-['DM_Sans'] text-[12px] text-[#888888]">
                    Client
                </p>
            </div>

        </div>


        <!-- Contenu -->
        <div class="px-6 py-5">

            <p class="font-['DM_Sans'] text-[13px] leading-6 text-[#555555]">
                Vous êtes sur le point de supprimer définitivement cet avis.
                Cette action est irréversible.
            </p>

        </div>


        <!-- Actions -->
        <div class="flex flex-col-reverse gap-3 border-t border-[#eeeeee] bg-[#fafafa] px-6 py-5 sm:flex-row sm:justify-end">

            <button
                type="button"
                id="cancelDeleteAvis"
                class="inline-flex h-[42px] items-center justify-center rounded-[10px] border border-[#dddddd] bg-white px-5 font-['DM_Sans'] text-[12px] font-bold text-[#444444] transition hover:bg-[#f5f5f5]">
                Annuler
            </button>

            <form
                id="deleteAvisForm"
                method="POST"
                action="/admin/avis/delete">
                <input
                    type="hidden"
                    id="deleteAvisId"
                    name="id"
                    value="">

                <button
                    type="submit"
                    class="inline-flex h-[42px] w-full items-center justify-center gap-2 rounded-[10px] bg-red-500 px-5 font-['DM_Sans'] text-[12px] font-bold text-white transition hover:bg-red-600 sm:w-auto">
                    <i class="fa-regular fa-trash-can"></i>
                    Supprimer l'avis
                </button>
            </form>

        </div>

    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const modal = document.getElementById('deleteAvisModal');
        const clientLabel = document.getElementById('deleteAvisClient');
        const idInput = document.getElementById('deleteAvisId');
        const cancelButton = document.getElementById('cancelDeleteAvis');

        if (!modal || !clientLabel || !idInput || !cancelButton) {
            return;
        }


        function ouvrirModal(id, client) {

            idInput.value = id;
            clientLabel.textContent = 'Avis de ' + client;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            modal.setAttribute('aria-hidden', 'false');

            document.body.classList.add('overflow-hidden');
        }


        function fermerModal() {

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            modal.setAttribute('aria-hidden', 'true');

            document.body.classList.remove('overflow-hidden');

            idInput.value = '';
        }


        document.addEventListener('click', function(event) {

            const button = event.target.closest('[data-delete-avis]');

            if (!button) {
                return;
            }

            ouvrirModal(
                button.dataset.avisId,
                button.dataset.client || 'Client'
            );
        });


        cancelButton.addEventListener('click', fermerModal);


        modal.addEventListener('click', function(event) {

            if (event.target === modal) {
                fermerModal();
            }

        });


        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {
                fermerModal();
            }

        });

    });
</script>