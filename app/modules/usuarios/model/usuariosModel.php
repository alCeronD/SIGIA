<?php


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
    private $campos =['usu_docum','usu_nombres','usu_apellidos','usu_email','usu_telefono'];
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function create(array $data = []) {

        // Valida si el tipo de dato recibido es de tipo array.
        if (!is_array($data)) {
           exit();
        }

        $usu_docum     = $this->conn->real_escape_string($data['usu_docum']);
        $usu_nombres   = $this->conn->real_escape_string($data['usu_nombres']);
        $usu_apellidos = $this->conn->real_escape_string($data['usu_apellidos']);
        $usu_password  = password_hash($data['usu_password'], PASSWORD_DEFAULT);
        $usu_email     = $this->conn->real_escape_string($data['usu_email']);
        $usu_direccion = $this->conn->real_escape_string($data['usu_direccion']);
        $usu_telefono  = $this->conn->real_escape_string($data['usu_telefono']);
        $rol_id        = (int)$data['rol_id'];
        //1 activo = 2 inactivo.
        $usu_id_estado = 1;
        $usu_tp_id = 3;

        $query = "
            INSERT INTO usuarios 
            (usu_docum, usu_nombres, usu_apellidos, usu_password, usu_email, usu_direccion ,usu_telefono,usu_id_estado,usu_tp_id)
            VALUES 
            ('$usu_docum', '$usu_nombres', '$usu_apellidos', '$usu_password', '$usu_email', '$usu_direccion' ,'$usu_telefono', '$usu_id_estado','$usu_tp_id')
        ";

        if ($this->conn->query($query)) {
            $usu_id = $this->conn->insert_id;
    
            $queryRol = "
                INSERT INTO usuarios_roles (usr_usu_id, usr_rl_id) 
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

    public function update($datos,$id) {
        
        $datos;
        $cadena = "";
        
        foreach ($datos as $campo => $value) {
            $cadena .= "$campo = '$value' ,";
        }

        $cadena = trim($cadena,",");
        $query ="UPDATE usuarios SET $cadena WHERE usu_id = '$id'";
        // dd($query);
        $resultado = $this->conn->query($query);
        
        if ($resultado) {
            return true;
        }else {
            return "Error al actualizar: " . $this->conn->error;
        }
        
    }
    
    public function organization($datos){
        $cadena = '';
        foreach ($datos as $key => $value) {
            # code...
        }
    }
    
    public function search() {
        $usuarios = [];
        
        $query = "
            SELECT 
                u.usu_id,
                u.usu_docum,
                u.usu_nombres,
                u.usu_apellidos,
                u.usu_email,
                u.usu_telefono,
                r.rl_nombre
            FROM 
                usuarios u
            JOIN 
                usuarios_roles ur ON u.usu_id = ur.usr_usu_id
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
        
    //Busca un registro específico.
    public function searchU(int $id=0){

        if (!is_int($id)) {
            exit();
        }

        if ($id) {
       $query = "SELECT 
                    usu_id,
                    usu_docum,
                    usu_nombres,
                    usu_apellidos,
                    usu_email,
                    usu_telefono
                FROM usuarios WHERE usu_id = '$id'";
        $resultado = $this->conn->query($query);
        // var_dump($resultado);
        
            if ($resultado && $resultado->num_rows > 0) {
                return $resultado->fetch_assoc();
            } else {
                return null; 
            }
        }else {
            return null; 
        }
    }
}

    

?>
