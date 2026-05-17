<?php

require_once __DIR__ . '/../Config/Conn.php';

#Clase crud para crear toda la estructura general de consultas.
/**
 * Listado de funciones requeridas
 * select
 * insert
 * order by
 * group by
 * inner join
 * left join
 * natural join
 * right join
 */

abstract class Crud
{
  protected $conn;
  protected $sql;
  protected $table;
  protected $campos;
  protected $id;
  protected $typeCampos;

  public function __construct()
  {
    $this->conn = (new Conn)->getConnect();
  }

  # Función para transformar el arreglo de campos en cadenas de string
  public function organizarCampos(array $datos = ["*"])
  {
    $cadena = "";
    foreach ($datos as $dta) {
      // echo $datos;
      $cadena .= $dta . ", ";
    }

    return trim($cadena, ", ");
  }

  # Funcion para organizar los campos de los datos de la tabla en caso de que los vamos a realizar proceso transaccional como insert o update.
  public function organizarDatos($datos)
  {
    $string = "";

    /**
     * array(2){
     * 	["gc_nombre"]=>string(6)"nombre"
     * ["gc_descrip"]=>string(11)"descripcion"
     * }
     */


    // Concatenamos con signos de interrogacion para preparar la consulta.
    foreach ($datos as $key => $camp) {
      // valido que las keys esten en el modelo de las tablas;
      if (in_array($key, $this->campos)) {
        // $string .= "'". $camp. "', ";
        $string .= "?" . ", ";
        // $string .= "'". "?". "', ";

      }
    }



    return trim($string, ", ");
  }

  public function organizarDatosUpdate() {}

  # Función para definir la estructura select
  public function select()
  {
    $this->sql = "SELECT " . $this->organizarCampos($this->campos) . " FROM " . $this->table;
  }
  public function insert(array $insertValue)
  {
    $this->sql = "INSERT INTO " . $this->table . " (" . $this->organizarCampos($this->campos) . ") VALUES (" . $this->organizarDatos($insertValue) . ")";
  }
  public function delete(int $valueId)
  {
    $this->sql = "DELETE FROM " . $this->table . " WHERE " . $this->id . "= " . $valueId;
  }
  public function update($valuesUpdate) {}

  public function where() {}

  // Función para crear la estructura de paginación
  public function paginate() {}

  public function groupBy() {}




  # Function para preparar la consulta y pasar los valores por referencia
  public function prepareSql(array $datos = [])
  {
    $select = explode(' ', $this->sql);

    $sql = $this->conn->prepare($this->sql);

    // Si es un select, solamente preparamos la consulta y retornamos su resultado
    if ((strpos($this->sql, 'SELECT') === 0) && ($select[0] === "SELECT")) {
      $stmSql = $sql;
      return $stmSql;
    } else {

      #Extraigo los tipos de datos
      $types = $datos['types'];
      #Extraigo la informacion
      $data = $datos['data'];
      # validamos los parámetros con el tipo de dato
      $sql->bind_param($types, ...$data);
      return $sql;
    }

    return true;
  }

  # Function para castear los tipos de datos de las tablas y devolver un string con el tipo de dato: ejemplo: [s,s,s,i] = devolver un sssi
  public function castParam()
  {
    $types = $this->typeCampos;
    $typeCasted = "";
    foreach ($types as $value) {
      $typeCasted .= $value;
    }

    return $typeCasted;
  }

  # Obtener el resultado sql y devolverlo
  public function get(mysqli_stmt|bool $prepare = false)
  {
    try {
      # Variable para verificar si es un select
      $checkSelect = explode(' ', $this->sql);

      $prepare->execute();

      # Verificamos si es un select para solamente devolver un arreglo asociativo
      if ((strpos($this->sql, 'SELECT') === 0) && ($checkSelect[0] === "SELECT")) {
        $result = $prepare->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
      }

      return true;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  # Funcion de prueba para verificar como esta armada la sql
  public function showSql()
  {
    return $this->sql;
  }
}
