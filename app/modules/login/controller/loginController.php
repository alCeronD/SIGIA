<?php

include_once __DIR__ . '/../model/loginModel.php';
require_once __DIR__ . '/../../Permisos/Model/PermisosModel.php';
include_once __DIR__ . '/../../../helpers/response.php';
require_once __DIR__ . '/../../../helpers/expg.php';
require_once __DIR__ . '/../../../config/conn.php';

class loginController
{
    private $conn;
    private Regex $regex;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
        $this->regex = new Regex();
    }
    public function index()
    {
        include_once __DIR__ . '/../views/loginView.php';
    }

    public function login()
    {
        $permisosModel = new PermisosModel();
        header('Content-Type: application/json');
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit();
        }

        $documento = $_POST['docum'] ?? '';
        $password = $_POST['pass'] ?? '';

        try {

            if (empty($documento)) throw new Exception("Documento debe ser obligatorio");
            if (empty($password)) throw new Exception("Contraseña debe ser obligatorio");

            if(!$this->regex->validarNumeros($documento)) throw new Exception("No se permiten caracteres especiales");

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
                'url' => '/app/dashboard.php?modulo=dashboard&controlador=dashboard&funcion=dashboard'
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

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => true,
                'message' => 'Sesión cerrada correctamente.',
                'redirect' => getUrl('login', 'login', 'index', false, false)
            ]);
            exit();
        }


        redirect(getUrl('login', 'login', 'index', false, false));

        exit();
    }
}
