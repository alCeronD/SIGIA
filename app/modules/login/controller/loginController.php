<?php 

include_once __DIR__ . '/../model/loginModel.php';
include_once __DIR__ . '/../../../helpers/response.php';

class loginController {
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

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit();
        }

        $documento = $_POST['docum'] ?? '';
        $password = $_POST['pass'] ?? '';

        if (empty($documento) || empty($password)) {
            fail('Todos los campos son obligatorios');
        }

        if (!$this->conn) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
            exit();
        }

        $modeloLogin = new login($this->conn);
        $usuarioResult = $modeloLogin->buscarUsuarioPorDocumento($documento);

        if (!$usuarioResult['status']) {
            echo json_encode(['status' => false, 'message' => $usuarioResult['message']]);
            exit();
        }

        $usuario = $usuarioResult['usuario'];

        if (!$modeloLogin->verificarPassword($password, $usuario['usu_password'])) {
            echo json_encode(['status' => false, 'message' => 'Contraseña incorrecta']);
            exit();
        }

        

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

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();

        redirect(getUrl('login', 'login', 'index', false, false));
    }
}
