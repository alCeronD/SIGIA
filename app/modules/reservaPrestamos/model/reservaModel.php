<?php
require_once __DIR__ . '/../../../helpers/session.php';
include_once __DIR__ . '/../../../config/conn.php';


//TODO: en los mensajes de retorno, definir una estructura de retorno específica, así evitar devolver o valores null, o un string, la idea es que devuelva un array con su status y mensaje, en todos los retornos.
class ReservaModel
{
    private $conect;

    public function __construct()
    {
        $this->conect = new Conection();
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
                return $stmtUser->error;
            }
            $resultId = $stmtUser->get_result();
            $userRow = $resultId->fetch_assoc();
            if (!$userRow) {
                $conn->rollback();
                return "Usuario con cédula $cedula no encontrado.";
            }
            $id = (int) $userRow['id'];
            //segunda transacción, insertar los registros en el prestamo.
            $presSql = "INSERT INTO prestamos (pres_fch_slcitud,pres_fch_reserva,pres_hor_inicio,pres_hor_fin,pres_fch_entrega,pres_observacion,pres_destino,pres_estado,tp_pres,pres_rol) VALUES (NOW(),?,?,?,?,?,?,?,?,?)";

            $stmtPres = $conn->prepare($presSql);

            if (!$stmtPres) {
                $conn->rollback();
                return $conn->error;
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
                return $conn->error;
            }
            //Capturo el id del prestamo, lo voy a usar para insertar en la tabla prestamos_elementos.
            $lastId = $conn->insert_id;

            //tercera para actualizar el estado de los elementos devolutivos
            $updateStatusElements = "UPDATE elementos SET elm_cod_estado = ? WHERE elm_cod = ?";
            $stmtUpdateStatus = $conn->prepare($updateStatusElements);
            $status = 3;
            $codigosDevolu = array_column($codDevolu,'codigo');
            foreach ($codigosDevolu as $elementos) {
                $stmtUpdateStatus->bind_param('ii', $status, $elementos);

                if (!$stmtUpdateStatus->execute()) {
                    $conn->rollback();
                    return $stmtUpdateStatus->error;
                }
            }

            $cantidadConsumibles = array_column($codConsumibles,'cantidad');
            $codidogConsumibles = array_column($codConsumibles,'codigo');

            //Cuarta transacción, traer la cantidad disponible del elemento.
            $sqlGetCantidad = "SELECT elm_existencia FROM elementos WHERE elm_cod = ?";
            
            
            //Cuarta transacción, reducir la cantidad de elementos a los elementos consumibles.
            $sqlConsumibles = "UPDATE elementos SET elm_existencia = ? WHERE elm_cod = ?";
            $stmtGetCantidad = $conn->prepare($sqlGetCantidad);
            $stmtConsumibles = $conn->prepare($sqlConsumibles);

            foreach ($codidogConsumibles as $key => $value) {
                //Parámetros para traer la cantidad de existencias.
                $stmtGetCantidad->bind_param('i',$value);

                if (!$stmtGetCantidad->execute()) {
                    $conn->rollback();
                    return $stmtGetCantidad->error;
                }
                $cantidadResult = $stmtGetCantidad->get_result();
                $cantidad = $cantidadResult->fetch_assoc()['elm_existencia'];
                // var_dump("cantidad Extraida $cantidad");
                $cantidadTotal = $cantidad - $cantidadConsumibles[$key];
                // var_dump("cantidad existencia $cantidadTotal");

                $stmtConsumibles->bind_param('ii',$cantidadTotal,$value);

                if (!$stmtConsumibles->execute()) {
                    $conn->rollback();
                    return $stmtConsumibles->error;
                }
            }

            //Query para reportar la salida de los elementos consumibles.
            $sqlInsertSalida = "INSERT INTO entradas_salidas (ent_sal_cantidad,ent_fech_registro,entr_tp_movmnt,ent_id_usu,ent_sal_cod_elemtn) VALUES(?,NOW(),?,?,?)";

            $stmtSalida = $conn->prepare($sqlInsertSalida);
            //Salida
            $tipoMovimento = 2;

            foreach ($codConsumibles as $element) {
                //Extraigo la cantidad y el código del elemento.
                $codConsumibles = $element['codigo'];
                $cant= $element['cantidad'];
                $stmtSalida->bind_param('iiii',$cant,$tipoMovimento,$id,$codConsumibles);

                if (!$stmtSalida->execute()) {
                    $conn->rollback();
                    return $stmtConsumibles->error;
                }
            }

            $codsDevel = array_column($codDevolu, 'codigo');

            //quinta transacción insertar los elementos devolutivos en la tabla prestamos_elementos.
            $sqlElementosDev = "INSERT INTO prestamos_elementos (pres_cod,pres_el_usu_id,pres_el_elem_cod) VALUES(?,?,?)";
            $stmtElementosDev = $conn->prepare($sqlElementosDev);
            foreach ($codsDevel as $value) {
                $value = (int) $value;
                $stmtElementosDev->bind_param('iii', $lastId, $id, $value);

                if (!$stmtElementosDev->execute()) {
                    $conn->rollback();
                    return $stmtElementos->error;
                }
            }

            //Sexta transacción, insertar los elementos consumibles
            $sqlElementosConsu = "INSERT INTO prestamos_elementos (pres_cod,pres_el_usu_id,pres_el_elem_cod) VALUES(?,?,?)";
            $stmtElementosConsu = $conn->prepare($sqlElementosConsu);
            foreach ($codidogConsumibles as $value) {
                $value = (int) $value;
                $stmtElementosConsu->bind_param('iii',$lastId,$id,$value);

                if (!$stmtElementosConsu->execute()) {
                    $conn->rollback();
                    return $stmtElementosConsu->error;

                }
            }

            //TODO: Registrar la salida del elemento, definir como la voy a implementar.
            //TODO: definir cuando voy a hacer una reserva, si tiene elementos consumibles, devolutivos o ambos.
            //TODO: los elementos consumibles no deben de cambiar su estado.
            $result = [
                'data' => [],
                'status' => true
            ];

            $conn->commit();
            
        } catch (\Throwable $th) {
            $conn->rollback();
            return $th->getMessage();
        }

        return $result;
    }

    public function updateReserva() {}

    //Función para finalizar la reserva y todos los elementos cambiar sus respectivos estados.
    public function endReserva($elementos, $codigo) {
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

            $codElementos = array_column($elementos,'codigo');

            //Primera transacción.
            foreach ($codElementos as $value) {
                $stmtStatus->bind_param('ii',$disponible,$value);

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
            $stmtEndReserva->bind_param('ii',$prestamoStatus,$codigo);

            if (!$stmtEndReserva->execute()) {
                $conn->rollback();
                //var_dump($stmtStatus->error);
                return $stmtEndReserva->error;
            }

            $conn->commit();
            $conn->close();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }




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

            $stmtCount->bind_param("i",$type);

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

            $stmt->bind_param('iii', $type,$limit, $offset);

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
                'type'=>$type
            ];

            return $results;
        } catch (\Throwable $th) {
            $conn->rollback();
            $conn->close();
            return  $th->getMessage();
        }
    }
    public function selectElementsConsumibles(int $page = 1){
        $conn = $this->conect->getConnect();
        try {

        } catch (\Throwable $th) {
            //throw $th;
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

    public function selectDetailReserva()
    {
        $conn = $this->conect->getConnect();
        try {

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
                r.rl_nombre AS rol,
                tp_pre.tp_nombre AS tipoPrestamo
                FROM prestamos pre
                INNER JOIN prestamos_elementos pre_el ON pre_el.pres_cod = pre.pres_cod
                INNER JOIN usuarios us ON pre_el.pres_el_usu_id = us.usu_id
                INNER JOIN estados_prestamos esp ON esp.es_pr_cod = pre.pres_estado
                INNER JOIN roles r ON r.rl_id = pre.pres_rol
                INNER JOIN tipo_prestamo tp_pre ON tp_pre.tp_pre = pre.tp_pres";

            $stmtResevas = $conn->prepare($sqlReservas);

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
                'status' => true,
                'data' => $dataReservas,
                'message' => 'reservas'
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
    public function selectElementsReserva(int $codigo = 0){
        $conn = $this->conect->getConnect();
        try {
            //Consulta para traer los elementos basado en el código del prestamo.
            //TODO: Mejorar consulta, esta consulta debe de traerme la cantidad de los elementos consumibles.
            $sqlElementsReserva = "SELECT 
                el.elm_cod AS 'codigo',
                el.elm_nombre AS 'nombre'
                FROM elementos el
                RIGHT JOIN prestamos_elementos prel ON
                el.elm_cod = prel.pres_el_elem_cod 
                LEFT JOIN prestamos pre ON
                pre.pres_cod = prel.pres_cod
                WHERE prel.pres_cod = ?";


                $stmtResevasElm = $conn->prepare($sqlElementsReserva);
                $stmtResevasElm->bind_param('i',$codigo);

                if (!$stmtResevasElm->execute()) {
                    return null;
                }

                $resultSave = $stmtResevasElm->get_result();

                $data = [];

                while ($row = $resultSave->fetch_assoc()) {
                    $data [] = $row;
                }

                return $data;


                
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
