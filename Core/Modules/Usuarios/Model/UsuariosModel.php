<?php
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;
include_once BASE_URL . '/' . CR_ROUTE_CONN;


class UsuariosModel extends Crud
{

    protected $id = "usu_id";
    protected $table = "usuarios";
    protected $campos = [
        'usu_docum',
        'usu_nombres',
        'usu_apellidos',
        'usu_password',
        'usu_email',
        'usu_direccion',
        'usu_telefono',
        'usu_observacion',
        'usu_id_estado',
        'usu_tp_id',
        'created_at',
        'updated_at'
    ];


    // public function actualizarContrasena($id, $hashContrasena)
    // {

    //     $conn = $this->conn->getConnect();
    //     $query = "UPDATE usuarios SET usu_password = '$hashContrasena' WHERE usu_id = '$id'";
    //     $resultado = $conn->query($query);

    //     if ($resultado) {
    //         return true;
    //     } else {
    //         echo "Error al actualizar la contraseña: " . $conn->error;
    //         return false;
    //     }
    // }

    // public function validateDocumento($documento)
    // {
    //     $documento = trim($documento);
    //     $conn = $this->conn->getConnect();
    //     $sql = "SELECT 1 FROM usuarios WHERE TRIM(usu_docum) = ?";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->execute([$documento]);

    //     $result = $stmt->fetch();
    //     return is_array($result);
    // }
    // public function inhabilitarUsuario(int $usu_id = 0)
    // {
    //     try {
    //         $conn = $this->conn->getConnect();

    //         if ($usu_id <= 0) {
    //             return [
    //                 'status' => false,
    //                 'message' => "El ID no debe ser negativo."
    //             ];
    //         }

    //         $validateId = $this->searchU($usu_id);
    //         $data = $validateId['data'] ?? [];

    //         if (empty($data)) {
    //             return [
    //                 'status' => false,
    //                 'message' => "ID no encontrado en la base de datos."
    //             ];
    //         }

    //         $usuId = (int) $data['usu_id'];

    //         $query = "UPDATE usuarios
    //                   SET usu_id_estado = CASE
    //                       WHEN usu_id_estado = 1 THEN 2
    //                       ELSE 1 END
    //                   WHERE usu_id = ?";

    //         $stmt = $conn->prepare($query);
    //         $stmt->bind_param("i", $usuId);

    //         if (!$stmt->execute()) {
    //             return [
    //                 'status' => false,
    //                 'message' => "Error al ejecutar la consulta: " . $stmt->error
    //             ];
    //         }

    //         return [
    //             'status' => true,
    //             'message' => "Estado del usuario cambiado correctamente."
    //         ];
    //     } catch (\Throwable $th) {
    //         return [
    //             'status' => false,
    //             'message' => "Error: " . $th->getMessage()
    //         ];
    //     }
    // }
}
