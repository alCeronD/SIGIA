<?php
include_once __DIR__ . '/../model/solicitudPrestamosModel.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../configModules/model/configModulesModel.php';
include_once __DIR__ . '/../../elementos/model/elementosModel.php';
include_once __DIR__ . '/../../../helpers/session.php';

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
    
    
    public function verDetallePrestamo() {
        $id = $_GET['pres_cod'] ?? null;
    
        if (!$id || !is_numeric($id)) {
            echo "<div class='alert'>ID no válido</div>";
            return;
        }
    
        $modelo = new solicitudPrestamos($this->conn);
        $detalle = $modelo->searchU((int) $id);
    
        if (!$detalle) {
            echo "<div class='alert'>No se encontró información del préstamo.</div>";
            return;
        }
    
        function formatField($value) {
            return ($value === '0000-00-00' || $value === '00:00:00' || empty($value)) ? 'No registrado' : htmlspecialchars($value);
        }
    
        echo "
        <style>
          .detalle-container {
            display: flex;
            flex-direction: column;
            font-size: 14px;
            padding: 10px;
          }
    
          .row-pair {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 12px;
          }
    
          .label {
            font-weight: bold;
            color: #333;
            margin-bottom: 4px;
          }
    
          .value-box {
            background-color: #f7f7f7;
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            color: #444;
          }
    
          .column {
            flex: 1;
            display: flex;
            flex-direction: column;
          }
    
          .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
          }
        </style>
    
        <div class='detalle-container'>
          <div class='title'>Detalle del préstamo</div>
    
          <div class='row-pair'>
            <div class='column'>
              <span class='label'>Fecha de Solicitud:</span>
              <div class='value-box'>" . formatField($detalle['pres_fch_slcitud']) . "</div>
            </div>
            <div class='column'>
              <span class='label'>Fecha de Reserva:</span>
              <div class='value-box'>" . formatField($detalle['pres_fch_reserva']) . "</div>
            </div>
          </div>
    
          <div class='row-pair'>
            <div class='column'>
              <span class='label'>Hora Inicio:</span>
              <div class='value-box'>" . formatField($detalle['pres_hor_inicio']) . "</div>
            </div>
            <div class='column'>
              <span class='label'>Hora Fin:</span>
              <div class='value-box'>" . formatField($detalle['pres_hor_fin']) . "</div>
            </div>
          </div>
    
          <div class='row-pair'>
            <div class='column'>
              <span class='label'>Fecha Entrega:</span>
              <div class='value-box'>" . formatField($detalle['pres_fch_entrega']) . "</div>
            </div>
            <div class='column'>
              <span class='label'>Destino:</span>
              <div class='value-box'>" . formatField($detalle['pres_destino']) . "</div>
            </div>
          </div>
    
          <div class='row-pair'>
            <div class='column'>
              <span class='label'>Observación:</span>
              <div class='value-box'>" . formatField($detalle['pres_observacion']) . "</div>
            </div>
          </div>
        </div>
        ";
    }
}

?>