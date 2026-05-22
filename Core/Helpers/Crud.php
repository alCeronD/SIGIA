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
  protected $conn; # En donde se guarda la conexion
  protected $sql; # String que crea la consulta sql
  protected $table; # Nombre de la tabla, hereda su valor desde el modelo
  protected $campos; # Arreglo que contiene campos de la tabla, hereda su valor desde el modelo
  protected $id; # primary key de la tabla, hereda su valor desde el modelo
  protected $typeCampos; # arreglo que tiene los tipos de datos de la tabla, su orden es igual orden de la tabla
  protected $typedCasted; # String que me devuelve los tipos de datos casteados segun su estructura.
  protected $stmt; # en donde se guarda el mysqliprepared
  protected $typeId; # tipo de dato del primary key
  protected $typeCampos2;
  protected $operators = [
    '=',
    '!=',
    '<>',
    '<',
    '>',
    '<=',
    '>=',
    'BETWEEN',
    'IN',
    'IS NULL',
    'LIKE'
  ]; # arreglo para validar e implementar las condicionales

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
   * Function para crear los campos a actualizar junto a la cantidad de parametros, es una function auxiliar.
   *
   * @param array $datos - arreglo clave valor con el name del input que viene desde el formulario y el value debe ser el valor a actualizar.
   * @return void
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
   * @return $this
   */
  public function select(bool $campos = false)
  {
    $campos = ($campos) ? $this->campos : ["*"];
    $this->sql = "SELECT " . $this->organizarCampos($campos) . " FROM " . $this->table;
    return $this;
  }
  public function insert(array $insertValue)
  {
    $this->sql = "INSERT INTO " . $this->table . " (" . $this->organizarCampos($this->campos) . ") VALUES (" . $this->organizarDatos($insertValue) . ")";
    return $this;
  }
  public function delete()
  {
    $this->sql = "DELETE FROM " . $this->table;
    return $this;
  }

  /**
   * function Crear estructura UPDATE para la actualizacion de datos
   *
   * @param array $datos - arreglo clave valor en donde la clave debe ser igual al valor asignado en name del input de los formularios, y value su valor a actualizar.
   * @return $this
   */
  public function update(array $datos = [])
  {
    $valuesUpdate = $this->organizarDatosUpdate($datos);
    $this->sql .= "UPDATE $this->table SET $valuesUpdate";
    return $this;
  }
  public function where(array $datos = [])
  {
    // necesito los datos, para validar que existen y asi validarlos, los operadores de comparacion
    if (array_key_exists($this->id, $datos)) {
      $this->sql .= " WHERE $this->id = ?";
    }
    return $this;
  }

  /**
   * Function para devolver la cantidad de registros de una tabla
   *
   * @return $this
   */
  public function count()
  {
    $this->sql = "SELECT COUNT(*) FROM $this->table";
    return $this;
  }







  public function groupBy() {}

  /**
   * Function para definir el orden del campo, como parámetro se recomienda enviar el index de la tabla, por defecto, asigna el id.
   * @param string|null $campo - String o null para definir el index de la tabla, si no se envia nada, se determina que es el id de la tabla, en caso contrario, se valida la información enviada
   * @param boolean $ASC - flag para determinar si es ascendente o descendente dependiendo del booleano recibido
   * @return $this;
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
    return $this;
  }
  public function limit()
  {
    $this->sql .= " LIMIT ?";
    return $this;
  }

  public function offset()
  {
    $this->sql .= " OFFSET ?";

    return $this;
  }


  # Function para preparar la consulta y pasar los valores por referencia
  public function prepareSql(array $datos = [])
  {
    $select = explode(' ', $this->sql);


    $this->stmt = $this->conn->prepare($this->sql);
    $typesData = "";
    if (substr_count($this->sql, "?") > 0) {
      # Ejecuto el casteo de los datos.
      $typesData = $this->castParam($datos, $this->typeCampos2);
    }


    #Extraigo los tipos de datos
    $types = $typesData ?? [];


    #Extraigo la informacion
    // $data = empty($datos) ? [] : array_values($datos);
    $data = isset($datos['data']) ? array_values($datos['data']) : [];

    // Si es un select, solamente preparamos la consulta y retornamos su resultado
    if ((strpos($this->sql, 'SELECT') === 0) && ($select[0] === "SELECT")) {

      // validar si tiene un COUNT para solo devolver la consulta
      $hasCount = str_contains($this->sql, "COUNT");
      if ($hasCount) {
        return $this;
      }

      // validar si el string contiene o WHERE u OFFSET O LIMIT
      $hasOffset = str_contains($this->sql, "OFFSET");
      $hasLimit = str_contains($this->sql, "LIMIT");
      # Validar si requiere paginación
      if ($hasOffset && $hasLimit) {
        $this->stmt->bind_param($types, ...$data);
      }


      return $this;
    } else {


      # validamos los parámetros con el tipo de dato
      $this->stmt->bind_param($types, ...$data);
      return $this;
    }

    return $this;
  }
  /**
   * function para castear los tipos de datos de las tablas y devolver un string con el tipo de dato: ejemplo: [s,s,s,i] = devolver un sssi
   *
   * @param array $datos
   * @return string;
   */
  public function castParam(array $datos = [], array $tiposDatos = [])
  {
    # variable en donde vamos a adjuntar poco a poco la cantidad de tipos de datos basados en la consulta
    $finalTypes = "";

    # verificar primero cuantos argumentos hay
    $cantidadParametros = substr_count($this->sql, "?");

    # Retornamos el tipo del id que esta definido en el modelo
    if (str_contains(strtoupper($this->sql), 'DELETE')) {
      return $this->typedCasted .= $this->typeId;
    }

    # Validar si la consulta tiene un OFFSET o LIMIT
    if (str_contains(strtoupper($this->sql), 'OFFSET') && str_contains(strtoupper($this->sql), 'LIMIT')) {
      $this->typedCasted .= "ii";
    }

    // si la estructura tiene UPDATE,DELETE, IMPLEMENTAR EL WHERE
    if (str_contains(strtoupper($this->sql), 'UPDATE')) {
      foreach ($tiposDatos as $key => $value) {
        if (isset($datos['data'][$key])) {
          $this->typedCasted .= $tiposDatos[$key];
        }
      }
    }

    if (str_contains(strtoupper($this->sql), 'INSERT')) {
      foreach ($tiposDatos as $key => $value) {
        if (isset($datos['data'][$key])) {
          $this->typedCasted .= $tiposDatos[$key];
        }
      }
    }


    // validar si hay un where para implementar el tipo de dato integer
    if (str_contains(strtoupper($this->sql), 'WHERE')) {
      $this->typedCasted .= $this->typeId;
    }

    # arreglo con los tipos de datos segun el campo DEL MODELO['s','s','i'];
    $typesCampos = $this->typeCampos;


    return $this->typedCasted;
  }

  # Obtener el resultado sql y devolverlo
  public function get()
  {
    try {
      # Variable para verificar si es un select
      $checkSelect = explode(' ', $this->sql);

      // if (!$prepare->execute()) return $prepare->error_list;
      $this->stmt->execute();


      # Verificamos si es un select para solamente devolver un arreglo asociativo
      if ((strpos($this->sql, 'SELECT') === 0) && ($checkSelect[0] === "SELECT")) {
        $result = $this->stmt->get_result();

        if (str_contains(strtoupper($this->sql), "COUNT")) {
          return (int) $result->fetch_row()[0];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
      }

      if (str_contains(strtoupper($this->sql), "UPDATE")) {
        return $this->stmt->affected_rows;
      }
      $this->sql = "";
      $this->stmt = null;
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
