<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? htmlspecialchars($titre) . ' | Saveur 221' : 'Saveur 221' ?></title>
     <script src="https://cdn.tailwindcss.com"></script>
     <!-- Dans le <head> de votre layout.php -->

</head>
<body class="bg-orange-50 text-stone-900 font-sans antialiased">
    <?php \App\Core\View::partial('navbar') ?>

    <main>
        <?= $content ?>
    </main>

    <?php \App\Core\View::partial('footer') ?>
</body>
</html>
