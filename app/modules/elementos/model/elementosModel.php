<?php

class ElementoModelo {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function obtenerElementos() {
        $elementos = [];
        $sql = "SELECT * FROM elementos";
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

    public function obtenerElemento($id) {
        $sql = "SELECT * FROM elementos WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado->fetch_assoc();
    }

    public function insertarElemento($datos) {
        $sql = "INSERT INTO elementos (elm_placa, elm_nombre, elm_existencia, elm_uni_medida, elm_cod_tp_elemento, elm_cod_estado, elm_area_cod) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isiiiii", $datos['elm_placa'], $datos['elm_nombre'], $datos['elm_existencia'], $datos['elm_uni_medida'], $datos['elm_cod_tp_elemento'], $datos['elm_cod_estado'], $datos['elm_area_cod']);

        return $stmt->execute();
    }

    public function actualizarElemento($id, $datos) {
        $sql = "UPDATE elementos SET elm_placa = ?, elm_nombre = ?, elm_existencia = ?, elm_uni_medida = ?, elm_cod_tp_elemento = ?, elm_cod_estado = ?, elm_area_cod = ? WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isiiiiii", $datos['elm_placa'], $datos['elm_nombre'], $datos['elm_existencia'], $datos['elm_uni_medida'], $datos['elm_cod_tp_elemento'], $datos['elm_cod_estado'], $datos['elm_area_cod'], $id);

        return $stmt->execute();
    }

    public function eliminarElemento($id) {
        $sql = "DELETE FROM elementos WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
?>
