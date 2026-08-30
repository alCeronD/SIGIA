<?php

require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_PATH . CR_ROUTE_CONN;
require_once BASE_PATH . '/' . CR_AUTOLOAD;


class ServicesSolicitudPrestamos
{

    private SolicitudPrestamosModel $solicitudPrestamos;
    public function __construct()
    {
        $conn = (new Conn)->getConnect();
        $this->solicitudPrestamos = new SolicitudPrestamosModel($conn);
    }

    public function callTask()
    {
        $this->solicitudPrestamos->actualizarEstadosPorFecha();
    }
}
