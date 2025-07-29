
<?php 
include_once __DIR__ . '/../../dashboard/model/dashboardModel.php';
include_once __DIR__ . '/../../../config/conn.php';


class DashboardController{

    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }
    
    public function dashboard(){
        $nombre = $_SESSION['usuario']['nombre'];
        $apellido = $_SESSION['usuario']['apellido'];
        $nombreCompleto = $nombre . ' ' . $apellido;
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
        $id = $_SESSION['usuario']['id'];
    
        $prestamoModel = new dashboard($this->conn);
        $prestamos = $prestamoModel->search($id);
        include __DIR__ . '/../views/dashboardView.php';
    }

}


?>