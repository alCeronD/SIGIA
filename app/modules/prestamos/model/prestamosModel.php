<?php

class PrestamosModelo {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function obtenerPrestamos() {
        $prestamos = [];

        $sql = "SELECT * FROM prestamos";
        $resultado = $this->conn->query($sql);
        if ($resultado) {
            while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
                $prestamos[] = $fila;
            }
        } else {
            echo "Error en la consulta SQL. " . $this->conn->error;
        }
        return $prestamos;
    }

    public function insertarPrestamo($pres_fch_slcitud, $pres_fch_reserva, $pres_fch_entrega, $pres_observacion, $pres_destino, $pres_estado, $res_cod) {
        $sql = "INSERT INTO prestamos (pres_fch_slcitud, pres_fch_reserva, pres_fch_entrega, pres_observacion, pres_destino, pres_estado, res_cod)
                VALUES ('$pres_fch_slcitud', '$pres_fch_reserva', '$pres_fch_entrega', '$pres_observacion', '$pres_destino', $pres_estado, $res_cod)";
        
        $resultado = $this->conn->query($sql);

        if ($resultado) {
            return true;
        } else {
            echo "Error al insertar préstamo: " . $this->conn->error;
            return false;
        }
    }
}
?>
