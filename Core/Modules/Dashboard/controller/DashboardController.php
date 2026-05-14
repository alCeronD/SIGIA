
<?php
require_once BASE_URL . CR_ROUTE_CONN;
include_once __DIR__ . '/../../Dashboard/model/dashboardModel.php';


Class DashboardController{

    private $conn;

    public function __construct()
    {
    }

    public function dashboard(){
        $nombre = $_SESSION['usuario']['nombre'];
        $apellido = $_SESSION['usuario']['apellido'];
        $nombreCompleto = $nombre . ' ' . $apellido;
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
        $id = $_SESSION['usuario']['id'];

        $prestamoModel = new dashboard();
        $prestamos = $prestamoModel->search($id);
        include __DIR__ . '/../views/dashboardView.php';
    }

}


?>