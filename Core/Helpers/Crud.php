<?php

require_once __DIR__ . '/../Config/Conn.php';

#Clase crud para crear toda la estructura general de consultas.
/**
 * Listado de funciones requeridas
 * select
 * insert
 * order by
 * group by
 *
 */

abstract class Crud {
  protected $conn;
  protected $sql;
  protected $table;
  protected $campos;
  protected $id;

  public function __construct() {
    $this->conn = (new Conn)->getConnect();
  }

  # Función para transformar el arreglo de campos en cadenas de string
  public function organizarCampos(array $datos=["*"]){
    $cadena = "";
    foreach ($datos as $dta) {
      // echo $datos;
      $cadena .= $dta.", ";
    }

    return trim($cadena,", ");
  }

  # Funcion para organizar los campos de los datos de la tabla en caso de que los vamos a realizar proceso transaccional
  public function organizarDatos($datos){
    $string = "";

    // colocar un validador adicional, los valores deben de venir de forma arreglo asociativo, en donde su clave debe ser el nombre de la tabla y el value, el valor a insertar.
    foreach ($datos as $camp) {
      $string .= "'". $camp. "', ";
    }
    return trim($string,", ");
  }

  public function organizarDatosUpdate(){

  }

  # Función para definir la estructura select
  public function select(){
    $this->sql = "SELECT ".$this->organizarCampos($this->campos)." FROM ".$this->table;
  }

  public function insert(array $insertValue){
    $this->sql = "INSERT INTO ".$this->table." (".$this->organizarCampos($this->campos).") VALUES (".$this->organizarDatos($insertValue).")";
  }

  public function delete(int $valueId){
    $this->sql = "DELETE FROM ".$this->table. " WHERE ".$this->id. "= ".$valueId;
  }

  public function update($valuesUpdate){

  }

  # Obtener el resultado sql y devolverlo
  public function get(){
    try {
      $sql = $this->conn->prepare($this->sql);
      $sql->execute();
      $select = explode(' ',$this->sql);

      # Valido si la sentencia es de tipo select para devolver un arreglo asociativo con la información.
      if ((strpos($this->sql, 'SELECT') === 0) && ($select[0] === "SELECT")) {
        $result = $sql->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
      }

      return true;

    } catch (\Exception $e) {
      return $e->getMessage();
    }

  }

  # Funcion de prueba para verificar como esta armada la sql
  public function showSql() {return $this->sql;}

}