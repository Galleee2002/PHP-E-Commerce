<?php
/*
Clase de conexión a nuestra base de datos, usando PDO.
*/

class DBConexion
{
    public const DB_HOST = "127.0.0.1:3306";
    // public const DB_HOST = "127.0.0.1:8889"; // Alternativa posible para Mac.
    public const DB_USER = "root";
    public const DB_PASS = "";
    // public const DB_PASS = "root"; // Alternativa posible.
    public const DB_NAME = "prog2_2026_1_n";

    public function getConexion(): PDO
    {
        $db_dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";

        try {
            $db = new PDO($db_dsn, self::DB_USER, self::DB_PASS);
            
            return $db;
        } catch (\Throwable $th) {
            // echo "¡Oh oh! Parece que hay algún problema con nuestro servidores. Ya hemos despachado una escuadra de monos ninja a resolverlo.";
            throw $th; // Re-arrojamos la Exception.
        }
    }
}