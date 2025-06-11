<?php
include_once __DIR__ . '/../model/solicitudPrestamosModel.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../configModules/model/configModulesModel.php';
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
        
        // Me traigo el listado de los elementos al front
        $objetoElemento = new solicitudPrestamos($this->conn);
        $elementos = $objetoElemento->search();
        
        // Me traigo el listado de areas para el filtro por area
        $objetoArea = new ConfigModulesModel();
        $areas = $objetoArea->select("SELECT * FROM areas");
        
        return include_once __DIR__ . '/../views/solicitudPrestamosView.php';
    }
    
    public function consultarPrestamosView() {
    
        $nombre = $_SESSION['usuario']['nombre'];
        $apellido = $_SESSION['usuario']['apellido'];
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
      
        $prestamoModel = new solicitudPrestamos($this->conn);
        $prestamos = $prestamoModel->search();
            
        return include_once __DIR__ . '/../views/consultarPrestamosView.php';
    }
    
    public function verDetallePrestamoView(){
        dd($_GET);
    }
    
    public function registrarPrestamo(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario_id = $_SESSION['usuario']['id_usuario'];
            $elementos_seleccionados = $_POST['elementos_seleccionados'];
            unset($_POST['elementos_seleccionados']);
            $data = $_POST;
            $datos = new solicitudPrestamos($this->conn);
            $lastId = $datos->create($data);
            if (is_numeric($lastId)) {
                include_once __DIR__ . '/../../configModules/prestamosElementos/model/prestamosElementosModel.php';
                $prestamoElemento = new prestamoElementos($this->conn);
    
                foreach ($elementos_seleccionados as $elemento_id) {
                    $prestamoElemento->create($lastId, $usuario_id ,$elemento_id);
                }
                if ($prestamoElemento == true) {
                    // dd("hola llegue");
                    echo "<script>alert('Usuario registrado exitosamente'); window.location.href = '" . getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamosView', false, 'dashboard') . "';</script>";
                    
                }else {
                    echo "<script>alert('Usuario no se registro'); window.location.href = '" . getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamosView', false, 'dashboard') . "';</script>";
                    // dd("error aqui");
                }
                exit;
            } else {
                echo "Error al registrar el préstamo: " . $lastId;
            }
        }
    }

    
       
    
}

?>