<?php
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/UsuariosConst.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;

class UsuariosController extends ConfigController
{

    public $usu_docum;
    public $usu_nombres;
    public $usu_apellidos;
    public $usu_password;
    public $usu_email;
    public $usu_telefono;
    public $usu_id_estado;
    protected RolesModel $rolesModel;
    // protected ConfigModulesModel $configModules;
    protected UsuariosModel $usuariosModel;
    protected array $files = [
        "css" => [
            'usuariosIndex' => ['UsuariosIndex.css'],
            'createUserView' => ['CreateUser.css'],
            'auditoriaUserView' => ['AuditoriasUsuarios.css']
        ],
        "js" => [
            'usuariosIndex' => [],
            'createUserView' => ['CreateUser.js'],
            'auditoriaUserView' => ['AuditoriasUsuarios.css']
        ]
    ];
    public function __construct()
    {
        $this->rolesModel = new RolesModel(); //esto creo que nos puede servir para extraer el listado de los roles, en este caso, no hacemos composicion al modelo sino al servicio.
        // $this->configModules = new ConfigModulesModel();
        $this->usuariosModel = new UsuariosModel();
        $this->createRoutes();
    }
    public function createRoutes()
    {
        $this->routes = [
            'dashboard' => [
                'label' => 'inicio',
                'url' => Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE)
            ],
            'usuariosIndex' => [
                'label' => 'Usuarios',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'usuariosIndex', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'dashboard'
            ],
            'createUserView' => [
                'label' => 'Crear usuario',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'createUserView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'usuariosIndex'
            ]

        ];
    }
    /**
     * Vista principal del modulo de usuarios.
     *
     * @return void
     */
    public function usuariosIndex()
    {
        $path = BASE_URL . US_ROUTE_USUARIOS_INDEX;
        Parent::renderView($path, __FUNCTION__);
    }

    /**
     * Vista para crear un usuario
     *
     * @return void
     */
    public function createUserView()
    {
        $path = BASE_URL . US_ROUTE_USUARIOS_LIST_VIEW;
        Parent::renderView($path, __FUNCTION__);
    }
    public function auditoriaUserView() {}

    // public function createUser(array $data = [])
    // {
    //     validatePermisos('Usuarios', 'createUser');

    //     header('Content-Type: application/json; charset=utf-8');

    //     if (!isset($data['usu_email']) || !isset($data['usu_docum'])) {
    //         http_response_code(200);
    //         echo json_encode([
    //             "status" => "error",
    //             "message" => "No se recibieron datos válidos para crear el usuario."
    //         ]);
    //         exit;
    //     }


    //     $emailExists = $this->usuariosModel->validateEmail($data['usu_email'], $data['usu_docum'], false);



    //     if ($emailExists) {
    //         http_response_code(409);
    //         echo json_encode([
    //             "status" => "error",
    //             "message" => "El correo ya está registrado."
    //         ]);
    //         // return;
    //         exit;
    //     }
    //     $documentExists = $this->usuariosModel->validateDocumento($data['usu_docum']);
    //     if ($documentExists) {
    //         http_response_code(409);
    //         echo json_encode([
    //             "status" => "error",
    //             "message" => "El número de documento ya está registrado."
    //         ]);
    //         exit;
    //     }

    //     $datos = [
    //         'usu_docum'       => $data['usu_docum'],
    //         'usu_nombres'     => $data['usu_nombres'],
    //         'usu_apellidos'   => $data['usu_apellidos'],
    //         'usu_password'    => $data['usu_password'],
    //         'usu_email'       => $data['usu_email'],
    //         'usu_direccion'   => $data['usu_direccion'],
    //         'usu_telefono'    => $data['usu_telefono'],
    //         'usu_id_estado'   => 1,
    //         'usu_tp_id'       => $data['usu_tp_id'],
    //         'rol_id'          => $data['rol_id'],
    //         'usu_observacion' => $data['usu_observacion']
    //     ];

    //     $resultado = $this->usuariosModel->create($datos);

    //     if (!$resultado) {
    //         http_response_code(500);
    //         echo json_encode([
    //             "status" => "error",
    //             "message" => "Error al crear el usuario."
    //         ]);
    //         exit;
    //     }

    //     http_response_code(200);
    //     echo json_encode([
    //         "status" => "success",
    //         "message" => "Usuario creado exitosamente.",
    //         "data" => $datos
    //     ]);
    //     exit;
    // }
    // public function consultUser()
    // {

    //     $modeloUsuarios = new UsuariosModel();

    //     $usuarios = $modeloUsuarios->search();
    //     $resultado = $this->rolesModel->obtenerRoles();
    //     $rowTp = $this->configModules->select("SELECT * FROM tipo_documento");

    //     $path = __DIR__ . '/../views/consultView.php';
    //     $_SESSION['css'] = 'usuarios/usuarios.css';
    //     return include $path;
    // }
    // public function updateUserJSON(array $data)
    // {
    //     // validatePermisos('usuarios','updateUserJSON');
    //     header('Content-Type: application/json; charset=utf-8');

    //     if (!isset($data['usu_id']) || empty($data['usu_id'])) {
    //         http_response_code(400);
    //         echo json_encode(["status" => "error", "message" => "ID de usuario requerido."]);
    //         exit;
    //     }

    //     $id = $data['usu_id'];
    //     $rol_id = $data['rol_id'];
    //     $contrasena = $data['usu_password'] ?? null;

    //     unset($data['usu_id'], $data['rol_id'], $data['usu_password']);

    //     $userData = $this->usuariosModel->searchU($id);
    //     $correoActual = $userData['data']['usu_email'];

    //     if ($correoActual !== $data['usu_email']) {
    //         $email = $this->usuariosModel->validateEmail($data['usu_email'], $id, false);
    //         if ($email) {
    //             http_response_code(409);
    //             echo json_encode(["status" => "error", "message" => "El correo ya está en uso."]);
    //             exit;
    //         }
    //     }

    //     foreach ($data as $key => $value) {
    //         if (empty($value)) {
    //             http_response_code(422);
    //             echo json_encode(["status" => "error", "message" => "El campo \"$key\" es obligatorio."]);
    //             exit;
    //         }
    //     }

    //     $this->usuariosModel->update($data, $id);

    //     if (!empty($contrasena)) {
    //         $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    //         $this->usuariosModel->actualizarContrasena($id, $hash);
    //     }

    //     $this->rolesModel->actRolUser($id, $rol_id);

    //     http_response_code(200);
    //     echo json_encode(["status" => "success", "message" => "Usuario actualizado exitosamente."]);
    //     exit;
    // }
    // public function updateUserView()
    // {
    //     $id = $_GET['usu_id'];
    //     $_SESSION['css'] = 'usuarios/usuarios.css';
    //     $datos = new UsuariosModel();
    //     $usuarioUpdate = $datos->searchU($id);

    //     include_once __DIR__ . '/../../usuarios/views/updateView.php';
    // }
    // public function deleteUserView()
    // {
    //     include_once '../proyecto_sigia/app/modules/usuarios/views/deleteView.php';
    // }

    // public function cambiarEstadoUsuarioJSON($data)
    // {
    //     validatePermisos('usuarios', 'cambiarEstadoUsuarioJSON');
    //     if (!isset($data['usu_id'])) {
    //         http_response_code(400);
    //         echo json_encode([
    //             "status" => "error",
    //             "message" => "ID de usuario no proporcionado"
    //         ]);
    //         return;
    //     }

    //     $usu_id = (int)$data['usu_id'];

    //     // Llamamos al modelo que ya tienes
    //     $resultado = $this->usuariosModel->inhabilitarUsuario($usu_id);

    //     if ($resultado['status']) {
    //         echo json_encode([
    //             "status" => "success",
    //             "message" => $resultado['message']
    //         ]);
    //     } else {
    //         http_response_code(500);
    //         echo json_encode([
    //             "status" => "error",
    //             "message" => $resultado['message']
    //         ]);
    //     }
    // }
    // public function actualizarDatosView()
    // {
    //     $_SESSION['css'] = 'usuarios/usuarios.css';
    //     $id = $_SESSION['usuario']['id'];
    //     $datos = new UsuariosModel();
    //     $data = $datos->searchU($id);
    //     // Este valor es usado en la vista para dar visualizar su información.
    //     $usuarioUpdate = $data['data'];

    //     include_once __DIR__ . '/../../Usuarios/views/updateUserDate.php';
    // }
    // public function updateUserInfo()
    // {
    //     $id = $_POST['usu_id'];
    //     unset($_POST['usu_id']);

    //     $data = $_POST;
    //     foreach ($data as $key => $value) {
    //         if (empty($value)) {
    //             echo "<script>alert('El campo \"$key\" debe ser diligenciado.'); window.history.back();</script>";
    //             return;
    //         }
    //     }

    //     $dato = new UsuariosModel();
    //     $dato->update($data, $id);

    //     $modeloUsuarios = new UsuariosModel();
    //     $usuarios = $modeloUsuarios->search();

    //     $loginObj = new loginController($this->conn);

    //     echo "<script>alert('Usuario actualizado exitosamente, vuelve a iniciar la sesión.'); window.location.href = '" . Router::createRoute('Dashboard', 'Dashboard', 'dashboard', false, 'dashboard') . "';</script>";
    //     $loginObj->logout();
    // }
}

// $objUsuarios = new usuariosController();

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $input = file_get_contents("php://input");
//     $data = json_decode($input, true);

//     if (is_array($data) && isset($data['action'])) {
//         $action = $data['action'];
//         unset($data['action']);

//         switch ($action) {
//             case 'addUser':
//                 $objUsuarios->createUser($data);
//                 break;

//             case 'updateUser':
//                 $objUsuarios->updateUserJSON($data); // método nuevo que creamos abajo
//                 break;

//             case 'cambiarEstado':
//                 $objUsuarios->cambiarEstadoUsuarioJSON($data);
//                 break;


//             default:
//                 http_response_code(400);
//                 echo json_encode([
//                     "status" => "error",
//                     "message" => "Acción no válida"
//                 ]);
//                 break;
//         }
//     } else {
//         http_response_code(400);
//         echo json_encode([
//             "status" => "error",
//             "message" => "No se recibió una acción válida"
//         ]);
//     }
// }
