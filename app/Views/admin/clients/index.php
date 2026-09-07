<?php

$clients = $clients ?? [];
$termeRecherche = trim($_GET['q'] ?? '');
?>

<div class="space-y-6">

    <!-- En-tête -->

    <section
        class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-orange-900 shadow-sm">


        <div
            class="flex flex-col gap-5 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <div class="flex items-center gap-2">
                    <div class="h-1 w-8 rounded-full bg-[#ff9500]"></div>

                    <span class="text-xs font-semibold uppercase tracking-wider text-[#ff9500]">
                        Clients
                    </span>
                </div>


                <h1
                    class="text-2xl font-bold text-white sm:text-3xl">
                    Gestion des clients
                </h1>

                <p
                    class="mt-2 max-w-2xl text-sm text-gray-300">
                    Consultez les clients et leur historique de commandes.
            </div>

        </div>
    </section>



    <!-- Recherche -->
    <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">

        <form
            id="formRechercheClient"
            class="flex flex-col gap-3 sm:flex-row">

            <div class="relative flex-1">

                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>

                <input
                    type="text"
                    name="q"
                    value="<?= htmlspecialchars(
                                $termeRecherche,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                    placeholder="Rechercher par nom, prénom ou email..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-10 pr-4 text-sm text-gray-700 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-2 focus:ring-orange-100">

            </div>

            <button
                type="submit"
                class="rounded-xl bg-[#ff9500] px-5 py-3 text-sm font-semibold text-white transition hover:bg-orange-600">
                <i class="fa-solid fa-magnifying-glass mr-2"></i>
                Rechercher
            </button>

            <?php if ($termeRecherche !== ''): ?>

                <a
                    href="/admin/clients"
                    class="rounded-xl border border-gray-200 px-5 py-3 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                    Réinitialiser
                </a>

            <?php endif; ?>

        </form>

    </div>


    <!-- Liste -->
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="border-b border-gray-100 bg-gray-50">
                    <tr>
                        <th class="px-5 py-4 text-left font-semibold text-gray-500">
                            Client
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-gray-500">
                            Email
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-gray-500">
                            Téléphone
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-gray-500">
                            Adresse
                        </th>

                        <th class="px-5 py-4 text-right font-semibold text-gray-500">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    <?php if (empty($clients)): ?>

                        <tr>
                            <td
                                colspan="5"
                                class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">

                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <i class="fa-solid fa-users text-xl"></i>
                                    </div>

                                    <p class="mt-4 font-semibold text-gray-700">
                                        Aucun client trouvé
                                    </p>

                                    <p class="mt-1 text-sm text-gray-400">
                                        <?php if ($termeRecherche !== ''): ?>
                                            Aucun résultat pour
                                            « <?= htmlspecialchars(
                                                    $termeRecherche,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?> ».
                                        <?php else: ?>
                                            Aucun client enregistré pour le moment.
                                        <?php endif; ?>
                                    </p>

                                </div>
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($clients as $client): ?>

                            <tr class="transition hover:bg-orange-50/40">

                                <!-- Client -->
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm font-bold text-orange-600">
                                            <?= htmlspecialchars(
                                                mb_strtoupper(
                                                    mb_substr(
                                                        $client->getPrenom(),
                                                        0,
                                                        1
                                                    )
                                                ),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                        <div class="min-w-0">

                                            <p class="truncate font-semibold text-gray-800">
                                                <?= htmlspecialchars(
                                                    $client->getNomComplet(),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </p>

                                            <p class="text-xs text-gray-400">
                                                Client #<?= (int) $client->getId() ?>
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                <!-- Email -->
                                <td class="px-5 py-4 text-gray-600">
                                    <?= htmlspecialchars(
                                        $client->getEmail(),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>


                                <!-- Téléphone -->
                                <td class="px-5 py-4 text-gray-600">
                                    <?= htmlspecialchars(
                                        $client->getTelephone() ?? '-',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>


                                <!-- Adresse -->
                                <td class="max-w-xs px-5 py-4 text-gray-600">
                                    <span class="block truncate">
                                        <?= htmlspecialchars(
                                            $client->getAdresse(),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>
                                </td>


                                <!-- Action -->
                                <td class="px-5 py-4 text-right">

                                    <a
                                        href="/admin/clients/show/<?= (int) $client->getId() ?>"
                                        title="Consulter"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-orange-50 hover:text-[#ff9500]">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<script>
    const formRechercheClient =
        document.getElementById('formRechercheClient');

    if (formRechercheClient) {
        formRechercheClient.addEventListener(
            'submit',
            function(event) {
                event.preventDefault();

                const input =
                    formRechercheClient.querySelector(
                        'input[name="q"]'
                    );

                const terme = input.value.trim();

                if (terme === '') {
                    window.location.href =
                        '/admin/clients';
                    return;
                }

                window.location.href =
                    '/admin/clients/recherche/' +
                    encodeURIComponent(terme);
            }
        );
    }
</script>