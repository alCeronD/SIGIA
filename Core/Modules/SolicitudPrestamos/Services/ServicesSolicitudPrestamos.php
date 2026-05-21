<?php

require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_URL . CR_ROUTE_CONN;
require_once BASE_URL . '/' . CR_AUTOLOAD;


class ServicesSolicitudPrestamos
{

    private SolicitudPrestamosModel $solicitudPrestamos;
    public function __construct()
    {
        $conexion = new Conn();
        $conn = $conexion->getConnect();
        $this->solicitudPrestamos = new SolicitudPrestamosModel($conn);
    }

    public function callTask()
    {
        $this->solicitudPrestamos->actualizarEstadosPorFecha();
    }
}
