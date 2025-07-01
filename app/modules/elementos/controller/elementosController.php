<?php
include_once __DIR__ . '/../model/elementosModel.php';
require_once __DIR__ . '/../../configModules/model/configModulesModel.php';

class elementosController {
    private $modeloElemento;
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
        $this->modeloElemento = new ElementoModelo($conexion);
    }

 public function mostrarElementos() {
    // Parámetros de paginación
    $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
    $limite = 10;
    $offset = ($pagina - 1) * $limite;

    // Obtener elementos paginados
    $elementos = $this->modeloElemento->obtenerElementoPaginado($limite, $offset);

    // Contar total de elementos para el paginador
    $totalElementos = $this->modeloElemento->contarElementos();
    $totalPaginas = ceil($totalElementos / $limite);

    // Obtener las áreas
    $modeloGenerico = new ConfigModulesModel();
    $areas = $modeloGenerico->select("SELECT ar_cod AS codigo, ar_nombre AS nombre FROM areas");

    // Buscar el código del área "general"
    $area_general_codigo = null;
    foreach ($areas as $area) {
        if (strtolower(trim($area['nombre'])) === 'general') {
            $area_general_codigo = $area['codigo'];
            break;
        }
    }

    // Incluir la vista pasando las variables necesarias
    include __DIR__ . '/../views/elementosView.php';
}




    public function registrarElemento() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Validar que existan todos los campos obligatorios
        if (
            isset($_POST['elm_placa'], $_POST['elm_nombre'], $_POST['elm_existencia'],
                   $_POST['elm_uni_medida'], $_POST['elm_cod_tp_elemento'],
                   $_POST['elm_cod_estado'], $_POST['elm_area_cod'])
        ) {
            $datos = [
                'elm_placa' => $_POST['elm_placa'],
                'elm_nombre' => $_POST['elm_nombre'],
                'elm_existencia' => $_POST['elm_existencia'],
                'elm_uni_medida' => $_POST['elm_uni_medida'],
                'elm_cod_tp_elemento' => $_POST['elm_cod_tp_elemento'],
                'elm_cod_estado' => $_POST['elm_cod_estado'],
                'elm_area_cod' => $_POST['elm_area_cod']
            ];

            $exito = $this->modeloElemento->insertarElemento($datos);

            if ($exito) {
                echo "<script>alert('Elemento registrado exitosamente'); window.location.href = '" . getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') . "';</script>";
            } else {
                echo "<div class='alert alert-danger text-center'>Error al registrar el elemento.</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center'>Faltan datos obligatorios para registrar.</div>";
        }
    } else {
        $modeloGenerico = new ConfigModulesModel();
        $areas = $modeloGenerico->select("SELECT ar_cod AS codigo, ar_nombre AS nombre FROM areas");
        include __DIR__ . '/../views/elementosRegistrar.php';
    }
}


    

public function editarElemento() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['elm_cod'], $_POST['elm_nombre'], $_POST['elm_uni_medida'], $_POST['elm_area_cod'])) {
            $id = $_POST['elm_cod'];

            // Llenar arreglo con los datos que sí se actualizan
            $datos = [
                'elm_nombre' => $_POST['elm_nombre'],
                'elm_uni_medida' => $_POST['elm_uni_medida'],
                'elm_area_cod' => $_POST['elm_area_cod'],
                'elm_cod_estado' => 1 // Siempre activo (o puedes recibirlo por POST si quieres que sea editable)
            ];

            // Llamar al modelo para actualizar
            $exito = $this->modeloElemento->actualizarElemento($id, $datos);

            if ($exito) {
                echo "<script>alert('Elemento actualizado correctamente'); window.location.href = '" . getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') . "';</script>";
            } else {
                echo "<div class='alert alert-danger text-center'>Error al actualizar el elemento.</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center'>Faltan datos obligatorios.</div>";
        }

    } else {
        // Mostrar formulario de edición
        if (isset($_GET['elm_cod'])) {
            $id = $_GET['elm_cod'];
            $elemento = $this->modeloElemento->obtenerElementoPorId($id);

            // Usamos el modelo genérico para obtener áreas
            $modeloGenerico = new ConfigModulesModel();
            $areas = $modeloGenerico->select("SELECT ar_cod AS codigo, ar_nombre AS nombre FROM areas");

            if ($elemento) {
                include __DIR__ . '/../views/elementosEditar.php';
            } else {
                echo "<div class='alert alert-danger text-center'>Elemento no encontrado.</div>";
            }
        }
    }
}

    public function cambiarEstadoElemento() {
        if (isset($_GET['elm_cod'])) {
            $id = $_GET['elm_cod'];
            $exito = $this->modeloElemento->toggleEstadoElemento($id);

            if ($exito) {
                echo "<script>alert('Estado del elemento cambiado correctamente'); window.location.href = '" . getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') . "';</script>";
            } else {
                echo "<div class='alert alert-danger text-center'>Error al cambiar estado del elemento.</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center'>No se especificó el elemento para cambiar estado.</div>";
        }
    }
}
?>
