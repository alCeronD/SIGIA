<?php

// require_once __DIR__ . '/../../../helpers/session.php';
require_once __DIR__ . '/../../../helpers/const.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../../helpers/expg.php';
class ElementoModelo
{
    private $conn;

    private Regex $expg;

    public function __construct()
    {
        $conexion = new Conection();
        //Esto no es lo ideal, lo ideal es en cada función traer la conexión y cerrarla.
        $this->conn = $conexion->getConnect();
        $this->expg = new Regex();
    }

    /**
     * Obtiene todos los elementos con información relacionada (departamento, tipo, estado).
     * Esta función se aplica al modulo de reportes.
     * @return array Lista de elementos con sus respectivos datos.
     */
    public function obtenerElemento()
    {
        $elementos = [];
        $sql = "SELECT 
            e.elm_cod AS codigoElemento,
            e.elm_placa AS placa,
            e.elm_nombre AS nombreElemento,
            e.elm_existencia AS cantidad,
            e.elm_uni_medida AS unidadMedida,
            ar.ar_nombre AS nombreArea,
            tpE.tp_el_nombre AS tipoElemento,
            es_e.est_nombre AS estadoElemento
        FROM elementos e
        INNER JOIN areas ar ON ar.ar_cod = e.elm_area_cod
        INNER JOIN tipo_elemento tpE ON tpE.tp_el_cod = e.elm_cod_tp_elemento
        INNER JOIN estados_elementos es_e ON es_e.est_el_cod = e.elm_cod_estado
        ORDER BY e.elm_placa ASC";

        $resultado = $this->conn->query($sql);
        if ($resultado) {
            while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
                $elementos[] = $fila;
                // dd($elementos);
            }
        } else {
            echo "Error al ejecutar la consulta: " . $this->conn->error;
        }

        return $elementos;
    }

    // Obtener un solo elemento con nombres relacionados para edición, función que no me es de utilidad, desgraciadamente.
    // public function obtenerElementoPorId($id)
    // {
    //     $sql = "SELECT 
    //         e.elm_cod,
    //         e.elm_placa,
    //         e.elm_nombre,
    //         e.elm_existencia,
    //         e.elm_uni_medida,
    //         e.elm_cod_tp_elemento,
    //         e.elm_cod_estado,
    //         e.elm_area_cod,
    //         ar.ar_nombre AS nombreArea,
    //         tpE.tp_el_nombre AS tipoElemento
    //     FROM elementos e
    //     INNER JOIN areas ar ON ar.ar_cod = e.elm_area_cod
    //     INNER JOIN tipo_elemento tpE ON tpE.tp_el_cod = e.elm_cod_tp_elemento
    //     WHERE e.elm_cod = ?";

    //     $stmt = $this->conn->prepare($sql);
    //     if (!$stmt) {
    //         echo "Error en prepare: " . $this->conn->error;
    //         return null;
    //     }
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     $resultado = $stmt->get_result();
    //     return $resultado->fetch_assoc();
    // }
    /**
     * Busca elementos cuyo nombre o placa coincida parcialmente con un valor.
     *
     * @param string $inputValue Valor de búsqueda.
     * @return array Resultado con mensaje, estado y datos encontrados.
     */
    public function getElementLike(String $inputValue = '')
    {
        $sql = "SELECT 
        e.elm_cod AS codigoElemento,
        e.elm_placa AS placa,
        e.elm_nombre AS nombreElemento,
        e.elm_existencia AS cantidad,
        e.elm_uni_medida AS unidadMedida,
        ar.ar_nombre AS nombreArea,
        tpE.tp_el_nombre AS tipoElemento,
        es_e.est_nombre AS estadoElemento
    FROM elementos e
    INNER JOIN areas ar ON ar.ar_cod = e.elm_area_cod
    INNER JOIN tipo_elemento tpE ON tpE.tp_el_cod = e.elm_cod_tp_elemento
    INNER JOIN estados_elementos es_e ON es_e.est_el_cod = e.elm_cod_estado
    WHERE e.elm_nombre LIKE CONCAT('%', ?, '%')
      AND LENGTH(e.elm_nombre) <= 20
      OR e.elm_placa LIKE CONCAT('%',?,'%') OR e.elm_serie LIKE CONCAT('%',?,'%')";

        $stmtSearch = $this->conn->prepare($sql);

        if (!$stmtSearch) {
            return [
                'message' => "error al realizar consulta",
                'status' => false
            ];
        }

        $stmtSearch->bind_param('ss', $inputValue, $inputValue);

        if (!$stmtSearch->execute()) {
            return [
                'message' => "error al realizar consulta $stmtSearch->error",
                'status' => false
            ];
        }
        $result = $stmtSearch->get_result();

        $row = [];
        while ($resultRow = $result->fetch_assoc()) {
            $row[] = $resultRow;
        }

        return [
            'message' => "coincidencias encontradas",
            'status' => true,
            'data' => $row
        ];
    }

    /**
     * Obtiene elementos paginados según su tipo (consumible, devolutivo o todos).
     *
     * @param int $limite Cantidad de resultados por página.
     * @param int $offset Índice desde donde iniciar la búsqueda.
     * @param string $type Tipo de elemento: 'consumible', 'devolutivo', o 'all'.
     * @return array Resultado de la consulta con mensaje, estado y datos.
     */
    public function obtenerElementoPaginado(int $limite, int $offset, String $type, bool $isBusqueda = false, String $value = "")
    {
        $elementos = [];

        $newValue = $this->expg->validarNumeros($value) ? (int) $value : (string) $value;
        if (!in_array($type, ['consumible', 'devolutivo', 'all'])) {
            return [
                'message' => 'Tipo de elemento no definido',
                'status' => false,
                'data' => []
            ];
        }

        $baseSql = "SELECT 
        e.elm_cod AS codigoElemento,
        e.elm_placa AS placa,
        e.elm_serie AS serie,
        e.elm_nombre AS nombreElemento,
        e.elm_existencia AS cantidad,
        e.elm_sugerencia AS sugerenciaIngresada,
        e.elm_observacion AS observacionElemento,
        e.elm_fecha_registro AS fechaRegistro,
        e.elm_ma_cod AS codMarca,
        ar.ar_nombre AS nombreArea,
        ar.ar_cod as codArea,
        tpE.tp_el_cod AS codTipoElemento,
        tpE.tp_el_nombre AS tipoElemento,
        tpE.tp_el_nombre AS nombreTipoElemento,
        es_e.est_el_cod  AS codEstadoElemento,
        es_e.est_nombre AS estadoElemento,
        tpU.nombre_tp_uni AS nombreUnidad,
        tpU.cod_tp_uni AS codUnidadMedida
        FROM elementos e
        INNER JOIN areas ar ON ar.ar_cod = e.elm_area_cod
        INNER JOIN tipo_elemento tpE ON tpE.tp_el_cod = e.elm_cod_tp_elemento
        INNER JOIN tipo_unidad tpU ON e.elm_uni_medida = tpU.cod_tp_uni
        INNER JOIN estados_elementos es_e ON es_e.est_el_cod = e.elm_cod_estado";

        if ($isBusqueda) {
            if ($type === 'all') {
                $sql = "$baseSql 
                    WHERE (
                        e.elm_nombre LIKE CONCAT('%', ?, '%') 
                        OR e.elm_placa LIKE CONCAT('%', ?, '%') 
                        OR e.elm_serie LIKE CONCAT('%', ?, '%')
                    ) 
                    ORDER BY e.elm_fecha_registro DESC LIMIT ? OFFSET ?";
                $stmt = $this->conn->prepare($sql);

                if ($this->expg->validarNumeros($value)) {
                    $stmt->bind_param("iiiii", $newValue, $newValue, $newValue, $limite, $offset);
                } else {
                    $stmt->bind_param("sssii", $newValue, $newValue, $newValue, $limite, $offset);
                }
            } else {
                $codType = ($type === 'consumible') ? 2 : 1;

                if ($this->expg->validarNumeros($value)) {
                    $sql = "$baseSql WHERE (e.elm_placa LIKE CONCAT('%',?,'%')) AND e.elm_cod_tp_elemento = ? ORDER BY e.elm_fecha_registro DESC LIMIT ? OFFSET ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param("iiii", $newValue, $codType, $limite, $offset);
                } else {
                    $sql = "$baseSql WHERE (e.elm_nombre LIKE CONCAT('%', ?, '%') AND LENGTH(e.elm_nombre) <= 100 OR e.elm_placa LIKE CONCAT('%',?,'%') OR e.elm_serie LIKE CONCAT('%',?,'%')) AND e.elm_cod_tp_elemento = ? ORDER BY e.elm_fecha_registro DESC LIMIT ? OFFSET ?";
                    $stmt = $this->conn->prepare($sql);

                    $stmt->bind_param("sssiii", $newValue, $newValue, $newValue, $codType, $limite, $offset);
                }
            }
        } else {

            if ($type === 'all') {
                $sql = "$baseSql ORDER BY e.elm_fecha_registro DESC LIMIT ? OFFSET ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ii", $limite, $offset);
            } else {
                $codType = ($type === 'consumible') ? 2 : 1;
                $sql = "$baseSql WHERE tpE.tp_el_cod = ? ORDER BY e.elm_fecha_registro DESC LIMIT ? OFFSET ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("iii", $codType, $limite, $offset);
            }
        }

        if (!$stmt) {
            return [
                'message' => "Error en prepare: " . $this->conn->error,
                'status' => false,
                'data' => []
            ];
        }

        if (!$stmt->execute()) {
            return [
                'message' => "Error al ejecutar la consulta: " . $stmt->error,
                'status' => false,
                'data' => []
            ];
        }

        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
            $elementos[] = $fila;
        }

        $stmt->close();

        return [
            'message' => "Consulta exitosa",
            'status' => true,
            'data' => $elementos
        ];
    }
    // Contar total de elementos, puedo mejorar esta función, que me permita ejecutar el count segun su parámetro, si es consumibles, devolutivos o todos.
    public function contarElementos(string $type = 'all')
    {
        $type = strtolower($type);

        if (!in_array($type, ['consumible', 'devolutivo', 'all'])) {
            return [
                'message' => 'Tipo de elemento no válido',
                'status' => false
            ];
        }

        $sqlBase = "SELECT COUNT(*) AS total FROM elementos";

        if ($type === 'all') {
            $sql = $sqlBase;
            $stmtsql = $this->conn->prepare($sql);
        } else {
            $codType = $type === 'consumible' ? 2 : 1;
            $sql = "$sqlBase WHERE elm_cod_tp_elemento = ?";
            $stmtsql = $this->conn->prepare($sql);
            if (!$stmtsql) {
                return [
                    'message' => "Error en prepare: " . $this->conn->error,
                    'status' => false
                ];
            }
            $stmtsql->bind_param('i', $codType);
        }

        if (!$stmtsql->execute()) {
            return [
                'message' => "Error al ejecutar la consulta: " . $stmtsql->error,
                'status' => false
            ];
        }

        $resultado = $stmtsql->get_result();
        $fila = $resultado->fetch_assoc();
        $stmtsql->close();

        return [
            'total' => (int)$fila['total'],
            'status' => true
        ];
    }

    public function contarElementosBusqueda(String $type = 'all', $value)
    {

        $sqlBase = "SELECT COUNT(*) AS total FROM elementos e";
        if ($type === 'all') {

            // Valido que en caso de que el valor que se envie no sea un entero, entonces aplique solo 2 condiciones.
            if (!$this->expg->validarNumeros($value)) {

                $sql = "$sqlBase WHERE (
                        e.elm_nombre LIKE CONCAT('%', ?, '%') 
                        OR e.elm_serie LIKE CONCAT('%', ?, '%')
                        )";

                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('ss', $value, $value);
            } else {
                // Si es entero, solo la placa.
                $sql = "$sqlBase WHERE (
                        e.elm_placa LIKE CONCAT('%', ?, '%') 
                )";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('i', $value);
            }
        } else {
            $newType = ($type === 'consumible') ? 2 : 1;
            if (!$this->expg->validarNumeros($value)) {

                $sql = "$sqlBase WHERE (
                        e.elm_nombre LIKE CONCAT('%', ?, '%') 
                        OR e.elm_serie LIKE CONCAT('%', ?, '%')
                        ) AND e.elm_cod_tp_elemento = ?";

                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('ssi', $value, $value, $newType);
            } else {
                // Si es entero, solo la placa.
                $sql = "$sqlBase WHERE (
                        e.elm_placa LIKE CONCAT('%', ?, '%') 
                ) AND e.elm_cod_tp_elemento = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('ii', $value, $newType);
            }
        }

        if (!$stmt->execute()) {
            return [
                'message' => "Error al ejecutar la consulta: " . $stmt->error,
                'status' => false
            ];
        }

        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();

        return [
            'total' => (int)$fila['total'],
            'status' => true
        ];
    }

    // Insertar nuevo elemento
    public function insertarElemento(array $datos = [])
    {
        $sql = "INSERT INTO elementos (
        elm_placa,
        elm_serie,
        elm_nombre,
        elm_existencia,
        elm_fecha_registro,
        elm_sugerencia,
        elm_observacion,
        elm_uni_medida,
        elm_cod_tp_elemento,
        elm_cod_estado,
        elm_area_cod
    ) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $elm_cod_estado = 1; // valor fijo si aplica

        if (!$stmt) {
            return [
                'message' => "Error en prepare: " . $this->conn->error,
                'status' => false
            ];
        }

        $placa = (int) $datos['elm_placa'];
        $serie = $datos['elm_serie'];
        $nombre = $datos['elm_nombre'];
        $existencia = (int) $datos['elm_existencia'];
        $sugerencia = $datos['elm_sugerencia'];
        $observacion = $datos['elm_observacion'];
        $unidadMedida = (int) $datos['elm_uni_medida'];
        $tpElemento = (int) $datos['elm_cod_tp_elemento'];
        $estado = 1; // fijo
        $area = (int) $datos['elm_area_cod'];



        $stmt->bind_param(
            "ississiiii",
            $placa,
            $serie,
            $nombre,
            $existencia,
            $sugerencia,
            $observacion,
            $unidadMedida,
            $tpElemento,
            $estado,
            $area
        );

        if (!$stmt->execute()) {
            return [
                'message' => "Error al ejecutar la consulta:  $stmt->error",
                'status' => false
            ];
        }

        return [
            'message' => 'Registro exitoso',
            'status' => true
        ];
    }

    // Actualizar elemento sin modificar placa ni tipo (solo otros campos)
    public function actualizarElemento(array $data = [])
    {

        $sql = "UPDATE elementos 
            SET elm_nombre = ?, 
                elm_area_cod = ?, 
                elm_sugerencia = ?,
                elm_observacion = ?,
                elm_ma_cod = ?,
                elm_cod_tp_elemento = ?
            WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [
                'message' => "error al ejecutar actualización",
                'status' => false
            ];;
        }
        $codArea = (int) $data['elm_area_cod'];
        $codMarca = (empty($data['elm_ma_cod'])) ? NULL : (int) $data['elm_ma_cod'];
        $codTpElemento = (int) $data['elm_cod_tp_elemento'];
        $stmt->bind_param(
            "sissiii", // nombre(string), área(int), sugerencia(string), observación(string), id(int)
            $data['elm_nombre'],
            $codArea,
            $data['elm_sugerencia'],
            $data['elm_observacion'],
            $codMarca,
            $codTpElemento,
            $data['elm_cod'],
        );

        if (!$stmt->execute()) {
            return [
                'message' => "error al ejecutar actualización",
                'status' => false
            ];
        }

        $this->conn->close();
        return [
            'message' => "Elemento actualizado",
            'status' => true
        ];
    }

    // Alternar estado entre Disponible (1) e Inhabilitado (4)
    /**
     * Summary of toggleEstadoElemento cambiar el estado del elemento desde el modulo de ELEMENTOS.
     * @param int $cod
     * @param int $status
     * @return array{message: string, status: bool|array{messsage: string, status: bool}|bool}
     */
    public function toggleEstadoElemento(int $cod = 0, int $status = 0)
    {
        $estadoDisponible = 1;
        $estadoInhabilitado = 4;

        $sql = "SELECT elm_cod_estado FROM elementos WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [
                'messsage' => "error al preprar consulta " . $this->conn->error,
                'status' => false
            ];
        }
        $stmt->bind_param("i", $cod);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $estadoActual = (int) $fila['elm_cod_estado'];

            if ($estadoActual === $estadoDisponible) {
                $nuevoEstado = $estadoInhabilitado;
            } elseif ($estadoActual === $estadoInhabilitado) {
                $nuevoEstado = $estadoDisponible;
            } else {
                return [
                    "message" => "estado no definido",
                    "status" => false
                ];
            }

            $sqlUpdate = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            if (!$stmtUpdate) {
                return [
                    "message" => "Error en prepare: " . $this->conn->error,
                    "status" => false
                ];
            }
            $stmtUpdate->bind_param("ii", $nuevoEstado, $cod);
            if (!$stmtUpdate->execute()) {
                return [
                    'message' => "error al actualizar el registro" . $stmtUpdate->error,
                    'status' => false
                ];
            }
        }

        return [
            'message' => $nuevoEstado === 1 ? "elemento disponible" : "elemento inhabilitado",
            'status' => true
        ];
    }
    // Buscar elementos activos (devolutivo o consumible)//
    public function searchElements($tipoElemento = 1)
    {
        $query = "SELECT
            e.*,
            a.ar_cod,
            a.ar_nombre,
            ee.est_nombre
        FROM
            elementos e
        JOIN areas a ON e.elm_area_cod = a.ar_cod
        JOIN estados_elementos ee ON e.elm_cod_estado = ee.est_el_cod
        WHERE ee.est_el_cod = 1 
          AND elm_cod_tp_elemento = ? 
          AND (e.elm_existencia IS NULL OR e.elm_existencia > 0)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $tipoElemento);
        $stmt->execute();
        $result = $stmt->get_result();

        $elementos = [];
        while ($row = $result->fetch_assoc()) {
            $elementos[] = $row;
        }

        return $elementos;
    }

    // Hace parte de lógica solicitud prestamo, con esta función se actualiza el estado de en reserva a cancelado.
    public function actualizarEstadoElemento($id, $nuevo_estado)
    {
        $sql = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $nuevo_estado, $id);
        return $stmt->execute();
    }

    // Esta función sirve para disminuir la existencia del elemento cuando da salida.
    public function disminuirExistenciaElemento($id, $cantidad)
    {

        $sql = "UPDATE elementos 
                SET elm_existencia = elm_existencia - ? 
                WHERE elm_cod = ? AND elm_existencia >= ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("iii", $cantidad, $id, $cantidad);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true; // Se actualizó correctamente
        } else {
            return false; // No se actualizó (posible existencia insuficiente o id inválido)
        }
    }

    /**
     * Cambia la existencia de un elemento en inventario, registrando la operación como una compra o reembolso.
     *
     * Este método realiza dos operaciones dentro de una transacción:
     * 1. Inserta un registro en la tabla `compras` con la información de la operación.
     * 2. Actualiza el campo `elm_existencia` en la tabla `elementos` según el tipo de movimiento.
     *
     * @param array $data Arreglo asociativo con los siguientes índices:
     *  - 'elm_cod' (int): Código del elemento a modificar.
     *  - 'co_cantidad' (int): Cantidad del movimiento.
     *  - 'co_cantidad_disponible' (int): Existencia actual del elemento antes de la operación.
     *  - 'tipo_movimiento' (int): Tipo de movimiento (1 = Compra, 5 = Reembolso).
     *  - 'descripcion_movimiento' (string): Descripción del movimiento.
     *  - 'operation' (int): Operación a ejecutar (1 = Suma, 5 = Resta).
     *
     * @return array Resultado del proceso con las claves:
     *  - 'message' (string): Mensaje informativo del resultado.
     *  - 'status' (bool): `true` si la operación fue exitosa, `false` en caso de error.
     */
    public function cambiarExistencia(array $data = [])
    {
        $codElemento = (int) $data['elm_cod'];
        $cantidad = (int) $data['co_cantidad'];
        $cantidadActual = (int) $data['co_cantidad_disponible'];
        $tp_mvmento = (int) $data['tipo_movimiento'];
        $descripcion = (string) $data['descripcion_movimiento'];

        $conn = $this->conn;

        try {
            $conn->begin_transaction();
            // Primera transacción, insertar los valores en la tabla compras.
            $sqlCompra = "INSERT INTO compras (co_cod_elm,co_cantidad,co_tp_movimiento,co_descripcion,co_fecha_compra) VALUES (?,?,?,?,NOW())";

            $stmtCompra = $conn->prepare($sqlCompra);

            if (!$sqlCompra) {
                return [
                    "message" => "error al preparar la consulta",
                    "status" => false
                ];
            }

            $stmtCompra->bind_param("iiis", $codElemento, $cantidad, $tp_mvmento, $descripcion);
            if (!$stmtCompra->execute()) {
                return
                    [
                        "message" => "Error al ejecutar la consulta" . $stmtCompra->error_list,
                        "status" => false
                    ];
            }
            // // Compra
            if ($tp_mvmento === 1) {
                $existenciaActual = $cantidad + $cantidadActual;
            }

            // Reembolzo
            if ($tp_mvmento === 5) {
                $existenciaActual = $cantidadActual - $cantidad;
            }
            // Segunda transacción actualizar la cantidad del elemento en la tabla elementos.
            $sqlExistencia = "UPDATE elementos SET elm_existencia = ? WHERE elm_cod  = ?";

            $stmtExistencia = $conn->prepare($sqlExistencia);
            if (!$stmtExistencia) {
                return [
                    "message" => "error al preparar la consulta",
                    "status" => false
                ];
            }

            $stmtExistencia->bind_param("ii", $existenciaActual, $codElemento);
            if (!$stmtExistencia->execute()) {
                return
                    [
                        "message" => "Error al ejecutar la consulta" . $stmtExistencia->error_list,
                        "status" => false
                    ];
            }

            $conn->commit();
        } catch (\Throwable $th) {
            $conn->rollback();
            return [
                "message" => "error al ejecutar el proceso " . $th->getMessage(),
                "status" => false
            ];
        }

        return [
            "message" => $tp_mvmento === 1 ? "Existencia adicionada con exito" : "Existencia disminuida con exito.",
            "status" => true
        ];
    }
    public function getElementByType(int $id = 1): ?int
    {
        $sql = "SELECT elm_cod_tp_elemento FROM elementos WHERE elm_cod = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            return null;
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            return null;
        }

        return (int) $row['elm_cod_tp_elemento'];
    }

    public function getAllPlacas()
    {
        try {
            $placas = [];

            $sqlPlacas = "SELECT elm_placa FROM elementos";


            $stmtPlacas = $this->conn->prepare($sqlPlacas);

            if (!$stmtPlacas) {
                return [
                    'message' => "error de consulta",
                    'status' => false
                ];
            }

            $stmtPlacas->execute();
            $stmtPlacas->execute();
            // Este arreglo sirve para guardar solo las placas registradas
            $placaRegistrada = [];
            $result = $stmtPlacas->get_result();
            $placasRow = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($placasRow as $key => $value) {
                $placa = $value['elm_placa'];
                // $placas [] = $value;
                $sqlSerial = "SELECT elm_serie AS serie FROM elementos WHERE elm_serie LIKE ? GROUP BY elm_serie ORDER BY elm_serie ASC";
                $stmtSerial = $this->conn->prepare($sqlSerial);

                if (!$stmtSerial) {
                    return [
                        'message' => "error al preparar la consulta",
                        'status' => false
                    ];
                }

                // Si ya esta la placa en la placa registrada, omitir el proceso
                if (in_array($placa, $placaRegistrada)) {
                    continue;
                }

                // en caso de que no este, agrego la placa en la placa registrada.
                $placaRegistrada[] = $placa;


                $likeParam = $placa . '-%';
                $stmtSerial->bind_param('s', $likeParam);
                if (!$stmtSerial->execute()) {
                    return [
                        'message' => 'error al ejecutar la consulta',
                        'status' => false
                    ];
                }
                $serialesResult = $stmtSerial->get_result();


                // Si hay resultados, guardelo en un arreglo asociado, en caso de que no, dejalo como arreglo vacio.
                $seriales = $serialesResult ? $serialesResult->fetch_all(MYSQLI_ASSOC) : [];
                // puedes agregar el resultado al array si lo necesitas

                $stmtSerial->close();

                $placas[] = [
                    'elm_placa' => $placa,
                    'seriales' => $seriales
                ];
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return [
            'message' => 'placas y seriales asociados',
            'data' => $placas,
            'status' => true
        ];
    }


    /**
     * Validar la disponibilidad del elemento antes de realizar su respectiva reserva inmediata.
     * @param int $codigoElemento
     * @param array $elementos
     * @return array{data: array, message: string, status: bool}
     */
    public function validateDisponiblidad($codigoElemento = 0, bool $isOnly = false, array $elementos = []){
        try {

            $sql = "SELECT 
                e.elm_cod As 'codigoElemento',
                e.elm_serie AS 'seriElemento',
                e.elm_nombre AS 'nombreElemento',
                e.elm_cod_tp_elemento AS 'tipoElemento',
                p.pres_fch_reserva AS 'fechaReserva',
                p.pres_fch_entrega AS 'fechaDevolucion'
                FROM elementos e
                INNER JOIN prestamos_elementos pe ON
                pe.pres_el_elem_cod = e.elm_cod 
                INNER JOIN prestamos p ON
                pe.pres_cod = p.pres_cod WHERE e.elm_cod = ? AND p.tp_pres = 2";

            $stmtFechas = $this->conn->prepare($sql);

            if (!$stmtFechas) {
                return [
                    'status' => false,
                    'message' => 'error al preparar la consulta',
                    'data' => []
                ];
            }

            if ($isOnly) {
                $elemento = (int) $codigoElemento ?? null;

                $stmtFechas->bind_param('i', $elemento);

                if (!$stmtFechas->execute()) {
                    return [
                        'status' => false,
                        'message' => "Error al ejecutar la consulta" . $this->conn->error,
                        'data' => []
                    ];
                }

                $result = $stmtFechas->get_result();
                $fechas = [];
                while ($row = $result->fetch_assoc()) {
                    $fechas[] = $row;
                }
            } else {
                $elementosYaReservados = [];
                foreach ($elementos as $key => $value) {

                    $codigoElemento = (int) $value['codigo'];
                    $stmtFechas->bind_param('i', $codigoElemento);

                    if (!$stmtFechas->execute()) {
                        return [
                            'status' => false,
                            'message' => "Error al ejecutar la consulta" . $this->conn->error,
                            'data' => []
                        ];
                    }

                    $resultElementos = $stmtFechas->get_result();

                    while ($row = $resultElementos->fetch_assoc()) {
                        $elementosYaReservados[] = $row;
                    }
                }
            }

            if (!$stmtFechas->execute()) {
                return [
                    'data' => [],
                    'message' => "error al ejecutar la consulta" . $this->conn->error,
                    'status' => false
                ];
            }

            $dataReturn = $isOnly ? [
                'message' => "No hay fechas para este elemento",
                'data' => $fechas,
                'status' => count($fechas) > 0 ? true : false
            ] : [
                'message' => "Fechas de reserva relacionadas a los elementos",
                'data' => $elementosYaReservados,
                'status' => count($elementosYaReservados) > 0 ? true : false
            ];

            return $dataReturn;
        } catch (\Throwable $th) {
            return [
                'message'=> "error al ejecutar el procedimiento".$th->getMessage(),
                'status'=> false,
                'data'=> []
            ];
        }
    }
}

// $objElementos = new ElementoModelo();
// $resultado = $objElementos->contarElementosBusqueda('consumible','papel');
// $resultado = $objElementos->obtenerElementoPaginado(10,0,'consumible',true,'papel');
// var_dump($resultado);