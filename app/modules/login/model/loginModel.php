<?php

class login {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;    
    }

    public function loginValidation($password, $documento) {
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

        $estado_user = 1;
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $documento, $estado_user);

        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Error al ejecutar la consulta'];
        }

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
        
            if (password_verify($password, $usuario['usu_password'])) {
                return [
                    'success' => true,
                    'usuario' => $usuario
                    
                ];
            } else {
                return ['success' => false, 'message' => 'Contraseña incorrecta'];
            }
        } else {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }
    }
}
