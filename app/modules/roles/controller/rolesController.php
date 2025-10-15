<?php
include_once __DIR__ . '/../model/rolesModel.php';
include_once __DIR__ . '/../../Permisos/Model/PermisosModel.php';
include_once __DIR__ . '/../../../config/conn.php';
require_once __DIR__ . '/../../../helpers/response.php';
require_once __DIR__ . '/../../../helpers/session.php';
/**
 * En este documento está adjunto la variable de sessión, getUrl y response que me permite mandar el json al front como respuesta.
 */
require_once __DIR__ . "/../../../helpers/validatePermisos.php";
class RolesController
{
    private RolModelo $modeloRol;
    private PermisosModel $permisosModel;
    private Conection $conn;

    public function __construct()
    {
        // Debo de cambiar esto, si voy a usar varios modales en un controlador, implementar una interfaz.
        $this->conn = new Conection();
        $this->modeloRol = new RolModelo();
        $this->permisosModel = new PermisosModel();
    }

    public function mostrarRoles()
    {
        return include_once __DIR__ . '../../views/rolesViews.php';
    }
    public function registrarRol(array $data = [])
    {

        validatePermisos('Roles', 'registrarRol');
        $objPermisosModel = new PermisosModel();

        $rol_nombre = $data['rol_nombre'];
        $rol_descripcion = $data['rol_descripcion'];
        $exito = $this->modeloRol->insertarRoles($rol_nombre, $rol_descripcion);
        if (!$exito['status']) {
            fail('Error al registrar rol', $exito);
        }
        success('Proceso Ejecutado con exito', $exito);
        // Cuando se ejecute el proceso de asignacion de permisos, debo si o si llamar nuevamente a la función de render menu
        // $objPermisosModel->renderMenu($_SESSION['usuario']['rl_id']);
    }
    public function editarRol(array $data = [])
    {

        validatePermisos('Roles', 'editarRol');

        $rol_id = (int) $data['rol_id'];
        $rol_nombre = $data['modal_rol_nombre'];
        $rol_descripcion = $data['rol_descripcion'];
        $responseRol = $this->modeloRol->actualizarRol($rol_id, $rol_nombre, $rol_descripcion);


        if (!$responseRol['status']) {
            fail('Error al actualizar el recurso', $responseRol);
        }
        success('Recurso actualizado', $responseRol);
    }
    public function statusRoles(array $data = [])
    {
        validatePermisos('Roles', 'statusRoles');
        $idRol = (int) $data['idRol'];
        $status = (int) $data['statusRol'] == 1 ? 0 : 1;

        $exito = $this->modeloRol->eliminarRol($idRol, $status);
        if (!$exito['status']) {
            fail('error al actualizar el estado del elemento');
        }
        success('recurso actualizado', $exito);
    }
    public function getRoles()
    {
        
        $roles = $this->modeloRol->obtenerRoles();
        
        success('roles', $roles);
    }

    public function assingRoles()
    {
        validatePermisos('Roles', 'assingRoles');
        $dataResult = $this->modeloRol->getRolesPermisos();

        if (!$dataResult['status']) {
            fail('error al procesar la data', $dataResult);
        }
        success('roles y permisos encontrados', $dataResult);
    }

    public function getPermisosRolAsig(int $rolId = 0)
    {
        $rolesResult = $this->modeloRol->getPermisosFuncion($rolId);

        if (!$rolesResult['status']) {
            fail('No hay permisos asociadas a este rol', $rolesResult);
        }
        success('roles asociados', $rolesResult);
    }
    
    public function setPermisos(array $data = []){

        $rolId = $_SESSION['usuario']['rol_id'];

        $responsePermisos = $this->modeloRol->assocPermisos($data);

        if (!$responsePermisos['status']) {
           fail('error al ejecutar el proceso', $responsePermisos);
        }

        $result = $this->permisosModel->renderMenu($rolId);
        $_SESSION['renderMenu'] = $result['data'];
        success('Permisos Asociados correctamente', $responsePermisos);

    }
}

$objRolesController = new RolesController();

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $case = $_GET['action'] ?? '';
        $pages = $_GET['pages'] ?? 1;

        $codigo = $_GET['codigo'] ?? 0;
        $codigo = (int) $codigo;
        $dataIdRol = $_GET['idRol'] ?? null;

        switch ($case) {
            case 'getRoles':
                if (method_exists($objRolesController, 'getRoles')) {
                    $objRolesController->getRoles();
                }
                break;

            // Obtengo todos las funciones y modulos a las cuales pertenecen
            case 'getRolesPermisos':
                if (method_exists($objRolesController, 'assingRoles')) {
                    $objRolesController->assingRoles();
                }

            case 'getPermisosRolAsig':
                if (method_exists($objRolesController, 'getPermisosRolAsig')) {
                    $objRolesController->getPermisosRolAsig($dataIdRol);
                }

                break;

            default:

                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        $action = $data['action'];
        unset($data['action']);

        switch ($action) {
            case 'updateRol':
                if (method_exists($objRolesController, 'editarRol')) {
                    $objRolesController->editarRol($data);
                }

                break;

            case 'statusRol':
                if (method_exists($objRolesController, 'statusRoles')) {
                    $objRolesController->statusRoles($data);
                }
                break;

            default:

                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input = file_get_contents("php://input");
        $dataAdd = json_decode($input, true);
        $action = $dataAdd['action'];
        unset($dataAdd['action']);
        $newData = $dataAdd;
        switch ($action) {
            case 'addRol':
                if (method_exists($objRolesController, 'registrarRol')) {
                    $objRolesController->registrarRol($newData);
                }
                break;

            case 'setPermisos':
                if (method_exists($objRolesController, 'setPermisos')) {
                    $objRolesController->setPermisos($newData);
                }
                break;

            default:
                # code...
                break;
        }
    }
}
