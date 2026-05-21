<?php


require_once __DIR__ . '/Config.php';


class Conn
{
    private mysqli $conn;

    public function __construct()
    {
        $this->setConnect();
    }

    public function setConnect()
    {
        try {
            $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $this->conn->set_charset(CHARSET);
            if ($this->conn->connect_error) {
                die("Conexión fallida: " . $this->conn->connect_error);
            }

            return $this->conn;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getConnect()
    {
        return $this->conn;
    }
}
