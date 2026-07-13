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
        'usu_tp_id'
    ];


    // public $usu_id;
    // public $usu_docum;
    // public $usu_nombres;
    // public $usu_apellidos;
    // public $usu_password;
    // public $usu_email;
    // public $usu_telefono;
    // public $usu_id_estado;
    // public $usu_tp_id;
    // private $campos = ['usu_docum', 'usu_nombres', 'usu_apellidos', 'usu_email', 'usu_telefono'];
    // private $conn;

    // public function organization($datos)
    // {

    //     $cadena = '';
    //     foreach ($datos as $key => $value) {
    //         # code...
    //     }
    // }

    // public function search()
    // {

    //     $conn = $this->conn->getConnect();
    //     $usuarios = [];
    //     $query = "SELECT
    //         u.usu_id,
    //         u.usu_docum,
    //         u.usu_nombres,
    //         u.usu_apellidos,
    //         u.usu_email,
    //         u.usu_telefono,
    //         u.usu_direccion,
    //         r.rl_nombre,
    //         ur.usr_rl_id AS 'rolIdUser',
    //         eu.est_nombre AS estado_usuario
    //     FROM
    //         usuarios u
    //     JOIN usuarios_roles ur ON
    //         u.usu_id = ur.usr_usu_id
    //     JOIN roles r ON
    //         ur.usr_rl_id = r.rl_id
    //     JOIN estados_usuarios eu ON
    //         u.usu_id_estado = eu.est_id;
    //     ";

    //     $resultado = $conn->query($query);
    //     if ($resultado && $resultado->num_rows > 0) {
    //         while ($fila = $resultado->fetch_assoc()) {
    //             $usuarios[] = $fila;
    //         }
    //     }

    //     return $usuarios;
    // }
    // public function validateEmail(string $email = "", $identifier = 0, bool $isId = true): bool
    // {
    //     $conn = $this->conn->getConnect();
    //     $query = $isId
    //         ? "SELECT usu_id FROM usuarios WHERE usu_email = ? AND usu_id != ?"
    //         : "SELECT usu_id FROM usuarios WHERE usu_email = ? AND usu_docum != ?";


    //     $stmt = $conn->prepare($query);
    //     $paramType = $isId ? "si" : "ss";
    //     $stmt->bind_param($paramType, $email, $identifier);

    //     if (!$stmt->execute()) {
    //         return false;
    //     }
    //     $result = $stmt->get_result();

    //     return $result->num_rows > 0;
    // }
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
    // public function searchU(int $id = 0, $isCedula = false)
    // {
    //     $conn = $this->conn->getConnect();

    //     if (!is_int($id)) {
    //         return [
    //             'message' => "id no definido",
    //             'status' => false
    //         ];
    //     }

    //     $query = $isCedula
    //         ? "SELECT usu_id FROM usuarios WHERE usu_docum = ?"
    //         : "SELECT usu_id, usu_docum, usu_nombres, usu_apellidos, usu_email, usu_direccion, usu_telefono FROM usuarios WHERE usu_id = ?";

    //     $stmtUser = $conn->prepare($query);
    //     $stmtUser->bind_param("i", $id);
    //     if (!$stmtUser->execute()) {
    //         return [
    //             'message' => 'error al ejecutar la consulta',
    //             'status' => false
    //         ];
    //     }

    //     $result = $stmtUser->get_result();

    //     if ($result && $result->num_rows > 0) {
    //         return [
    //             'data' => $result->fetch_assoc(),
    //             'message' => 'registro encontrado',
    //             'status' => true
    //         ];
    //     } else {
    //         return [
    //             'data' => [],
    //             'message' => "no hay registro",
    //             'status' => false
    //         ];
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
