<?php


require_once __DIR__ . '/../../../helpers/session.php';
require_once __DIR__ . '/../../../helpers/const.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../usuarios/model/usuariosModel.php';
require_once __DIR__ . '/../../elementos/model/elementosModel.php';


//TODO: en los mensajes de retorno, definir una estructura de retorno específica, así evitar devolver o valores null, o un string, la idea es que devuelva un array con su status y mensaje, en todos los retornos.
class ReservaModel
{
    private Conection $conect;

    protected $id;

    protected $usuario;

    private ElementoModelo $modelElemento;

    public function __construct()
    {
        $this->conect = new Conection();
        $this->usuario = new usuarios();
        $this->modelElemento = new ElementoModelo();
    }

    public function insertReserva(array $data = [], array $codDevolu = [], array $codConsumibles = [])
    {

        $conn = $this->conect->getConnect();
        try {
            $conn->begin_transaction();

            $cedula = (int) $data["cedula"];
            unset($data["cedula"]);
            // $tpPrestamo = (int) $data['tpPrestamo'];

            //primera id del usuario.
            $sqlIdUser = "SELECT usu_id AS 'id' FROM usuarios WHERE usu_docum = ?";
            $stmtUser = $conn->prepare($sqlIdUser);
            $stmtUser->bind_param('i', $cedula);
            if (!$stmtUser->execute()) {
                $conn->rollback();
                return [
                    'message' => $stmtUser->error,
                    'status' => false
                ];
            }
            $resultId = $stmtUser->get_result();
            $userRow = $resultId->fetch_assoc();
            if (!$userRow) {
                $conn->rollback();
                return [
                    'message' => "Usuario con cédula $cedula no encontrado.",
                    'status' => false
                ];
            }
            $id = (int) $userRow['id'];
            $this->id = $id;

            // Creo la consulta dependiendo del tipo de proceso, si es prestamo o reserva
            $presSql = $data['tp_pres'] === 2 ? "INSERT INTO prestamos (pres_fch_slcitud,pres_fch_reserva,pres_fch_entrega,pres_observacion,pres_destino,pres_estado,tp_pres,pres_rol) VALUES (NOW(),?,?,?,?,?,?,?)" : " INSERT INTO prestamos (pres_fch_slcitud,pres_fch_entrega,pres_observacion,pres_destino,pres_estado,tp_pres,pres_rol) VALUES (NOW(),?,?,?,?,?,?)";

            //segunda transacción, insertar los registros en el prestamo.
            $stmtPres = $conn->prepare($presSql);

            if (!$stmtPres) {
                $conn->rollback();
                return [
                    'message' => "error al preparar la consulta",
                    'status' => false
                ];
            }
            // Debo usar esta por el tema de la versión de php.
            extract($data, EXTR_PREFIX_ALL, 'p');

            if ($data['tp_pres'] === 2) {
                $stmtPres->bind_param(
                    'ssssiii',
                    $p_pres_fch_reserva,
                    $p_pres_fch_entrega,
                    $p_pres_observacion,
                    $p_pres_destino,
                    $p_pres_estado,
                    $p_tp_pres,
                    $p_pres_rol
                );
            } else {
                $stmtPres->bind_param(
                    'sssiii',
                    $p_pres_fch_entrega,
                    $p_pres_observacion,
                    $p_pres_destino,
                    $p_pres_estado,
                    $p_tp_pres,
                    $p_pres_rol
                );
            }

            if (!$stmtPres->execute()) {
                $conn->rollback();
                return [
                    'message' => "error al registrar el prestamo $stmtPres->error",
                    'status' => false
                ];
            }

            //Capturo el id del prestamo, lo voy a usar para insertar en la tabla prestamos_elementos.
            $lastId = $conn->insert_id;

            if (!$lastId) {
                $conn->rollback();
                return [
                    'data' => 'Error: no se pudo obtener el ID del préstamo insertado.',
                    'status' => false
                ];
            }

            // Defino el estado del elemento, 3 si es reservado y 1 disponible dependiendo del proceso seleccionado, 1 es prestamo y 2 es reserva.
            $status = $data['tp_pres'] == 1 ? (int) 3 : (int) 1;

            // Este proceso se hace para validar si es una reserva Y SI SE REALIZA PARA ESA MISMA FECHA, si se realiza para la misma fecha, los estados de los elementos cambian a reservados.
            $fechaHoy = date('Y-m-d');
            if ($data['tp_pres'] == 2) {
                if ($fechaHoy === $p_pres_fch_reserva) $status = 5;
            }

            //tercera para actualizar el estado de los elementos devolutivos
            $updateStatusElements = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $stmtUpdateStatus = $conn->prepare($updateStatusElements);
            // $status = (int) 3;
            $codigosDevolu = array_column($codDevolu, 'codigo');
            foreach ($codigosDevolu as $elementos) {
                $stmtUpdateStatus->bind_param('ii', $status, $elementos);

                if (!$stmtUpdateStatus->execute()) {
                    $conn->rollback();
                    return [
                        'message' => "$stmtUpdateStatus->error",
                        'status' => false
                    ];
                }
            }

            $codsDevel = array_column($codDevolu, 'codigo');
            $cantidadDevolutivo = 1;
            //quinta transacción insertar los elementos devolutivos en la tabla prestamos_elementos.
            $sqlElementosDev = "INSERT INTO prestamos_elementos (pres_cod,pres_el_usu_id,pres_el_elem_cod,pres_el_cantidad) VALUES(?,?,?,?)";
            $stmtElementosDev = $conn->prepare($sqlElementosDev);
            foreach ($codsDevel as $value) {
                $value = (int) $value;
                $stmtElementosDev->bind_param('iiii', $lastId, $id, $value, $cantidadDevolutivo);

                if (!$stmtElementosDev->execute()) {
                    $conn->rollback();
                    return [
                        'data' => $stmtElementosDev->error,
                        'status' => false
                    ];
                }
            }

            //Sexta transacción, insertar los elementos consumibles
            $sqlElementosConsu = "INSERT INTO prestamos_elementos (pres_cod,pres_el_usu_id,pres_el_elem_cod,pres_el_cantidad) VALUES(?,?,?,?)";
            $stmtElementosConsu = $conn->prepare($sqlElementosConsu);
            foreach ($codConsumibles as $value) {
                $cod = (int) $value['codigo'];
                $cant = $value['cantidad'];
                $stmtElementosConsu->bind_param('iiii', $lastId, $id, $cod, $cant);

                if (!$stmtElementosConsu->execute()) {
                    $conn->rollback();
                    return [
                        'message' => $stmtElementosConsu->error,
                        'status' => false
                    ];
                }
            }

            $cantidadConsumibles = array_column($codConsumibles, 'cantidad');
            $codidogConsumibles = array_column($codConsumibles, 'codigo');

            //Cuarta transacción, traer la cantidad disponible del elemento.
            $sqlGetCantidad = "SELECT elm_existencia FROM elementos WHERE elm_cod = ?";

            //quinta transacción, reducir la cantidad de elementos a los elementos consumibles.
            $sqlConsumibles = "UPDATE elementos SET elm_existencia = ? WHERE elm_cod = ?";
            $stmtGetCantidad = $conn->prepare($sqlGetCantidad);
            $stmtConsumibles = $conn->prepare($sqlConsumibles);

            foreach ($codidogConsumibles as $key => $value) {
                //Parámetros para traer la cantidad de existencias.
                $stmtGetCantidad->bind_param('i', $value);

                if (!$stmtGetCantidad->execute()) {
                    $conn->rollback();

                    return [
                        'message' => $stmtGetCantidad->error,
                        'status' => false
                    ];
                }
                $cantidadResult = $stmtGetCantidad->get_result();
                $cantidad = $cantidadResult->fetch_assoc()['elm_existencia'];
                $cantidadTotal = $cantidad - $cantidadConsumibles[$key];

                $stmtConsumibles->bind_param('ii', $cantidadTotal, $value);

                if (!$stmtConsumibles->execute()) {
                    $conn->rollback();

                    return [
                        'message' => "$stmtConsumibles->error",
                        'status' => false
                    ];
                }
            }

            //Transacción para validar la salida de los elementos.
            $sqlInsertSalida = "INSERT INTO entradas_salidas (ent_sal_cantidad,ent_fech_registro,ent_sal_observacion,entr_tp_movmnt,ent_id_usu,ent_sal_cod_elemtn,ent_sal_cod_prestamo) VALUES(?,NOW(),?,?,?,?,?)";

            $stmtSalida = $conn->prepare($sqlInsertSalida);
            //Salida
            $tipoMovimento = 2;

            foreach ($codConsumibles as $element) {
                //Extraigo la cantidad y el código del elemento.
                $codigo = $element['codigo'];
                $cant = $element['cantidad'];

                $stmtSalida->bind_param('isiiii', $cant, $p_pres_observacion, $tipoMovimento, $id, $codigo, $lastId);
                if (!$stmtSalida->execute()) {
                    $conn->rollback();
                    $result = [
                        'message' => "$stmtSalida->error",
                        'status' => false
                    ];

                    return $result;
                }
            }

            foreach ($codDevolu as $key => $value) {
                //Extraigo la cantidad y el código del elemento.
                $codigo = $value['codigo'];
                $cant = $value['cantidad'];

                $stmtSalida->bind_param('isiiii', $cant, $p_pres_observacion, $tipoMovimento, $id, $codigo, $lastId);
                if (!$stmtSalida->execute()) {
                    $conn->rollback();
                    $result = [
                        'message' => "$stmtSalida->error",
                        'status' => false
                    ];

                    return $result;
                }
            }

            $conn->commit();
            return [
                'message' => 'proceso realizado exitosamente',
                'status' => true
            ];
        } catch (\Throwable $th) {
            $messageError = $th->getMessage();
            $conn->rollback();
            return [
                'message' => "Error en el proceso: $messageError",
                'status' => false
            ];
        }
    }

    //Función para finalizar la reserva y todos los elementos cambiar sus respectivos estados.
    public function endReserva(array $elementos = [], int $codigo, array $data = [])
    {
        //Objetivo:
        /**
         * 0- Traer el id del usuario.
         * 1- Actualizar el estado de los elementos a disponible.
         * 2- Actualizar el estado del prestamo a finalizado.
         * 3- Registrar la entrada en la tabla entradas_salidas
         */
        try {


            $conn = $this->conect->getConnect();
            $conn->begin_transaction();
            $cedula = $data['dataUsuario']['nroIdentidad'];

            $responseIdUsuario = $this->usuario->searchU($cedula, true);
            $idUsuario = $responseIdUsuario['data'];
            $id = $idUsuario['usu_id'];


            $disponible = 1;
            $sqlStatus = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $stmtStatus = $conn->prepare($sqlStatus);
            $codElementos = array_column($elementos, 'codigo');
            // $cantidadElementos = array_column($elementos,'cantidadSolicitada');

            //Primera transacción.
            foreach ($codElementos as $value) {
                $stmtStatus->bind_param('ii', $disponible, $value);

                if (!$stmtStatus->execute()) {

                    $conn->rollback();
                    return $stmtStatus->error;
                }
            }
            // //Finalizado
            $prestamoStatus = 4;

            //Segunda transacción.
            $sqlEndReserva = "UPDATE prestamos SET pres_estado = ? WHERE pres_cod = ?";
            $stmtEndReserva = $conn->prepare($sqlEndReserva);
            $stmtEndReserva->bind_param('ii', $prestamoStatus, $codigo);

            if (!$stmtEndReserva->execute()) {
                $conn->rollback();
                return $stmtEndReserva->error;
            }

            // Tercera transacción, registrar la entrada de los elementos en la base de datos..
            $sqlEntrada = "INSERT INTO entradas_salidas (
                ent_sal_cantidad,
                ent_fech_registro,
                ent_sal_observacion,
                entr_tp_movmnt, 
                ent_id_usu,
                ent_sal_cod_elemtn,
                ent_sal_cod_prestamo
            ) VALUES (?, NOW(), ?, ?, ?, ?, ?)";
            $stmtEntrada = $conn->prepare($sqlEntrada);

            $observacionSalida = $data['observacionSalida'];
            $entrada = 4;
            $codigoReserva = $data['codigoReserva'];
            foreach ($elementos as $key => $elm) {
                $codigoElementoEntrada = $elm['codigo'];
                $cantidad = $elm['cantidadSolicitada'];
                // var_dump($cantidad);
                // var_dump($codigoElementoEntrada);
                $stmtEntrada->bind_param('isiiii', $cantidad, $observacionSalida, $entrada, $id, $codigoElementoEntrada, $codigoReserva);

                if (!$stmtEntrada->execute()) {
                    $conn->rollback();
                    return [
                        'status' => false,
                        'message' => "error al ejecutar el proceso" . $conn->error,
                        'data' => []
                    ];
                }
            }

            $conn->commit();
            $conn->close();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $result = [
            'status' => true,
            'message' => 'Prestamo finalizado.'
        ];

        return $result;
    }

    //Función para traer los elementos, posiblemente deba implementarla en el modelo de elementos, no en el modelo de reserva.
    public function selectElements(int $page = 1, int $type = 1)
    {

        try {
            $conn = $this->conect->getConnect();
            $conn->begin_transaction();

            $countElements = "SELECT
                COUNT(*) AS 'Total'
                FROM
                    elementos el
                INNER JOIN tipo_elemento tp ON
                    tp.tp_el_cod = el.elm_cod_tp_elemento
                INNER JOIN estados_elementos esl ON
                    esl.est_el_cod = el.elm_cod_estado
                INNER JOIN areas ar ON
                    el.elm_area_cod = ar.ar_cod
                WHERE
                el.elm_cod_estado = 1 AND el.elm_cod_tp_elemento = ? AND ar.ar_status = 1";

            $stmtCount = $conn->prepare($countElements);

            $stmtCount->bind_param("i", $type);

            if (!$stmtCount->execute()) {
                return null;
            }

            $resultCount = $stmtCount->get_result();
            $rows = $resultCount->fetch_assoc()['Total'];

            //TODO: implementar paginado valor limit de manera gglobal.
            $limit = LIMIT;

            //Numero de páginas en base a la cantidad de elementos, redondeo hacía el número más grande.

            /**
             * @var $page - Es el parámetro que le mando a los elementos.
             */
            $offset = ($page - 1) * $limit;
            //Cantidad de páginas.
            $pages = (int) ceil($rows / $limit);

            if ($type == 1) {
                //Sql para devolutivos
                $sqlDevolutivo = "SELECT
                    el.elm_cod AS codigo,
                    el.elm_serie AS serie,
                    el.elm_nombre AS elemento,
                    tp.tp_el_nombre AS tipoElemento,
                    ar.ar_nombre AS area
                FROM
                    elementos el
                INNER JOIN tipo_elemento tp ON
                    tp.tp_el_cod = el.elm_cod_tp_elemento
                INNER JOIN estados_elementos esl ON
                    esl.est_el_cod = el.elm_cod_estado
                INNER JOIN areas ar ON
                    el.elm_area_cod = ar.ar_cod
                WHERE
                el.elm_cod_estado = 1 AND el.elm_cod_tp_elemento = ? AND ar.ar_status = 1 ORDER BY el.elm_cod ASC LIMIT ? OFFSET ?";
                $stmt = $conn->prepare($sqlDevolutivo);
            }
            if ($type == 2) {
                //Sql para consumibles.
                $sqlConsumible = "SELECT
                    el.elm_cod AS 'codigo',
                    el.elm_nombre AS 'elemento',
                    el.elm_existencia AS 'cantidad'
                FROM
                    elementos el
                INNER JOIN tipo_elemento tp ON
                    tp.tp_el_cod = el.elm_cod_tp_elemento
                INNER JOIN estados_elementos esl ON
                    esl.est_el_cod = el.elm_cod_estado
                INNER JOIN areas ar ON
                    el.elm_area_cod = ar.ar_cod
                WHERE
                    el.elm_existencia > 0 
                    AND el.elm_cod_estado = 1 
                    AND el.elm_cod_tp_elemento = ?
                    AND ar.ar_status = 1
                ORDER BY el.elm_nombre ASC LIMIT ? OFFSET ?";

                $stmt = $conn->prepare($sqlConsumible);
            }

            $stmt->bind_param('iii', $type, $limit, $offset);

            if (!$stmt->execute()) {
                echo json_encode(["error" => "Error al ejecutar la consulta"]);
                exit();
            }

            $result = $stmt->get_result();
            $data = [];

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $conn->commit();
            $conn->close();

            $results = [
                'data' => $data,
                'pages' => $pages,
                'type' => $type
            ];

            return $results;
        } catch (\Throwable $th) {
            $conn->rollback();
            $conn->close();
            return  $th->getMessage();
        }
    }

    //Función apra traer los elementos, posiblemente da implementarla en el modelo de usuarios.
    public function selectUsers($pages)
    {
        try {

            $conn = $this->conect->getConnect();
            $conn->begin_transaction();
            //Creo que esto va en otra función a parte.
            $count = "SELECT COUNT(*) AS 'Total'
                        FROM
                            usuarios us
                        INNER JOIN estados_usuarios es_u ON
                            es_u.est_id = us.usu_id_estado
                        INNER JOIN usuarios_roles usr ON
                            usr.usr_usu_id = us.usu_id
                        INNER JOIN roles r ON
                            usr.usr_rl_id = r.rl_id
                        WHERE
                    r.rl_id != 2 AND r.rl_status = 1 AND us.usu_id_estado = 1";

            $stmtCount = $conn->prepare($count);

            if (!$stmtCount->execute()) {
                return null;
            }

            $result = $stmtCount->get_result();
            $registros = $result->fetch_assoc()['Total'];

            /**
             *  LIMIT = el limite de los registros que devuelve
             *  OFFSET = salte N, es el parámetro que le mandamos, es la ventana que se va a devolver. le indigamos que se salte los primeros N RESULTADOS. Este es el parámetro.
             * 
             * OFFSET 0 = Devuelve los primeros resultados basados en el limit
             * OFFSET 20 limit 20 = se salta los primeros 20 resultados y devuelve los 20 siguientes, devuelve filas entre la posició 20 Y 40.
             */
            //page es la página que vamos a ver.

            //Redondeo el valor de la página hacía arriba.
            $limit = 5;

            //Este valor lo envió al front para colocar el número de páginas que hay.
            $page = ceil($registros / $limit);

            $offset = ($pages - 1) * $limit;

            $sql = "SELECT 
                us.usu_docum AS 'nroDocumento',
                us.usu_nombres AS 'nombres',
                us.usu_apellidos AS 'apellidos',
                us.usu_telefono AS 'telefono',
                us.usu_email AS 'email',
                r.rl_nombre AS 'rol'
                FROM usuarios us
                INNER JOIN estados_usuarios es_u ON
                es_u.est_id = us.usu_id_estado
                INNER JOIN usuarios_roles usr ON 
                usr.usr_usu_id = us.usu_id 
                INNER JOIN roles r 
                ON usr.usr_rl_id = r.rl_id
                WHERE r.rl_id != 2 AND r.rl_status = 1 AND us.usu_id_estado = 1 ORDER BY us.usu_docum ASC LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $limit, $offset);
            if (!$stmt->execute()) {
                return null;
            }

            $rows = $stmt->get_result();

            $data = [];
            while ($row = $rows->fetch_assoc()) {
                $data[] = $row;
            }

            $results = [
                'data' => $data,
                'pages' => $page
            ];


            $conn->commit();
            $conn->close();
            return $results;
        } catch (\Throwable $th) {
            $conn->rollback();
            $conn->close();
            return $th->getMessage();
        }
    }

    //Funcion para visualizar las reservas.
    /**
     * Summary of selectReservas Trae las reservas, por defecto, trae todas, pero aplicando el filtrado me captura el tipo de prestamo específicos basado en su estado.
     * @return array{data: array, message: string, status: bool|string}
     */
    public function selectDetailReserva(int $page = 1, int $type = 0)
    {
        //valido que el page que mande sea como minimo 1 y Máximo la cantidad requerida.
        $page = max(1, (int)$page);

        $conn = $this->conect->getConnect();
        try {
            $limitConst = LIMIT;

            $offset = ($page - 1) * LIMIT;

            $sqlBase = "SELECT DISTINCT
                pre.pres_cod AS codigo,
                us.usu_docum AS nroIdentidad,
                us.usu_nombres AS nombre,
                us.usu_apellidos AS apellido,
                pre.pres_fch_slcitud AS fechaSolicitud,
                pre.pres_fch_reserva AS fechaReserva,
                pre.pres_hor_inicio AS horaInicio,
                pre.pres_hor_fin AS horaFin,
                pre.pres_fch_entrega AS fechaDevolucion,
                pre.pres_observacion AS observacion,
                esp.es_pr_nombre AS estadoPrestamo,
                esp.es_pr_cod AS estadoCodigoPrestamo,
                r.rl_nombre AS nombreRol,
                tp_pr.tp_pre AS codigoTipoPrestamo,
                tp_pr.tp_nombre AS tipoPrestamo
                FROM prestamos pre
                INNER JOIN prestamos_elementos pre_el ON pre_el.pres_cod = pre.pres_cod
                INNER JOIN usuarios us ON pre_el.pres_el_usu_id = us.usu_id
                INNER JOIN estados_prestamos esp ON esp.es_pr_cod = pre.pres_estado
                INNER JOIN roles r ON r.rl_id = pre.pres_rol
                INNER JOIN tipo_prestamo tp_pr ON tp_pr.tp_pre = pre.tp_pres ";

            $type = $type === 0 ? null : $type;

            $sqlBaseCountReserva = "SELECT COUNT(DISTINCT pre.pres_cod) AS Total
                FROM prestamos pre
                INNER JOIN prestamos_elementos pre_el ON pre_el.pres_cod = pre.pres_cod
                INNER JOIN usuarios us ON pre_el.pres_el_usu_id = us.usu_id
                INNER JOIN estados_prestamos esp ON esp.es_pr_cod = pre.pres_estado
                INNER JOIN roles r ON r.rl_id = pre.pres_rol
                INNER JOIN tipo_prestamo tp_pr ON tp_pr.tp_pre = pre.tp_pres ";
            if (is_null($type)) {
                $queryCountReservas = $sqlBaseCountReserva;
                //Obtengo la cantidad de registros de la tabla prestamos
                $sqlReservas = "$sqlBase ORDER BY pre.pres_fch_slcitud DESC LIMIT ? OFFSET ?";
                $stmtResevas = $conn->prepare($sqlReservas);
                $stmtResevas->bind_param('ii', $limitConst, $offset);
            } else {
                $queryCountReservas = "$sqlBaseCountReserva WHERE esp.es_pr_cod = $type";
                $sqlReservas = "$sqlBase WHERE pre.pres_estado = ? ORDER BY pre.pres_fch_slcitud ASC LIMIT ? OFFSET ?";
                $stmtResevas = $conn->prepare($sqlReservas);
                $stmtResevas->bind_param('iii', $type, $limitConst, $offset);
            }
            $getCountReservas = (int) $this->getCount($queryCountReservas, 'prestamos');

            //Redondear cantidad de páginas.
            $pages = (int) ceil($getCountReservas / LIMIT);


            if (!$stmtResevas->execute()) {

                $result = [
                    'status' => false,
                    'data' => [],
                    'message' => $stmtResevas->error_list
                ];
                return $result;
            }
            $resultReservas = $stmtResevas->get_result();

            $dataReservas = [];
            while ($row = $resultReservas->fetch_assoc()) {

                $dataReservas[] = $row;
            }

            // Aplico recursividad en caso de que la cantidad de registros sea 0 para que vuelva a ejecutar dicha función.
            if (count($dataReservas) === 0 && $page !== 1) {
                return $this->selectDetailReserva(1, $type);
            }

            return [
                'status' => count($dataReservas) == 0 ? false : true,
                'data' => $dataReservas,
                'message' => count($dataReservas) > 0 ? 'reservas encontradas' : 'no hay reservas registradas',
                'pages' => $pages,
                'totalRows' => $getCountReservas
            ];
        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    public function selectElementsReserva(int $codigo = 0)
    {
        $conn = $this->conect->getConnect();
        try {

            $sqlElementsReserva = "SELECT DISTINCT
            el.elm_cod AS 'codigo',
            el.elm_serie AS 'seriElemento',
            el.elm_nombre AS 'nombre',
            en_s.ent_sal_cantidad AS 'cantidadSolicitada',
            tpE.tp_el_cod AS 'codTipoElemento',
            tpE.tp_el_nombre AS 'nombreTipoElemento'
        FROM
            elementos el
            LEFT JOIN prestamos_elementos prel ON prel.pres_el_elem_cod = el.elm_cod
            LEFT JOIN prestamos pre ON pre.pres_cod = prel.pres_cod
            INNER JOIN entradas_salidas en_s ON el.elm_cod = en_s.ent_sal_cod_elemtn
                AND en_s.ent_sal_cod_prestamo = prel.pres_cod
            RIGHT JOIN tipo_elemento tpE ON el.elm_cod_tp_elemento = tpE.tp_el_cod
        WHERE
            prel.pres_cod = ?
            AND en_s.ent_sal_cod_prestamo = ?";

            $stmtResevasElm = $conn->prepare($sqlElementsReserva);
            // $stmtResevasElm->bind_param('i', $codigo);
            $stmtResevasElm->bind_param('ii', $codigo, $codigo);

            if (!$stmtResevasElm->execute()) {
                return null;
            }

            $resultSave = $stmtResevasElm->get_result();

            $data = [];

            while ($row = $resultSave->fetch_assoc()) {
                $data[] = $row;
            }

            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    /**
     * Función para validar las solicitudes que hacen tanto el usuario instructor como aprendices.
     * @return array
     * 
     */
    public function validateSolicitud(array $data = [], int $cedula = 0)
    {
        $conn = $this->conect->getConnect();

        try {
            $conn->begin_transaction();

            $elmConsumiblesCon = $data['elementosSalida']['elmConsumibles'];
            $elmDevolutivosDev = $data['elementosSalida']['elmDevolutivos'];

            if (!isset($data['elementosSalida']) && !in_array($data['elementosSalida'], $data['elmentosSalida']['elmConsumibles'])) {
                return ['message' => 'valores no enviados correctamente', 'status' => false];
            }

            $responseIdUsuario = $this->usuario->searchU($cedula, true);
            $idUsuario = $responseIdUsuario['data'];
            $id = $idUsuario['usu_id'];

            $codigoPrestamo = $data['codigoReserva']; // Es un solo ID, no array
            $observacionSalida = $data['observacionSalida'];

            // Primera transacción : actualizo el estado del prestamo, FUNCIONA.
            $estado = 1; // Validado
            $query = "UPDATE prestamos SET pres_estado = ? WHERE pres_cod = ?";
            $stmtValidate = $conn->prepare($query);
            $stmtValidate->bind_param('ii', $estado, $codigoPrestamo);

            if (!$stmtValidate->execute()) {
                $conn->rollback();
                return [
                    'message' => $stmtValidate->error,
                    'status' => false
                ];
            }

            // //Segunda transacción: inserto los elementos VALIDADOS POR EL USUARIO para su entrega

            // //Teniendo ya el codigo del prestamo, accedemos a el y actualizamos los elementos
            $queryPrestamosElementos = "UPDATE prestamos_elementos SET pres_el_cantidad = ? WHERE pres_cod = ? AND pres_el_elem_cod = ?";

            // Consumibles
            $stmtPrestamosElementos = $conn->prepare($queryPrestamosElementos);
            if (!$stmtPrestamosElementos) {
                $conn->rollback();
                return [
                    'message' => "error al preparar la consulta",
                    'status' => false
                ];
            }

            //Consumibles.
            foreach ($elmConsumiblesCon as $value) {
                $codigoElemento = (int) $value['cod'];
                $cantidad = (int) $value['cantidadSalida'];
                $stmtPrestamosElementos->reset();
                $stmtPrestamosElementos->bind_param('iii', $cantidad, $codigoPrestamo, $codigoElemento);

                if (!$stmtPrestamosElementos->execute()) {
                    $conn->rollback();
                    return [
                        'message' => "error al preparar la consulta $stmtPrestamosElementos->error",
                        'status' => false
                    ];
                }
            }

            //Devolutivos
            foreach ($elmDevolutivosDev as $key => $value) {
                $codDevolutivo = (int) $value['cod'];
                $cantidad = (int) $value['cantidadSalida'];
                $stmtPrestamosElementos->reset();
                $stmtPrestamosElementos->bind_param('iii', $cantidad, $codigoPrestamo, $codDevolutivo);
                if (!$stmtPrestamosElementos->execute()) {
                    $conn->rollback();
                    return [
                        'message' => "error al preparar la consulta $stmtPrestamosElementos->error",
                        'status' => false
                    ];
                }
            }


            /**
             * De aca, se debe restar las cantidades de los elementos y registrarlas en las entradas_salidas
             *
             */

            // //Disminumos la cantidad al consumible
            foreach ($elmConsumiblesCon as $key => $value) {
                $codConsumible = (int) $value['cod'];
                $cantidad = (int) $value['cantidadSalida'];

                // Disminuye existencia de elemento consumible sin tocar estado
                $this->modelElemento->disminuirExistenciaElemento($codConsumible, $cantidad);
            }

            $statusPrestamo = 3;
            // // Tercera transacción: actualizo el estado del elemento devolutivo de reservado a PRESTADO.
            $queryUpdateStatus = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $stmtQueryStatus = $conn->prepare($queryUpdateStatus);
            foreach ($elmDevolutivosDev as $key => $value) {
                $codDevolutivo = (int) $value['cod'];
                $stmtQueryStatus->reset();
                $stmtQueryStatus->bind_param('ii', $statusPrestamo, $codDevolutivo);

                if (!$stmtQueryStatus->execute()) {
                    return [
                        'message' => "proceso cancelado $stmtQueryStatus->error",
                        'status' => false
                    ];
                }
            }

            // // cuarta transacción : inserto los elementos que se han validado.
            $queryValidateSalida = "INSERT INTO entradas_salidas (
                ent_sal_cantidad,
                ent_fech_registro,
                ent_sal_observacion,
                entr_tp_movmnt, 
                ent_id_usu,
                ent_sal_cod_elemtn,
                ent_sal_cod_prestamo
            ) VALUES (?, NOW(), ?, ?, ?, ?, ?)";

            $fechaSolicitud  = $data['dataUsuario']['fechaSolicitud'];
            $tipoMovimiento = 2;
            //ya esta, está más arriba.

            $stmtValidateSalida = $conn->prepare($queryValidateSalida);

            //devolutivos.
            foreach ($elmDevolutivosDev as $value) {
                $cantidad = (int) $value['cantidadSalida'];
                $codigoElemento = (int) $value['cod'];
                $stmtValidateSalida->reset();
                $stmtValidateSalida->bind_param(
                    'isiiii',
                    $cantidad,          // i
                    $observacionSalida, // s
                    $tipoMovimiento,    // i
                    $id,                // i
                    $codigoElemento,    // i
                    $codigoPrestamo     // i
                );

                if (!$stmtValidateSalida->execute()) {
                    $conn->rollback();
                    return [
                        'message' => $stmtValidateSalida->error,
                        'status' => false
                    ];
                }
            }

            //consumibles
            foreach ($elmConsumiblesCon as $item) {
                $cantidad = (int) $item['cantidadSalida'];
                $codigoElemento = (int) $item['cod'];
                $stmtValidateSalida->reset();
                $stmtValidateSalida->bind_param(
                    'isiiii',
                    $cantidad,          // i
                    $observacionSalida, // s
                    $tipoMovimiento,    // i
                    $id,                // i
                    $codigoElemento,    // i
                    $codigoPrestamo     // i
                );

                if (!$stmtValidateSalida->execute()) {
                    $conn->rollback();
                    return [
                        'message' => $stmtValidateSalida->error,
                        'status' => false
                    ];
                }
            }

            $conn->commit();

            return [
                'message' => 'Solicitud validada con éxito',
                'status' => true
            ];
        } catch (Exception $e) {
            $conn->rollback();
            return [
                'message' => $e->getMessage(),
                'status' => false
            ];
        }
    }
    /**
     * Summary of getCount Con esta función puedo saber el total de cantidad de registros que hay disponibles.
     * @param string $tableName
     * @return array|int
     */
    public function getCount(String $query = '', String $tableName = '')
    {
        $conn = $this->conect->getConnect();
        $stmtCount = $conn->prepare($query);

        // $stmtCount->bind_param("i",$type);

        if (!$stmtCount->execute()) {
            return [
                'message' => 'error',
                'status' => false
            ];
        }

        $resultCount = $stmtCount->get_result();
        return $resultCount->fetch_assoc()['Total'];
    }

    /**
     * Función que pertenece a tarea automática para cancelar las reservas que se hayan pasado de fecha.
     * @return void
     */
    public function cancelarPrestamosFecha()
    {
        try {
            $conn = $this->conect->getConnect();

            $conn->begin_transaction();
            $newFecha = new DateTime('now', new DateTimeZone('America/Bogota'));

            $fecha = $newFecha->format('Y-m-d');

            // Primera transaccion: capturar los prestamos asociados a una fecha anterior.
            $sql = "SELECT 
            p.pres_cod AS 'codigoPrestamo', 
            p.pres_fch_reserva AS 'fechaReserva'
            FROM prestamos p 
            WHERE p.pres_fch_reserva < ? && p.pres_estado = 3";

            $stmtFirst = $conn->prepare($sql);


            $stmtFirst->bind_param("s", $fecha);

            if (!$stmtFirst->execute()) {
                $conn->rollback();
                $conn->close();
                // Agregar throw de error de procedimiento.
            }

            $result = $stmtFirst->get_result();

            $prestamosResult = [];
            while ($row = $result->fetch_assoc()) {
                $prestamosResult[] = $row['codigoPrestamo'];
            }


            if (count($prestamosResult) === 0) return;

            // Segunda transacción: traer los elementos que pertenezcan a los elementos y aplicar su cambio de estado a reservado.
            $secondSlq = "SELECT 
                el.elm_cod AS 'codigoElemento',
                el.elm_serie AS 'serieElemento',
                el.elm_nombre AS 'nombreElemento',
                el.elm_cod_estado AS 'estadoElemento',
                p.pres_cod AS 'codigoPrestamo',
                p.pres_fch_reserva AS 'fechaReserva'
                FROM prestamos p 
                INNER JOIN prestamos_elementos pre ON
                pre.pres_cod = p.pres_cod 
                INNER JOIN elementos el ON
                pre.pres_el_elem_cod = el.elm_cod 
                WHERE p.pres_fch_reserva < ? AND p.pres_cod = ? ORDER BY p.pres_cod ASC";

            $thirdSql = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $fourSql = "UPDATE prestamos SET pres_estado = ? WHERE pres_cod = ?";
            $stmtSecond = $conn->prepare($secondSlq);
            $stmtThird = $conn->prepare($thirdSql);
            $stmtFour = $conn->prepare($fourSql);

            $estadoPrestamos = 5;
            $estadoElementos = 1;

            foreach ($prestamosResult as $value) {
                $codigoPrestamo = (int) $value;

                $stmtSecond->bind_param('si', $Fecha, $codigoPrestamo);

                if (!$stmtSecond->execute()) {
                    $conn->rollback();
                }

                $result = $stmtSecond->get_result();

                $dataElements = [];
                while ($row = $result->fetch_assoc()) {
                    $dataElements[] = $row['codigoElemento'];
                }

                // Tercera transacción: Actualizar estados de los elementos a disponible.
                foreach ($dataElements as $value) {
                    $codigo = (int) $value;

                    $stmtThird->bind_param('ii', $estadoElementos, $codigo);

                    if (!$stmtThird->execute()) {
                        $conn->rollback();
                    }
                }

                $stmtFour->bind_param('ii', $estadoPrestamos, $codigoPrestamo);
                if (!$stmtFour->execute()) {
                    $conn->rollback();
                }
            }

            $conn->commit();
        } catch (Exception $e) {

            $conn->rollback();
            // Esto se guarda en mi archivo php_error.log
            error_log("Error al ejecutar la transacción de cancelarPrestamo fecha".$e->getMessage());
        // Aplico un finally cuando todo el proceso ocurre
        } finally {
            $conn->close();
        }
    }
    
    public function cancelarPrestamo(int $codPrestamo = 0){
        try {
            $conn = $this->conect->getConnect();

            $conn->begin_transaction();
            //Objetivos:
            // 1 - Traer los elementos relacionados al prestamo
            // 2 - Actualizar los estados del elemento
            // 3 - Actualizar el prestamo de por validar a cancelado.

            // Primera transacción - traer los elementos relacionados al prestamo.
            $sqlGetElementsPrestamo = "SELECT 
                el.elm_cod AS 'codigoElemento',
                el.elm_cod_estado AS 'estadoActual'
                FROM elementos el
                INNER JOIN prestamos_elementos prel ON
                prel.pres_el_elem_cod = el.elm_cod 
                INNER JOIN prestamos p ON
                prel.pres_cod = p.pres_cod WHERE p.pres_cod = ?";

                $stmt1 = $conn->prepare($sqlGetElementsPrestamo);

                $stmt1->bind_param('i', $codPrestamo);

                if (!$stmt1->execute()) {
                    $conn->rollback();
                    return [
                        'status'=>false,
                        'message'=> "Error al traer elementos".$conn->error,
                        'data'=> []
                    ];
                }

                $elementosPrestamo = [];

                $result1 = $stmt1->get_result();
                while($row = $result1->fetch_assoc()){
                    $elementosPrestamo[]= $row;
                }

            // Segunda transacción - Actualizar estado de los elementos.
            $updateElementos = "UPDATE elementos el SET elm_cod_estado = 1 WHERE el.elm_cod = ?";
            $stmt2 = $conn->prepare($updateElementos);
            
            foreach ($elementosPrestamo as $key => $value) {
                $codigoElemento = $value['codigoElemento'];
                $stmt2->bind_param('i', $codigoElemento);
                if (!$stmt2->execute()) {
                    $conn->rollback();
                    return [
                        'status'=>false,
                        'message'=> "Error al actualizar código elemento".$conn->error,
                        'data'=> []
                    ];
                }
            }

            // Tercera transacción - Actualizar prestamo, de por validar a cancelado.
            $updatePrestamo = "UPDATE prestamos pe SET pe.pres_estado = 5 WHERE pe.pres_cod = ?";
            $stmt3 = $conn->prepare($updatePrestamo);

            $stmt3->bind_param('i',$codPrestamo);

            if (!$stmt3->execute()) {
                $conn->rollback();
                return [
                    'status'=>false,
                    'message'=> "Error al actualizar el estado del prestamo".$conn->error,
                    'data'=>[]
                ];
            }

            $conn->commit();

            return [
                'status'=> true,
                'message'=> "Prestamo ".$codPrestamo." cancelado con exito",
                'data'=> []
            ];

        } catch (\Throwable $e) {
            return [
                'status'=>false,
                'message'=> "Error al ejecutar el procesod".$e->getMessage(),
                'data'=>[]
            ];
        }
    }
}
