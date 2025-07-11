<?php


// require_once __DIR__ . '/../../../helpers/session.php';

use function PHPSTORM_META\map;

require_once __DIR__ . '/../../../helpers/const.php';
include_once __DIR__ . '/../../../config/conn.php';
class ElementoModelo
{
    private $conn;

    public function __construct()
    {
        $conexion = new Conection();
        $this->conn = $conexion->getConnect();
    }

/**
     * Obtiene todos los elementos con información relacionada (área, tipo, estado).
     *
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
            }
        } else {
            echo "Error al ejecutar la consulta: " . $this->conn->error;
        }

        return $elementos;
    }

    // Obtener un solo elemento con nombres relacionados para edición, función que no me es de utilidad, desgraciadamente.
    public function obtenerElementoPorId($id)
    {
        $sql = "SELECT 
            e.elm_cod,
            e.elm_placa,
            e.elm_nombre,
            e.elm_existencia,
            e.elm_uni_medida,
            e.elm_cod_tp_elemento,
            e.elm_cod_estado,
            e.elm_area_cod,
            ar.ar_nombre AS nombreArea,
            tpE.tp_el_nombre AS tipoElemento
        FROM elementos e
        INNER JOIN areas ar ON ar.ar_cod = e.elm_area_cod
        INNER JOIN tipo_elemento tpE ON tpE.tp_el_cod = e.elm_cod_tp_elemento
        WHERE e.elm_cod = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            echo "Error en prepare: " . $this->conn->error;
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
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
      OR e.elm_placa LIKE CONCAT('%',?,'%')";

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
    public function obtenerElementoPaginado(int $limite, int $offset, string $type)
    {
        $elementos = [];

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
        ar.ar_nombre AS nombreArea,
        ar.ar_cod as codArea,
        tpE.tp_el_cod AS codTipoElemento,
        tpE.tp_el_nombre AS tipoElemento,
        es_e.est_nombre AS codEstadoElemento,
        es_e.est_nombre AS estadoElemento,
        tpU.nombre_tp_uni AS nombreUnidad,
        tpU.cod_tp_uni AS codUnidadMedida
    FROM elementos e
    INNER JOIN areas ar ON ar.ar_cod = e.elm_area_cod
    INNER JOIN tipo_elemento tpE ON tpE.tp_el_cod = e.elm_cod_tp_elemento
    INNER JOIN tipo_unidad tpU ON e.elm_uni_medida = tpU.cod_tp_uni
    INNER JOIN estados_elementos es_e ON es_e.est_el_cod = e.elm_cod_estado";

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
                elm_observacion = ?
            WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [
                'message'=>"error al ejecutar actualización",
                'status'=>false
            ];;
        }

        $codArea = (int) $data['elm_area_cod'];
        $stmt->bind_param(
        "sissi", // nombre(string), área(int), sugerencia(string), observación(string), id(int)
        $data['elm_nombre'],
        $codArea,
        $data['elm_sugerencia'],
        $data['elm_observacion'],
        $data['elm_cod']
    );

        if (!$stmt->execute()) {
            return [
                'message'=>"error al ejecutar actualización",
                'status'=>false
            ];
        }

        $this->conn->close();
        return [
            'message'=>"Elemento actualizado",
            'status'=> true
        ];
    }
    
    // Alternar estado entre Disponible (1) e Inhabilitado (4)
    public function toggleEstadoElemento($id)
    {
        $estadoDisponible = 1;
        $estadoInhabilitado = 4;

        $sql = "SELECT elm_cod_estado FROM elementos WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            echo "Error en prepare: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $estadoActual = (int) $fila['elm_cod_estado'];

            if ($estadoActual === $estadoDisponible) {
                $nuevoEstado = $estadoInhabilitado;
            } elseif ($estadoActual === $estadoInhabilitado) {
                $nuevoEstado = $estadoDisponible;
            } else {
                return false;
            }

            $sqlUpdate = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            if (!$stmtUpdate) {
                echo "Error en prepare: " . $this->conn->error;
                return false;
            }
            $stmtUpdate->bind_param("ii", $nuevoEstado, $id);
            return $stmtUpdate->execute();
        }

        return false;
    }
    // Buscar elementos activos (devolutivo o consumible)//
    public function searchElements($tipoElemento = 1) {
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

    public function actualizarEstadoElemento($id, $nuevo_estado)
    {
        $sql = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $nuevo_estado, $id);
        return $stmt->execute();
    }

    public function disminuirExistenciaElemento($id, $cantidad) {
        $sql = "UPDATE elementos 
                SET elm_existencia = elm_existencia - ? 
                WHERE elm_cod = ? AND elm_existencia >= ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->reset();
        $stmt->bind_param("iii", $cantidad, $id, $cantidad);
        return $stmt->execute();
    }

    public function getElementByType(int $id = 1){
        $sqlType = "SELECT elm_cod_tp_elemento  FROM elementos e WHERE elm_cod = ?";

        $stmtType = $this->conn->prepare($sqlType);
        $stmtType->bind_param('i',$id);

        if (!$stmtType->execute()) {
            return null;
        }

        $result = $stmtType->get_result();

        return (int) $result->fetch_assoc()['elm_cod_tp_elemento'];


    }

    public function getAllPlacas(){
        try {
            $placas = [];

            $sqlPlacas = "SELECT elm_placa FROM elementos";

            
            $stmtPlacas = $this->conn->prepare($sqlPlacas);

            if (!$stmtPlacas) {
                return [
                    'message'=>"error de consulta",
                    'status'=> false
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
                        'message'=>"error al preparar la consulta",
                        'status'=>false
                    ];
                }

                // Si ya esta la placa en la placa registrada, omitir el proceso
                if (in_array($placa,$placaRegistrada)) {
                    continue;
                }

                // en caso de que no este, agrego la placa en la placa registrada.
                $placaRegistrada []= $placa;


                $likeParam = $placa . '-%';
                $stmtSerial->bind_param('s',$likeParam);
                if (!$stmtSerial->execute()) {
                    return [
                        'message'=>'error al ejecutar la consulta',
                        'status'=> false
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
            'message'=>'placas y seriales asociados',
            'data'=> $placas,
            'status'=> true
        ];
    }
}
