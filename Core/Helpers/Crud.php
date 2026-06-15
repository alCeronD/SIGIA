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

  /**
   * Function para retornar la cadena where del mysql
   * @param array $datos - ['columna', 'operador', 'valor'] - con esta estructura se define el where especifico.
   * @param array $datos - [] arreglo vacio concatena con el primary key
   * @return $this
   */
  public function where(array $datos = [])
  {
    if (!empty($datos)) {
      $columna = $datos[0];
      $operador = $datos[1];
      $valor = $datos[2];

      $this->sql .= " WHERE $columna $operador :$columna";
    }

    if (empty($datos)) {
      // necesito los datos, para validar que existen y asi validarlos, los operadores de comparacion
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

  /**
   * Metodo para armar una consulta between entre un rango especifico.
   *
   * @param string $campo - nombre del campo de la tabla
   * @param array $valores - arreglo que contiene los valores a implementar
   * @return $this;
   */
  public function whereBetween(string $campo, array $valores = [])
  {
    if (str_contains($this->sql, "WHERE")) {
      $this->sql .= " AND {$campo} BETWEEN {$valores[0]} {$this->and()} {$valores[1]}";
    } else {
      $this->sql .= " WHERE {$campo} BETWEEN {$valores[0]} {$this->and()} {$valores[1]}";
    }

    return $this;
  }

  public function and()
  {
    return "AND";
  }

  # Function para preparar la consulta y pasar los valores por referencia
  //TODO:: AGREGAR TRY Y CATCH Y VALIDAR QUE SE HAYAN ENVIADO LOS PARAMETROS ADECUADOS PARA SU EJECUCION.
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
          $this->stmt->bindValue(":{$key}", $value);
        }
      }

      foreach ($data as $key => $value) {
        $marcador = ":" . $key;
        // valido si el marcador enviado existe en los datos que enviamos, si existe, este lo agrega en su valor.
        if (str_contains($this->sql, $marcador)) {
          $this->stmt->bindValue(":{$key}", $value);
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
        $this->sql = null;
        $this->stmt = null;
        return $result;
      }

      if (str_contains(strtoupper($this->sql), "UPDATE")) {
        $rowCounts = $this->stmt->rowCount();
        $this->sql = "";
        $this->stmt = null;
        return $rowCounts;
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
