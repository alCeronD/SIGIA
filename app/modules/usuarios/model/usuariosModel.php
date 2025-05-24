<?php

include_once '../proyecto_sigia/app/config/conn.php';

class usuarios {
    public $usu_id;
    public $usu_docum;
    public $usu_nombres;
    public $usu_apellidos;
    public $usu_password;
    public $usu_email;
    public $usu_telefono;
    public $usu_id_estado;
    public $usu_tp_id;
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function create() {
        
    }

    public function update() {
        
    }
    
    public function search() {
        
    }
}
?>
