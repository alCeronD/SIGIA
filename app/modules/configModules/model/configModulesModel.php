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
    public function selectTable(String $nameTable)
    {

        $conn = $this->mysqli->getConnect();
        $sql = "SELECT * FROM $nameTable";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function insertData(String $value) {}

    public function deleteRow(int $id) {}
    public function updateRow(int $id, String $value) {}

    public function getNombre()
    {
        return 'nombre desde modelo';
    }
}
