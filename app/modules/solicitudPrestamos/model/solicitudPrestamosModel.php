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

    public function create(array $data = [],$rol_usuario) {
        if (!is_array($data)) {
            exit();
        }

        $pres_fch_reserva  = $this->conn->real_escape_string($data['pres_fch_reserva']);
        $pres_fch_entrega  = $this->conn->real_escape_string($data['pres_fch_entrega']);
        $pres_observacion  = $this->conn->real_escape_string($data['pres_observacion']);
        $pres_destino      = $this->conn->real_escape_string($data['pres_destino']);
        $pres_estado       = 3;
        $tp_pres           = 1;
        $pres_rol          = $rol_usuario;
        
        $query = "INSERT INTO prestamos (
                pres_fch_slcitud, pres_fch_reserva,
                pres_fch_entrega, pres_observacion, pres_destino, pres_estado, tp_pres, pres_rol
            ) VALUES (NOW(), '$pres_fch_reserva', '$pres_fch_entrega', '$pres_observacion', '$pres_destino', $pres_estado, $tp_pres, $pres_rol
            )
        ";
        
        if ($this->conn->query($query)) {
            return $this->conn->insert_id; 
        } else {
            return "Error al registrar el préstamo: " . $this->conn->error;
        }
    }
    
    public function update($datos, $id) {
        $cadena = "";

        foreach ($datos as $campo => $value) {
            $cadena .= "$campo = '" . $this->conn->real_escape_string($value) . "',";
        }

        $cadena = rtrim($cadena, ",");
        $query = "UPDATE solicitud_prestamos SET $cadena WHERE pres_cod = '$id'";
        $resultado = $this->conn->query($query);

        if ($resultado) {
            return true;
        } else {
            return "Error al actualizar el préstamo: " . $this->conn->error;
        }
    }

    public function delete(int $id) {
        $query = "DELETE FROM solicitud_prestamos WHERE pres_cod = '$id'";
        $resultado = $this->conn->query($query);

        if ($resultado) {
            return true;
        } else {
            echo "Problemas al eliminar el préstamo";
            exit();
        }
    }

    public function search($id = 0) {
        $sql = "SELECT DISTINCT
            p.pres_cod AS 'codigoSolicitud',
            p.pres_fch_slcitud AS 'fechaSolicitud',
            p.pres_fch_reserva AS 'fechaReserva',
            p.pres_hor_inicio AS 'horaInicio',
            p.pres_hor_fin AS 'horaFin',
            p.pres_fch_entrega AS 'fechaEntrega',
            p.pres_observacion AS 'observacion',
            p.pres_destino AS 'destino',
            p.pres_estado AS 'estadoPrestamo',
            tp.tp_nombre As 'tipoPrestamo'
            FROM prestamos p
            LEFT JOIN prestamos_elementos pe ON
            p.pres_cod = pe.pres_cod 
            LEFT JOIN usuarios us ON
            us.usu_id = pe.pres_el_usu_id
            LEFT JOIN tipo_prestamo tp ON tp.tp_pre = p.tp_pres
            WHERE us.usu_id = ? ORDER BY p.pres_fch_slcitud DESC;";

            $stmtSelect = $this->conn->prepare($sql);
            $stmtSelect->bind_param('i',$id);

    
            $stmtSelect->execute();
        
            $result = $stmtSelect->get_result();
            $data = [];
        
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
    
        return $data;
    }


    public function searchU(int $id) {
        if (!is_int($id)) {
            exit();
        }

        $query = "SELECT * FROM prestamos WHERE pres_cod = $id";

        $resultado = $this->conn->query($query);

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
            
        } else {
            return null;
        }
    }

    public function cancelarPrestamo(int $presCod): array {
    // Validar ID
        if (!$presCod || !is_numeric($presCod)) {
            return ['success' => false, 'message' => 'Código inválido de préstamo'];
        }
    
        // Cambiar estado del préstamo a cancelado (ej. tipo = 3)
        $stmt = $this->conn->prepare("UPDATE prestamos SET pres_estado = 5 WHERE pres_cod = ?");
        $stmt->bind_param("i", $presCod);
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            // Cambiar estado de los elementos a disponible
            $query = "SELECT pres_el_elem_cod FROM prestamos_elementos WHERE pres_cod = ?";
            $elementosStmt = $this->conn->prepare($query);
            $elementosStmt->bind_param("i", $presCod);
            $elementosStmt->execute();
            $result = $elementosStmt->get_result();
    
            include_once __DIR__ . '/../../elementos/model/elementosModel.php';
            $elementoModel = new ElementoModelo($this->conn);
    
            while ($row = $result->fetch_assoc()) {
                $elementoModel->actualizarEstadoElemento($row['pres_el_elem_cod'], 1); // 1 = Disponible
            }
    
            return ['success' => true, 'message' => 'Préstamo cancelado correctamente'];
        } else {
            return ['success' => false, 'message' => 'No se pudo cancelar el préstamo'];
        }
    }

        public function registrarElem($pres_cod, $usuario_id ,$elm_cod) {
        // dd("llego modelo");
        $pres_cod = (int) $pres_cod;
        $elm_cod = (int) $elm_cod;
        $usua_id = (int) $usuario_id;

        $query = "INSERT INTO prestamos_elementos (pres_cod, pres_el_usu_id, pres_el_elem_cod ) VALUES ($pres_cod, $usua_id, $elm_cod)";
        
        return $this->conn->query($query);
    }
    
}

?>
