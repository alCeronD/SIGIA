<?php

class RolModelo {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    // Método para obtener todos los roles
    public function obtenerRoles() {
        $roles = [];

        $sql = "SELECT * FROM roles";
        $resultado = $this->conn->query($sql);

        if ($resultado) {
            while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
                $roles[] = $fila;
            }
        } else {
            echo "❌ Error al ejecutar la consulta: " . $this->conn->error;
        }

        return $roles;
    }

    public function insertarRoles($rol_nombre){
    $sql = "INSERT INTO roles (rl_nombre) VALUES ('$rol_nombre')";
    $resultado = $this->conn->query($sql);

    if ($resultado) {
        return true;
    } else {
        echo "Error al insertar rol: " . $this->conn->error;
        return false;
    }
}

public function actualizarRol($rol_id, $rol_nombre) {
    $sql = "UPDATE roles SET rl_nombre = '$rol_nombre' WHERE rl_id = $rol_id";
    $resultado = $this->conn->query($sql);

    if ($resultado) {
        return true;
    } else {
        echo "Error al actualizar rol: " . $this->conn->error;
        return false;
    }
}

public function eliminarRol($rl_id) {
    $sql = "DELETE FROM roles WHERE rl_id = $rl_id";
    $resultado = $this->conn->query($sql);

    if ($resultado) {
        return true;
    } else {
        echo "Error al eliminar rol: " . $this->conn->error;
        return false;
    }
}



}
?>
