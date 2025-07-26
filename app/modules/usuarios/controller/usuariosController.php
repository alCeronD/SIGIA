<?php
require_once __DIR__ . "/../model/usuariosModel.php";
include_once __DIR__ . '/../../roles/model/rolesModel.php';
include_once __DIR__ . '/../../configModules/model/configModulesModel.php';
include_once __DIR__ . '/../../../config/conn.php';
require_once __DIR__ . "/../../../helpers/response.php";
require_once __DIR__ . "/../../../helpers/session.php";
require_once __DIR__ . "/../../login/controller/loginController.php";

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

    private $conn;

    protected $rolesModel;
    protected $configModules;
    protected $usuariosModel;

    public function __construct()
    {
        $this->rolesModel = new RolModelo();
        $this->configModules = new ConfigModulesModel();
        $this->usuariosModel = new usuarios();
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

    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['usu_id'];
            unset($_POST['usu_id']);


            $rol_id = $_POST['rol_id'];
            unset($_POST['rol_id']);

            $contrasena = $_POST['usu_password'];
            unset($_POST['usu_password']);
        }

        $data = $_POST;
        $userData = $this->usuariosModel->searchU($id); //traigo la informacion del usuario
        $correoActual = $userData['data']['usu_email']; //guardo el correo que ya tiene registrado en BD para compararlo
        
        if ($correoActual != $data['usu_email']) {
            $email = $this->usuariosModel->validateEmail($data['usu_email'], $id, false);
            if ($email) {
                echo "<script>alert('El correo ya se encuentra en uso por otro usuario.'); window.history.back();</script>";
                return;
            }
        }
        
        // dd($correoActual);
        // Validar campos obligatorios (excepto contraseña)
        foreach ($data as $key => $value) {
            if (empty($value)) {
                echo "<script>alert('El campo \"$key\" debe ser diligenciado.'); window.history.back();</script>";
                return;
            }
        }

        $dato = new usuarios();
        // Actualizar datos generales
        $dato->update($data, $id);

        // Si la contraseña fue diligenciada, actualizarla
        if (!empty($contrasena)) {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $dato->actualizarContrasena($id, $hash);
        }
        // Actualizar rol del usuario
        $rolesModel = new RolModelo();
        $rolesModel->actRolUser($id, $rol_id);

        // Mostrar usuarios actualizados
        $modeloUsuarios = new usuarios();
        $usuarios = $modeloUsuarios->search();

        echo "<script>alert('Usuario actualizado exitosamente'); window.location.href = '" . getUrl('usuarios', 'usuarios', 'consultUser', false, 'dashboard') . "';</script>";
        return include_once __DIR__ . '/../views/consultView.php';
    }


    public function offUser() {}

    //
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

    public function cambiarEstadoUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $usu_id = (int) $_GET['usu_id'];

            $result = $this->usuariosModel->inhabilitarUsuario($usu_id);

            if ($result) {
                echo "<script>alert('Estado cambiado exitosamente'); window.location.href = '" . getUrl('usuarios', 'usuarios', 'consultUser', false, 'dashboard') . "';</script>";
            }
        } else {
            echo "Método no permitido";
        }
    }

    public function actualizarDatosView()
    {
        $_SESSION['css'] = 'usuarios/usuarios.css';
        $id = $_SESSION['usuario']['id'];
        $datos = new usuarios();
        $usuarioUpdate = $datos->searchU($id);

        include_once __DIR__ . '/../../usuarios/views/updateUserDate.php';
    }

    public function updateUserInfo()
    {
        $id = $_POST['usu_id'];
        unset($_POST['usu_id']);

        // dd($usuario = $_SESSION['usuario']);

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

    public function userPermView() {}
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

            case 'updatEuSER':
                // Logica para actualizar
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
