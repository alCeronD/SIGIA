<?php

include_once __DIR__ . '/../../elementos/model/elementosModel.php';
include_once __DIR__ . '/../model/reportesModel.php';
include_once __DIR__ . '/../../../helpers/response.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../../helpers/session.php';

class ReportesController {

    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function genReporteView() {
        $objElm = new ElementoModelo();
        $objEstElm = new ReportesModel();

        $estados = $objEstElm->getEstadosReport();
        $estadoSeleccionado = $_GET['estadoElemento'] ?? '';

        $elementos = (!empty($estadoSeleccionado))
            ? $objEstElm->obtenerElementosPorEstado($estadoSeleccionado)
            : $objElm->obtenerElemento();

        include_once __DIR__ . '/../views/reporteElementosView.php';
    }

    public function filtrarElementosAjax() {
        if (!ajaxGeneral()) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso no permitido']);
            exit;
        }
    
        header('Content-Type: application/json');
    
        $estado = $_POST['estadoElemento'] ?? '';
    
        $modelo = new ReportesModel();
        $datos = (!empty($estado))
            ? $modelo->obtenerElementosPorEstado($estado)
            : (new ElementoModelo())->obtenerElemento();
    
        echo json_encode($datos);
        exit;
    }

}
