<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary p-3 z-1 w-100 fixed-top">
        <div class="container-fluid">

            <a class="navbar-brand" href="/">
                <img src="<?= CONF_SYSTEM_LOGO ?>" alt="<?= CONF_SYSTEM_TITLE ?>" style="height: 40px;">
            </a>

            <div class="d-flex align-items-center">
                <?php if (empty($userIsLoggedIn)): ?>
                    <a class="btn btn-outline-primary me-2" href="<?= $router->url('auth.index') ?>">
                        <i data-feather="log-in"></i>
                        Login
                    </a>
                <?php else: ?>
                    <a class="btn btn-outline-danger me-2" href="<?= $router->url('auth.logout') ?>">
                        <i data-feather="log-out"></i>
                        Sair
                    </a>
                <?php endif; ?>

                <button id="darkModeToggle" class="btn btn-outline-secondary">
                    <i id="darkModeToggleIcon" data-feather="sun"></i>
                </button>
            </div>
        </div>
    </nav>
</header>
