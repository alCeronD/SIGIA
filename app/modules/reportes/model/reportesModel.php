<?php

include_once __DIR__ . '/../../../config/conn.php';

class ReportesModel{

    private $conn;

    public function __construct()
    {
        $conexion = new Conection();
        $this->conn = $conexion->getConnect();
    }

    ///////////////////////////////////////////////////////////
    //Estare usando estas funciones para el reporte a generar// 
    ///////////////////////////////////////////////////////////
    
   public function getEstadosReport() {
        $sql = "SELECT est_el_cod,est_nombre FROM estados_elementos";
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt->execute()) {
            return null;
        }
    
        $result = $stmt->get_result();
        $estados = [];
    
        while ($row = $result->fetch_assoc()) {
            $estados[] = $row;
        }
        // dd($estados);
        return $estados;
    }
        public function getTiposElemento() {
        $sql = "SELECT tp_el_cod AS tip_cod, tp_el_nombre AS tip_nombre FROM tipo_elemento";
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt->execute()) {
            return null;
        }
    
        $result = $stmt->get_result();
        $tipos = [];
    
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }
    
        return $tipos;
    }

    public function obtenerElementosPorEstado($estado) {
        $sql = "SELECT 
                    e.elm_cod AS codigoElemento,
                    e.elm_placa AS placa,
                    e.elm_nombre AS nombreElemento,
                    e.elm_existencia AS cantidad,
                    e.elm_uni_medida AS unidadMedida,
                    ar.ar_nombre AS nombreArea,
                    tpE.tp_el_nombre AS tipoElemento,
                    es_e.est_nombre AS estadoElemento
                FROM elementos e
                INNER JOIN areas ar ON ar.ar_cod = e.elm_area_cod
                INNER JOIN tipo_elemento tpE ON tpE.tp_el_cod = e.elm_cod_tp_elemento
                INNER JOIN estados_elementos es_e ON es_e.est_el_cod = e.elm_cod_estado
                WHERE e.elm_cod_estado = ?
                ORDER BY e.elm_placa ASC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $estado);
    
        if (!$stmt->execute()) {
            return [];
        }
    
        $result = $stmt->get_result();
        $elementos = [];
    
        while ($row = $result->fetch_assoc()) {
            $elementos[] = $row;
        }
    
        return $elementos;
    }






}



?>