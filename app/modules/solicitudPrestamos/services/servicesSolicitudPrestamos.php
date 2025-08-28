<?php
require_once __DIR__ . '/../model/solicitudPrestamosModel.php';
require_once __DIR__ . '/../../../config/conn.php';

class servicesSolicitudPrestamos{
    
   

    private solicitudPrestamos $solicitudPrestamos;
    public function __construct(){
        $conexion = new Conection();
        $conn = $conexion->getConnect();
        $this->solicitudPrestamos = new solicitudPrestamos($conn);
    }
    
    public function callTask(){
        $this->solicitudPrestamos->actualizarEstadosPorFecha();
    }
}



?>