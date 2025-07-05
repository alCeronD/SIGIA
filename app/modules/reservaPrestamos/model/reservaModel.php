<?php
require_once __DIR__ . '/../../../helpers/session.php';
require_once __DIR__ . '/../../../helpers/const.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../usuarios/model/usuariosModel.php';
require_once __DIR__ . '/../../elementos/model/elementosModel.php';


//TODO: en los mensajes de retorno, definir una estructura de retorno específica, así evitar devolver o valores null, o un string, la idea es que devuelva un array con su status y mensaje, en todos los retornos.
class ReservaModel
{
    private $conect;

    protected $id;

    protected $usuario;

    private $modelElemento;

    public function __construct()
    {
        $this->conect = new Conection();
        $this->usuario = new usuarios($this->conect);
        $this->modelElemento = new ElementoModelo();
    }

    public function insertReserva(array $data = [], array $codDevolu = [], array $codConsumibles = [])
    {

        $conn = $this->conect->getConnect();

        try {
            $conn->begin_transaction();

            $cedula = (int) $data["cedula"];
            unset($data["cedula"]);

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
            //segunda transacción, insertar los registros en el prestamo.
            $presSql = "INSERT INTO prestamos (pres_fch_slcitud,pres_fch_reserva,pres_hor_inicio,pres_hor_fin,pres_fch_entrega,pres_observacion,pres_destino,pres_estado,tp_pres,pres_rol) VALUES (NOW(),?,?,?,?,?,?,?,?,?)";

            $stmtPres = $conn->prepare($presSql);


            if (!$stmtPres) {
                $conn->rollback();
                return [
                    'message' => "error al preparar la consulta",
                    'status' => false
                ];
            }
            //Debo usar esta por el tema de la versión de php.
            extract($data, EXTR_PREFIX_ALL, 'p');
            $stmtPres->bind_param(
                'ssssssiii',
                $p_pres_fch_reserva,
                $p_pres_hor_inicio,
                $p_pres_hor_fin,
                $p_pres_fch_entrega,
                $p_pres_observacion,
                $p_pres_destino,
                $p_pres_estado,
                $p_tp_pres,
                $p_pres_rol
            );
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

            //tercera para actualizar el estado de los elementos devolutivos
            $updateStatusElements = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $stmtUpdateStatus = $conn->prepare($updateStatusElements);
            $status = 3;
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

            $sqlInsertSalida = "INSERT INTO entradas_salidas (ent_sal_cantidad,ent_fech_registro,entr_tp_movmnt,ent_id_usu,ent_sal_cod_elemtn,ent_sal_cod_prestamo) VALUES(?,NOW(),?,?,?,?)";

            $stmtSalida = $conn->prepare($sqlInsertSalida);
            //Salida
            $tipoMovimento = 2;

            foreach ($codConsumibles as $element) {
                //Extraigo la cantidad y el código del elemento.
                $codigo = $element['codigo'];
                $cant = $element['cantidad'];

                $stmtSalida->bind_param('iiiii', $cant, $tipoMovimento, $id, $codigo, $lastId);
                if (!$stmtSalida->execute()) {
                    $conn->rollback();
                    $result = [
                        'message' => "$stmtConsumibles->error",
                        'status' => false
                    ];

                    return $result;
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
    public function updateReserva() {}

    //Función para finalizar la reserva y todos los elementos cambiar sus respectivos estados.
    public function endReserva($elementos, $codigo)
    {
        //Objetivo:
        /**
         * 1- Actualizar el estado de los elementos a disponible.
         * 2- Actualizar el estado del prestamo a finalizado.
         */
        try {
            $conn = $this->conect->getConnect();
            $conn->begin_transaction();
            $disponible = 1;
            $sqlStatus = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";

            $stmtStatus = $conn->prepare($sqlStatus);

            $codElementos = array_column($elementos, 'codigo');

            //Primera transacción.
            foreach ($codElementos as $value) {
                $stmtStatus->bind_param('ii', $disponible, $value);

                if (!$stmtStatus->execute()) {

                    $conn->rollback();
                    // var_dump($stmtStatus->error);
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
                //var_dump($stmtStatus->error);
                return $stmtEndReserva->error;
            }

            //TODO: registrar en entradas_salidas la parte de finalizar el prestamo.
            /** 
             * 
             * 
            */

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
            $limit = 10;

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
     * Summary of selectReservas TODO: Cambiar a Páginado con javascript.
     * @return array{data: array, message: string, status: bool|string}
     */
    public function selectDetailReserva(int $page = 1)
    {
        //valido que el page que mande sea como minimo 1 y Máximo la cantidad requerida.
        $page = max(1, (int)$page);

        $conn = $this->conect->getConnect();
        try {
            $queryCountReservas = "SELECT COUNT(DISTINCT pre.pres_cod) AS Total
                FROM prestamos pre
                INNER JOIN prestamos_elementos pre_el ON pre_el.pres_cod = pre.pres_cod
                INNER JOIN usuarios us ON pre_el.pres_el_usu_id = us.usu_id
                INNER JOIN estados_prestamos esp ON esp.es_pr_cod = pre.pres_estado
                INNER JOIN roles r ON r.rl_id = pre.pres_rol
                INNER JOIN tipo_prestamo tp_pr ON tp_pr.tp_pre = pre.tp_pres";
            //Obtengo la cantidad de registros de la tabla prestamos
            $getCountReservas = (int) $this->getCount($queryCountReservas, 'prestamos');

            $offset = ($page - 1) * LIMIT;
            //Cantidad de páginas.
            $pages = (int) ceil($getCountReservas / LIMIT);

            $sqlReservas = "SELECT DISTINCT
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
                INNER JOIN tipo_prestamo tp_pr ON tp_pr.tp_pre = pre.tp_pres ORDER BY pre.pres_fch_slcitud ASC LIMIT ? OFFSET ?";
            $stmtResevas = $conn->prepare($sqlReservas);
            $limitConst = LIMIT;

            $stmtResevas->bind_param('ii', $limitConst, $offset);

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

            $result = [
                'status' => count($dataReservas) == 0 ? false : true,
                'data' => $dataReservas,
                'message' => count($dataReservas) > 0 ? 'reservas encontradas' : 'no hay reservas registradas',
                'pages' => $pages,
                'totalRows' => $getCountReservas
            ];
            return $result;
        } catch (\Throwable $e) {

            $result = [
                'status' => false,
                'message' => $e->getMessage()
            ];
            return $result;
        }
    }
    public function selectElementsReserva(int $codigo = 0)
    {
        $conn = $this->conect->getConnect();
        try {

            $sqlElementsReserva = "SELECT 
                el.elm_cod AS 'codigo',
                el.elm_nombre AS 'nombre',
                `tpE`.tp_el_cod AS 'codTipoElemento',
                `tpE`.tp_el_nombre as 'nombreTipoElemento',
                prel.pres_el_cantidad AS 'cantidadSolicitada'
                FROM elementos el
                RIGHT JOIN prestamos_elementos prel ON
                el.elm_cod = prel.pres_el_elem_cod 
                LEFT JOIN prestamos pre ON
                pre.pres_cod = prel.pres_cod
                LEFT JOIN tipo_elemento tpE ON
                el.elm_cod_tp_elemento = `tpE`.tp_el_cod
                WHERE prel.pres_cod = ?";


            $stmtResevasElm = $conn->prepare($sqlElementsReserva);
            $stmtResevasElm->bind_param('i', $codigo);

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
     */
    public function validateSolicitud(array $data = [], int $cedula = 0)
    {
        $conn = $this->conect->getConnect();

        try {
            $conn->begin_transaction();

            $elmConsumibles = $data['elementos']['elmConsumibles'];
            $elmDevolutivos = $data['elementos']['elmDevolutivos'];

            $idUsario = $this->usuario->searchU($cedula, true);
            $id = $idUsario['usu_id'];

            $codigoPrestamo = $data['codigoReserva']; // Es un solo ID, no array
            $observacionSalida = $data['observacionSalida'];
            // var_dump($data);

            // Primera transacción : actualizo el estado del prestamo
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
            $queryPrestamosElementos = "UPDATE prestamos_elementos SET pres_el_cantidad = ? WHERE pres_cod = ? AND pres_el_elm_cod = ?";

            // Consumibles
            $stmtPrestamosElementos = $conn->prepare($queryPrestamosElementos);
            foreach ($elmConsumibles as $key => $value) {
                $codigoElemento = (int) $value['cod'];
                $cantidad = (int) $value['cantidadSalida'];
                $stmtPrestamosElementos->reset();
                $stmtPrestamosElementos->bind_param('iii', $cantidad,$codigoPrestamo,$codigoElemento);

                if (!$stmtPrestamosElementos->execute()) {
                    $conn->rollback();
                    return [
                        'message' => "error al preparar la consulta $stmtPrestamosElementos->error",
                        'status' => false
                    ];
                }
            }

            // devolutivos
            foreach ($elmDevolutivos as $key => $value) {
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

            //Disminumos la cantidad al consumible
            foreach ($elmConsumibles as $key => $value) {
                $codConsumible = (int) $value['cod'];
                $cantidad = (int) $value['cantidadSalida'];
                
                // Disminuye existencia de elemento consumible sin tocar estado
                $this->modelElemento->disminuirExistenciaElemento($codConsumible, $cantidad);
            }

            $statusPrestamo = 3;
            // // Tercera transacción: actualizo el estado del elemento devolutivo de reservado a PRESTADO.
            $queryUpdateStatus = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $stmtQueryStatus = $conn->prepare($queryUpdateStatus);
            foreach ($elmDevolutivos as $key => $value) {
                $codDevolutivo = (int) $value['cod'];
                $stmtQueryStatus->reset();
                $stmtQueryStatus->bind_param('ii', $statusPrestamo,$codDevolutivo);

                if (!$stmtQueryStatus->execute()) {
                    return [
                        'message'=> "proceso cancelado $stmtQueryStatus->error",
                        'status'=> false
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
            foreach ($elmDevolutivos as $value) {
                $cantidad =(int) $value['cantidadSalida'];
                $codigoElemento = (int) $value['cod'];
                $stmtValidateSalida->reset();
                $stmtValidateSalida->bind_param('isiiii',
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
            foreach ($elmConsumibles as $item) {
                $cantidad = (int) $item['cantidadSalida'];
                $codigoElemento = (int) $item['cod'];
                $stmtValidateSalida->reset();
                $stmtValidateSalida->bind_param('isiiii',
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
}
