<?php

class RolesModel extends Crud
{

    protected $table = 'roles';
    protected $id = 'rl_id';
    protected $campos = [
        'rl_nombre',
        'rl_descripcion',
        'rl_status'
    ];


    // // Función para capturar los modulos y las funciones que pertenecen al modulo
    // public function getRolesPermisos()
    // {
    //     try {
    //         $conn = (new Conn())->getConnect();
    //         $conn->begin_transaction();

    //         /**
    //          * Primera consulta: traer los modulos
    //          */
    //         $sqlModulos = "SELECT id_m AS 'idModulo',cod_nombre_m AS 'nombre_Modulo' FROM modulos";
    //         $stmtModulos = $conn->prepare($sqlModulos);

    //         if (!$stmtModulos) {
    //             $conn->rollback();
    //             $conn->close();
    //             return [
    //                 'message' => "error al preparar la consulta" . $conn->error,
    //                 'status' => false,
    //                 'data' => []
    //             ];
    //         }
    //         if (!$stmtModulos->execute()) {
    //             $conn->rollback();
    //             $conn->close();
    //             return [
    //                 'message' => "error al ejecutar la consulta" . $conn->error,
    //                 'status' => false,
    //                 'data' => []
    //             ];
    //         }

    //         $result = $stmtModulos->get_result();
    //         $modulos = [];
    //         while ($rowModulos = $result->fetch_assoc()) {
    //             $modulos[] = $rowModulos;
    //         }

    //         // mapModulos
    //         $mapModulos = [];

    //         // Creo el mapa de los modulos basados en el id del modulo.
    //         foreach ($modulos as $modulo) {
    //             $mapModulos[$modulo['idModulo']] = $modulo['nombre_Modulo'];
    //         }

    //         /**
    //          * Summary
    //          * String $sqlFuncionesName - Traer las funciones basadas en los modulos asignados.
    //          */
    //         $sqlFuncionesName = "SELECT id_funcion AS 'idFuncion', nombre_funcion AS 'nmFuncion', id_modulo AS 'idModulo', nombre_funcion_user AS 'nmFuncionUser' FROM funciones WHERE id_modulo = ?";

    //         $stmtFuncionesModulos = $conn->prepare($sqlFuncionesName);

    //         if (!$stmtFuncionesModulos) {
    //             $conn->rollback();
    //             $conn->close();
    //             return [
    //                 'message' => "Error al preparar la consulta" . $conn->error,
    //                 'status' => false,
    //                 'data' => []
    //             ];
    //         }

    //         $resultModulosPermisos = [];
    //         foreach ($mapModulos as $id => $nombre) {
    //             $resultModulosPermisos[$nombre] = [];
    //         }

    //         $funcionesModulos = [];
    //         foreach ($modulos as $value) {
    //             $idModule = $value['idModulo'];
    //             $nameModule = $value['nombre_Modulo'];

    //             $stmtFuncionesModulos->bind_param('i', $idModule);

    //             if (!$stmtFuncionesModulos->execute()) {
    //                 $conn->rollback();
    //                 $conn->close();
    //                 return [
    //                     'status' => false,
    //                     'message' => "error ejecutar la consulta" . $conn->error,
    //                     'data' => []
    //                 ];
    //             }

    //             $result = $stmtFuncionesModulos->get_result();
    //             $funcionesModulos[$nameModule] = [];

    //             while ($row = $result->fetch_assoc()) {
    //                 // var_dump($row);
    //                 $resultModulosPermisos[$nameModule][] = [
    //                     'idFuncion' => $row['idFuncion'],
    //                     'nmFuncion' => $row['nmFuncion'],
    //                     'idModulo'  => $row['idModulo'],
    //                     'nmFuncionUser' => $row['nmFuncionUser']
    //                 ];
    //             }
    //         }


    //         $conn->commit();
    //         return [
    //             'message' => "Modulos y permisos",
    //             'data' => [
    //                 'funciones' => $resultModulosPermisos,
    //                 'modulos' => $modulos
    //             ],
    //             'status' => true
    //         ];
    //     } catch (\Throwable $th) {
    //         echo $th->getMessage();
    //         return [
    //             'message' => "error al ejecutar el proceso" . $th->getMessage(),
    //             'status' => false,
    //             'data' => []
    //         ];
    //     }
    // }


    // /**
    //  * Summary of assocPermisos - Esta función agrega los permisos al rol específico.
    //  * @param array $data
    //  * @return array{data: array, message: string, status: bool}
    //  */
    // public function assocPermisos(array $data = [])
    // {

    //     try {
    //         $conn = (new Conn())->getConnect();
    //         $conn->begin_transaction();
    //         $rolId = (int) $data['rolId'];
    //         $funciones = $data['rolesPorAsociar'];
    //         $funcionesDesseleccionadas = $data['rolesDesleccionados'];
    //         // Primera transacción, permisos ya registrados en la bd.
    //         $sqlPermisosAssoc = "SELECT rlp_id_funcion AS 'funcionesRegistradas' FROM roles_funciones WHERE rlp_id_rl = ?";
    //         $stmtPermisosAssoc = $conn->prepare($sqlPermisosAssoc);

    //         if (!$stmtPermisosAssoc) {
    //             $conn->rollback();
    //             $conn->close();
    //             return [
    //                 'status' => false,
    //                 'message' => "Error al preparar la consulta",
    //                 'data' => []
    //             ];
    //         }

    //         $stmtPermisosAssoc->bind_param('i', $rolId);
    //         if (!$stmtPermisosAssoc->execute()) {
    //             $conn->close();
    //             $conn->rollback();
    //             return [
    //                 'status' => false,
    //                 'message' => "Error al ejecutar la consulta",
    //                 'data' => []
    //             ];
    //         }

    //         $resultPermisosAssoc = $stmtPermisosAssoc->get_result();
    //         $funcionesRegistradas = [];
    //         // Guardo los ids de manera limpia para después insertar los nuevos permisos.
    //         while ($row = $resultPermisosAssoc->fetch_assoc()) {
    //             $funcionesRegistradas[] = (int) $row['funcionesRegistradas'];
    //         }

    //         // Comparo los permisos que ya existen con los que se han seleccionado desde el lado del cliente para evitar duplicados
    //         $funcionesAAgregar = [];
    //         foreach ($funciones as $key => $value) {

    //             if (!in_array($value, $funcionesRegistradas)) {

    //                 $funcionesAAgregar[] = $value;
    //             }
    //             continue;
    //         }

    //         // Segunda transacción eliminar las funciones que el usuario ha desmarcado.
    //         $sqlDeleteFuncions = "DELETE FROM roles_funciones WHERE rlp_id_rl = ? AND rlp_id_funcion = ?";
    //         $stmtDeleteFuncions = $conn->prepare($sqlDeleteFuncions);

    //         foreach ($funcionesDesseleccionadas as $key => $value) {
    //             $stmtDeleteFuncions->bind_param('ii', $rolId, $value);

    //             if (!$stmtDeleteFuncions->execute()) {
    //                 $conn->rollback();
    //                 $conn->close();
    //                 return [
    //                     'status' => false,
    //                     'message' => "error al eliminar la función" . $conn->close(),
    //                     'data' => []
    //                 ];
    //             }
    //         }

    //         // Tercera transacción insertar las funciones que se van asociar al rol.
    //         $sqlAddPermisos = "INSERT INTO roles_funciones (rlp_id_rl,rlp_id_funcion) VALUES (?,?)";
    //         $stmtAddPermisos = $conn->prepare($sqlAddPermisos);
    //         if (!$stmtAddPermisos) {
    //             $conn->close();
    //             $conn->rollback();
    //             return [
    //                 'status' => false,
    //                 'message' => "Error al preparar la consulta",
    //                 'data' => []
    //             ];
    //         }

    //         foreach ($funcionesAAgregar as $key => $value) {
    //             $stmtAddPermisos->bind_param('ii', $rolId, $value);
    //             if (!$stmtAddPermisos->execute()) {
    //                 $conn->close();
    //                 $conn->rollback();
    //                 return [
    //                     'status' => false,
    //                     'message' => "Error al ejecutar la consulta",
    //                     'data' => []
    //                 ];
    //             }
    //         }



    //         $conn->commit();
    //         $conn->close();
    //         return [
    //             'status' => true,
    //             'data' => [],
    //             'message' => 'Permisos asociados correctamente'
    //         ];
    //     } catch (\Throwable $th) {
    //         $conn->rollback();
    //         $conn->close();
    //         return [
    //             'status' => false,
    //             'message' => $th->getMessage(),
    //             'data' => []
    //         ];
    //     }
    // }
}
