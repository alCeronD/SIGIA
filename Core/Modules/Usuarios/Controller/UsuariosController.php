<?php

use ZipStream\Test\Util;

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
    protected ServicesUsuarios $sUser;
    protected array $files = [
        "css" => [
            'usuariosIndex' => ['UsuariosIndex.css'],
            'createUserView' => ['CreateUser.css'],
            'auditoriaUserView' => ['AuditoriasUsuarios.css'],
            'usuariosView' => ['UsuariosView.css'],
            'actualizarDatosView' => ['UpdatePersonalData.css']
        ],
        "js" => [
            'usuariosIndex' => [],
            'createUserView' => ['CreateUser.js'],
            'auditoriaUserView' => ['AuditoriasUsuarios.js'],
            'usuariosView' => ['UsuariosView.js'],
            'actualizarDatosView' => ['UpdatePersonalData.js', 'Functions-updatePersonalData.js']
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
        $this->sUser = new ServicesUsuarios();
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

        $updateData[CR_DATA] = $data;
        // Update.
        $resulUpdatePersonalData = $this->usuariosModel->update($data)->where()->prepareSql($updateData)->get();

        if (!$resulUpdatePersonalData) {
            $dataResponse = DatabaseHandler::validateResponse($resulUpdatePersonalData);
            Response::responseRequest($dataResponse[CR_CODE_RESPONSE], false, $dataResponse[CR_MESSAGE], []);
        }

        Response::responseRequest(HttpStatus::OK, true, US_MESSAGE_DATA_USER . US_MESSAGE_UPDATE_PERSONAL_DATA, []);
    }
    public function consultUser()
    {
        header(CONTENT_TYPE);
        // extraer las claves de acceso para la consulta de los usuarios usando filtros.
        $filter = empty($_GET['keyFilter']) ? '' : $_GET['keyFilter'];
        $valueFilter = empty($_GET['valueFilter']) ? '' : $_GET['valueFilter'];
        $page = (isset($_GET[CR_PAGINA])) ? (int) $_GET[CR_PAGINA] : 1;
        $limit = (isset($_GET[CR_WORD_LIMIT])) ? (int) $_GET[CR_WORD_LIMIT] : LIMIT;

        if (empty($filter)) {
            $filter = "";
        } else if ($filter === 'nombre') {
            $filter = 'usu_nombres';
        } else if ($filter === 'documento') {
            $filter = 'usu_docum';
        } else {
            $filter = 'usu_id_estado';
        }

        if ($filter === 'usu_id_estado') {
            $valueFilter = $valueFilter === 'activo' ? 1 : 2;
        }

        if ($filter === 'usu_docum') $valueFilter = (int) $valueFilter;

        // creamos el arreglo con los valores o en su defecto vacio para ejecutar el count para obtener su paginacion.
        if (!empty($filter) && !empty($valueFilter)) {
            $dataCountSql[CR_DATA] = [
                $filter => "%$valueFilter%"
            ];
        } else {
            $dataCountSql[CR_DATA] = [];
        }

        // accedemos al count en el servicio
        $countUsers = $this->sUser->getCount($filter, $valueFilter)->prepareSql($dataCountSql)->get();

        $paginate = UtilsFunctions::executePaginate($countUsers['rowCounts'], $limit, $page);


        $dataSql[CR_DATA] = [
            CR_WORD_LIMIT           => $limit,
            CR_OFFSET => (int) $paginate[CR_OFFSET],
        ];

        if (!empty($filter) && !empty($valueFilter)) {
            $dataSql[CR_DATA][$filter] = "%$valueFilter%";
        }

        $queryAllUsers = $this->sUser->getAllUsers($filter, $valueFilter)->prepareSql($dataSql)->get();

        if (count($paginate) > 0) {
            Response::responseRequest(HttpStatus::OK, true, "Registros", [
                CR_TOTAL_REGISTROS => $countUsers['rowCounts'],
                CR_PAGINA_ACTUAL => ($page > $paginate[CR_TOTAL_PAGINAS]) ? $paginate[CR_TOTAL_PAGINAS] : $page, //Aca devolvemos la pagina, pero cuando se borra el ultimo registro de una pagina estamos devolviendo la pagina que recibimos desde la peticion, cuando hacemos la paginacion, si la pagina ES MAYOR A LA CANTIDAD DE PAGINAS TOTALES, NO DEVOLVEMOS LA PAGINA RECIBIDA, SINO LA ULTIMA PAGINA. esto para poder renderizar de forma correcta la informacion.
                CR_CANTIDAD_PAGINAS => $paginate[CR_TOTAL_PAGINAS],
                CR_DATA => $queryAllUsers
            ]);
        }
    }
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
