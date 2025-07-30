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

    // Función para capturar los modulos y a su ves las funciones a las cuales pertenecen
    public function getRolesPermisos()
    {
        try {
            $conn = (new Conection())->getConnect();
            $conn->begin_transaction();

            $sqlModulos = "SELECT id_m AS 'idModulo',cod_nombre_m AS 'nombreModulo' FROM modulos AS modulos";
            $stmtModulos = $conn->prepare($sqlModulos);

            if (!$stmtModulos) {
                $conn->rollback();
                $conn->close();
                return [
                    'message' => "error al preparar la consulta" . $conn->error,
                    'status' => false,
                    'data' => []
                ];
            }
            if (!$stmtModulos->execute()) {
                $conn->rollback();
                $conn->close();
                return [
                    'message' => "error al ejecutar la consulta" . $conn->error,
                    'status' => false,
                    'data' => []
                ];
            }

            $result = $stmtModulos->get_result();
            $modulos = [];
            while ($rowModulos = $result->fetch_assoc()) {
                $modulos[] = $rowModulos;
            }

            // mapModulos
            $mapModulos = [];

            // Creo el mapa de los modulos basados en el id del modulo.
            foreach ($modulos as $modulo) {
                // var_dump($key);
                // var_dump($value);
                $mapModulos[$modulo['idModulo']] = $modulo['nombreModulo'];
            }

            // var_dump($mapModulos);

            $sqlFuncionesName = "SELECT id_funcion AS 'idFuncion', nombre_funcion AS 'nmFuncion', id_modulo AS 'idModulo' FROM funciones WHERE id_modulo = ?";

            $stmtFuncionesModulos = $conn->prepare($sqlFuncionesName);

            if (!$stmtFuncionesModulos) {
                $conn->rollback();
                $conn->close();
                return [
                    'message' => "Error al preparar la consulta" . $conn->error,
                    'status' => false,
                    'data' => []
                ];
            }

            $resultModulosPermisos = [];
            foreach ($mapModulos as $id => $nombre) {
                $resultModulosPermisos[$nombre] = [];
            }

            // var_dump($resultModulosPermisos);

            $funcionesModulos = [];
            foreach ($modulos as $value) {
                $idModule = $value['idModulo'];
                $nameModule = $value['nombreModulo'];
                // var_dump($idModule);
                // var_dump($nameModule);

                $stmtFuncionesModulos->bind_param('i', $idModule);

                if (!$stmtFuncionesModulos->execute()) {
                    $conn->rollback();
                    $conn->close();
                    return [
                        'status' => false,
                        'message' => "error ejecutar la consulta" . $conn->error,
                        'data' => []
                    ];
                }

                $result = $stmtFuncionesModulos->get_result();


                $funcionesModulos[$nameModule] = [];

                while ($row = $result->fetch_assoc()) {
                    $resultModulosPermisos[$nameModule][] = [
                        'idFuncion' => $row['idFuncion'],
                        'nmFuncion' => $row['nmFuncion'],
                        'idModulo'  => $row['idModulo']
                    ];
                }

                // while($row = $result->fetch_assoc()){
                //     if ($nameModule) {
                //         // $resultadoFinal[$resultModulosPermisos[$nameModule]][] = [
                //         //     'id'=> $idModule,
                //         //     'nameModule'=> $nameModule,
                //         //     'funcionName'=> $row['nmFuncion']
                //         // ];
                //         $resultModulosPermisos[$nameModule][] = [
                //             'id'=> $idModule,
                //             'nameModule'=> $nameModule,
                //             'funcionName'=> $row['nmFuncion']
                //         ];
                //     }
                // }
            }


            $conn->commit();
            // var_dump($funcionesModulos);
            return [
                'message' => "Modulos y permisos",
                'data' => $resultModulosPermisos,
                'status' => true
            ];
        } catch (\Throwable $th) {
            echo $th->getMessage();
            return [
                'message' => "error al ejecutar el proceso" . $th->getMessage(),
                'status' => false,
                'data' => []
            ];
        }
    }
}
