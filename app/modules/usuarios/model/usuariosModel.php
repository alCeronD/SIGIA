<?php

include_once '../proyecto_sigia/app/config/conn.php';

class usuarios {
    public $usu_id;
    public $usu_docum;
    public $usu_nombres;
    public $usu_apellidos;
    public $usu_password;
    public $usu_email;
    public $usu_telefono;
    public $usu_id_estado;
    public $usu_tp_id;
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function create($data) {
        $usu_docum     = $this->conn->real_escape_string($data['usu_docum']);
        $usu_nombres   = $this->conn->real_escape_string($data['usu_nombres']);
        $usu_apellidos = $this->conn->real_escape_string($data['usu_apellidos']);
        $usu_password  = password_hash($data['usu_password'], PASSWORD_DEFAULT);
        $usu_email     = $this->conn->real_escape_string($data['usu_email']);
        $usu_telefono  = $this->conn->real_escape_string($data['usu_telefono']);
        $rol_id        = (int)$data['rol_id'];
    
        $query = "
            INSERT INTO usuarios 
            (usu_docum, usu_nombres, usu_apellidos, usu_password, usu_email, usu_telefono)
            VALUES 
            ('$usu_docum', '$usu_nombres', '$usu_apellidos', '$usu_password', '$usu_email', '$usu_telefono')
        ";
    
        if ($this->conn->query($query)) {
            $usu_id = $this->conn->insert_id;
    
            $queryRol = "
                INSERT INTO usuarios_roles (usu_id, usr_rl_id) 
                VALUES ($usu_id, $rol_id)
            ";
    
            if ($this->conn->query($queryRol)) {
                return true;
            } else {
                return "Error al asignar el rol: " . $this->conn->error;
            }
        } else {
            return "Error al registrar el usuario: " . $this->conn->error;
        }
    }

    public function update() {
        dd("llego a update modelo");
    }
    
    public function search() {
        $usuarios = [];

        $query = "
            SELECT 
                u.usu_nombres,
                u.usu_apellidos,
                r.rl_nombre
            FROM 
                usuarios u
            JOIN 
                usuarios_roles ur ON u.usu_id = ur.usu_id
            JOIN 
                roles r ON ur.usr_rl_id = r.rl_id
        ";
    
        $resultado = $this->conn->query($query);
    
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $usuarios[] = $fila;
            }
        }
    
        return $usuarios;
        }
    }
?>
