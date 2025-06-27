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

    //    var_dump($data);
        //$pres_fch_slcitud  = $this->conn->real_escape_string($data['pres_fch_slcitud']) ?? '';
        $pres_fch_reserva  = $this->conn->real_escape_string($data['pres_fch_reserva']);
        // $pres_hor_inicio   = $this->conn->real_escape_string($data['pres_hor_inicio']) ?? '';
        // $pres_hor_fin      = $this->conn->real_escape_string($data['pres_hor_fin']) ?? '';
        $pres_fch_entrega  = $this->conn->real_escape_string($data['pres_fch_entrega']);
        $pres_observacion  = $this->conn->real_escape_string($data['pres_observacion']);
        $pres_destino      = $this->conn->real_escape_string($data['pres_destino']);
        $pres_estado       = 3;
        $tp_pres           = 2;
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

    public function search() {
        $sql = "SELECT 
                    p.pres_cod,
                    p.pres_fch_slcitud,
                    p.pres_fch_reserva,
                    p.pres_hor_inicio,
                    p.pres_hor_fin,
                    p.pres_fch_entrega,
                    p.pres_observacion,
                    p.pres_destino,
                    p.pres_estado,
                    tp.tp_nombre AS tipo_prestamo
                FROM 
                    prestamos p
                LEFT JOIN 
                    tipo_prestamo tp ON p.tp_pres = tp.tp_pre
                ORDER BY 
                    p.pres_fch_slcitud DESC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->get_result();
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
        $stmt = $this->conn->prepare("UPDATE prestamos SET tp_pres = 3 WHERE pres_cod = ?");
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

    
}

?>
