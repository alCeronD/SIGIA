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


  /**
   * Undocumented function
   *
   * @param array $datos
   * @return string
   */
  public function organizarDatosUpdate(array $datos = [])
  {
    $sql = "";
    foreach ($datos as $key => $value) {

      if (in_array($key, $this->campos)) {
        $sql .= "$key = ? ,";
      }
    }
    return trim($sql, ", ");
  }

  /**
   * function para crear sentencia select
   *
   * @param boolean $campos - flag para determinar si hacemos un select con * o en su defecto con los campos de la tabla sin su id.
   * @return void
   */
  public function select(bool $campos = false)
  {
    $campos = ($campos) ? $this->campos : ["*"];
    $this->sql = "SELECT " . $this->organizarCampos($campos) . " FROM " . $this->table;
  }
  public function insert(array $insertValue)
  {
    $this->sql = "INSERT INTO " . $this->table . " (" . $this->organizarCampos($this->campos) . ") VALUES (" . $this->organizarDatos($insertValue) . ")";
  }
  public function delete(int $valueId)
  {
    $this->sql = "DELETE FROM " . $this->table . " WHERE " . $this->id . "= " . $valueId;
  }
  public function update(array $datos = [])
  {
    $valuesUpdate = $this->organizarDatosUpdate($datos);
    $this->sql .= "UPDATE $this->table SET $valuesUpdate";
  }

  /**
   * Function para devolver la cantidad de registros de una tabla
   *
   * @return void
   */
  public function count()
  {
    $this->sql = "SELECT COUNT(*) FROM $this->table";
  }

  public function where(array $datos = [])
  {
    if (array_key_exists($this->id, $datos)) {
      $this->sql .= " WHERE $this->id = ?";
    }
  }

  // Función para crear la estructura de paginación


  public function groupBy() {}

  /**
   * Function para definir el orden del campo, como parámetro se recomienda enviar el index de la tabla, por defecto, asigna el id.
   * @param string|null $campo - String o null para definir el index de la tabla, si no se envia nada, se determina que es el id de la tabla, en caso contrario, se valida la información enviada
   * @param boolean $ASC - flag para determinar si es ascendente o descendente dependiendo del booleano recibido
   * @return void
   */
  public function orderBy(string $campo = "", bool $ASC = true)
  {
    #SELECT * FROM `GeneralCrud` ORDER BY gc_id ASC LIMIT 5 OFFSET 5;
    $campoValido = "";
    if (empty($campo)) {
      $campoValido = $this->id;
    }


    // Function para definir el orden del campo, como parámetro se recomienda enviar el index de la tabla, por defecto, asigna el id.

    if (in_array($campo, $this->campos)) {
      $campoValido = $campo;
    }


    $ASC = ($ASC) ? 'ASC' : 'DESC';
    $this->sql .= " ORDER BY $campoValido $ASC";
  }
  public function limit()
  {
    $this->sql .= " LIMIT ?";
  }

  public function offset()
  {
    $this->sql .= " OFFSET ?";
  }

  # Function para preparar la consulta y pasar los valores por referencia
  public function prepareSql(array $datos = [])
  {
    $select = explode(' ', $this->sql);

    $sql = $this->conn->prepare($this->sql);

    #Extraigo los tipos de datos
    $types = $datos['types'] ?? [];
    #Extraigo la informacion
    $data = $datos['data'] ?? [];

    // Si es un select, solamente preparamos la consulta y retornamos su resultado
    if ((strpos($this->sql, 'SELECT') === 0) && ($select[0] === "SELECT")) {

      // validar si tiene un COUNT para solo devolver la consulta
      $hasCount = str_contains($this->sql, "COUNT");
      if ($hasCount) {
        $stmt = $sql;
        return $stmt;
      }

      // validar si el string contiene o WHERE u OFFSET O LIMIT
      $hasOffset = str_contains($this->sql, "OFFSET");
      $hasLimit = str_contains($this->sql, "LIMIT");
      # Validar si requiere paginación
      if ($hasOffset && $hasLimit) {
        $sql->bind_param($types, ...$data);
      }

      $stmSql = $sql;
      return $stmSql;
    } else {


      # validamos los parámetros con el tipo de dato
      $sql->bind_param($types, ...$data);
      return $sql;
    }

    return true;
  }

  /**
   * function para castear los tipos de datos de las tablas y devolver un string con el tipo de dato: ejemplo: [s,s,s,i] = devolver un sssi
   *
   * @return string;
   */
  public function castParam()
  {
    $types = $this->typeCampos;
    $typeCasted = "";
    foreach ($types as $value) {
      $typeCasted .= $value;
    }

    // si la estructura tiene UPDATE,DELETE, IMPLEMENTAR EL WHERE
    if (str_contains(strtoupper($this->sql), 'WHERE') || str_contains(strtoupper($this->sql), 'DELETE') || str_contains(strtoupper($this->sql), 'UPDATE')) {
      $typeCasted .= "i";
    }

    return $typeCasted;
  }

  # Obtener el resultado sql y devolverlo
  public function get(mysqli_stmt|bool $prepare = false)
  {
    try {
      # Variable para verificar si es un select
      $checkSelect = explode(' ', $this->sql);

      // if (!$prepare->execute()) return $prepare->error_list;
      $prepare->execute();

      # Verificamos si es un select para solamente devolver un arreglo asociativo
      if ((strpos($this->sql, 'SELECT') === 0) && ($checkSelect[0] === "SELECT")) {
        $result = $prepare->get_result();

        if (str_contains(strtoupper($this->sql), "COUNT")) {
          return (int) $result->fetch_row()[0];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
      }

      if ((strpos($this->sql, 'UPDATE') === 0) && str_contains(strtoupper($this->sql), "UPDATE")) {
        return $prepare->affected_rows;
      }

      return true;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function getPrimaryKey()
  {
    return $this->id;
  }

  # Funcion de prueba para verificar como esta armada la sql
  public function showSql()
  {
    return $this->sql;
  }
}
