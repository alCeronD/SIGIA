<?php
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/RolesConst.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;

class RolesController
{
    protected RolesModel $modeloRol;
    protected PermisosModel $permisosModel;
    protected FuncionesModel $fModel;
    protected ServicesRoles $sRoles;
    protected ServicesModulos $sModulos;
    protected ServicesFunciones $sFunciones;


    public function __construct()
    {

        $this->modeloRol = new RolesModel();
        $this->permisosModel = new PermisosModel();
        $this->fModel = new FuncionesModel();
        $this->sRoles = new ServicesRoles();
        $this->sModulos = new ServicesModulos();
        $this->sFunciones = new ServicesFunciones();
    }
    /**
     * Vista principal del modulo roles
     *
     * @return void
     */
    public function rolesIndex()
    {
        return include_once __DIR__ . '../../views/rolesIndex.php';
    }

    /**
     * Vista que contiene el listado de los roles.
     *
     * @return void
     */
    public function mostrarRoles()
    {
        return include_once __DIR__ . '../../views/rolesViews.php';
    }

    public function getRoles()
    {
        header(CONTENT_TYPE);
        $roles = $this->sRoles->getAllRoles();
        if (count($roles) > 0) {
            Response::responseRequest(HttpStatus::OK, true, 'registros', $roles);
        }
    }

    public function editarRol()
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

    public function statusRoles()
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

    public function registrarRol(array $data = [])
    {
        header(CONTENT_TYPE);
        $data = UtilsFunctions::returnGetDecode();
        $data['rl_status'] = 1;
        $dataInsert['data'] = $data;
        $responseAddRol = $this->modeloRol->insert($data)->prepareSql($dataInsert)->get();
        if ($responseAddRol) {
            Response::responseRequest(HttpStatus::CREATED, true, RL_MESSAGE_CREATED_ROL, []);
        }
    }

    public function deleteRol()
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
    public function getPermisosRolAsig()
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


        $allModulos = $this->sModulos->getAllModulos(); //PASO #1
        $allFunctions = $this->sFunciones->getAllFunctions(); //PASO #2


        //PASO #3
        $finalFunctionsModules = [];
        foreach ($allModulos as $value) {
            $finalFunctionsModules[$value['nombre_modulo']] = [];
        }

        foreach ($allFunctions as $key =>  $funcion) {
            // var_dump($value['id_modulo']);;
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

    // public function assingRoles()
    // {
    //     validatePermisos('Roles', 'assingRoles');
    //     $dataResult = $this->modeloRol->getRolesPermisos();

    //     if (!$dataResult['status']) {
    //         Response::fail('error al procesar la data', $dataResult);
    //     }
    //     Response::success('roles y permisos encontrados', $dataResult);
    // }



    /**
     * Function para establecer los permisos asociados al rol.
     *
     * @param array $data
     * @return void
     */
    // public function setPermisos(array $data = [])
    // {

    //     $rolId = $_SESSION['usuario']['rol_id'];

    //     $responsePermisos = $this->modeloRol->assocPermisos($data);

    //     if (!$responsePermisos['status']) {
    //         Response::fail('error al ejecutar el proceso', $responsePermisos);
    //     }

    //     $result = $this->permisosModel->renderMenu($rolId);
    //     var_dump($result);
    //     $_SESSION['renderMenu'] = $result['data'];
    //     Response::success('Permisos Asociados correctamente', $responsePermisos);
    // }
}
