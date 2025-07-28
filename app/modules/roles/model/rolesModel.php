<?php
include_once __DIR__ . '/../../../config/conn.php';

class RolModelo
{


    public function __construct()
    {
        // Ya no se necesita guardar la conexión en $this->conn
    }

    // Método para obtener todos los roles
    public function obtenerRoles()
    {
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

    public function obtenerRol(int $id = 0)
    {
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

    public function insertarRoles($rol_nombre, $rol_descripcion)
    {
        try {
            $conn = (new Conection())->getConnect();
            $rol_status = 1;

            $sql = "INSERT INTO roles (rl_nombre, rl_descripcion, rl_status) 
                VALUES (?, ?, ?)";

            $stmtAddRol = $conn->prepare($sql);
            if (!$stmtAddRol) {
                $conn->close();
                return [
                    'message' => "Error al preparar la consulta: " . $conn->error,
                    'status' => false,
                    'data' => []
                ];
            }

            // 'ssi': string, string, integer
            $stmtAddRol->bind_param('ssi', $rol_nombre, $rol_descripcion, $rol_status);

            if (!$stmtAddRol->execute()) {
                $conn->close();
                return [
                    'message' => "Error al ejecutar la consulta: " . $stmtAddRol->error,
                    'status' => false,
                    'data' => []
                ];
            }

            // Opcional: obtener el ID insertado si lo necesitas
            $insertId = $stmtAddRol->insert_id;

            $conn->close();
            return [
                'message' => "Rol insertado correctamente",
                'status' => true,
                'data' => [$insertId]
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th,
                'data' => []
            ];
        }
    }

    public function actualizarRol($rol_id, $rol_nombre, $rol_descripcion)
    {

        try {
            $conn = (new Conection())->getConnect();

            $sql = "UPDATE roles SET rl_nombre = ?, rl_descripcion = ?
                    WHERE rl_id = ?";

            $stmtSql = $conn->prepare($sql);
            if (!$stmtSql) {
                $conn->close();
                return [
                    'message' => "Error al preparar la consulta" . $conn->error,
                    'status' => false,
                    'data' => []
                ];
            }

            $stmtSql->bind_param('ssi', $rol_nombre, $rol_descripcion, $rol_id);

            if (!$stmtSql->execute()) {
                $conn->close();
                return [
                    'message' => "error al ejecutar la consulta" . $conn->error,
                    'status' => false,
                    'data' => []
                ];
            }

            return [
                'message' => "recurso actualizado",
                'status' => true,
                'data' => [1]
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th,
                'data' => []
            ];
        }
    }

    public function eliminarRol($rl_id, $status)
    {

        try {
            $conn = (new Conection())->getConnect();
            $sql = "UPDATE roles SET rl_status = ? WHERE rl_id = ?";
            $stmtRol = $conn->prepare($sql);
            if (!$stmtRol) {
                $conn->close();
                return
                    [
                        'status' => false,
                        'message' => "error al preparar consulta" . $conn->error,
                        'data' => []
                    ];
            }

            $stmtRol->bind_param('ii', $status, $rl_id);

            if (!$stmtRol->execute()) {
                $conn->close();
                return [
                    'message' => "error al ejecutar la consulta",
                    'status' => false,
                    'data' => []
                ];
            }
            return [
                'message' => 'recurso actualizado',
                'status' => true,
                'data' => []
            ];
        } catch (\Throwable $e) {
            return [
                'message' => "errror al ejecutar el procedimiento" . $e->getMessage(),
                'status' => false,
                'data' => []
            ];
        }
    }

    // Actualizo rol del usuario
    public function actRolUser($id_user, $rol_id)
    {
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
