<?php

require_once __DIR__ . '/../../../config/conn.php';

//Crud general para todos los elementos.
class ConfigModulesModel
{
    private Conection $mysqli;
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

        try {
            $conn = $this->mysqli->getConnect();
            //Consulta insert sql.
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new \RuntimeException("Error al preparar la consulta " . $conn->error);
            }
            $bindParams = [];
            foreach ($values as $key => $value) {
                if (($key == 0 && trim($value) === '')) {
                    $conn->close();
                    return [
                        'status'=> false,
                        'message'=> "El valor en la posición $key está vacío o contiene solo espacios.",
                        'data'=> []
                    ];
                }
                $bindParams[] = &$values[$key];
            }
            array_unshift($bindParams, $types);
            call_user_func_array([$stmt, 'bind_param'], $bindParams);


        if (!$stmt->execute()) {
            $conn->close();
            return [
                'status' => false,
                'message' => "Error al ejecutar la consulta: " . $stmt->error,
                'data'=> []
            ];
        }

        $insertedId = $conn->insert_id;

        // Cerrar la conexión
        $conn->close();

        return [
            'status'=> true,
            'message'=> 'Recurso creado con exito',
            'data'=> [$insertedId]
        ];
        } catch (\Throwable $th) {
            return [
                'status'=> false,
                'message'=> $th->getMessage(),
                'data'=> []
            ];
        }

        
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
                return [
                    'status'=> false,
                    'message'=> 'error al procesar la actualización',
                    'data'=> []
                ];
            }
        } catch (PDOException $th) {
            return [
                'status'=> false,
                'message'=> $th->getMessage(),
                'data'=> []
            ];
        }

        //Cerrar conexión.
        $stmt->close();

        return [
            'status'=> true,
            'message'=> 'registro actualizado con exito',
            'data'=> []
        ];
    }


        /**
     * Valida si un valor en una columna específica de una tabla ya existe (para evitar duplicados).
     *
     * Esta función realiza una consulta SELECT sobre la columna `$nameColum` de la tabla `$tableName`,
     * y verifica si el valor `$nameValueColum` ya está registrado. Se utiliza típicamente para validaciones
     * de unicidad antes de insertar registros.
     *
     * @param string $nameColum        Nombre de la columna a validar.
     * @param string $tableName        Nombre de la tabla donde se hará la validación.
     * @param string $nameValueColum   Valor a verificar si ya existe en la columna.
     *
     * @return array {
     *     @type string $message  Mensaje indicando si el valor está disponible o es duplicado.
     *     @type bool   $status   Estado de la validación: true si es único, false si ya existe.
     *     @type array  $data     Datos adicionales (vacío si es duplicado, [1] si está disponible).
     * }
     */
    public function validateUnique(
    string $column,
    string $table,
    string $value,
    ?int $excludeId = null,
    string $pkColumn = 'id'
) {
    $conn = $this->mysqli->getConnect();

    $sql = "SELECT `$column` FROM `$table` WHERE `$column` = ?";
    $types = 's';
    $params = [$value];

    if ($excludeId !== null) {
        $sql .= " AND `$pkColumn` != ?";
        $types .= 'i';
        $params[] = $excludeId;
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [
            'message' => 'Error en prepare: ' . $conn->error,
            'status' => false,
            'data' => []
        ];
    }

    $refs = [];
    foreach ($params as $k => $v) {
        $refs[$k] = &$params[$k];
    }

    array_unshift($refs, $types);
    call_user_func_array([$stmt, 'bind_param'], $refs);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return [
            'message' => 'Entrada duplicada',
            'status' => false,
            'data' => []
        ];
    }

    return [
        'message' => 'Item disponible para registro',
        'status' => true,
        'data' => [1]
    ];
}
}


