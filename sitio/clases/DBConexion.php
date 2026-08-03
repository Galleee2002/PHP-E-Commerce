<?php

class DBConexion
{
    public const DB_HOST = "127.0.0.1";
    public const DB_USER = "root";
    public const DB_NAME = "dw3_kuringhian_garcia";

    /** MAMP (Mac) */
    public const DB_PORT_MAC = "8889";
    public const DB_PASS_MAC = "root";

    /** XAMPP / WAMP (Windows) */
    public const DB_PORT_WIN = "3306";
    public const DB_PASS_WIN = "";

    public function getConexion(): PDO
    {
        $esMac = PHP_OS_FAMILY === "Darwin";
        $port = $esMac ? self::DB_PORT_MAC : self::DB_PORT_WIN;
        $pass = $esMac ? self::DB_PASS_MAC : self::DB_PASS_WIN;

        $db_dsn = "mysql:host=" . self::DB_HOST
            . ";port=" . $port
            . ";dbname=" . self::DB_NAME
            . ";charset=utf8mb4";

        return new PDO($db_dsn, self::DB_USER, $pass);
    }
}
