<?php
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/RolesConst.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;

class RolesController
{
    protected RolesModel $modeloRol;
    protected PermisosModel $permisosModel;

    public function __construct()
    {

        $this->modeloRol = new RolesModel();
        $this->permisosModel = new PermisosModel();
    }

    public function mostrarRoles()
    {
        return include_once __DIR__ . '../../views/rolesViews.php';
    }
    public function getRoles()
    {
        header(CONTENT_TYPE);
        $roles = $this->modeloRol->select()->prepareSql()->get();
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

        // si mando un id relacionado con la tabla roles_funciones me va a devolver un error de foraneas: - tengo que borrar los roles de las funciones y de ahi si borrar el rol. si envio un id de rol que no este asociado a ninguna funcion, ahi si no marca error.
        //Error: Cannot delete or update a parent row: a foreign key constraint fails (sigia.roles_funcionesCONSTRAINT fk_rol FOREIGN KEY (rlp_id_rl) REFERENCES roles (rl_id))
        $responseDeleteRol = $this->modeloRol->delete()->where()->prepareSql($dataDelete)->get();
        if ($responseDeleteRol) {
            Response::responseRequest(HttpStatus::OK, true, RL_MESSAGE_SUCCESS_DELETE, []);
        }
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
