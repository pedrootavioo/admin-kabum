<?php

use Source\Core\Env;

$env = new Env(__DIR__ . "/../.env");
$env->load();

const CONF_SYSTEM_TITLE = "KaBuM - Portal Administrativo";
const CONF_SYSTEM_LOGO = "https://static.kabum.com.br/conteudo/icons/logo.svg";

define("DB", [
    "DRIVER" => $_ENV['DB_DRIVER'] ?? 'mysql',
    "HOST" => $_ENV['DB_HOST'] ?? 'localhost',
    "PORT" => $_ENV['DB_PORT'] ?? '3306',
    "NAME" => $_ENV['DB_NAME'] ?? 'kabum',
    "USER" => $_ENV['DB_USER'] ?? 'root',
    "PASSWD" => $_ENV['DB_PASSWD'] ?? '',
    "OPTIONS" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

const CONF_SESSION_IDENTIFIER = 'KaBuM';

define("CONF_USER_ADMIN_TEST", [
    'name' => $_ENV['CONF_USER_ADMIN_TEST_NAME'] ?? 'Guardião KaBuM',
    'email' => $_ENV['CONF_USER_ADMIN_TEST_EMAIL'] ?? 'email@kabum.com',
    'password' => $_ENV['CONF_USER_ADMIN_TEST_PASSWORD'] ??'123456',
]);