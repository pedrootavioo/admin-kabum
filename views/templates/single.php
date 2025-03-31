<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= !empty($title) ? $title . " - " . CONF_SYSTEM_TITLE : CONF_SYSTEM_TITLE ?></title>
    <?= \Source\Support\Asset::assets('main.js', __DIR__ . '/../../public/dist/.vite/manifest.json', $router) ?>
    <link rel="icon" href="https://static.kabum.com.br/conteudo/favicon/favicon.ico" type="image/ico">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary vh-100">
<?php //= $view->insert('header') ?>
<main class="form-single w-100 m-auto">
    <?= $view->content() ?>
    <?= $session->flash() ?>
</main>
<footer>
    <div class="darkModeToggle">
        <button id="darkModeToggle" class="btn btn-outline-secondary">
            <i id="darkModeToggleIcon" data-feather="sun"></i>
        </button>
    </div>
</footer>
</body>
</html>