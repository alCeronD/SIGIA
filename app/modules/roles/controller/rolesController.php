<?php
<<<<<<< HEAD
// require_once './app/modules/roles/model/rolesModel.php';
// require_once __DIR__ . './app/modules/roles/model/rolesModel.php';

include_once __DIR__ . '/../model/rolesModel.php';
class rolesController {
=======
require_once './app/modules/roles/model/rolesModel.php';

class ControllerRol {
>>>>>>> 751a1fa (edward_commit)
    private $modeloRol;

    public function __construct($conexion) {
        $this->modeloRol = new RolModelo($conexion);
    }

    public function mostrarRoles() {
        $roles = $this->modeloRol->obtenerRoles();
<<<<<<< HEAD
        return include __DIR__ . './../views/rolesViews.php';
    }
    
    public function editarRolesView(){
        return include __DIR__ . './../views/rolesEditar.php';
    }
    
=======
        include './app/modules/roles/views/rolesViews.php';
    }

>>>>>>> 751a1fa (edward_commit)
    public function registrarRoles(){
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $rol_nombre = $_POST['rol_nombre'];
        $exito = $this->modeloRol->insertarRoles($rol_nombre);

        if ($exito) {
            echo "<div class='alert alert-success text-center'>Rol registrado correctamente.</div>";
<<<<<<< HEAD
            // header("Location: index.php?action=rolesListar");
            header("Location: dashboard.php");
=======
            header("Location: index.php?action=rolesListar");
>>>>>>> 751a1fa (edward_commit)
            exit();
        } else {
            echo "<div class='alert alert-danger text-center'>Error al registrar el Rol.</div>";
        }
    } else {
<<<<<<< HEAD
        return include __DIR__ .  './../views/rolesRegistrar.php';
=======
        include './app/modules/roles/views/rolesRegistrar.php';
>>>>>>> 751a1fa (edward_commit)
    }
}

public function editarRol() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $rol_id = $_POST['rol_id'];
        $rol_nombre = $_POST['rol_nombre'];
        $exito = $this->modeloRol->actualizarRol($rol_id, $rol_nombre);

        if ($exito) {
            echo "<div class='alert alert-success text-center'>Rol actualizado correctamente.</div>";
            header("Location: index.php?action=rolesListar");
            exit();
        } else {
            echo "<div class='alert alert-danger text-center'>Error al actualizar el rol.</div>";
        }
    } else {
        // Mostrar formulario con datos actuales del rol
        $rol_id = $_GET['id'];
        $roles = $this->modeloRol->obtenerRoles();
        $rol_actual = null;
        foreach ($roles as $rol) {
            if ($rol['rl_id'] == $rol_id) {
                $rol_actual = $rol;
                break;
            }
        }
        if ($rol_actual) {
<<<<<<< HEAD
            return include __DIR__ . './../views/rolesEditar.php';
=======
            include './app/modules/roles/views/rolesEditar.php';
>>>>>>> 751a1fa (edward_commit)
        } else {
            echo "<div class='alert alert-danger text-center'>Rol no encontrado.</div>";
        }
    }
}

public function eliminarRol() {
    if (isset($_GET['id'])) {
        $rl_id = $_GET['id'];
        $exito = $this->modeloRol->eliminarRol($rl_id);

        if ($exito) {
            echo "<div class='alert alert-success text-center'>Rol eliminado correctamente.</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error al eliminar el rol.</div>";
        }
    } else {
        echo "<div class='alert alert-warning text-center'>ID de rol no especificado.</div>";
    }

    // Redirigir o volver al listado de roles después de la acción
    header("Location: index.php?action=rolesListar");
    exit();

}

    
}

?>
