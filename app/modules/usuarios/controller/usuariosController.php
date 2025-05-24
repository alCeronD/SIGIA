<?php
include_once __DIR__ . '/../model/usuariosModel.php';

class usuariosController{

    private $usu_docum;
    private $usu_nombres;
    private $usu_apellidos;
    private $usu_password;
    private $usu_email;
    private $usu_telefono;
    private $usu_id_estado;
    private $usu_tp_id;

    private $conn;

    function __construct($conexion) {
        $this->conn = $conexion;
    }


    public function userView(){

        include_once __DIR__ . '/../views/usuariosView.php';
    }
    public function createUser(){

        $this->usu_docum = $_POST['usu_docum'];
        $this->usu_nombres = $_POST['usu_nombres'];
        $this->usu_apellidos = $_POST['usu_apellidos'];
        $this->usu_password = $_POST['usu_password'];
        $this->usu_email = $_POST['usu_email'];
        $this->usu_telefono = $_POST['usu_telefono'];
        



    }
}





?>