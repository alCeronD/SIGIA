<?php

class categorias {    
    public $ca_id;
    public $ca_nombre;
    public $ca_descripcion;
    public $ca_status;
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function create(array $data = []) {
        if (!is_array($data)) {
            return "Datos inválidos";
        }
    
        $ca_nombre = $this->conn->real_escape_string($data['ca_nombre']);
        $ca_descripcion = $this->conn->real_escape_string($data['ca_descripcion']);
        $ca_status = (int)$data['ca_status'];
    
        $query = "
            INSERT INTO categoria (ca_nombre, ca_descripcion, ca_status)
            VALUES ('$ca_nombre', '$ca_descripcion', $ca_status)
        ";
    
        if ($this->conn->query($query)) {
            $insertedId = $this->conn->insert_id;
    
            return [
                'ca_id' => $insertedId,
                'ca_nombre' => $data['ca_nombre'],
                'ca_descripcion' => $data['ca_descripcion'],
                'ca_status' => $ca_status
            ];
        } else {
            return "Error al registrar la categoría: " . $this->conn->error;
        }
    }




    public function update($datos,$id) {
        
        $datos;
        $cadena = "";
        
        foreach ($datos as $campo => $value) {
            $cadena .= "$campo = '$value' ,";
        }
        
        $cadena = trim($cadena,",");
        $query ="UPDATE categoria SET $cadena WHERE ca_id = '$id'";
        // dd($query);
        $resultado = $this->conn->query($query);
        
        if ($resultado) {
            return true;
        }else {
            return "Error al actualizar: " . $this->conn->error;
        }
        
    }
    
    public function delete(int $id){
        
        $query = "DELETE FROM categoria WHERE ca_id = '$id'";
        $resultado = $this->conn->query($query);
        
        if ($resultado) {
            return true;
        }else {
            echo "Problemas al eliminar el registro";
            exit();
        }
    }
    
    public function organization($datos){
        $cadena = '';
        foreach ($datos as $key => $value) {
            # code...
        }
    }
    
    public function search() {
        $query = "SELECT ca_id, ca_nombre, ca_descripcion, ca_status FROM categoria";
        $result = $this->conn->query($query);

        $categorias = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categorias[] = $row;
            }
        }

        return $categorias;
    }
        
    //Busca un registro específico.
    public function searchU(int $id){

        if (!is_int($id)) {
            exit();
        }

        if ($id) {
        $query = "SELECT ca_id, ca_nombre, ca_descripcion, ca_status FROM categoria WHERE ca_id = $id";
        $resultado = $this->conn->query($query);
        
            if ($resultado && $resultado->num_rows > 0) {
                return $resultado->fetch_assoc();
            } else {
                return null; 
            }
        }else {
            return null; 
        }
    }
    
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

}

    

?>
