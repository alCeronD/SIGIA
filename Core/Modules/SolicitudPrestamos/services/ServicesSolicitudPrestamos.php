<?php
require_once __DIR__ . CR_ROUT_SOLICITUD_PRESTAMOS_MODEL;
require_once __DIR__ . '/../..'.CR_ROUTE_CONN;

class ServicesSolicitudPrestamos{



    private solicitudPrestamos $solicitudPrestamos;
    public function __construct(){
        $conexion = new Conn();
        $conn = $conexion->getConnect();
        $this->solicitudPrestamos = new solicitudPrestamos($conn);
    }

    public function callTask(){
        $this->solicitudPrestamos->actualizarEstadosPorFecha();
    }
}



?>