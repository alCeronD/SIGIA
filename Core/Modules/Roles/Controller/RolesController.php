<?php
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/RolesConst.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;

class RolesController
{
    protected RolesModel $modeloRol;
    protected PermisosModel $permisosModel;
    protected ServicesRoles $sRoles;
    protected $message;
    protected $codeResponse;

    public function __construct()
    {

        $this->modeloRol = new RolesModel();
        $this->permisosModel = new PermisosModel();
        $this->sRoles = new ServicesRoles();
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
        $message = "";
        $codigo = null;
        // si el estatus es falso, validamos el codigo de error
        if (!$responseDeleteRol['status']) {
            $codigoError = (int) $responseDeleteRol['codeError'];
            // codigo de error de constraint.
            if ($codigoError === 23000) {
                $message = RL_MESSAGE_ERROR_ENTITY_DATA;
                $codigo = HttpStatus::UNPROCESSABLE_ENTITY;
            }
        } else {
            $message = RL_MESSAGE_SUCCESS_DELETE;
            $codigo = HttpStatus::OK;
        }
        Response::responseRequest($codigo, true, $message, []);
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

    // public function getPermisosRolAsig(int $rolId = 0)
    // {
    //     $rolesResult = $this->modeloRol->getPermisosFuncion($rolId);

    //     if (!$rolesResult['status']) {
    //         Response::fail('No hay permisos asociadas a este rol', $rolesResult);
    //     }
    //     Response::success('roles asociados', $rolesResult);
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
