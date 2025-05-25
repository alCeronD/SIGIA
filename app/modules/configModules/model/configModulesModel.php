<?php


// Este es el modelo que tendrá el crud general para las tablas Categoría, tipoDocumento,Area, Marca.
require_once __DIR__ . '/../../../config/conn.php';

//Crud general para todos los elementos.
class ConfigModulesModel
{
    private $mysqli;

    //puede que no necesite constructor.
    public function __construct()
    {
        $this->mysqli = new Conection();
    }
    public function select(String $sql)
    {

        $conn = $this->mysqli->getConnect();

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function insert(String $value, String $tableName)
    {
        $conn = $this->mysqli->getConnect();
        $sql = "INSERT INTO $tableName values ($value)";

        $stmt = $conn->prepare($sql);
        if (!$stmt->execute()) {
            return null;
        }

        return true;
    }

    public function delete(int $tableName, String $fila, String $pk, String $value, String $pkValue)
    {
        $conn = $this->mysqli->getConnect();

        $sql = "UPDATE $tableName SET $fila = $value WHERE $pk = $pkValue";
        //$stmt = $conn->prepare($sql);
        var_dump($sql);
    }

    //Actualiza formando la consulta.
    public function update(String $sql, array $prepareValues, array $types)
    {
        $conn = $this->mysqli->getConnect();

        try {
            $tp = implode('',$types);
            $stmt = $conn->prepare($sql);
            $val = [];
            $val[] = $tp;

            foreach ($prepareValues as $key => $value) {
                //Uso el valor por referencia.
                $val[] = &$prepareValues[$key];
            }

            call_user_func_array([$stmt,'bind_param'],$val);
            if (!$stmt->execute()) {
                return false;
            }

        
        } catch (PDOException $th) {
            return $th->getMessage();
        }

        //Cerrar conexión.
        $stmt->close();

        return true;
        

    }

}
