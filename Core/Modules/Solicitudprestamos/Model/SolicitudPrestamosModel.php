<?php

include_once __DIR__ . '/../../../Config/Conn.php';
class SolicitudPrestamosModel
{
    public $pres_cod;
    public $pres_fch_slcitud;
    public $pres_fch_reserva;
    public $pres_hor_inicio;
    public $pres_hor_fin;
    public $pres_fch_entrega;
    public $pres_observacion;
    public $pres_destino;
    public $pres_estado;
    public $tp_pres;

    private $conn;

    public function __construct()
    {
        $conexion = (new Conn)->getConnect();
        $this->conn = $conexion;
    }

    public function create(array $data, $rol_usuario)
    {
        $data = [];

        if (!is_array($data)) {
            exit();
        }
        $pres_fch_reserva  = $data['pres_fch_reserva'];
        $pres_fch_entrega  = $data['pres_fch_entrega'];
        $pres_observacion  = $data['pres_observacion'];
        $pres_destino      = $data['pres_destino'];

        $pres_hor_inicio =  null;
        $pres_hor_fin = null;

        $pres_estado       = 3;
        $tp_pres           = 2;
        $pres_rol          = $rol_usuario;

        $query = "INSERT INTO prestamos (
                pres_fch_slcitud, pres_fch_reserva,pres_hor_inicio,pres_hor_fin,
                pres_fch_entrega, pres_observacion, pres_destino, pres_estado, tp_pres, pres_rol
            ) VALUES (NOW(), '$pres_fch_reserva','$pres_hor_inicio','$pres_hor_fin', '$pres_fch_entrega', '$pres_observacion', '$pres_destino', $pres_estado, $tp_pres, $pres_rol
            )
        ";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            // todo: devolver el tipo de error e implementar esto dentro de un bloque try y catch
            return "Error al registrar el prestamo";
        }
    }

    public function update($datos, $id)
    {
        $cadena = "";

        foreach ($datos as $campo => $value) {
            $cadena .= "$campo = '" . $value . "',";
        }

        $cadena = rtrim($cadena, ",");
        $query = "UPDATE solicitud_prestamos SET $cadena WHERE pres_cod = '$id'";
        $resultado = $this->conn->query($query);

        if ($resultado) {
            return true;
        } else {
            return "Error al actualizar el préstamo: ";
        }
    }

    public function delete(int $id)
    {
        $query = "DELETE FROM solicitud_prestamos WHERE pres_cod = '$id'";
        $resultado = $this->conn->query($query);

        if ($resultado) {
            return true;
        } else {
            echo "Problemas al eliminar el préstamo";
            exit();
        }
    }


    public function search($id = 0)
    {
        $sql = "SELECT DISTINCT
            p.pres_cod AS codigoSolicitud,
            p.pres_fch_slcitud AS fechaSolicitud,
            p.pres_fch_reserva AS fechaReserva,
            p.pres_hor_inicio AS horaInicio,
            p.pres_hor_fin AS horaFin,
            p.pres_fch_entrega AS fechaEntrega,
            p.pres_observacion AS observacion,
            p.pres_destino AS destino,
            ep.es_pr_nombre AS estadoNombre,
            tp.tp_nombre AS tipoPrestamo
        FROM prestamos p
        LEFT JOIN prestamos_elementos pe ON p.pres_cod = pe.pres_cod
        LEFT JOIN usuarios us ON us.usu_id = pe.pres_el_usu_id
        LEFT JOIN tipo_prestamo tp ON tp.tp_pre = p.tp_pres
        LEFT JOIN estados_prestamos ep ON ep.es_pr_cod = p.pres_estado
        WHERE us.usu_id = :id_usuario
        ORDER BY p.pres_fch_slcitud DESC";

        $stmtSelect = $this->conn->prepare($sql);
        $stmtSelect->bindValue(':id_usuario', $id, PDO::PARAM_INT);
        $stmtSelect->execute();

        $result = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

        $data = [];

        foreach ($result as $key => $value) {
            $data[]  = $value;
        }


        return $data;
    }

    public function searchU(int $id)
    {
        if (!is_int($id)) {
            exit();
        }

        $query = "SELECT * FROM prestamos WHERE pres_cod = $id";

        $resultado = $this->conn->query($query);

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }

    public function cancelarPrestamo(int $presCod): array
    {
        if (!$presCod || !is_numeric($presCod)) {
            return ['success' => false, 'message' => 'Código inválido de préstamo'];
        }

        // Cambiar estado del préstamo a cancelado
        $stmt = $this->conn->prepare("UPDATE prestamos SET pres_estado = 5 WHERE pres_cod = ?");
        $stmt->bind_param("i", $presCod);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Obtener los elementos asociados al préstamo y sus cantidades
            $query = "SELECT pres_el_elem_cod, pres_el_cantidad FROM prestamos_elementos WHERE pres_cod = ?";
            $elementosStmt = $this->conn->prepare($query);
            $elementosStmt->bind_param("i", $presCod);
            $elementosStmt->execute();
            $result = $elementosStmt->get_result();

            // include_once __DIR__ . '/../../elementos/model/elementosModel.php';
            // $elementoModel = new ElementoModelo();

            // while ($row = $result->fetch_assoc()) {
            //     $elemento_id = $row['pres_el_elem_cod'];
            //     $cantidad = $row['pres_el_cantidad'];

            //     // Cambiar estado del elemento a disponible
            //     $elementoModel->actualizarEstadoElemento($elemento_id, 1); // 1 = Disponible
            //     // Sumar cantidad de vuelta a elm_existencia
            //     $sumarQuery = "UPDATE elementos SET elm_existencia = elm_existencia + ? WHERE elm_cod = ?";
            //     $sumarStmt = $this->conn->prepare($sumarQuery);
            //     $sumarStmt->bind_param("ii", $cantidad, $elemento_id);
            //     $sumarStmt->execute();
            // }

            return ['success' => true, 'message' => 'Préstamo cancelado'];
        } else {
            return ['success' => false, 'message' => 'No se pudo cancelar el préstamo'];
        }
    }
    public function registrarElem($pres_cod, $usuario_id, $elm_cod)
    {
        $query = "INSERT INTO prestamos_elementos (pres_cod, pres_el_usu_id, pres_el_elem_cod, pres_el_cantidad)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $cantidad = 1;
        $stmt->bind_param("iiii", $pres_cod, $usuario_id, $elm_cod, $cantidad);
        return $stmt->execute();
    }

    public function registrarElemConsumible($pres_cod, $usuario_id, $elm_cod, $cantidad)
    {
        $query = "INSERT INTO prestamos_elementos (pres_cod, pres_el_usu_id, pres_el_elem_cod, pres_el_cantidad)
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiii", $pres_cod, $usuario_id, $elm_cod, $cantidad);
        return $stmt->execute();
    }

    public function registrarSalida($cantidades_consumibles, $fecha_registro, $usuario_id, $lastId, $elementos_devolutivos)
    {
        $tipo_movimiento = 3; // salida
        $id_prestamo = $lastId;
        $usuario = $usuario_id;
        $observacion = 'solicitud de salida';
        //procesar los elementos consumibles
        foreach ($cantidades_consumibles as $codElemento => $cantidad) {
            if (is_numeric($cantidad) && ($cantidad > 0)) {
                $sqlSalida = "INSERT INTO entradas_salidas (
                    ent_sal_cantidad,
                    ent_fech_registro,
                    ent_sal_observacion,
                    entr_tp_movmnt,
                    ent_id_usu,
                    ent_sal_cod_elemtn,
                    ent_sal_cod_prestamo
                ) VALUES (?, NOW(), ?, ?, ?, ?, ?)";

                $stmt = $this->conn->prepare($sqlSalida);

                if (!$stmt) {
                    return false;
                }

                $stmt->bind_param(
                    "isiiii",
                    $cantidad,
                    $observacion,
                    $tipo_movimiento,
                    $usuario,
                    $codElemento,
                    $id_prestamo
                );

                if (!$stmt->execute()) {
                    return false;
                }
            }
        }

        if (!is_array($elementos_devolutivos)) {
            $elementos_devolutivos = explode(',', $elementos_devolutivos);
        }

        // Extraer solo los códigos si vienen como array de objetos
        $codigos_devolutivos = [];

        foreach ($elementos_devolutivos as $item) {
            if (is_array($item) && isset($item['codigo'])) {
                $codigos_devolutivos[] = (int) $item['codigo'];
            } elseif (is_numeric($item)) {
                $codigos_devolutivos[] = (int) $item;
            }
        }

        // Limpiar, eliminar duplicados y vacíos
        $elementos_devolutivos = array_filter(array_unique($codigos_devolutivos));


        // 2. Procesar los elementos devolutivos (una unidad defecto)
        foreach ($elementos_devolutivos as $elementoCod) {
            $sqlSalida = "INSERT INTO entradas_salidas (
            ent_sal_cantidad,
            ent_fech_registro,
            ent_sal_observacion,
            entr_tp_movmnt,
            ent_id_usu,
            ent_sal_cod_elemtn,
            ent_sal_cod_prestamo
            ) VALUES (?, NOW(), ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sqlSalida);

            if (!$stmt) {
                return false;
            }

            $cantidad = 1;

            $stmt->bind_param(
                "isiiii",
                $cantidad,
                $observacion,
                $tipo_movimiento,
                $usuario,
                $elementoCod,
                $id_prestamo
            );

            if (!$stmt->execute()) {
                return false;
            }
        }
        return true;
    }

    public function elementoReservadoEnRango($codigoElemento, $fechaInicio, $fechaFin)
    {
        $sql = "SELECT COUNT(*)
                FROM prestamos p
                INNER JOIN prestamos_elementos pe ON p.pres_cod = pe.pres_cod
                WHERE pe.pres_el_elem_cod = ?
                  AND p.pres_estado IN (1,3) -- Validado o por valiar
                  AND p.pres_fch_reserva <= ?
                  AND p.pres_fch_entrega >= ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en prepare: " . $this->conn->error);
        }

        $stmt->bind_param('iss', $codigoElemento, $fechaFin, $fechaInicio);
        $stmt->execute();

        $count = 0;
        $stmt->bind_result($count);
        if ($stmt->fetch() === null) {
            $count = 0;
        }

        $stmt->null;

        return $count > 0;
    }

    public function actualizarEstadosPorFecha()
    {
        $fechaHoy = date('Y-m-d');
        // $fechaHoy = '2025-08-08';
        $sql = "SELECT p.pres_cod
                FROM prestamos p
                WHERE p.pres_fch_reserva = :pres_fch_reserva
                  AND p.pres_estado = 3"; // Por validar
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':pres_fch_reserva', $fechaHoy, PDO::PARAM_STR);
        $stmt->execute();


        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $prestamosActualizados = [];

        foreach ($result as $key => $value) {
            $pres_cod = $key['pres_cod'];

            $sqlElementos = "SELECT pres_el_elem_cod FROM prestamos_elementos WHERE pres_cod = :pres_cod";
            $stmtElems = $this->conn->prepare($sqlElementos);
            $stmtElems->bindValue(':pres_cod', $pres_cod, PDO::PARAM_INT);
            $stmtElems->execute();

            $resElems = $stmtElems->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resElems as $key2 => $value2) {
                $elm_cod = $key2['pres_el_elem_cod'];
                $updateElem = $this->conn->prepare("UPDATE elementos SET elm_cod_estado = 5 WHERE elm_cod = :elm_cod");
                $updateElem->bindValue(':elm_cod', $elm_cod, PDO::PARAM_INT);
                $updateElem->execute();
            }
            $prestamosActualizados[] = $pres_cod;
        }


        return $prestamosActualizados;
    }
}
