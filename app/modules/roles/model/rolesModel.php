<?php
include_once __DIR__ . '/../../../config/conn.php';

class RolModelo {

    public function __construct() {
        // Ya no se necesita guardar la conexión en $this->conn
    }

    // Método para obtener todos los roles
    public function obtenerRoles() {
        $roles = [];

        $conn = (new Conection())->getConnect();
        $sql = "SELECT * FROM roles";
        $resultado = $conn->query($sql);

        if ($resultado) {
            while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
                $roles[] = $fila;
            }
        }

        $conn->close();
        return $roles;
    }

    public function obtenerRol(int $id = 0) {
        if (!is_int($id) || !$id) {
            return null;
        }

        $conn = (new Conection())->getConnect();
        $query = "SELECT * FROM roles WHERE rl_id = $id";
        $resultado = $conn->query($query);

        $rol = null;
        if ($resultado && $resultado->num_rows > 0) {
            $rol = $resultado->fetch_assoc();
        }

        $conn->close();
        return $rol;
    }

    public function insertarRoles($rol_nombre, $rol_descripcion) {
        $conn = (new Conection())->getConnect();
        $rol_status = 1;
        $sql = "INSERT INTO roles (rl_nombre, rl_descripcion, rl_status) 
                VALUES ('$rol_nombre', '$rol_descripcion', '$rol_status')";
        $resultado = $conn->query($sql);

        if ($resultado) {
            $conn->close();
            return true;
        } else {
            echo "Error al insertar rol: " . $conn->error;
            $conn->close();
            return false;
        }
    }

    public function actualizarRol($rol_id, $rol_nombre, $rol_descripcion) {
        $conn = (new Conection())->getConnect();
        $sql = "UPDATE roles SET rl_nombre = '$rol_nombre', rl_descripcion = '$rol_descripcion' 
                WHERE rl_id = $rol_id";
        $resultado = $conn->query($sql);

        if ($resultado) {
            $conn->close();
            return true;
        } else {
            echo "Error al actualizar rol: " . $conn->error;
            $conn->close();
            return false;
        }
    }

    public function eliminarRol($rl_id, $status) {
        $conn = (new Conection())->getConnect();
        $sql = "UPDATE roles SET rl_status = $status WHERE rl_id = $rl_id";
        $resultado = $conn->query($sql);

        if ($resultado) {
            $conn->close();
            return true;
        } else {
            echo "Error al eliminar rol: " . $conn->error;
            $conn->close();
            return false;
        }
    }

    // Actualizo rol del usuario
    public function actRolUser($id_user, $rol_id) {
        $conn = (new Conection())->getConnect();
        $query = "UPDATE usuarios_roles SET usr_rl_id = '$rol_id' WHERE usr_usu_id = '$id_user'";
        $resultado = $conn->query($query);

        if ($resultado) {
            $conn->close();
            return true;
        } else {
            echo "Error al actualizar el rol del usuario: " . $conn->error;
            $conn->close();
            return false;
        }
    }
}

?>
