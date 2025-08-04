<?php

class categorias
{
    public $ca_id;
    public $ca_nombre;
    public $ca_descripcion;
    public $ca_status;
    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }

    public function create($data)
    {
        try {
            $query = "INSERT INTO categoria (ca_nombre, ca_descripcion, ca_status) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssi", $data['ca_nombre'], $data['ca_descripcion'], $data['ca_status']);

            if ($stmt->execute()) {
                return $stmt->insert_id; // Devuelve el ID insertado
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }



    public function update($datos, $id)
    {

        $datos;
        $cadena = "";

        foreach ($datos as $campo => $value) {
            $cadena .= "$campo = '$value' ,";
        }

        $cadena = trim($cadena, ",");
        $query = "UPDATE categoria SET $cadena WHERE ca_id = '$id'";
        // dd($query);
        $resultado = $this->conn->query($query);

        if ($resultado) {
            return true;
        } else {
            return "Error al actualizar: " . $this->conn->error;
        }
    }

    public function delete(int $id)
    {

        $query = "DELETE FROM categoria WHERE ca_id = '$id'";
        $resultado = $this->conn->query($query);

        if ($resultado) {
            return true;
        } else {
            echo "Problemas al eliminar el registro";
            exit();
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
    public function searchU(int $id)
    {

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
        } else {
            return null;
        }
    }

    public function getLastInsertedId()
    {
        return $this->conn->insert_id;
    }

    public function findById($id)
    {
        $query = "SELECT * FROM categoria WHERE ca_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function contarCategorias()
    {
        $sql = "SELECT COUNT(*) as total FROM categoria";
        $result = $this->conn->query($sql);
        return $result[0]['total'];
    }

    public function listarPaginado($offset, $limite)
    {
        $sql = "SELECT * FROM categoria LIMIT $offset, $limite";
        return $this->conn->query($sql);
    }

    // Contar total de registros
    public function contarTotal()
    {
        $query = "SELECT COUNT(*) as total FROM categoria";
        $resultado = $this->conn->query($query);
        $fila = $resultado->fetch_assoc();
        return (int)$fila['total'];
    }

    // Obtener registros paginados
    public function listarPaginadas($offset, $limite)
    {
        $query = "SELECT * FROM categoria LIMIT $offset, $limite";
        $resultado = $this->conn->query($query);
        $categorias = [];

        while ($fila = $resultado->fetch_assoc()) {
            $categorias[] = $fila;
        }

        return $categorias;
    }

    public function actualizarEstado(int $id, int $estado)
    {
        $query = "UPDATE categoria SET ca_status = ? WHERE ca_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $estado, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return "Error: " . $this->conn->error;
        }
    }
}
