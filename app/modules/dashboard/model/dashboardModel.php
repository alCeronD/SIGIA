<?php

require_once __DIR__ . '/../../../helpers/session.php';
require_once __DIR__ . '/../../../helpers/const.php';
include_once __DIR__ . '/../../../config/conn.php';


class dashboard{



    private $conn;
    
    public function __construct()
    {
        $objConn= new Conection();
        $this->conn = $objConn->getConnect();
    }
    
    public function search($id = 0)
    {
        $sql = "SELECT DISTINCT
            p.pres_cod AS codigoSolicitud,
            p.pres_fch_slcitud AS fechaSolicitud,
            p.pres_fch_reserva AS fechaReserva,
            p.pres_hor_inicio AS horaInicio,
            p.pres_hor_fin AS horaFin,
            p.pres_fch_entrega AS fechaEntrega,
            p.pres_observacion AS observacion,
            p.pres_destino AS destino,
            ep.es_pr_nombre AS estadoNombre,        
            tp.tp_nombre AS tipoPrestamo
        FROM prestamos p
        LEFT JOIN prestamos_elementos pe ON p.pres_cod = pe.pres_cod 
        LEFT JOIN usuarios us ON us.usu_id = pe.pres_el_usu_id
        LEFT JOIN tipo_prestamo tp ON tp.tp_pre = p.tp_pres
        LEFT JOIN estados_prestamos ep ON ep.es_pr_cod = p.pres_estado  
        WHERE us.usu_id = ? AND ep.es_pr_nombre = 'Por validar'
        ORDER BY p.pres_fch_slcitud DESC";
    
        $stmtSelect = $this->conn->prepare($sql);
        $stmtSelect->bind_param('i', $id);
        $stmtSelect->execute();
    
        $result = $stmtSelect->get_result();
        $data = [];
    
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    
        return $data;
    }


}
?>