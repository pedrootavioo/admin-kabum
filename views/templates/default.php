<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= !empty($title) ? $title . " - " . CONF_SYSTEM_TITLE : CONF_SYSTEM_TITLE ?></title>
    <?= \Source\Support\Asset::assets('main.js') ?>
    <link rel="icon" href="https://static.kabum.com.br/conteudo/favicon/favicon.ico" type="image/ico">
</head>
<body>
<?= $view->insert('header') ?>

<div class="container-fluid main-container">
    <div class="row">

        <?= $view->insert('sidebar') ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-5 py-5">
            <?= $session->flash() ?>
            <?= $view->content() ?>
        </main>

    </div>
</div>


<?= $view->insert('footer') ?>
</body>
</html>
