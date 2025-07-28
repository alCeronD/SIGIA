<?php
include_once __DIR__ . '/../model/rolesModel.php';
include_once __DIR__ . '/../../../config/conn.php';
require_once __DIR__ . '/../../../helpers/response.php';

class RolesController {
    private $modeloRol;
    private $conn;

    public function __construct() {
        $this->conn = new Conection();
        $this->modeloRol = new RolModelo();
    }

    public function mostrarRoles() {
        return include_once __DIR__ . '../../views/rolesViews.php';
    }
    public function registrarRol(array $data = [])
    {

        $rol_nombre = $data['rol_nombre'];
        $rol_descripcion = $data['rol_descripcion'];
        $exito = $this->modeloRol->insertarRoles($rol_nombre, $rol_descripcion);
        if (!$exito['status']) {
            fail('Error al registrar rol', $exito);
        }
        success('Proceso Ejecutado con exito', $exito);

        // $this->mostrarRoles();
        // echo "<script>alert('Rol registrado exitosamente'); window.location.href = '" . getUrl('roles', 'roles', 'mostrarRoles', false, 'dashboard') . "';</script>";
        // return;
    
    }
    public function editarRol(array $data = [])
    {
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

        $idRol = (int) $data['idRol'];
        $status = (int) $data['statusRol'] == 1 ? 0 : 1;

        $exito = $this->modeloRol->eliminarRol($idRol, $status);
        if (!$exito['status']) {
            fail('error al actualizar el estado del elemento');
        }
        success('recurso actualizado', $exito);
        
    }
    public function getRoles(){
        $roles = $this->modeloRol->obtenerRoles();

        success('roles', $roles);

    }

}

$objRolesController = new RolesController();



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $case = $_GET['action'] ?? '';
        $pages = $_GET['pages'] ?? 1;

        $codigo = $_GET['codigo'] ?? 0;
        $codigo = (int) $codigo;

        switch ($case) {
            case 'getRoles':
                if (method_exists($objRolesController, 'getRoles')) {
                    $objRolesController->getRoles();
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
            default:
                # code...
                break;
        }
    }
}
?>
