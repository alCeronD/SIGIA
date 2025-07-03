<?php
require_once __DIR__ . '/../../../helpers/session.php';
require_once __DIR__ . '/../../../helpers/const.php';
include_once __DIR__ . '/../../../config/conn.php';
class ElementoModelo {
    private $conn;

    public function __construct() {
        $conexion = new Conection();
        $this->conn = $conexion->getConnect();
    }

    // Obtener todos los elementos con nombres relacionados
    public function obtenerElemento() {
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
    public function obtenerElementoPorId($id) {
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

    public function getElementLike(String $inputValue = ''){
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
        'message'=>"error al realizar consulta",
        'status'=>false
    ];
}

$stmtSearch->bind_param('ss',$inputValue,$inputValue);

if (!$stmtSearch->execute()) {
    return [
        'message' => "error al realizar consulta $stmtSearch->error",
        'status'=>false
    ];
}   $result = $stmtSearch->get_result();

$row = [];
while ($resultRow = $result->fetch_assoc()) {
    $row[] = $resultRow;
}

        return [
            'message'=>"coincidencias encontradas",
            'status'=>true,
            'data'=>$row
        ];
    }


    
    // Obtener elementos paginados con JOIN para nombres relacionados
public function obtenerElementoPaginado(int $limite,int $offset, String $type) {
    $elementos = [];

    if (!isset($type)) {
        return [
            'message'=> 'tipo de elemento no valido',
            'status'=> false
        ];
    }

    $type = strtolower($type);

    if (!in_array($type,['consumible','devolutivo','all'])) {
        return [
            'message'=>'tipo de elemento no definido',
            'status'=> false
        ];
    }


    $baseSql = "SELECT 
        e.elm_cod AS 'codigoElemento',
        e.elm_placa AS 'placa',
        e.elm_nombre AS 'nombreElemento',
        e.elm_existencia AS 'cantidad',
        ar.ar_nombre AS 'nombreArea',
        tpE.tp_el_cod  AS 'codTipoElemento',
		tpE.tp_el_nombre AS 'tipoElemento',
        es_e.est_nombre AS 'codEstadoElemento',
        es_e.est_nombre AS 'estadoElemento',
        tpU.nombre_tp_uni AS 'nombreUnidad',
        tpU.cod_tp_uni AS 'codUnidadMedida'
    FROM elementos e
    INNER JOIN areas ar ON ar.ar_cod = e.elm_area_cod
    INNER JOIN tipo_elemento tpE ON tpE.tp_el_cod = e.elm_cod_tp_elemento
    INNER JOIN tipo_unidad tpU ON e.elm_uni_medida = tpU.cod_tp_uni
    INNER JOIN estados_elementos es_e ON es_e.est_el_cod = e.elm_cod_estado";

    //Si el tipo de elemento es all
    if ($type == 'all') {
        $sql = "$baseSql ORDER BY e.elm_placa ASC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
        return [
            'message'=>"Error en prepare: " . $this->conn->error,
            'status'=>false
        ];
    }
        $stmt->bind_param("ii", $limite, $offset);
    }else{
        $codType = ($type == 'consumible') ? 2 : 1;

        //Si es consumible o devolutivo
        $sql = "$baseSql WHERE `tpE`.tp_el_cod = ? ORDER BY e.elm_placa ASC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
        return [
            'message'=>"Error en prepare: " . $this->conn->error,
            'status'=>false
        ];
        }
        $stmt->bind_param("iii", $codType,$limite, $offset);
    }

    if (!$stmt->execute()) {
        return [
            'message'=>"error al ejecutar la consulta $stmt->error",
            'status'=> false
        ];
    }

    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
        $elementos[] = $fila;
    }

    $stmt->close();
    return [
        'message' => ($type == 'all') ? "Todos los registros" : "Elementos de tipo $type",
        'data'=> $elementos,
        'status'=> true
    ];
}

// Contar total de elementos, puedo mejorar esta función, que me permita ejecutar el count segun su parámetro, si es consumibles, devolutivos o todos.
public function contarElementos(string $type = 'all') {
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
    public function insertarElemento($datos) {
        $sql = "INSERT INTO elementos (elm_placa, elm_nombre, elm_existencia, elm_uni_medida, elm_cod_tp_elemento, elm_cod_estado, elm_area_cod) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            echo "Error en prepare: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param("isiiiii", $datos['elm_placa'], $datos['elm_nombre'], $datos['elm_existencia'], $datos['elm_uni_medida'], $datos['elm_cod_tp_elemento'], $datos['elm_cod_estado'], $datos['elm_area_cod']);
        return $stmt->execute();
    }

    // Actualizar elemento sin modificar placa ni tipo (solo otros campos)
    public function actualizarElemento($id, $datos) {
    $sql = "UPDATE elementos 
            SET elm_nombre = ?, 
                elm_uni_medida = ?, 
                elm_cod_estado = ?, 
                elm_area_cod = ? 
            WHERE elm_cod = ?";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        echo "Error en prepare: " . $this->conn->error;
        return false;
    }
    $stmt->bind_param(
        "siiii",
        $datos['elm_nombre'],
        $datos['elm_uni_medida'],
        $datos['elm_cod_estado'],
        $datos['elm_area_cod'],
        $id
    );
    return $stmt->execute();
}


    // Alternar estado entre Disponible (1) e Inhabilitado (4)
    public function toggleEstadoElemento($id) {
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

    // Buscar elementos activos
    public function searchElements() {
        $query = "SELECT
            e.*,
            a.ar_cod,
            a.ar_nombre,
            ee.est_nombre
        FROM
            elementos e
        JOIN areas a ON e.elm_area_cod = a.ar_cod
        JOIN estados_elementos ee ON e.elm_cod_estado = ee.est_el_cod
        WHERE ee.est_el_cod = 1";

        $result = $this->conn->query($query);
        $prestamos = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $prestamos[] = $row;
            }
        }
        return $prestamos;
    }
    
    public function actualizarEstadoElemento($id, $nuevo_estado) {
        $sql = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $nuevo_estado, $id);
        return $stmt->execute();
    }

}
?>
