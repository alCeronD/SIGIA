<?php

require_once __DIR__ . "/../../../Config/conn.php";

class PermisosModel
{

    public function getModuleName(String $nombreModulo)
    {
        try {
            $conn = (new Conection())->getConnect();
            $sql = "SELECT id_m FROM modulos WHERE cod_nombre_m = ? ";
            $stmtModulo = $conn->prepare($sql);

            $stmtModulo->bind_param('s', $nombreModulo);

            if (!$stmtModulo->execute()) {
                return [
                    'message' => "error al ejecutar la consulta",
                    'status' => false,
                    'data' => []
                ];
            }

            $result = $stmtModulo->get_result();
            return [
                'message' => "id modulo encontrado",
                'data' => $result->fetch_assoc(),
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
            $conn = (new Conection())->getConnect();
            $sqlFuncion = "SELECT id_funcion
                FROM funciones f 
                INNER JOIN modulos mo ON
                mo.id_m = f.id_modulo WHERE mo.cod_nombre_m = ? AND mo.id_m = ? AND f.nombre_funcion = ?";

            $stmtFuncion = $conn->prepare($sqlFuncion);
            $stmtFuncion->bind_param('sis', $modelName, $idModulo, $functionName);

            if (!$stmtFuncion->execute()) {
                return [
                    'message' => "error al ejecutar la consulta" . $conn->error,
                    'status' => false,
                    'data' => []
                ];
            }
            $result = $stmtFuncion->get_result();
            $row = $result->fetch_assoc();
            return [
                'message' => "id encontrado",
                'status' => true,
                'data' => $row
            ];
        } catch (\Throwable $th) {
            return [
                'message' => "Error al ejecutar el proceso $th",
                'status' => false,
                'data' => []
            ];
        }
    }

    public function getPermisoFuncion(int $rolId = 0, $idFuncion)
    {
        try {
            $conn = (new Conection())->getConnect();
            // Con esta función valido que el ROL PUEDA ACCEDER A ESA FUNCIÓN, que hace parte del modulo.
            $sql = "SELECT * FROM funciones fu 
                INNER JOIN roles_funciones rf ON 
                fu.id_funcion = rf.rlp_id_funcion 
                INNER JOIN roles ro ON
                ro.rl_id = rf.rlp_id_rl 
                INNER JOIN modulos mo ON
                mo.id_m = fu.id_modulo
                WHERE ro.rl_id = ? AND rf.rlp_id_funcion = ?";

            $stmtGetPermisoFuncion = $conn->prepare($sql);

            if (!$stmtGetPermisoFuncion) {
                return [
                    'message' => 'error al preparar la consulta',
                    'status' => false,
                    'data' => []
                ];
            }

            $stmtGetPermisoFuncion->bind_param('ii', $rolId, $idFuncion);
            if (!$stmtGetPermisoFuncion->execute()) {
                return [
                    'message' => "error al preparar la consulta",
                    'status' => false,
                    'data' => []
                ];
            }

            $result = $stmtGetPermisoFuncion->get_result();
            $row = $result->fetch_assoc();

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
        $coon = (new Conection)->getConnect();


        $sqlMenu = "SELECT DISTINCT
            mo.id_m AS 'idModulo',
            mo.cod_nombre_m AS 'nombreModulo'
            FROM modulos mo 
            INNER JOIN funciones fu ON
            fu.id_modulo = mo.id_m 
            INNER JOIN roles_funciones rof ON
            rof.rlp_id_funcion = fu.id_funcion 
            INNER JOIN roles r ON
            r.rl_id = rof.rlp_id_rl WHERE r.rl_id = ?";

        $stmtMenu = $coon->prepare($sqlMenu);

        $stmtMenu->bind_param('i', $idRol);

        $stmtMenu->execute();

        $result = $stmtMenu->get_result();

        $modulosMenu = [];
        while ($row = $result->fetch_assoc()) {
            $modulosMenu[] = $row;
        }

        $sqlOptionsMenu = "SELECT DISTINCT 
            fu.nombre_funcion_user AS 'nombreFuncionUser',
            fu.id_funcion AS 'idFunción'
            FROM funciones fu
            INNER JOIN tipo_funcion tpf ON
            tpf.id_tp_funcion = fu.tp_funcion 
            INNER JOIN roles_funciones rof ON 
            rof.rlp_id_funcion = fu.id_funcion
            INNER JOIN roles r ON 
            r.rl_id = rof.rlp_id_rl 
            INNER JOIN modulos mo ON
            mo.id_m = fu.id_modulo
            WHERE tpf.id_tp_funcion = 1 AND r.rl_id = ? AND mo.id_m = ?";

        $stmtOptionsMenu = $coon->prepare($sqlOptionsMenu);

        $optionsMenu = [];
        foreach ($modulosMenu as $key => $value) {
            $modulo = $value['idModulo'];

            $stmtOptionsMenu->bind_param('ii', $idRol, $modulo);

            $stmtOptionsMenu->execute();

            $resultOptions = $stmtOptionsMenu->get_result();
            $row = $resultOptions->fetch_assoc();
            $optionsMenu[] = $row;
        }

        $data = [
            'modulos'=> $modulosMenu,
            'vistas'=> $optionsMenu
        ];

        return [
            'status'=>true,
            'message'=> 'Vistas y modulos encontrados',
            'data'=> $data
        ];


    }
}

// $objPermisosModel = new PermisosModel();
// $result = $objPermisosModel->renderMenu(16);
// var_dump($result['data']);