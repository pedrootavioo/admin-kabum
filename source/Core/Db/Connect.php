<?php

namespace Source\Core\Db;

use PDO;
use PDOException;

class Connect
{
    private static ?PDO $connection = null;

    public static function getConnection(): ?PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = DB['DRIVER'] . ":host=" . DB['HOST'] . ";dbname=" . DB['NAME'] . ";port=" . DB['PORT'];
                self::$connection = new PDO($dsn, DB['USER'], DB['PASSWD'], DB['OPTIONS']);
            } catch (PDOException $e) {
                return null;
            }
        }

        return self::$connection;
    }

    public static function closeConnection(): void
    {
        self::$connection = null;
    }
}