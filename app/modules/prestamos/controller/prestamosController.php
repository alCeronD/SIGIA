<?php
require_once './app/modules/prestamos/model/prestamosModel.php';

class ControllerPrestamos{
    private $modeloPrestamos;

    public function __construct($conexion) {
        $this->modeloPrestamos = new PrestamosModelo($conexion);
    }

    public function mostrarPrestamos() {
        $prestamos = $this->modeloPrestamos->obtenerPrestamos();
        include './app/modules/prestamos/views/prestamosViews.php';
    }

    public function registrarPrestamos() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fch_slcitud   = $_POST['pres_fch_slcitud'];
            $fch_reserva   = $_POST['pres_fch_reserva'];
            $fch_entrega   = $_POST['pres_fch_entrega'];
            $observacion   = $_POST['pres_observacion'];
            $destino       = $_POST['pres_destino'];
            $estado        = $_POST['pres_estado'];
            $res_cod       = $_POST['res_cod'];

            $exito = $this->modeloPrestamos->insertarPrestamo($fch_slcitud, $fch_reserva, $fch_entrega, $observacion, $destino, $estado, $res_cod);

            if ($exito) {
                echo "<div class='alert alert-success text-center'>✅ Préstamo registrado correctamente.</div>";
            } else {
                echo "<div class='alert alert-danger text-center'>❌ Error al registrar el préstamo.</div>";
            }
        }

        include './app/modules/prestamos/views/prestamosRegistrar.php';
    }
}
?>
