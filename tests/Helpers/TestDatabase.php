<?php

namespace Test\Helpers;

use PDO;

function truncateTables(PDO $pdo, array $tables): void
{
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    foreach ($tables as $table) {
        $pdo->exec("TRUNCATE TABLE {$table}");
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
}