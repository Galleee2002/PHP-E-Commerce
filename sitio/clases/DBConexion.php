<?php

class DBConexion
{
    // public const DB_HOST = "127.0.0.1:3306";
    // public const DB_USER = "root";
    // public const DB_PASS = "";
    // public const DB_NAME = "dw3_kuringhian_garcia";

    public const DB_HOST = "127.0.0.1:8889";
    public const DB_USER = "root";
    public const DB_PASS = "root";
    public const DB_NAME = "dw3_kuringhian_garcia";

    public function getConexion(): PDO
    {
        $db_dsn = "mysql:host=" . self::DB_HOST
            . ";dbname=" . self::DB_NAME
            . ";charset=utf8mb4";

        return new PDO($db_dsn, self::DB_USER, self::DB_PASS);
    }
}
