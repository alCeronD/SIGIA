<?php 
require_once __DIR__ . '/config.php';
class Conection {
    private $conn;

    public function __construct() {
        $this->setConnect();
    }

    public function setConnect() {
    }

    public function getConnect() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>