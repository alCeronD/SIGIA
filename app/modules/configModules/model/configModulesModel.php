<?php

require_once __DIR__ . '/../../../config/conn.php';

//Crud general para todos los elementos.
class ConfigModulesModel
{
    private $mysqli;
    private $conn;
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

        $conn->close();
        return $data;
    }

    public function insert(String $sql = '', String $types = '', array $values = [], String $tableName = '')
    {

        $conn = $this->mysqli->getConnect();
            //Consulta insert sql.
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $bindParams = [];
            foreach ($values as $key => $value) {
                $bindParams[] = &$values[$key];
            }

            array_unshift($bindParams, $types);
            call_user_func_array([$stmt, 'bind_param'], $bindParams);


        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $insertedId = $conn->insert_id;

        // Cerrar la conexión
        $conn->close();

        return true;
    }

    public function delete(String $sql, String $types, array $values)
    {

        try {

            $conn = $this->mysqli->getConnect();

            $stmt = $conn->prepare($sql);

            $refs = [];
            //Paso los valores por referencia, esto sirve para usar el call_user_func_array
            foreach ($values as $key => $value) {
                $refs[$key] = &$values[$key];
            }

            array_unshift($refs, $types);
            call_user_func_array([$stmt, 'bind_param'], $refs);
            if (!$stmt->execute()) {
                return "Error al ejecutar la consulta: $stmt->error";
            }
        } catch (mysqli_sql_exception $e) {
            return "Excepción capturada: " . $e->getMessage();
        }

        return true;
    }

    //Actualiza formando la consulta.
    public function update(String $sql, array $prepareValues, array $types)
    {
        $conn = $this->mysqli->getConnect();

        try {
            $tp = implode('', $types);
            $stmt = $conn->prepare($sql);
            $val = [];
            $val[] = $tp;

            foreach ($prepareValues as $key => $value) {
                //Uso el valor por referencia.
                $val[] = &$prepareValues[$key];
            }

            call_user_func_array([$stmt, 'bind_param'], $val);
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

    public function validateUnique(String $nameColum, String $tableName, String $nameValueColum)
    {
        $conn = $this->mysqli->getConnect();
        $sqlUnique = "SELECT `$nameColum` FROM `$tableName` WHERE `$nameColum` = ?";
        $stmtUnique = $conn->prepare($sqlUnique);

        $stmtUnique->bind_param('s',$nameValueColum);

        $stmtUnique->execute();
        $valueResult = $stmtUnique->get_result();
        $valueCompare = $valueResult->fetch_assoc();
        //Si es null, significa que no hay coincidencias, lo que signficia que no es único.
        if ($valueCompare == null) {
            return false;
        }

        return true;
        
    }
}
