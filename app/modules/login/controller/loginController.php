<?php 

include_once __DIR__ . '/../model/loginModel.php';

class loginController {
    
    private $documento;
    private $password;
    private $conn;
    
    public function __construct($conexion) {
        $this->conn = $conexion;
    }
    
    public function index() {
        include_once __DIR__ . '/../views/loginView.php';
    }
    
    public function login() {
        session_start();
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $password = $this->password = $_POST['pass'];
            $documento =$this->documento = $_POST['docum'];

            if (!$this->conn) {
                die("Conexión fallida");
            }
    
            $query = "SELECT 
                    u.usu_id,
                    u.usu_docum,
                    u.usu_password, 
                    u.usu_nombres,
                    u.usu_apellidos,
                    u.usu_telefono,
                    u.usu_email,
                    r.rl_id, 
                    r.rl_nombre
                FROM 
                    usuarios u
                INNER JOIN  
                    usuarios_roles ur ON u.usu_id = ur.usr_usu_id
                INNER JOIN 
                    roles r ON ur.usr_rl_id = r.rl_id
                WHERE 
                    u.usu_docum = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $documento);
            
            if (!$stmt->execute()) {
                exit();
            }
            $saveData = $stmt->get_result();
            
            if ($saveData->num_rows > 0) {
                $datos = $saveData->fetch_assoc();
                 //dd($datos['usu_password']);

                    if (password_verify($password, $datos['usu_password'])) {
                    // print()
                    $_SESSION['usuario'] = [
                        'id_usuario' => $datos['usu_id'],
                        'documento' => $datos['usu_docum'],
                        'password' => $datos['usu_password'],
                        'rol_id' => $datos['rl_id'],
                        'rol_nombre' => $datos['rl_nombre'],
                        'nombre' => $datos['usu_nombres'],      
                        'apellido' => $datos['usu_apellidos'],
                        'telefono' => $datos['usu_telefono'],
                        'correo' => $datos['usu_email']
                    ];
                    switch ($_SESSION['usuario']['rol_id']) {
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                            header("Location: /proyecto_sigia/app/dashboard.php");
                            break;
                        default:
                            header("Location: /proyecto_sigia/index.php");
                            break;
                    }

    
                    exit();
                } else {

                    // Contraseña incorrecta
                    header("Location: /proyecto_sigia/index.php");
                    exit();
                }
    
            } else {

                // Usuario no encontrado
                header("Location: /proyecto_sigia/index.php");
                exit();
            }
        }
    }   
    
    public function logout() {
        session_unset();
        // session_destroy();
        redirect(getUrl('login','login','index',false,false));
        // dd("llegue a cerrar sesion");    
    }
        
}

?>
