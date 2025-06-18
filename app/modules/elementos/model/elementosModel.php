<?php

class ElementoModelo {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    // Obtener todos los elementos con nombres relacionados
    public function obtenerElemento() {
        $elementos = [];
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
        ORDER BY e.elm_placa ASC;";

        $resultado = $this->conn->query($sql);

        if ($resultado) {
            while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
                $elementos[] = $fila;
            }
        } else {
            echo "Error al ejecutar la consulta: " . $this->conn->error;
        }

        return $elementos;
    }

    // Obtener un solo elemento con nombres relacionados para edición
    public function obtenerElementoPorId($id) {
        $sql = "SELECT 
            e.elm_cod,
            e.elm_placa,
            e.elm_nombre,
            e.elm_existencia,
            e.elm_uni_medida,
            e.elm_cod_tp_elemento,
            e.elm_cod_estado,
            e.elm_area_cod,
            ar.ar_nombre AS nombreArea,
            tpE.tp_el_nombre AS tipoElemento
        FROM elementos e
        INNER JOIN areas ar ON ar.ar_cod = e.elm_area_cod
        INNER JOIN tipo_elemento tpE ON tpE.tp_el_cod = e.elm_cod_tp_elemento
        WHERE e.elm_cod = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            echo "Error en prepare: " . $this->conn->error;
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Insertar nuevo elemento
    public function insertarElemento($datos) {
        $sql = "INSERT INTO elementos (elm_placa, elm_nombre, elm_existencia, elm_uni_medida, elm_cod_tp_elemento, elm_cod_estado, elm_area_cod) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            echo "Error en prepare: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param("isiiiii", $datos['elm_placa'], $datos['elm_nombre'], $datos['elm_existencia'], $datos['elm_uni_medida'], $datos['elm_cod_tp_elemento'], $datos['elm_cod_estado'], $datos['elm_area_cod']);
        return $stmt->execute();
    }

    // Actualizar elemento sin modificar placa ni tipo (solo otros campos)
    public function actualizarElemento($id, $datos) {
    $sql = "UPDATE elementos 
            SET elm_nombre = ?, 
                elm_uni_medida = ?, 
                elm_cod_estado = ?, 
                elm_area_cod = ? 
            WHERE elm_cod = ?";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        echo "Error en prepare: " . $this->conn->error;
        return false;
    }
    $stmt->bind_param(
        "siiii",
        $datos['elm_nombre'],
        $datos['elm_uni_medida'],
        $datos['elm_cod_estado'],
        $datos['elm_area_cod'],
        $id
    );
    return $stmt->execute();
}


    // Alternar estado entre Disponible (1) e Inhabilitado (4)
    public function toggleEstadoElemento($id) {
        $estadoDisponible = 1;
        $estadoInhabilitado = 4;

        $sql = "SELECT elm_cod_estado FROM elementos WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            echo "Error en prepare: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $estadoActual = (int) $fila['elm_cod_estado'];

            if ($estadoActual === $estadoDisponible) {
                $nuevoEstado = $estadoInhabilitado;
            } elseif ($estadoActual === $estadoInhabilitado) {
                $nuevoEstado = $estadoDisponible;
            } else {
                return false;
            }

            $sqlUpdate = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            if (!$stmtUpdate) {
                echo "Error en prepare: " . $this->conn->error;
                return false;
            }
            $stmtUpdate->bind_param("ii", $nuevoEstado, $id);
            return $stmtUpdate->execute();
        }

        return false;
    }

    // Buscar elementos activos
    public function searchElements() {
        $query = "SELECT
            e.*,
            a.ar_cod,
            a.ar_nombre,
            ee.est_nombre
        FROM
            elementos e
        JOIN areas a ON e.elm_area_cod = a.ar_cod
        JOIN estados_elementos ee ON e.elm_cod_estado = ee.est_el_cod
        WHERE ee.est_el_cod = 1";

        $result = $this->conn->query($query);
        $prestamos = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $prestamos[] = $row;
            }
        }
        return $prestamos;
    }
    
    public function actualizarEstadoElemento($id, $nuevo_estado) {
        $sql = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $nuevo_estado, $id);
        return $stmt->execute();
    }

}
?>
