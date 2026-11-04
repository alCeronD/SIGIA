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
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_TIMEOUT => 3 # Time out en caso de fallo
            ];

            $this->conn = new PDO($dns, DB_USER, DB_PASS, options: $options);
        } catch (\PDOException $e) {
            $trace = $e->getTrace();
            // si el entorno es local o produccion, mostramos un mensaje generico.
            if (defined('APP_DEBUG') && APP_DEBUG === true) {
                $mensaje = $e->getTraceAsString();
                $code = HttpStatus::INTERNAL_SERVER_ERROR;
                Response::responseTemplate($code, $mensaje, $trace[0]);
            } else {
                Response::responseTemplate(HttpStatus::INTERNAL_SERVER_ERROR, "Lo sentimos, servicio temporalmente no disponible", $trace[0]);
            }
        }
    }

    public function getConnect()
    {
        return $this->conn;
    }
}
