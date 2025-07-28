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
        // $roles = $this->modeloRol->obtenerRoles();
        return include_once __DIR__ . '../../views/rolesViews.php';
    }
    
    
    public function registrarRol(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rol_nombre = $_POST['rol_nombre'];
            $rol_descripcion = $_POST['rol_descripcion'];
            $exito = $this->modeloRol->insertarRoles($rol_nombre,$rol_descripcion);

            if ($exito) {

                $this->mostrarRoles();
                echo "<script>alert('Rol registrado exitosamente'); window.location.href = '" . getUrl('roles','roles','mostrarRoles',false,'dashboard') . "';</script>";
                return;

            } else {
                echo "<div class='alert alert-danger text-center'>Error al registrar el Rol.</div>";
            }
        } else {
            return include __DIR__ .  './../views/rolesRegistrar.php';
        }
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
    public function eliminarRol() {
        //dd($_GET);
        if (isset($_GET['rl_id'])) {
            $rl_id = $_GET['rl_id'];
            $status = ($_GET['rl_status'] == 0) ? 1 : 0;
            $exito = $this->modeloRol->eliminarRol($rl_id,$status);
            if ($exito) {

                if ($status == 0) {
                    $textValue = "habilitado";
                }else{
                    $textValue = "inhabilitado";
                }

                $this->mostrarRoles();
                echo "<script>alert('Rol $textValue exitosamente'); window.location.href = '" . getUrl('roles','roles','mostrarRoles',false,'dashboard') . "';</script>";
                return;
            } else {
                echo "<script>alert('Error al inhabilitar el rol.');</script>";
                $this->mostrarRoles();
                return;

            }
        } else {
            echo "<div class='alert alert-warning text-center'>ID de rol no especificado.</div>";
        }

        exit();

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
            
            default:

                break;
        }
    }
}
?>
