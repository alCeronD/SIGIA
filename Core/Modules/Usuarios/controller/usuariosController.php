<?php
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../roles/model/rolesModel.php';
require_once __DIR__ . "/../model/usuariosModel.php";
include_once __DIR__ . '/../../configModules/model/configModulesModel.php';
require_once __DIR__ . "/../../login/controller/loginController.php";
require_once __DIR__ . "/../../../helpers/validatePermisos.php";
require_once __DIR__ . "/../../../helpers/response.php";
require_once __DIR__ . "/../../../helpers/session.php";
require_once __DIR__ . "/../../../helpers/getUrl.php";
class usuariosController
{

    public $usu_docum;
    public $usu_nombres;
    public $usu_apellidos;
    public $usu_password;
    public $usu_email;
    public $usu_telefono;
    public $usu_id_estado;
    private $usu_tp_id;

    private mysqli $conn;

    protected RolModelo $rolesModel;
    protected ConfigModulesModel $configModules;
    protected usuarios $usuariosModel;

    public function __construct()
    {
        $this->rolesModel = new RolModelo();
        $this->configModules = new ConfigModulesModel();
        $this->usuariosModel = new usuarios();
        $this->conn = (new Conection())->getConnect();
    }
    public function userView()
    {

        $roles = [];

        //TODO: mandar a modelo.
        $resultado = $this->rolesModel->obtenerRoles();
        $rowTp = $this->configModules->select("SELECT * FROM tipo_documento");

        $_SESSION['css'] = 'usuarios/usuarios.css';
        return include __DIR__ . '/../views/usuariosView.php';
    }
    public function createUser(array $data = [])
    {
        validatePermisos('Usuarios', 'createUser');

        header('Content-Type: application/json; charset=utf-8');

        if (!isset($data['usu_email']) || !isset($data['usu_docum'])) {
            http_response_code(200);
            echo json_encode([
                "status" => "error",
                "message" => "No se recibieron datos válidos para crear el usuario."
            ]);
            exit;
        }


        $emailExists = $this->usuariosModel->validateEmail($data['usu_email'], $data['usu_docum'], false);



        if ($emailExists) {
            http_response_code(409);
            echo json_encode([
                "status" => "error",
                "message" => "El correo ya está registrado."
            ]);
            // return;
            exit;
        }
        $documentExists = $this->usuariosModel->validateDocumento($data['usu_docum']);
        if ($documentExists) {
            http_response_code(409);
            echo json_encode([
                "status" => "error",
                "message" => "El número de documento ya está registrado."
            ]);
            exit;
        }

        $datos = [
            'usu_docum'       => $data['usu_docum'],
            'usu_nombres'     => $data['usu_nombres'],
            'usu_apellidos'   => $data['usu_apellidos'],
            'usu_password'    => $data['usu_password'],
            'usu_email'       => $data['usu_email'],
            'usu_direccion'   => $data['usu_direccion'],
            'usu_telefono'    => $data['usu_telefono'],
            'usu_id_estado'   => 1,
            'usu_tp_id'       => $data['usu_tp_id'],
            'rol_id'          => $data['rol_id'],
            'usu_observacion' => $data['usu_observacion']
        ];

        $resultado = $this->usuariosModel->create($datos);

        if (!$resultado) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Error al crear el usuario."
            ]);
            exit;
        }

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Usuario creado exitosamente.",
            "data" => $datos
        ]);
        exit;
    }
    public function consultUser()
    {

        $modeloUsuarios = new usuarios();

        $usuarios = $modeloUsuarios->search();
        $resultado = $this->rolesModel->obtenerRoles();
        $rowTp = $this->configModules->select("SELECT * FROM tipo_documento");

        $path = __DIR__ . '/../views/consultView.php';
        $_SESSION['css'] = 'usuarios/usuarios.css';
        return include $path;
    }

    public function updateUserJSON(array $data)
    {
        // validatePermisos('usuarios','updateUserJSON');
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($data['usu_id']) || empty($data['usu_id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "ID de usuario requerido."]);
            exit;
        }

        $id = $data['usu_id'];
        $rol_id = $data['rol_id'];
        $contrasena = $data['usu_password'] ?? null;

        unset($data['usu_id'], $data['rol_id'], $data['usu_password']);

        $userData = $this->usuariosModel->searchU($id);
        $correoActual = $userData['data']['usu_email'];

        if ($correoActual !== $data['usu_email']) {
            $email = $this->usuariosModel->validateEmail($data['usu_email'], $id, false);
            if ($email) {
                http_response_code(409);
                echo json_encode(["status" => "error", "message" => "El correo ya está en uso."]);
                exit;
            }
        }

        foreach ($data as $key => $value) {
            if (empty($value)) {
                http_response_code(422);
                echo json_encode(["status" => "error", "message" => "El campo \"$key\" es obligatorio."]);
                exit;
            }
        }

        $this->usuariosModel->update($data, $id);

        if (!empty($contrasena)) {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $this->usuariosModel->actualizarContrasena($id, $hash);
        }

        $this->rolesModel->actRolUser($id, $rol_id);

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Usuario actualizado exitosamente."]);
        exit;
    }

    public function updateUserView()
    {
        $id = $_GET['usu_id'];
        $_SESSION['css'] = 'usuarios/usuarios.css';
        $datos = new usuarios();
        $usuarioUpdate = $datos->searchU($id);

        include_once __DIR__ . '/../../usuarios/views/updateView.php';
    }
    public function deleteUserView()
    {
        include_once '../proyecto_sigia/app/modules/usuarios/views/deleteView.php';
    }

    public function cambiarEstadoUsuarioJSON($data)
    {
        validatePermisos('usuarios','cambiarEstadoUsuarioJSON');
        if (!isset($data['usu_id'])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "ID de usuario no proporcionado"
            ]);
            return;
        }

        $usu_id = (int)$data['usu_id'];

        // Llamamos al modelo que ya tienes
        $resultado = $this->usuariosModel->inhabilitarUsuario($usu_id);

        if ($resultado['status']) {
            echo json_encode([
                "status" => "success",
                "message" => $resultado['message']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $resultado['message']
            ]);
        }
    }

    public function actualizarDatosView()
    {
        $_SESSION['css'] = 'usuarios/usuarios.css';
        $id = $_SESSION['usuario']['id'];
        $datos = new usuarios();
        $data = $datos->searchU($id);
        // Este valor es usado en la vista para dar visualizar su información.
        $usuarioUpdate = $data['data'];

        include_once __DIR__ . '/../../usuarios/views/updateUserDate.php';
    }
    public function updateUserInfo()
    {
        $id = $_POST['usu_id'];
        unset($_POST['usu_id']);

        $data = $_POST;
        foreach ($data as $key => $value) {
            if (empty($value)) {
                echo "<script>alert('El campo \"$key\" debe ser diligenciado.'); window.history.back();</script>";
                return;
            }
        }

        $dato = new usuarios();
        $dato->update($data, $id);

        $modeloUsuarios = new usuarios();
        $usuarios = $modeloUsuarios->search();

        $loginObj = new loginController($this->conn);

        echo "<script>alert('Usuario actualizado exitosamente, vuelve a iniciar la sesión.'); window.location.href = '" . getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard') . "';</script>";
        $loginObj->logout();
    }
}

$objUsuarios = new usuariosController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (is_array($data) && isset($data['action'])) {
        $action = $data['action'];
        unset($data['action']);

        switch ($action) {
            case 'addUser':
                $objUsuarios->createUser($data);
                break;

            case 'updateUser':
                $objUsuarios->updateUserJSON($data); // método nuevo que creamos abajo
                break;

            case 'cambiarEstado':
                $objUsuarios->cambiarEstadoUsuarioJSON($data);
                break;


            default:
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Acción no válida"
                ]);
                break;
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "No se recibió una acción válida"
        ]);
    }
}