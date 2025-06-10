<?php
include_once __DIR__ . '/../model/elementosModel.php';


class elementosController {
    private $modeloElemento;
        private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
        $this->modeloElemento = new ElementoModelo($conexion);
    }

    public function mostrarElementos() {
        $elementos = $this->modeloElemento->obtenerElementos();
        
        return include __DIR__ . '/../views/elementosView.php';
        
    }

    public function registrarElemento() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                echo "<script>alert('Elemento registrado exitosamente'); window.location.href = '" . getUrl('elementos','elementos','mostrarElementos',false,'dashboard') . "';</script>";
            } else {
                echo "<div class='alert alert-danger text-center'>Error al registrar el elemento.</div>";
            }
        } else {
            include __DIR__ . '/../views/elementosRegistrar.php';
        }
    }
}
?>
