<?php
include_once __DIR__ . '/../model/usuariosModel.php';
// include_once '../proyecto_sigia/app/config/conn.php'; 
include_once __DIR__ . '/../../../config/conn.php';

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

    public function __construct($conexion) {
        $this->conn = $conexion;
    }


    public function userView(){
        
        $roles = [];
        
        //TODO: mandar a modelo.
        $resultado = $this->conn->query("SELECT * FROM roles");
            if ($resultado && $resultado->num_rows > 0) {
                 while ($row = $resultado->fetch_assoc()) {
                    $roles[] = $row;
                    
                }  
            }
            // dd($roles);
        $rowTp = $this->conn->query("SELECT * FROM tipo_documento");
            if ($rowTp && $rowTp->num_rows > 0) {
                 while ($row = $rowTp->fetch_assoc()) {
                    $tp_documento[] = $row;
                    
                }  
            }
            // dd($roles);
        return include __DIR__ . '/../views/usuariosView.php';
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
                'usu_direccion' => $_POST['usu_direccion'],
                'usu_telefono'  => $_POST['usu_telefono'],
                'usu_id_estado' => 1,
                'usu_tp_id' => $_POST['usu_tp_id'],
                'rol_id'        => $_POST['rol_id'],
            ];
    
            $resultado = $usuariosModel->create($datos);
    
            if ($resultado === true) {
                echo "<script>alert('Usuario registrado exitosamente'); window.location.href = '" . getUrl('usuarios','usuarios','userView', false, 'dashboard') . "';</script>";
            } else {
                echo $resultado;
            }
        }
    }
    public function consultUser(){
        // include_once __DIR__ . '/../model/usuariosModel.php';
    
        $modeloUsuarios = new usuarios($this->conn);
    
        $usuarios = $modeloUsuarios->search();
    
        $path = __DIR__ . '/../views/consultView.php';
        // var_dump($path);
        return include $path;
    }
    
    public function updateUser(){
        //include_once __DIR__ . '/../model/usuariosModel.php';
        
        $id = $_POST['usu_id'];
        unset($_POST['usu_id']);
        
        
        $dato = new usuarios($this->conn);
        $dato->update($_POST,$id);
        
        $modeloUsuarios = new usuarios($this->conn);
        $usuarios = $modeloUsuarios->search();
    
        
        if ($dato) {
            return include_once __DIR__ . '/../views/consultView.php';
        }
        
    }
    public function offUser(){
        
    }
    
    //
    public function updateUserView(){
        //include_once __DIR__ . '/../model/usuariosModel.php';
        
        $id = $_GET['usu_id'];    
        
        $datos = new usuarios($this->conn);
        
        $usuarioUpdate = $datos->searchU($id);
        // print_r($usuarioUpdate);die();
    
       return include_once __DIR__ . '/../views/updateView.php';
    }
    
    public function deleteUserView(){
       include_once '../proyecto_sigia/app/modules/usuarios/views/deleteView.php';
    }
}

?>