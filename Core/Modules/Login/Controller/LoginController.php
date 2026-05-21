<?php
require_once __DIR__ . '/../../../Config/Conn.php';
require_once __DIR__ . '/../../../Helpers/Autoload.php';

use Core\Database\Conn;

class LoginController
{
    private $conn;
    private Regex $regex;


    public function __construct()
    {
        $this->conn = (new Conn)->getConnect();
        $this->regex = new Regex();
    }

    public function index()
    {
        include_once __DIR__ . '/../views/loginView.php';
    }

    public function login()
    {

        header('Content-Type: application/json');
        $permisosModel = new PermisosModel();
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit();
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $documento = $data['docum'];
        $password = $data['pass'];

        try {

            if (empty($documento)) throw new Exception("Documento debe ser obligatorio");
            if (empty($password)) throw new Exception("Contraseña debe ser obligatorio");

            if (!$this->regex->validarNumeros($documento)) throw new Exception("No se permiten caracteres especiales");

            if (empty($documento) || empty($password)) {
                Response::fail('Todos los campos son obligatorios');
            }

            if (!$this->conn) {
                echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
                exit();
            }

            $modeloLogin = new LoginModel($this->conn);
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

            // Elimino el id de sessión guardado anteriormente y lo regenero.
            session_regenerate_id(true);

            $result = $permisosModel->renderMenu((int) $usuario['rl_id']);

            $_SESSION['usuario'] = [
                'id' => $usuario['usu_id'],
                'nombre' => $usuario['usu_nombres'],
                'apellido' => $usuario['usu_apellidos'],
                'rol_id' => $usuario['rl_id'],
                'rol_nombre' => $usuario['rl_nombre'],
                'email' => $usuario['usu_email']
            ];


            $_SESSION['renderMenu'] = $result['data'];

            echo json_encode([
                'success' => true,
                'message' => 'Conectado',
                'url' => '/Core/dashboard.php?modulo=Dashboard&controlador=Dashboard&function=dashboard'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function logout()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();


        if (UtilsFunctions::ajaxGeneral()) {
            header(CONTENT_TYPE);
            // echo json_encode([
            //     'status' => true,
            //     'message' => 'Sesión cerrada correctamente.',
            //     'redirect' => Router::createRoute('Login', 'Login', 'index', false, 'dashboard')
            // ]);
            Response::success('Sesión cerrada correctamente.', [
                'redirect' => Router::createRoute('Login', 'Login', 'index', false, 'dashboard')
            ]);
            exit();
        }

        Rect::redirectTo(Router::createRoute('Login', 'Login', 'index', false, 'dashboard'));



        exit();
    }
}
