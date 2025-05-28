<?php

// include_once __DIR__ . 'app/config/conn.php';
include_once '../proyecto_sigia/app/config/conn.php';


class login{
    public $usu_docum;
    public $usu_password;
    private $conn;
    
    public function __construct($conexion){
        // var_dump($conexion);die();
        
        $this->conn == $conexion;    
    }
}

?>