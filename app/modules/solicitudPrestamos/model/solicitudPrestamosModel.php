<?php

class solicitudPrestamos {
    public $pres_cod;
    public $pres_fch_slcitud;
    public $pres_fch_reserva;
    public $pres_hor_inicio;
    public $pres_hor_fin;
    public $pres_fch_entrega;
    public $pres_observacion;
    public $pres_destino;
    public $pres_estado;
    public $tp_pres;

    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function create(array $data = []) {
        if (!is_array($data)) {
            exit();
        }

        $pres_fch_slcitud  = $this->conn->real_escape_string($data['pres_fch_slcitud']);
        $pres_fch_reserva  = $this->conn->real_escape_string($data['pres_fch_reserva']);
        $pres_hor_inicio   = $this->conn->real_escape_string($data['pres_hor_inicio']);
        $pres_hor_fin      = $this->conn->real_escape_string($data['pres_hor_fin']);
        $pres_fch_entrega  = $this->conn->real_escape_string($data['pres_fch_entrega']);
        $pres_observacion  = $this->conn->real_escape_string($data['pres_observacion']);
        $pres_destino      = $this->conn->real_escape_string($data['pres_destino']);
        $pres_estado       = (int)$data['pres_estado'];
        $tp_pres           = (int)$data['tp_pres'];

        $query = "
            INSERT INTO solicitud_prestamos (
                pres_fch_slcitud, pres_fch_reserva, pres_hor_inicio, pres_hor_fin,
                pres_fch_entrega, pres_observacion, pres_destino, pres_estado, tp_pres
            ) VALUES (
                '$pres_fch_slcitud', '$pres_fch_reserva', '$pres_hor_inicio', '$pres_hor_fin',
                '$pres_fch_entrega', '$pres_observacion', '$pres_destino', $pres_estado, $tp_pres
            )
        ";

        if ($this->conn->query($query)) {
            return true;
        } else {
            return "Error al registrar el préstamo: " . $this->conn->error;
        }
    }

    public function search() {
        $query = "SELECT 
                    e.elm_cod,
                    e.elm_placa,
                    e.elm_nombre,
                    e.elm_existencia,
                    e.elm_uni_medida,
                    e.elm_cod_tp_elemento,
                    e.elm_cod_estado,
                    e.elm_area_cod,
                    c.ca_nombre AS categoria_nombre
                  FROM elementos e
                  INNER JOIN categoria c ON e.elm_cod_tp_elemento = c.ca_id";
    
        $result = $this->conn->query($query);
    
        $elementos = [];
    
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $elementos[] = $row;
            }
        }
    
        return $elementos;
    }


    public function searchU(int $id) {
        if (!is_int($id)) {
            exit();
        }

        $query = "SELECT * FROM solicitud_prestamos WHERE pres_cod = $id";
        $resultado = $this->conn->query($query);

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }
}

?>
