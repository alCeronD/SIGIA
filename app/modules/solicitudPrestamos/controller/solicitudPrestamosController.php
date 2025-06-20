<?php

use Dba\Connection;
include_once __DIR__ . '/../model/solicitudPrestamosModel.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../configModules/model/configModulesModel.php';
include_once __DIR__ . '/../../elementos/model/elementosModel.php';
include_once __DIR__ . '/../../../helpers/session.php';
include_once __DIR__ . '/../../../helpers/response.php';
class solicitudPrestamosController{

    private $conn;
    
    public function __construct($conexion) {
        $this->conn = $conexion;
    }
    
    public function registrarPrestamosView(){
        $nombre = $_SESSION['usuario']['nombre'];
        $apellido = $_SESSION['usuario']['apellido'];
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
        
        // Me traigo el listado de areas para el filtro por area
        $objetoArea = new ConfigModulesModel();
        $areas = $objetoArea->select("SELECT * FROM areas");
        
        // Me traigo el listado de los elementos al front
        $objetoElemento = new ElementoModelo($this->conn);
        $elementos = $objetoElemento->searchElements();
          
        return include_once __DIR__ . '/../views/solicitudPrestamosView.php';
    }
    public function consultarPrestamosView() {
    
        $nombre = $_SESSION['usuario']['nombre'];
        $apellido = $_SESSION['usuario']['apellido'];
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
      
        $prestamoModel = new solicitudPrestamos($this->conn);
        $prestamos = $prestamoModel->search();
        // dd($prestamos);    
        return include_once __DIR__ . '/../views/consultarPrestamosView.php';
    }
    public function verDetallePrestamoView(){
        dd($_GET);
    }
    public function registrarPrestamo(){

        $conn = $this->conn;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          
            $usuario_id = $_SESSION['usuario']['id_usuario'];
            $rol_id = $_SESSION['usuario']['rol_id'];
            $elementos_seleccionados = $_POST['elementos_seleccionados'];
            unset($_POST['elementos_seleccionados']);
            $data = $_POST;
            $datos = new solicitudPrestamos($this->conn);
            $lastId = $datos->create($data, $rol_id);
            if (is_numeric($lastId)) {
                include_once __DIR__ . '/../../configModules/prestamosElementos/model/prestamosElementosModel.php';
                $prestamoElemento = new prestamoElementos($this->conn);
                $elementoModel = new ElementoModelo($this->conn);
                foreach ($elementos_seleccionados as $elemento_id) {
                    $prestamoElemento->create($lastId, $usuario_id ,$elemento_id);
                    $elementoModel->actualizarEstadoElemento($elemento_id, 3);

                }
                if ($prestamoElemento == true) {
                    echo "<script>alert('Solicitud realizada correctamente, en espera por respuesta'); window.location.href = '" . getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamosView', false, 'dashboard') . "';</script>";
                    
                }else {
                    echo "<script>alert('Prestamo no se registro'); window.location.href = '" . getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamosView', false, 'dashboard') . "';</script>";
                }
                exit;
            } else {
                echo "Error al registrar el préstamo: " . $lastId;
            }
        }
    }
    public function verDetallePrestamo(int $presCod) {
        

        if (!$presCod || !is_numeric($presCod)) {
          //TODO, lo vas a cambia por json encode y su respuesta la manipulas con javascript.
            // echo "<div class='alert'>ID no válido</div>";
            fail('Id no valido');
            return;
        }

        $conectar = $this->conn;

        $modelo = new solicitudPrestamos($this->conn);
        $detalle = $modelo->searchU($presCod);
        //var_dump($detalle);
        if (!$detalle) {
              fail('No se encontró información del préstamo');
        }
        success('Detalle del prestamo',$detalle);
    }
}

$conexion = new Conection();
$getConect = $conexion->getConnect();
$solicitudObj = new solicitudPrestamosController($getConect);
//IdCOd es un indicativo para validar lo que vamos a requerir para así apuntar a una función específica.
if (isset($_GET['pres_cod']) && isset($_GET['idCod'])) {
    $pres_cod = (int) $_GET['pres_cod'];
    $solicitudObj->verDetallePrestamo($pres_cod);

}

?>