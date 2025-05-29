<?php 
<<<<<<< HEAD
require_once __DIR__ . '/config.php';
class Conection {
    private $conn;

    public function __construct() {
        $this->setConnect();
    }

    public function setConnect() {
        // Ya no necesitas variables intermedias
    }

    public function getConnect() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
=======

class Conection{

    //Atributos
    private $db;
    private $user;
    private $host;
    private $password;
    private $conn; //DEFINIR LA CONEXIÓN.

    //Constructor
    /**
     * Summary of __construct
     * 
     * Cuando se crea un OBJETO o en otras palabras, una INSTANCIA DE LA CLASE. Si la clase TIENE UN CONSTRUCTOR, lo primero que va a hacer es APUNTAR AL CONSTRUCTOR Y EJECUTAR LO QUE TIENE AHÍ ADENTRO.
     */
    public function __construct(){
        $this->setConnect();
    }

    public function setConnect(){
        //Aca están las viriables para conectar.
        include_once 'config.php';
        $this->user = $user;
        $this->host = $host;
        $this->db = $database;
        $this->password = $password;

    }

    public function getConnect(){

        $this->conn = new mysqli($this->host,$this->user,$this->password,$this->db);

        if ($this->conn) {
            // echo 'conexión exitosa';
        }else{
            echo "conexión fallo ".$this->conn->connect_error;
        }

        return $this->conn;


    }

   
}

>>>>>>> d99da80 (commit brahiam)
?>