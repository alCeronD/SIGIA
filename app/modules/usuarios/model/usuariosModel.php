<?php

require_once __DIR__ . '/../../../helpers/session.php';
require_once __DIR__ . '/../../../helpers/const.php';
include_once __DIR__ . '/../../../config/conn.php';


class usuarios
{
    public $usu_id;
    public $usu_docum;
    public $usu_nombres;
    public $usu_apellidos;
    public $usu_password;
    public $usu_email;
    public $usu_telefono;
    public $usu_id_estado;
    public $usu_tp_id;
    private $campos = ['usu_docum', 'usu_nombres', 'usu_apellidos', 'usu_email', 'usu_telefono'];
    private $conn;

    public function __construct()
    {

        $objConn= new Conection();
        // $this->conn = $objConn->getConnect();
        $this->conn = $objConn;
    }

    public function create(array $data = [])
    {

        // Valida si el tipo de dato recibido es de tipo array.
        if (!is_array($data)) {
            exit();
        }

        $conn = $this->conn->getConnect();


        $usu_docum     = $conn->real_escape_string($data['usu_docum']);
        $usu_nombres   = $conn->real_escape_string($data['usu_nombres']);
        $usu_apellidos = $conn->real_escape_string($data['usu_apellidos']);
        $usu_password  = password_hash($data['usu_password'], PASSWORD_DEFAULT);
        $usu_email     = $conn->real_escape_string($data['usu_email']);
        $usu_direccion = $conn->real_escape_string($data['usu_direccion']);
        $usu_telefono  = $conn->real_escape_string($data['usu_telefono']);
        $rol_id        = (int)$data['rol_id'];
        //1 activo = 2 inactivo.
        $usu_id_estado = 1;
        $usu_tp_id = (int)$data['usu_tp_id'];
        $usu_observacion = $conn->real_escape_string($data['usu_observacion']);

        $query = "INSERT INTO usuarios 
(usu_docum, usu_nombres, usu_apellidos, usu_password, usu_email, usu_direccion ,usu_telefono, usu_id_estado, usu_tp_id, usu_observacion)
VALUES 
('$usu_docum', '$usu_nombres', '$usu_apellidos', '$usu_password', '$usu_email', '$usu_direccion' ,'$usu_telefono', '$usu_id_estado','$usu_tp_id', '$usu_observacion')";

        if ($conn->query($query)) {
            $usu_id = $conn->insert_id;
            $queryRol = "INSERT INTO usuarios_roles (usr_usu_id, usr_rl_id) 
                VALUES ($usu_id, $rol_id)
            ";

            if ($conn->query($queryRol)) {
                return true;
            } else {
                return "Error al asignar el rol: " . $conn->error;
            }
        } else {
            return "Error al registrar el usuario: " . $conn->error;
        }
    }

    public function update(array $datos = [], int $id = 0)
    {

        // $rolModel = 
        $conn = $this->conn->getConnect();
        unset($datos['rol_id']);
        $datos;
        $cadena = "";

        foreach ($datos as $campo => $value) {
            $cadena .= "$campo = '$value' ,";
        }

        $cadena = trim($cadena, ",");
        $query = "UPDATE usuarios SET $cadena WHERE usu_id = '$id'";
        $resultado = $conn->query($query);

        if ($resultado) {
            return true;
        } else {
            $conn->close();
            return "Error al actualizar: " . $conn->error;
        }
    }

    public function organization($datos)
    {

        $cadena = '';
        foreach ($datos as $key => $value) {
            # code...
        }
    }

    public function search()
    {

        $conn = $this->conn->getConnect();
        $usuarios = [];
        $query = "SELECT
            u.usu_id,
            u.usu_docum,
            u.usu_nombres,
            u.usu_apellidos,
            u.usu_email,
            u.usu_telefono,
            u.usu_direccion,
            r.rl_nombre,
            ur.usr_rl_id AS 'rolIdUser',
            eu.est_nombre AS estado_usuario
        FROM
            usuarios u
        JOIN usuarios_roles ur ON
            u.usu_id = ur.usr_usu_id
        JOIN roles r ON
            ur.usr_rl_id = r.rl_id
        JOIN estados_usuarios eu ON
            u.usu_id_estado = eu.est_id;
        ";

        $resultado = $conn->query($query);
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $usuarios[] = $fila;
            }
        }

        return $usuarios;
    }

    //Busca un registro específico. basado en su id.
    public function searchU(int $id = 0, $isCedula = false)
    {
        $conn = $this->conn->getConnect();

        if (!is_int($id)) {
            exit();
        }

        $query = $isCedula ? "SELECT usu_id FROM usuarios WHERE usu_docum = ?" : "SELECT usu_id, usu_docum, usu_nombres, usu_apellidos, usu_email, usu_direccion, usu_telefono FROM usuarios WHERE usu_id = ?";

        $stmtUser = $conn->prepare($query);
        $stmtUser->bind_param("i", $id);
        if (!$stmtUser->execute()) {
            return null;
        }

        $result = $stmtUser->get_result();

        if ($result && $result->num_rows > 0) {

            return $result->fetch_assoc();
        }
    }


    public function validateEmail(string $email = "", int $id = 0)
    {
        $conn = $this->conn->getConnect();

        $query = "SELECT usu_email FROM usuarios WHERE usu_email = ? AND usu_id != ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            return true; 
        }

        $stmt->bind_param("si", $email, $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
    public function actualizarContrasena($id, $hashContrasena)
    {

        $conn = $this->conn->getConnect();
        $query = "UPDATE usuarios SET usu_password = '$hashContrasena' WHERE usu_id = '$id'";
        $resultado = $conn->query($query);

        if ($resultado) {
            return true;
        } else {
            echo "Error al actualizar la contraseña: " . $conn->error;
            return false;
        }
    }

    public function inhabilitarUsuario(int $usu_id = 0){


        $conn = $this->conn->getConnect();

         $query = "UPDATE usuarios 
                      SET usu_id_estado = CASE 
                        WHEN usu_id_estado = 1 THEN 2 
                        ELSE 1 END 
                      WHERE usu_id = ?";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $usu_id);

            if (!$stmt->execute()) {
                return null;  
            } 

            return true;
    }

    

}
