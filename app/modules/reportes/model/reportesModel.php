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
        $sql = "SELECT e.codigo AS codigoElemento, e.nombre AS nombreElemento, e.placa, e.cantidad, es.est_nombre AS estadoElemento
                FROM elementos e
                JOIN estados_elementos es ON e.estado_id = es.est_el_cod
                WHERE es.est_el_cod = ?";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $estado); // Usa "i" porque est_el_cod es int
    
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