<?php

require_once __DIR__ . '/../Helpers/Autoload.php';

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
  protected $stmt; # en donde se guarda el mysqliprepared
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

    // Concatenamos con signos de interrogacion para preparar la consulta.
    foreach ($datos as $key => $camp) {
      // valido que las keys esten en el modelo de las tablas;
      if (in_array($key, $this->campos)) {
        $string .= ":$key" . ", ";
      }
    }

    return trim($string, ", ");
  }

  /**
   * Function para crear los campos a actualizar junto a la cantidad de parametros, es una function auxiliar.
   *
   * @param array $datos - arreglo clave valor con el name del input que viene desde el formulario y el value debe ser el valor a actualizar.
   * @return string
   */
  public function organizarDatosUpdate(array $datos = [])
  {
    $sql = "";
    foreach ($datos as $key => $value) {

      if (in_array($key, $this->campos)) {
        $sql .= "$key = :{$key} ,";
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
      $this->sql .= " WHERE $this->id = :{$this->id}";
    }
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
    $this->sql .= " ORDER BY {$campoValido} $ASC";
    return $this;
  }
  public function limit()
  {
    $this->sql .= " LIMIT :limit";
    return $this;
  }

  public function offset()
  {
    $this->sql .= " OFFSET :offset";

    return $this;
  }


  # Function para preparar la consulta y pasar los valores por referencia
  public function prepareSql(array $datos = [])
  {
    $select = explode(' ', $this->sql);

    $this->stmt = $this->conn->prepare($this->sql);

    #Extraigo la informacion
    $data = isset($datos['data']) ? ($datos['data']) : [];


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
        foreach ($data as $key => $value) {
          $this->stmt->bindValue(":{$key}", $value, PDO::PARAM_INT);
        }
      }

      return $this;
    } else {

      foreach ($data as $key => $value) {
        $marcador = ":" . $key;
        if (str_contains($this->sql, $marcador)) {
          $this->stmt->bindValue($marcador, $value);
        }
      }

      return $this;
    }

    return $this;
  }


  # Obtener el resultado sql y devolverlo
  public function get()
  {
    try {
      # Variable para verificar si es un select
      $checkSelect = explode(' ', $this->sql);

      $this->stmt->execute();

      # Verificamos si es un select para solamente devolver un arreglo asociativo
      if ((strpos($this->sql, 'SELECT') === 0) && ($checkSelect[0] === "SELECT")) {
        if (str_contains(strtoupper($this->sql), "COUNT")) {
          return (int) $this->stmt->fetchColumn();
        }

        $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      }

      if (str_contains(strtoupper($this->sql), "UPDATE")) {
        return $this->stmt->rowCount();
      }
      $this->sql = "";
      $this->stmt = null;
      return true;
    } catch (\PDOException $e) {
      return $e->getMessage();
    }
  }


  /**
   * Function para devolver la cantidad de registros de una tabla
   *
   * @return $this
   */
  public function getCount()
  {
    $this->sql = "SELECT COUNT(*) FROM $this->table";
    return $this;
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
