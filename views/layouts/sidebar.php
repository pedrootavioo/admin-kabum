<nav class="col-md-2 d-none d-md-block sidebar bg-body-secondary">
    <div class="sidebar-sticky ">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-body" href="<?= $router->url('app.index') ?>">
                    <span class="text-secondary" data-feather="home"></span>
                    Área do guardião
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-body" href="<?= $router->url('clients.index') ?>">
                    <span class="text-body-secondary" data-feather="users"></span>
                    Clientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-body" href="<?= $router->url('users.index') ?>">
                    <span class="text-body-secondary" data-feather="tool"></span>
                    Usuários (guardiões)
                </a>
            </li>
        </ul>
    </div>
</nav>