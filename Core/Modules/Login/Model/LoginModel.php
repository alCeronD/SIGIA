<?php
require_once __DIR__ . '/../../../Config/Conn.php';


class LoginModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Conn)->getConnect();
    }

    public function buscarUsuarioPorDocumento($documento, $estado_user = 1)
    {

        $query = "SELECT
                    u.usu_id,
                    u.usu_docum,
                    u.usu_password,
                    u.usu_nombres,
                    u.usu_apellidos,
                    u.usu_telefono,
                    u.usu_id_estado,
                    u.usu_email,
                    r.rl_id,
                    r.rl_nombre
                FROM
                    usuarios u
                INNER JOIN
                    usuarios_roles ur ON u.usu_id = ur.usr_usu_id
                INNER JOIN
                    roles r ON ur.usr_rl_id = r.rl_id
                WHERE
                    u.usu_docum = :documento AND u.usu_id_estado = :estado_usu_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":documento", $documento);
        $stmt->bindValue(":estado_usu_id", $estado_user);

        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Error al ejecutar la consulta'];
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            return ['status' => true, 'usuario' => $result];
        }

        return ['status' => false, 'message' => 'Usuario no encontrado'];
    }

    public function verificarPassword($passwordPlano, $passwordEncriptado)
    {
        return password_verify($passwordPlano, $passwordEncriptado);
    }
}
