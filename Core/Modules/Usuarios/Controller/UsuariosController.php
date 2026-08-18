<?php

require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/UsuariosConst.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;
// las funciones protected son funciones logicas del sistema que no deben de ser registradas en la base de datos.
class UsuariosController extends ConfigController implements CrudInterface
{

    protected ServicesTipoDocumento $stp;
    protected ServicesRoles $sRoles;
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
            'actualizarDatosView' => ['UpdatePersonalData.js', 'Functions-updatePersonalData.js'],
            'detailUser' => ['detailUser.js']
        ]
    ];
    public function __construct()
    {
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
            'usuariosIndexView' => [
                'label' => 'Usuarios',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'usuariosIndexView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'dashboard'
            ],
            'createUserView' => [
                'label' => 'Crear usuario',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'createUserView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'usuariosIndexView'
            ],
            'auditoriaUserView' => [
                'label' => 'Auditorias de usuario',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'auditoriaUserView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'usuariosIndexView'
            ],
            'usuariosView' => [
                'label' => 'Usuarios registrados',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'usuariosView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'usuariosIndexView'
            ],
            'actualizarDatosView' => [
                'label' => 'Actualizar datos personales',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'updatePersonalDataView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'usuariosIndexView'
            ],
            'detailUser' => [
                'label' => 'Detalle del usuario',
                'url' => Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'detailUser', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'usuariosView'
            ]
        ];
    }
    /**
     * Vista principal del modulo de usuarios.
     *
     * @return void
     */
    public function usuariosIndexView()
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
        // accedemos a la function mediante peticion para solicitar los roles y tipos de documento
        if (UtilsFunctions::ajaxGeneral()) {
            header(CONTENT_TYPE);
            $dataTipoDocumento = $this->stp->getAllTps(false);
            $dataRoles = $this->sRoles->getAllRoles();

            $data = [
                'tipoDocumento' => $dataTipoDocumento,
                'roles' => $dataRoles
            ];

            Response::responseRequest(HttpStatus::OK, true, CR_REGISTROS, $data);
        }
        // renderizamos la vista
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
     * Function para ver el detalle completo del usuario
     *
     * @return void
     */
    public function detailUserView()
    {
        $path = BASE_URL . US_ROUTE_DETAIL_USER;
        Parent::renderView($path, __FUNCTION__);
    }

    /**
     * Function para traer los usuarios.
     *
     * @return void
     */
    public function getData()
    {
        header(CONTENT_TYPE);
        // extraer las claves de acceso para la consulta de los usuarios usando filtros.
        $filter = empty($_GET['keyFilter']) ? '' : $_GET['keyFilter'];
        $valueFilter = empty($_GET['valueFilter']) ? '' : $_GET['valueFilter'];
        $page = (isset($_GET[CR_PAGINA])) ? (int) $_GET[CR_PAGINA] : 1;
        $limit = (isset($_GET[CR_WORD_LIMIT])) ? (int) $_GET[CR_WORD_LIMIT] : LIMIT;

        $filter = match ($filter ?? '') {
            'nombre'    => 'usu_nombres',
            'documento' => 'usu_docum',
            ''          => '',
            default     => 'usu_id_estado',
        };

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

    /**
     * Vista para actualizar los datos personales del usuario logueado.
     *
     * @return void
     */
    public function actualizarPersonalData()
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

    // function para actualizar informacion del usuario
    public function save()
    {
        try {
            header(CONTENT_TYPE);
            $data = UtilsFunctions::returnGetDecode();

            $usu_observacion = $data['usu_observacion'] ?? '';
            if (strlen($usu_observacion) > 100) {
                throw new Exception('Caracteres máximos permitidos en el campo de observacion: 100', HttpStatus::UNPROCESSABLE_ENTITY);
            }

            $dataUpdate[CR_DATA] = $data;
            // actualizar datos.
            $responseUpdate = $this->usuariosModel->update($data)->where()->prepareSql($dataUpdate)->get();

            if (!$responseUpdate['status']) {
                $messageResponse = DatabaseHandler::validateResponse($responseUpdate);
                Response::responseRequest($messageResponse['codeResponse'], false, $messageResponse['message'], []);
                return;
            }

            Response::responseRequest(HttpStatus::OK, true, US_MESSAGE_DATA_USER . US_MESSAGE_UPDATE_PERSONAL_DATA, []);
        } catch (\Throwable $e) {
            Response::responseRequest($e->getCode(), false, $e->getMessage(), []);
        }
    }

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

    public function changeStatusUser()
    {

        try {
            header(CONTENT_TYPE);
            $data = UtilsFunctions::returnGetDecode();

            if (empty($data[US_VAR_USU_ID_ESTADO]) || empty($data[US_VAR_USU_ID])) throw new Exception(US_MESSAGE_ERROR_ENTITY, HttpStatus::UNPROCESSABLE_ENTITY);

            $data[US_VAR_USU_ID_ESTADO] = (int) $data[US_VAR_USU_ID_ESTADO];
            $data[US_VAR_USU_ID] = (int) $data[US_VAR_USU_ID];
            $dataChangeStatus[CR_DATA] = $data;


            $finalMessage = match ($data[US_VAR_USU_ID_ESTADO] ?? 1) {
                1 => US_MESSAGE_USER_ENABLED,
                2 => US_MESSAGE_USER_DISABLED
            };

            $changeStatusResponse = $this->usuariosModel->update($data)->where()->prepareSql($dataChangeStatus)->get();

            if (!$changeStatusResponse[CR_STATUS]) {
                $responseHanlder = DatabaseHandler::validateResponse($changeStatusResponse[CR_RESPONSE]);
                throw new Exception($responseHanlder[CR_MESSAGE], $responseHanlder['codeResponse']);
            }


            Response::responseRequest(HttpStatus::OK, true, $finalMessage, []);
        } catch (\Exception $e) {
            Response::responseRequest($e->getCode(), false, $e->getMessage(), []);
        }
    }

    /**
     * Funcionalidad para eliminar el usuario, se deja vacia por la logica actual y los requerimientos e historia de usuario definidos previamente.
     *
     * @return void
     */
    public function delete() {}
}
