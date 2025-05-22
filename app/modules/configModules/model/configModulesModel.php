<?php 

use Dba\Connection;

// Este es el modelo que tendrá el crud general para las tablas Categoría, tipoDocumento,Area, Marca.
require_once __DIR__ . '/../../../config/conn.php';

//Crud general para todos los elementos.
class ConfigModulesModel{
    private $mysqli;

    //puede que no necesite constructor.
    public function __construct(){
        $conn = new Conection();

        $this->mysqli = $conn->getConnect();
    }
    public function selectTable(){

    }

    public function insertData(){

    }

    public function deleteRow(){

    }
    public function updateRow(){


    }
}


?>