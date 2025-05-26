<?php
include_once __DIR__ . '/../model/usuariosModel.php';
include_once '../proyecto_sigia/app/config/conn.php'; 

class usuariosController{

    public $usu_docum;
    public $usu_nombres;
    public $usu_apellidos;
    public $usu_password;
    public $usu_email;
    public $usu_telefono;
    public $usu_id_estado;
    private $usu_tp_id;

    private $conn;

    function __construct($conexion) {
        $this->conn = $conexion;
    }


    public function userView(){
        
        $roles = [];
        
        $resultado = $this->conn->query("SELECT * FROM roles");
            if ($resultado && $resultado->num_rows > 0) {
                 while ($row = $resultado->fetch_assoc()) {
                    $roles[] = $row;
                    
                }  
            }
            // dd($roles);
            include_once __DIR__ . '/../views/usuariosView.php';
        }
    public function createUser(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($this->conn->connect_error) {
                die("Error de conexión: " . $this->conn->connect_error);
            }

            $usuariosModel = new usuarios($this->conn); 
    
            $datos = [
                'usu_docum'     => $_POST['usu_docum'],
                'usu_nombres'   => $_POST['usu_nombres'],
                'usu_apellidos' => $_POST['usu_apellidos'],
                'usu_password'  => $_POST['usu_password'],
                'usu_email'     => $_POST['usu_email'],
                'usu_telefono'  => $_POST['usu_telefono'],
                'rol_id'        => $_POST['rol_id']
            ];
    
            $resultado = $usuariosModel->create($datos);
    
            if ($resultado === true) {
                echo "<script>alert('Usuario registrado exitosamente'); window.location.href = '" . getUrl('usuarios','usuarios','userView') . "';</script>";
            } else {
                echo $resultado;
            }
        }
    }
    public function consultUser(){
        include_once __DIR__ . '/../model/usuariosModel.php';
    
        $modeloUsuarios = new usuarios($this->conn);
    
        $usuarios = $modeloUsuarios->search();
    
        include_once __DIR__ . '/../views/consultView.php';
    }
    
    public function updateUser(){
        
    }
    
    public function offUser(){
        
    }
    
    public function updateUserView(){
       include_once '../proyecto_sigia/app/modules/usuarios/views/updateView.php';
    }
}





?>