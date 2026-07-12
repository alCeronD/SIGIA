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
    protected ServicesTipoDocumento $stp;
    protected ServicesRoles $sRoles;
    // protected ConfigModulesModel $configModules;
    protected UsuariosModel $usuariosModel;
    protected UsuariosRolesModel $usuariosRModel;
    protected UsuariosServices $sUser;
    protected array $files = [
        "css" => [
            'usuariosIndex' => ['UsuariosIndex.css'],
            'createUserView' => ['CreateUser.css'],
            'auditoriaUserView' => ['AuditoriasUsuarios.css'],
            'usuariosView' => ['UsuariosView.css'],
            'actualizarDatosView' => ['updatePersonalData.css']
        ],
        "js" => [
            'usuariosIndex' => [],
            'createUserView' => ['CreateUser.js'],
            'auditoriaUserView' => ['AuditoriasUsuarios.js'],
            'usuariosView' => ['UsuariosView.js'],
            'actualizarDatosView' => ['updatePersonalData.js']
        ]
    ];
    public function __construct()
    {
        $this->rolesModel = new RolesModel(); //esto creo que nos puede servir para extraer el listado de los roles, en este caso, no hacemos composicion al modelo sino al servicio.
        // $this->configModules = new ConfigModulesModel();
        $this->usuariosModel = new UsuariosModel();
        $this->usuariosRModel = new UsuariosRolesModel();
        $this->stp = new ServicesTipoDocumento();
        $this->sRoles = new ServicesRoles();
        $this->sUser = new UsuariosServices();
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
            ],
            'auditoriaUserView' => [
                'label' => 'Auditorias de usuario',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'auditoriaUserView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'usuariosIndex'
            ],
            'usuariosView' => [
                'label' => 'Usuarios registrados',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'usuariosView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'usuariosIndex'
            ],
            'actualizarDatosView' => [
                'label' => 'Actualizar datos personales',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'updatePersonalDataView', false, CR_DASHBOARD_LOWER_CASE),
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
        $this->stp->getAllTps();
        $path = BASE_URL . US_ROUTE_USUARIOS_CREATE_VIEW;
        Parent::renderView($path, __FUNCTION__);
    }
    /**
     * Vista para ver las acciones que el usuario ha realizado.
     *
     * @return void
     */
    public function auditoriaUserView()
    {
        $path = BASE_URL . US_ROUTE_AUDITORIAS_VIEW;
        Parent::renderView($path, __FUNCTION__);
    }

    public function usuariosView()
    {
        $path = BASE_URL . US_ROUTE_USUARIOS_LIST_VIEW;
        Parent::renderView($path, __FUNCTION__);
    }

    /**
     * Vista para actualizar los datos personales del usuario logueado.
     *
     * @return void
     */
    public function actualizarDatosView()
    {
        // validamos si accedemos a esta function mediante una peticion http o en su defecto como renderizado puro.
        if (UtilsFunctions::ajaxGeneral()) {
            header(CONTENT_TYPE);
            $idUsuario = $_SESSION['usuario']['id'];
            $documento = $_SESSION['usuario']['documento'];
            $dataGetUsu[CR_DATA] = [
                'usu_id' => $idUsuario
            ];
            // coloco 0 porque me devuelve en forma de arreglo asociativo.
            $dataResult = $this->sUser->getUserByDocum($documento)[0];


            if (empty($dataResult)) {
                Response::responseRequest(HttpStatus::INTERNAL_SERVER_ERROR, false, MSG_ERROR_EJECUTAR_PROCESO, []);
            }
            Response::responseRequest(HttpStatus::OK, true, US_MESSAGE_DATA_USER, $dataResult);
        }
        // renderizado de la vista con backend puro.
        $path = BASE_URL . US_ROUTE_USUARIO_UPDATE;
        Parent::renderView($path, __FUNCTION__);
    }

    /**
     * Function para obtener los tipos de documento requeridos en la vista crearUserView.Los datos del tipo de documento al javascript, lo pedimos desde el servicio
     *
     * @return void
     */
    public function getDataSelects()
    {
        header(CONTENT_TYPE);
        $dataTipoDocumento = $this->stp->getAllTps(false);
        $dataRoles = $this->sRoles->getAllRoles();

        $data = [
            'tipoDocumento' => $dataTipoDocumento,
            'roles' => $dataRoles
        ];

        Response::responseRequest(HttpStatus::OK, true, CR_REGISTROS, $data);
    }

    public function store()
    {
        // debo de crear el usuario y el rol en la tabla usuarios_roles
        header(CONTENT_TYPE);
        $data = UtilsFunctions::returnGetDecode();
        // eliminar el rol
        $rolData = $data['usr_rl_id'];
        unset($data['usr_rl_id']);
        $data['usu_id_estado'] = 1; #Activo
        $data['usu_password'] = password_hash($data['usu_password'], PASSWORD_DEFAULT); #hash a la contraseña

        try {
            $this->usuariosModel->beginTransaction();
            $dataInsertUsu[CR_DATA] = $data; #Datos para la tabla usuarios;

            // verificar si el usuario con el nro de documento ya esta registrado
            $verifyExist = $this->sUser->getUserByDocum($data['usu_docum']);
            if (count($verifyExist) > 0) {
                throw new PDOException("El usuario identificado con el nro {$data['usu_docum']} ya esta registrado en el sistema.", 1);
            }

            $resultStoreUsuarios = $this->usuariosModel->insert($data)->prepareSql($dataInsertUsu)->get();

            if (!$resultStoreUsuarios['status']) {
                $this->usuariosModel->rollback();
                $dataResponse = DatabaseHandler::validateResponse($resultStoreUsuarios);
                Response::responseRequest($dataResponse[CR_CODE_RESPONSE], false, $dataResponse[CR_MESSAGE], []);
                return;
            }
            $lastId = (int) $resultStoreUsuarios['lastId'];
            $dataInsertUsuRoles[CR_DATA] = [
                'usr_usu_id' => (int) $lastId,
                'usr_rl_id' => (int) $rolData
            ]; #Datos para la tabla usuarios_roles;
            $resultStoreUsuariosRoles = $this->usuariosRModel->insert($dataInsertUsuRoles[CR_DATA])->prepareSql($dataInsertUsuRoles)->get();
            if (!$resultStoreUsuariosRoles['status']) {
                $this->usuariosModel->rollback();
                $dataResponse = DatabaseHandler::validateResponse($resultStoreUsuarios);
                Response::responseRequest($dataResponse[CR_CODE_RESPONSE], false, $dataResponse[CR_MESSAGE], []);
                return;
            }
            $this->usuariosModel->commit();

            Response::responseRequest(HttpStatus::CREATED, true, US_MESSAGE_CREATE_USER_STORE, []);
        } catch (\PDOException $th) {
            $this->usuariosModel->rollback();
            Response::responseRequest(HttpStatus::BAD_REQUEST, false, $th->getMessage(), []);
        }
    }

    public function save() {}

    public function savePersonalData()
    {
        // extraer la informacion del usuario basada en el documento
        header(CONTENT_TYPE);
        $data = UtilsFunctions::returnGetDecode();
        var_dump($data);
        die();



        // FUNCTION PARA ACTUALIZAR LOS DATOS DEL USUARIO REGISTRADO EN LA BASE DE DATOS
        // public function updatePersonalData()
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

    // VISTA PARA ACTUALIZAR LOS DATOS PERSONALES DEL USUARIO.
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
