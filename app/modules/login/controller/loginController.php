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
        header('Content-Type: application/json');
        session_start();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $documento = $_POST['docum'] ?? '';
            $password = $_POST['pass'] ?? '';
            // dd($password);
            if (empty($documento) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                exit();
            }
    
            if (!$this->conn) {
                echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
                exit();
            }
    
            $modeloLogin = new login($this->conn);
            $esValido = $modeloLogin->loginValidation($password, $documento);
            if ($esValido['success']) {
                $usuario = $esValido['usuario']; 
            
                $_SESSION['usuario'] = [
                    'id' => $usuario['usu_id'],
                    'nombre' => $usuario['usu_nombres'],
                    'apellido' => $usuario['usu_apellidos'],
                    'rol_id' => $usuario['rl_id'],
                    'rol_nombre' => $usuario['rl_nombre'],
                    'email' => $usuario['usu_email']
                ];
            
                echo json_encode([
                    'success' => true,
                    'message' => 'Conectado',
                    'url' => '/proyecto_sigia/app/dashboard.php'
                ]);
            }
            else {
                echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
            }
    
            exit(); 
        }
    
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit();
    }
    
    public function logout() {
        session_unset();
        redirect(getUrl('login','login','index',false,false));
    }
        
}

?>
