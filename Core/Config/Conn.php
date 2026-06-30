<?php

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/../Helpers/Autoload.php';


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

            // si el entorno es local o produccion, mostramos un mensaje generico.
            if (defined('APP_DEBUG') && APP_DEBUG === true) {
                die('Fallo en la conexión: ' . $e->getMessage());
            } else {
                die('Lo sentimos, servicio temporalmente no disponible. Inténtelo más tarde.');
            }
        }
    }

    public function getConnect()
    {
        return $this->conn;
    }
}
