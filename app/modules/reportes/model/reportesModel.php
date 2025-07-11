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

    public function tipoElemento() {
        $sql = "SELECT tp_el_cod, tp_el_nombre FROM tipo_elemento ORDER BY tp_el_nombre ASC";
    
        $stmt = $this->conn->prepare($sql);
        if (!$stmt->execute()) {
            return [];
        }
    
        $result = $stmt->get_result();
        $tipos = [];
    
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }
    
        return $tipos;
    }


    public function obtenerElementosFiltrados($tipo, $estado) {
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
                WHERE 1 = 1";
    
        $params = [];
        $types = '';
    
        if (!empty($tipo)) {
            $sql .= " AND e.elm_cod_tp_elemento = ?";
            $types .= 'i';
            $params[] = $tipo;
        }
    
        if (!empty($estado)) {
            $sql .= " AND e.elm_cod_estado = ?";
            $types .= 'i';
            $params[] = $estado;
        }
    
        $sql .= " ORDER BY e.elm_placa ASC";
    
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
    
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


    public function obtenerTrazabilidad($tipo, $fechaInicio, $fechaFin)
    {
        $sql = "SELECT 
                    e.elm_cod AS codigoElemento,
                    e.elm_nombre AS nombreElemento,
                    e.elm_placa AS placa,
                    es.entr_tp_movmnt AS tipoMovimiento,
                    es.ent_sal_cantidad AS cantidad,
                    es.ent_fech_registro AS fechaMovimiento
                FROM entradas_salidas es
                INNER JOIN elementos e ON e.elm_cod = es.ent_sal_cod_elemtn
                WHERE DATE(es.ent_fech_registro) BETWEEN ? AND ?";
    
        $params = [$fechaInicio, $fechaFin];
        $types  = 'ss'; 
    
        if (!empty($tipo)) {
            $sql .= " AND e.elm_cod_tp_elemento = ?";
            $params[] = $tipo;
            $types .= 'i';
        }
    
        $sql .= " ORDER BY es.ent_fech_registro DESC";
    
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }
    
        $stmt->bind_param($types, ...$params);
    
        if (!$stmt->execute()) {
            return [];
        }
    
        $result = $stmt->get_result();
        $trazabilidad = [];
    
        while ($row = $result->fetch_assoc()) {
            $trazabilidad[] = $row;
        }
    
        return $trazabilidad;
    }


        /**
     * Consulta la trazabilidad (entradas / salidas) de elementos.
     *
     * @param int|string $tipo         Código del tipo de elemento (puede venir vacío).
     * @param string     $fechaInicio  Fecha “YYYY‑MM‑DD”.  Puede venir vacío.
     * @param string     $fechaFin     Fecha “YYYY‑MM‑DD”.  Puede venir vacío.
     * @return array                   Registros de movimientos.
     */
    public function consultEntSal($tipo = '', $fechaInicio = '', $fechaFin = '')
    {

        $sql = "SELECT
                  e.elm_cod            AS codigoElemento,
                  e.elm_nombre         AS nombreElemento,
                  e.elm_placa          AS placa,
                  es.ent_sal_cantidad  AS cantidad,
                  tm.cod_tp_nombre     AS tipoMovimiento,   
                  es.ent_fech_registro AS fechaMovimiento,
                  tp.tp_el_nombre      AS tipoElemento
                FROM entradas_salidas es
                INNER JOIN elementos      e  ON e.elm_cod = es.ent_sal_cod_elemtn
                INNER JOIN tipo_elemento  tp ON tp.tp_el_cod = e.elm_cod_tp_elemento
                INNER JOIN tipo_movimiento tm ON tm.cod_tp   = es.entr_tp_movmnt 
                WHERE 1 = 1";
        

    
        /* ---- filtros dinámicos ---- */
        $types  = '';  $params = [];
    
       
        if ($fechaInicio !== '' && $fechaFin !== '') {
            $sql   .= " AND DATE(es.ent_fech_registro) BETWEEN ? AND ?";
            $types .= 'ss';
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
    
        /* tipo de elemento */
        if ($tipo !== '') {
            $sql   .= " AND e.elm_cod_tp_elemento = ?";
            $types .= 'i';
            $params[] = $tipo;
        }
    
        $sql .= " ORDER BY es.ent_fech_registro DESC";
    
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) { return []; }
    
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
    
        if (!$stmt->execute()) {
            return [];
        }
    
        $result = $stmt->get_result();
        $movimientos = [];
    
        while ($row = $result->fetch_assoc()) {
            $movimientos[] = $row;
        }
    
        return $movimientos;
    }

}



?>