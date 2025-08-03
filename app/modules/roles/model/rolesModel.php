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

    // Función para capturar los modulos y las funciones que pertenecen al modulo
    public function getRolesPermisos()
    {
        try {
            $conn = (new Conection())->getConnect();
            $conn->begin_transaction();

            /**
             * Primera consulta: traer los modulos
             */
            $sqlModulos = "SELECT id_m AS 'idModulo',cod_nombre_m AS 'nombre_Modulo' FROM modulos";
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
                $mapModulos[$modulo['idModulo']] = $modulo['nombre_Modulo'];
            }

            /**
             * Summary 
             * String $sqlFuncionesName - Traer las funciones basadas en los modulos asignados.
             */
            $sqlFuncionesName = "SELECT id_funcion AS 'idFuncion', nombre_funcion AS 'nmFuncion', id_modulo AS 'idModulo', nombre_funcion_user AS 'nmFuncionUser' FROM funciones WHERE id_modulo = ?";

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

            $funcionesModulos = [];
            foreach ($modulos as $value) {
                $idModule = $value['idModulo'];
                $nameModule = $value['nombre_Modulo'];

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
                    // var_dump($row);
                    $resultModulosPermisos[$nameModule][] = [
                        'idFuncion' => $row['idFuncion'],
                        'nmFuncion' => $row['nmFuncion'],
                        'idModulo'  => $row['idModulo'],
                        'nmFuncionUser'=> $row['nmFuncionUser']
                    ];
                }


            }


            $conn->commit();
            return [
                'message' => "Modulos y permisos",
                'data' => [
                    'funciones' => $resultModulosPermisos,
                    'modulos' => $modulos
                ],
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

    // Función para capturar las funciones que están asociadas al rol.
    public function getPermisosFuncion(int $rolId = 0)
    {
        try {
            $conn = (new Conection())->getConnect();
            // Con esta función valido que el ROL PUEDA ACCEDER A ESA FUNCIÓN, que hace parte del modulo.
            $sql = "SELECT 
                fu.id_funcion as 'idFuncion',
fu.nombre_funcion as 'nombreFunción'
            FROM funciones fu 
                INNER JOIN roles_funciones rf ON 
                fu.id_funcion = rf.rlp_id_funcion 
                INNER JOIN roles ro ON
                ro.rl_id = rf.rlp_id_rl 
                INNER JOIN modulos mo ON
                mo.id_m = fu.id_modulo
                WHERE ro.rl_id = ?";

            $stmtGetPermisoFuncion = $conn->prepare($sql);

            if (!$stmtGetPermisoFuncion) {
                return [
                    'message' => 'error al preparar la consulta',
                    'status' => false,
                    'data' => []
                ];
            }

            $stmtGetPermisoFuncion->bind_param('i', $rolId);
            if (!$stmtGetPermisoFuncion->execute()) {
                return [
                    'message' => "error al preparar la consulta",
                    'status' => false,
                    'data' => []
                ];
            }

            $result = $stmtGetPermisoFuncion->get_result();
            // Funciones asociadas al rol
            $funcionAssoc = [];
            while ($row = $result->fetch_assoc()) {
                $funcionAssoc[] = $row;
            }


            return [
                'status' => true,
                'message' => "función y rol asociado",
                'data' => $funcionAssoc
            ];
        } catch (\Throwable $th) {
            return [
                'message' => 'error al ejecutar el procedimiento' . $th->getMessage(),
                'status' => false,
                'data' => []
            ];
        }
    }
    /**
     * Summary of assocPermisos - Esta función agrega los permisos al rol específico.
     * @param array $data
     * @return array{data: array, message: string, status: bool}
     */
    public function assocPermisos(array $data = [])
    {

        try {
            $conn = (new Conection())->getConnect();
            $conn->begin_transaction();
            $rolId = (int) $data['rolId'];
            $funciones = $data['rolesPorAsociar'];
            $funcionesDesseleccionadas = $data['rolesDesleccionados'];
            // Primera transacción, permisos ya registrados en la bd.
            $sqlPermisosAssoc = "SELECT rlp_id_funcion AS 'funcionesRegistradas' FROM roles_funciones WHERE rlp_id_rl = ?";
            $stmtPermisosAssoc = $conn->prepare($sqlPermisosAssoc);

            if (!$stmtPermisosAssoc) {
                $conn->rollback();
                $conn->close();
                return [
                    'status' => false,
                    'message' => "Error al preparar la consulta",
                    'data' => []
                ];
            }

            $stmtPermisosAssoc->bind_param('i', $rolId);
            if (!$stmtPermisosAssoc->execute()) {
                $conn->close();
                $conn->rollback();
                return [
                    'status' => false,
                    'message' => "Error al ejecutar la consulta",
                    'data' => []
                ];
            }

            $resultPermisosAssoc = $stmtPermisosAssoc->get_result();
            $funcionesRegistradas = [];
            // Guardo los ids de manera limpia para después insertar los nuevos permisos.
            while ($row = $resultPermisosAssoc->fetch_assoc()) {
                $funcionesRegistradas[] = (int) $row['funcionesRegistradas'];
            }

            // Comparo los permisos que ya existen con los que se han seleccionado desde el lado del cliente para evitar duplicados
            $funcionesAAgregar = [];
            foreach ($funciones as $key => $value) {

                if (!in_array($value, $funcionesRegistradas)) {

                    $funcionesAAgregar[] = $value;
                }
                continue;
            }

            // Segunda transacción eliminar las funciones que el usuario ha desmarcado.
            $sqlDeleteFuncions = "DELETE FROM roles_funciones WHERE rlp_id_rl = ? AND rlp_id_funcion = ?";
            $stmtDeleteFuncions = $conn->prepare($sqlDeleteFuncions);

            foreach ($funcionesDesseleccionadas as $key => $value) {
                $stmtDeleteFuncions->bind_param('ii', $rolId, $value);

                if (!$stmtDeleteFuncions->execute()) {
                    $conn->rollback();
                    $conn->close();
                    return [
                        'status' => false,
                        'message' => "error al eliminar la función" . $conn->close(),
                        'data' => []
                    ];
                }
            }

            // Tercera transacción insertar las funciones que se van asociar al rol.
            $sqlAddPermisos = "INSERT INTO roles_funciones (rlp_id_rl,rlp_id_funcion) VALUES (?,?)";
            $stmtAddPermisos = $conn->prepare($sqlAddPermisos);
            if (!$stmtAddPermisos) {
                $conn->close();
                $conn->rollback();
                return [
                    'status' => false,
                    'message' => "Error al preparar la consulta",
                    'data' => []
                ];
            }

            foreach ($funcionesAAgregar as $key => $value) {
                $stmtAddPermisos->bind_param('ii', $rolId, $value);
                if (!$stmtAddPermisos->execute()) {
                    $conn->close();
                    $conn->rollback();
                    return [
                        'status' => false,
                        'message' => "Error al ejecutar la consulta",
                        'data' => []
                    ];
                }
            }

            

            $conn->commit();
            $conn->close();
            return [
                'status' => true,
                'data' => [],
                'message' => 'Permisos asociados correctamente'
            ];
        } catch (\Throwable $th) {
            $conn->rollback();
            $conn->close();
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ];
        }
    }
}
