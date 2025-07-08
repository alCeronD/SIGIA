<?php

include_once __DIR__ . '/../model/elementosModel.php';
require_once __DIR__ . '/../../configModules/model/configModulesModel.php';
require_once __DIR__ . '/../../../helpers/response.php';
require_once __DIR__ . '/../../../helpers/const.php';
include_once __DIR__ . '/../../../config/conn.php';
class ReporteElementos{

    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }




    public function genReporteView(){
        include_once '../proyecto_sigia/app/modules/elementos/views/reporteElementosView.php';
    }






}

?>