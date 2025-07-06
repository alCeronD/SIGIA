<?php

class login {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;    
    }

    public function buscarUsuarioPorDocumento($documento, $estado_user = 1) {
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
                    u.usu_docum = ? AND u.usu_id_estado = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $documento, $estado_user);

        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Error al ejecutar la consulta'];
        }

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ['success' => true, 'usuario' => $result->fetch_assoc()];
        }

        return ['success' => false, 'message' => 'Usuario no encontrado'];
    }

    public function verificarPassword($passwordPlano, $passwordEncriptado) {
        return password_verify($passwordPlano, $passwordEncriptado);
    }
}
