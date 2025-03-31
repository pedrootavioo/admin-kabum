<?php
$router->get('/install', 'InstallController', 'form', 'install.form');
$router->post('/install/process', 'InstallController', 'process', 'install.process');
$router->get('/install/db', 'InstallController', 'db', 'install.db');
