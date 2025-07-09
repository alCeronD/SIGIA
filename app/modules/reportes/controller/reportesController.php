<?php

include_once __DIR__ . '/../../elementos/model/elementosModel.php';
include_once __DIR__ . '/../../reportes/model/reportesModel.php';
require_once __DIR__ . '/../../../helpers/response.php';
require_once __DIR__ . '/../../../helpers/const.php';
include_once __DIR__ . '/../../../config/conn.php';

include_once __DIR__ . '/../../../helpers/session.php';

class ReportesController{

    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }




    public function genReporteView() {
        $objElm = new ElementoModelo();
        $objEstElm = new ReportesModel();
    
        // Obtener estados disponibles para el filtro
        $estados = $objEstElm->getEstadosReport();
    
        // Leer estado desde GET si existe
        $estadoSeleccionado = isset($_GET['estadoElemento']) ? trim($_GET['estadoElemento']) : '';
    
        // Obtener elementos filtrados o todos
        if (!empty($estadoSeleccionado)) {
            $elementos = $objEstElm->obtenerElementosPorEstado($estadoSeleccionado); // ← esta es la línea corregida
        } else {
            $elementos = $objElm->obtenerElemento();
        }
    
        include_once __DIR__ . '/../views/reporteElementosView.php';
    }


}

?>