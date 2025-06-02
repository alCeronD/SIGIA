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
            echo " Error al ejecutar la consulta: " . $this->conn->error;
        }

        return $roles;
    }

    public function obtenerRol(int $id = 0){

        if (!is_int($id)) {
            exit();
        }

        if ($id) {
        $query = "SELECT * FROM roles WHERE rl_id = $id";
        $resultado = $this->conn->query($query);
        
            if ($resultado && $resultado->num_rows > 0) {
                return $resultado->fetch_assoc();
            } else {
                return null; 
            }
        }else {
            return null; 
        }
    }

    public function insertarRoles($rol_nombre,$rol_descripcion){
        $rol_status = 1;
        $sql = "INSERT INTO roles (rl_nombre,rl_descripcion,rl_status) VALUES ('$rol_nombre', '$rol_descripcion','$rol_status')";
        $resultado = $this->conn->query($sql);
    if ($resultado) {
        return true;
    } else {
        echo "Error al insertar rol: " . $this->conn->error;
        return false;
    }
}

public function actualizarRol($rol_id, $rol_nombre,$rol_descripcion) {
    $sql = "UPDATE roles SET rl_nombre = '$rol_nombre', rl_descripcion = '$rol_descripcion' WHERE rl_id = $rol_id";
    $resultado = $this->conn->query($sql);

    if ($resultado) {
        return true;
    } else {
        echo "Error al actualizar rol: " . $this->conn->error;
        return false;
    }
}

public function eliminarRol($rl_id,$status) {
    $sql = "UPDATE roles SET rl_status =$status WHERE rl_id = $rl_id";
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
