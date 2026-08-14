<?php

use function PHPUnit\Framework\throwException;

require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/RolesConst.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;

class RolesController extends ConfigController implements CrudInterface
{
    protected RolesModel $modeloRol;
    protected RolesFuncionesModel $rfModel;
    protected FuncionesModel $fModel;
    protected ServicesRoles $sRoles;
    protected ServicesGestionModulos $sModulos;
    protected ServicesFunciones $sFunciones;
    protected PermisosModel $permisosModel;
    /**
     * Arreglo con el listado de los recursos asociados a las vistas en donde la clave es css o javascript e internamente tienen mas claves y valores, las claves mas internas son los nombres de las funciones del controlador.
     *
     * @var array
     */
    protected array $files = [
        'css' => [
            'rolesIndex' => ['RolesIndex.css'],
            'mostrarRoles' => ['Roles.css'],
            'mostrarFuncionesAssoc' => ['RolesFunciones.css']
        ],
        'js' => [
            'mostrarRoles' => ['Roles.js'],
            'rolesIndex' => [],
            'mostrarFuncionesAssoc' => ['RolesFunciones.js']
        ]
    ];

    public function __construct()
    {
        $this->modeloRol = new RolesModel();
        $this->permisosModel = new PermisosModel();
        $this->fModel = new FuncionesModel();
        $this->sRoles = new ServicesRoles();
        $this->rfModel = new RolesFuncionesModel();
        $this->sModulos = new ServicesGestionModulos();
        $this->sFunciones = new ServicesFunciones();
        $this->createRoutes();
    }
    public function createRoutes()
    {

        $this->routes = [
            // inicio
            'dashboard' => ['label' => 'inicio', 'url' => Router::createRoute('Dashboard', 'Dashboard', 'dashboard', false, 'dashboard')],
            'permisosIndexView' => [
                'label' => 'Seguridad del sistema',
                'url' => Router::createRoute('Permisos', 'Permisos', 'permisosIndexView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'dashboard'
            ],
            'mostrarRoles' => [
                'label' => 'Ver Roles',
                'url' => Router::createRoute('Roles', 'Roles', 'mostrarRoles', false, 'dashboard'),
                'parent' => 'permisosIndexView'
            ],
            'mostrarFuncionesAssoc' => [
                'label' => 'Funciones asociadas',
                'url' => Router::createRoute('Roles', 'RolesFunciones', 'mostrarFuncionesAssoc', false, 'dashboard'),
                'parent' => 'rolesIndex'
            ]
        ];
    }
    /**
     * Vista principal del modulo roles
     *
     * @return void
     */
    public function rolesIndex(): void
    {
        $path = BASE_URL . RL_ROUTES_ROLES_INDEX;
        Parent::renderView($path, __FUNCTION__);
    }
    /**
     * Vista que contiene el listado de los roles.
     *
     * @return void
     */
    public function mostrarRoles(): void
    {
        $path = BASE_URL . RL_ROUTES_MOSTRAR_ROLES;
        Parent::renderView($path, __FUNCTION__);
    }

    public function getData(): void
    {
        header(CONTENT_TYPE);
        $roles = $this->sRoles->getAllRoles();
        if (count($roles) > 0) {
            Response::responseRequest(HttpStatus::OK, true, 'registros', $roles);
        }
    }

    public function save(): void
    {
        header(CONTENT_TYPE);
        $data = UtilsFunctions::returnGetDecode();
        $data['rl_id'] = (int) $data['rl_id']; //transformarmos el id del rol en int.
        $updateSql['data'] = $data;
        $responseUpdate = $this->modeloRol->update($data)->where()->prepareSql($updateSql)->get();
        $codeResponse = $responseUpdate > 0 ? HttpStatus::OK : HttpStatus::NOT_FOUNT;
        $messageResponse = $responseUpdate ? MSG_REGISTRO_ACTUALIZAOD : MSG_ERROR_EJECUTAR_PROCESO;
        Response::responseRequest($codeResponse, true, $messageResponse, []);
    }

    public function statusRoles(): void
    {
        header(CONTENT_TYPE);
        $data = UtilsFunctions::returnGetDecode();
        $dataUpdate['data'] = $data;
        $responseChangeStatusRol = $this->modeloRol->update($data)->where()->prepareSql($dataUpdate)->get();
        $message = null;

        // valido dependiendo de la cantidad de filas afectadas.
        if ($responseChangeStatusRol > 0) {
            // dependiendo del estado enviado, personalizamos el mensaje enviado al cliente.
            if ($data['rl_status'] === 1) {
                $message = RL_MESSAGE_SUCCESS_ENABLED;
            } else {
                $message = RL_MESSAGE_SUCCESS_DISABLED;
            }
        }

        Response::responseRequest(HttpStatus::OK, true, $message, []);
    }

    public function store(): void
    {
        try {
            header(CONTENT_TYPE);
            $data = UtilsFunctions::returnGetDecode();
            $data['rl_status'] = 1;
            $dataInsert[CR_DATA] = $data;
            $responseAddRol = $this->modeloRol->insert($data)->prepareSql($dataInsert)->get();
            if (!$responseAddRol[CR_STATUS]) {
                $responseHandler = DatabaseHandler::validateResponse($responseAddRol);
                throw new Exception($responseHandler['message'], $responseHandler['codeResponse']);
            }
            Response::responseRequest(HttpStatus::CREATED, true, "Rol creado exitosamente", []);
        } catch (\Exception $th) {
            Response::responseRequest($th->getCode(), false, $th->getMessage(), []);
        }
    }

    public function delete(): void
    {
        header(CONTENT_TYPE);
        $data = UtilsFunctions::returnGetDecode();
        $dataDelete['data'] = $data;
        if ($data['rl_id'] === 1 || $data['rl_id'] === 2) {
            Response::responseRequest(HttpStatus::UNAUTHORIZED, false, RL_MESSAGE_ERROR_DELETE, []);
        }
        $responseDeleteRol = $this->modeloRol->delete()->where()->prepareSql($dataDelete)->get();
        // si el estatus es falso, validamos el codigo de error
        if (!$responseDeleteRol['status']) {

            $dataResponse = DatabaseHandler::validateResponse($responseDeleteRol);
            // ese error de codigo representa un error en las relaciones producto de que este registro esta asociado en otra tabla.
            $message = $responseDeleteRol['codeError'] === 1451 ? 'El rol tiene funciones asociadas, si desea eliminar el rol, primero elimine las funciones asociados en el el modulo "funciones roles"' : $dataResponse['message'];
            Response::responseRequest($dataResponse['codeResponse'], false, $message, []);
            return;
        }
        Response::responseRequest(HttpStatus::OK, true, RL_MESSAGE_SUCCESS_DELETE, []);
    }

    /**
     * Function para obtener los permisos que YA ESTAN ASOCIADOS AL ROL
     * @return void
     */
    public function getPermisosRolAsig(): void
    {

        /**
         * OBJETIVOS
         * 1- TRAER LAS FUNCIONES YA ASOCIADAS AL ROL, ESTO PARA MARCAR INMEDIATAMENTE APENAS EL USUARIO DE CLICK EN LA TUERCA
         * 2 - TRAER TODAS LAS FUNCIONES QUE PERTENECEN AL MODULO PARA ASI RENDERIZAR LAS FUNCIONES EN EL MODULO.
         */

        header(CONTENT_TYPE);
        $data['rl_id'] = isset($_GET['rl_id']) ? (int) $_GET['rl_id'] : null;

        // OBJETIVO #1
        $functionsAlreadyAssocRol = $this->sRoles->getSetRolesFunciones($data['rl_id']); //capturamos las funciones asociadas al rol desde el servicio, esto para cuando el usuario de click, las funciones ya establecidas esten seleccionadas.

        /**
         * OBJETIVO #2
         *
         * este paso esta basado en la funcion comentada llamada getRolesPermisos que esta en el modelo
         * PASO1 - TRAER EL LISTADO DE LOS MODULOS
         * PASO2 - TRAER EL LISTADO DE TODAS LAS FUNCIONES
         * PASO3 - UNIFICAR EN UN SOLO ARREGLO LAS FUNCIONES EN DONDE LA KEY DEL ARREGLO TIENE QUE SER EL NOMBRE DEL MODULO
         */


        $modulos = $this->sModulos->getAllModulos(); //PASO #1
        $allFunctions = $this->sFunciones->getAllFunctions(); //PASO #2

        // validar si el id del rol es diferente de super administrador, en caso que lo sea, borrar el modulo super administrador.
        $nombreRol = $this->sRoles->getNameRol($data['rl_id'])[0]['rl_nombre'];
        $nombreRol = str_replace(' ', '', $nombreRol);

        // validamos si el nombre del rol es diferente de super administrador para eliminar las funciones y los modulos y asi evitar envio de datos erroneos.
        $allModulos = [];
        if (strtoupper($nombreRol) != 'SUPERADMINISTRADOR') {
            foreach ($modulos as $key => $value) {
                if ($value['nombre_modulo'] === 'Generalcrud' || $value['nombre_modulo'] === 'Gestionmodulos') {
                    continue;
                }
                $allModulos[] = $value;
            }
        } else {
            $allModulos = array_merge($modulos, $allModulos);
        }

        //PASO #3
        $finalFunctionsModules = [];
        foreach ($allModulos as $value) {
            $finalFunctionsModules[$value['nombre_modulo']] = [];
        }

        foreach ($allFunctions as $key =>  $funcion) {
            foreach ($allModulos as $key2 => $value2) {
                if ($value2['id_m'] === $funcion['id_modulo']) {
                    $finalFunctionsModules[$value2['nombre_modulo']][] = [
                        'idFuncion'     => $funcion['id_funcion'],
                        'nmFuncion'     => $funcion['nombre_funcion'],
                        'idModulo'      => $funcion['id_modulo'],
                        'nmFuncionUser' => $funcion['nombre_funcion_user']
                    ];
                    break;
                }
            }
        }
        $dataResponse = [
            'allFunctions' => $finalFunctionsModules,
            'functionsAllreadyAssoc' => $functionsAlreadyAssocRol,
            'allModulos' => $allModulos
        ];

        Response::responseRequest(HttpStatus::OK, true, CR_REGISTROS, $dataResponse);
    }

    /**
     * Function para establecer los permisos asociados al rol.
     *
     * @return void
     */
    public function setPermisos(): void
    {
        try {
            header(CONTENT_TYPE);
            $data = UtilsFunctions::returnGetDecode();
            $rolId = $data['rolId'];
            $message = "Proceso ejecutado correctamente";
            $funcionesPorEliminar = $data['funcionesPorEliminar'];
            $funcionesPorAsociar = $data['funcionesPorAsociar'];

            /**
             * PASOS PARA EJECUTAR LA ASIGNACION DE PERMISOS AL ROL.
             *
             * 1 - Extraer todas las funciones ya asignadas dependiendo del rol para asi comparar con las que el usuario envia y las que ya estan registradas.
             * 2 - comparar las funciones ya registradas con las por asignar para asi juntar las que no estan repetidas, asi evitamos duplicidad.
             * 3 - Eliminar las funciones que se van a desasociar por el usuario
             * 4 - Guardar las nuevas funciones asociadas al rol.
             */

            //PASO1
            $allFunctionsAssoc = $this->sRoles->getSetRolesFunciones($data['rolId']);
            // Extramos solamente los ids de las funciones
            $allFunctionsAssoc = array_column($allFunctionsAssoc, 'idFuncion');
            sort($allFunctionsAssoc);
            sort($funcionesPorAsociar);

            // PASO2
            # Comparamos los ids ya asociados con los ids que nos envia el cliente
            // FILTRAMOS LAS FUNCIONES QUE VAMOS A INSERTAR Y QUE NO ESTEN EN LA BASE DE DATOS.
            $filterFinalFunctions = array_diff($funcionesPorAsociar, $allFunctionsAssoc);


            //  PROCESO FINAL.
            $this->rfModel->beginTransaction();
            // PROCESO DELETE.
            if (!empty($funcionesPorEliminar)) {
                $prepareFunctions = [];
                foreach ($funcionesPorEliminar as $key => $value) {
                    $prepareFunctions["rlp_id_funcion" . $key] = $value;
                }
                $dtaPDFunctions[CR_DATA] = $prepareFunctions;
                // adicionamos el id del rol
                $dtaPDFunctions[CR_DATA]['rlp_id_rl'] = $rolId;

                $resultDeleteRolesFunciones = $this->rfModel->delete()->where(['rlp_id_rl', '=', $rolId])->whereIn($prepareFunctions, 'rlp_id_funcion')->prepareSql($dtaPDFunctions)->get();

                if (!$resultDeleteRolesFunciones[CR_STATUS]) {
                    $dataResponse = DatabaseHandler::validateResponse($resultDeleteRolesFunciones);
                    Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
                    return;
                }
                $message = "Permisos eliminados del rol correctamente";
            }

            if (!empty($filterFinalFunctions)) {
                # preparamos las funciones para adicionar al rol.
                $functionsToAdd = [];
                foreach ($filterFinalFunctions as $key => $value) {
                    # valido si las funciones enviadas con el cliente, no hay alguna que no este en las funciones ya asociadas por el cliente.
                    if (!in_array($value, $allFunctionsAssoc)) {
                        $functionsToAdd[] = [
                            'rlp_id_rl'  => $rolId,
                            'rlp_id_funcion'  => $value,
                            '__index' => $key,
                        ];
                    }
                }

                $functionsToAddPrepare = [];
                // ANTES DE ESO COMPARAMOS LAS FUNCIONES POR
                foreach ($functionsToAdd as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if ($key2 != '__index') {
                            $functionsToAddPrepare[CR_DATA]['rlp_id_funcion' . "{$value['__index']}"] = $value2;
                            $functionsToAddPrepare[CR_DATA]['rlp_id_rl' . "{$value['__index']}"] = $rolId;
                        }
                    }
                }
                $resultSetPermisos = $this->rfModel->insert($functionsToAdd)->prepareSql($functionsToAddPrepare)->get();

                if (!$resultSetPermisos[CR_STATUS]) {
                    $dataResponse = DatabaseHandler::validateResponse($resultSetPermisos);
                    Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
                    return;
                }
                $message = "Permisos asociados correctamente.";
            }

            $this->rfModel->commit();
            $result = $this->permisosModel->renderMenu($rolId);
            $_SESSION['renderMenu'] = $result[CR_DATA];
            Response::responseRequest(HttpStatus::OK, true, $message, []);
        } catch (\PDOException $th) {
            $this->rfModel->rollback();
            Response::responseRequest(HttpStatus::BAD_REQUEST, false, $th, []);
        }
    }
}
