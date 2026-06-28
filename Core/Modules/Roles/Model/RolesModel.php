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
