<?php

require_once __DIR__ . "/../../../Config/Conn.php";


class PermisosModel
{

    public function getModuleName(String $nombreModulo)
    {
        try {
            $conn = (new Conn())->getConnect();
            $sql = "SELECT id_m FROM modulos WHERE cod_nombre_m = :nombre_modulo";
            $stmtModulo = $conn->prepare($sql);
            $stmtModulo->bindValue(":nombre_modulo", $nombreModulo, PDO::PARAM_STR);

            if (!$stmtModulo->execute()) {
                return [
                    'message' => "error al ejecutar la consulta",
                    'status' => false,
                    'data' => []
                ];
            }

            $result = $stmtModulo->fetchAll(PDO::FETCH_ASSOC);
            return [
                'message' => "id modulo encontrado",
                'data' => $result,
                'status' => true
            ];
        } catch (\Throwable $th) {
            return [
                'message' => "Error al ejecutar el proceso $th",
                'status' => false,
                'data' => []
            ];
        }
    }

    public function getIdFuncion(String $functionName = "", String $modelName = "", int $idModulo = 0)
    {
        try {
            $conn = (new Conn())->getConnect();
            $sqlFuncion = "SELECT id_funcion
                FROM funciones f
                INNER JOIN modulos mo ON
                mo.id_m = f.id_modulo WHERE mo.cod_nombre_m = :nombre_modulo AND mo.id_m = :id_modulo AND f.nombre_funcion = :nombre_funcion";

            $stmtFuncion = $conn->prepare($sqlFuncion);
            $stmtFuncion->bindValue(':nombre_modulo', $modelName, PDO::PARAM_STR);
            $stmtFuncion->bindValue(':id_modulo', $idModulo, PDO::PARAM_INT);
            $stmtFuncion->bindValue(':nombre_funcion', $functionName, PDO::PARAM_STR);


            if (!$stmtFuncion->execute()) {
                return [
                    'message' => "error al ejecutar la consulta",
                    'status' => false,
                    'data' => []
                ];
            }
            $result = $stmtFuncion->fetchAll(PDO::FETCH_ASSOC);
            return [
                'message' => "id encontrado",
                'status' => true,
                'data' => $result
            ];
        } catch (\Throwable $th) {
            return [
                'message' => "Error al ejecutar el proceso $th",
                'status' => false,
                'data' => []
            ];
        }
    }

    public function getPermisoFuncion(int $rolId, $idFuncion)
    {
        try {
            if (empty($rolId)) $rolId = 0;


            $conn = (new Conn())->getConnect();
            // Con esta función valido que el ROL PUEDA ACCEDER A ESA FUNCIÓN, que hace parte del modulo.
            $sql = "SELECT * FROM funciones fu
                INNER JOIN roles_funciones rf ON
                fu.id_funcion = rf.rlp_id_funcion
                INNER JOIN roles ro ON
                ro.rl_id = rf.rlp_id_rl
                INNER JOIN modulos mo ON
                mo.id_m = fu.id_modulo
                WHERE ro.rl_id = :rol_id AND rf.rlp_id_funcion = :id_funcion";

            $stmtGetPermisoFuncion = $conn->prepare($sql);

            if (!$stmtGetPermisoFuncion) {
                return [
                    'message' => 'error al preparar la consulta',
                    'status' => false,
                    'data' => []
                ];
            }

            $stmtGetPermisoFuncion->bindValue('rol_id', $rolId, PDO::PARAM_INT);
            $stmtGetPermisoFuncion->bindValue('id_funcion', $idFuncion, PDO::PARAM_INT);

            if (!$stmtGetPermisoFuncion->execute()) {
                return [
                    'message' => "error al preparar la consulta",
                    'status' => false,
                    'data' => []
                ];
            }

            $row = $stmtGetPermisoFuncion->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => true,
                'message' => "función y rol asociado",
                'data' => $row
            ];
        } catch (\Throwable $th) {
            return [
                'message' => 'error al ejecutar el procedimiento' . $th->getMessage(),
                'status' => false,
                'data' => []
            ];
        }
    }

    // Esta función sirve para traer los modulos basados en los roles del usuario, con el fin de poder renderizar la información de la vista.
    public function renderMenu(int $idRol)
    {
        $modulesRender = [];
        $coon = (new Conn)->getConnect();
        $sqlMenu = "SELECT DISTINCT
            mo.id_m AS 'idModulo',
            mo.nombre_modulo AS 'nombreModulo',
            mo.icono AS 'iconModulo'
            FROM modulos mo
            INNER JOIN funciones fu ON
            fu.id_modulo = mo.id_m
            INNER JOIN roles_funciones rof ON
            rof.rlp_id_funcion = fu.id_funcion
            INNER JOIN roles r ON
            r.rl_id = rof.rlp_id_rl WHERE r.rl_id = :rol_id";

        $stmtMenu = $coon->prepare($sqlMenu);

        $stmtMenu->bindValue(':rol_id', $idRol);

        $stmtMenu->execute();

        $result = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);

        $modulosMenu = [];
        foreach ($result as $key => $value) {
            $modulosMenu[] = $value;
        }
        $newModulosMenu = [];
        foreach ($modulosMenu as $key => $value) {
            $nombreModulo = $value['nombreModulo'] === 'dashboard' ?? $value['nombreModulo'];

            if ($nombreModulo === 'dashboard') {
                $newModulosMenu[$key] = $value;
            }
            $newModulosMenu[$key] = $value;
        }


        $sqlOptionsMenu = "SELECT DISTINCT
            fu.nombre_funcion_user AS 'nombreFuncionUser',
            fu.id_funcion AS 'idFunción',
            fu.nombre_funcion AS 'nombreFuncionController',
            mo.nombre_modulo AS 'nombreModulo'
            FROM funciones fu
            INNER JOIN tipo_funcion tpf ON
            tpf.id_tp_funcion = fu.tp_funcion
            INNER JOIN roles_funciones rof ON
            rof.rlp_id_funcion = fu.id_funcion
            INNER JOIN roles r ON
            r.rl_id = rof.rlp_id_rl
            INNER JOIN modulos mo ON
            mo.id_m = fu.id_modulo
            WHERE tpf.id_tp_funcion = 1 AND r.rl_id = :id_rol AND mo.id_m = :id_modulo";

        $stmtOptionsMenu = $coon->prepare($sqlOptionsMenu);
        $optionsMenu = [];
        $optionsMenuClasificado = [];
        foreach ($modulosMenu as $key => $value) {

            $modulo = $value['idModulo'];
            $moduloNombre = $value['nombreModulo'];

            $stmtOptionsMenu->bindValue(":id_rol", $idRol);
            $stmtOptionsMenu->bindValue(":id_modulo", $modulo);

            $stmtOptionsMenu->execute();
            $resultOptions = $stmtOptionsMenu->fetchAll(PDO::FETCH_ASSOC);
            $optionsMenu[] = $resultOptions;
            $optionsMenuClasificado[$moduloNombre] = $resultOptions;
        }


        $data = [
            'modulos' => $modulosMenu,
            'vistas' => $optionsMenu,
            'subMenus' => $optionsMenuClasificado
        ];



        return [
            'status' => true,
            'message' => 'Vistas y modulos encontrados',
            'data' => $data
        ];
    }
}
