<?php
include_once __DIR__ . '/../model/usuariosModel.php';
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
        $_SESSION['css'] = 'usuarios/usuarios.css';
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
            // Validaciones
            //Pendiente realizar la consulta del Num_doc y si no esta que continue si el proceso
            
            if ($resultado) {
                echo "<script>alert('Usuario registrado exitosamente'); window.location.href = '" . getUrl('usuarios','usuarios','userView', false, 'dashboard') . "';</script>";
            } else {
                echo $resultado;
            }
        }
    }
    
    
    public function consultUser(){
    
        $modeloUsuarios = new usuarios($this->conn);
    
        $usuarios = $modeloUsuarios->search();
        // dd($usuarios);
        $path = __DIR__ . '/../views/consultView.php';
        $_SESSION['css'] = 'usuarios/usuarios.css';
        // var_dump($path);
        return include $path;
    }
    
    public function updateUser(){
        
        $id = $_POST['usu_id'];
        unset($_POST['usu_id']);
        $dato = new usuarios($this->conn);
        $dato->update($_POST,$id);
        
        $modeloUsuarios = new usuarios($this->conn);
        $usuarios = $modeloUsuarios->search();
    
        
        if ($dato) {
            echo "<script>alert('Usuario actualizado exitosamente'); window.location.href = '" . getUrl('usuarios','usuarios','consultUser', false, 'dashboard') . "';</script>";
            return include_once __DIR__ . '/../views/consultView.php';
        }
        
    }
    public function offUser(){
        
    }
    
    //
    public function updateUserView(){
        
        $id = $_GET['usu_id'];    
        $_SESSION['css'] = 'usuarios/usuarios.css';
        $datos = new usuarios($this->conn);
        
        $usuarioUpdate = $datos->searchU($id);
       include_once __DIR__ . '/../../usuarios/views/updateView.php';
    }
    
    public function deleteUserView(){
       include_once '../proyecto_sigia/app/modules/usuarios/views/deleteView.php';
    }
    
    public function cambiarEstadoUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $usu_id = $_GET['usu_id'];
    
            $query = "UPDATE usuarios 
                      SET usu_id_estado = CASE 
                        WHEN usu_id_estado = 1 THEN 0 
                        ELSE 1 END 
                      WHERE usu_id = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $usu_id);
            
            if ($stmt->execute()) {
                echo "<script>alert('Estado cambiado exitosamente'); window.location.href = '" . getUrl('usuarios','usuarios','consultUser', false, 'dashboard') . "';</script>";
            } else {
                echo "Error al cambiar estado: " . $stmt->error;
            }
        } else {
            echo "Método no permitido";
        }
    }
        
}

?>