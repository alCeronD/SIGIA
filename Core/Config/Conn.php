<?php

require_once __DIR__ . '/Config.php';


class Conn
{
    private \PDO $conn;

    public function __construct()
    {
        $this->setConnect();
    }

    public function setConnect()
    {
        try {
            // DNS
            $dns = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . CHARSET;


            // OPTIONS DE LA BASE DE DATOS.
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT => true
            ];

            $this->conn = new PDO($dns, DB_USER, DB_PASS, options: $options);
        } catch (\PDOException $e) {
            die('Fallo en la conexión' . $e->getMessage());
        }
    }

    public function getConnect()
    {
        return $this->conn;
    }
}
