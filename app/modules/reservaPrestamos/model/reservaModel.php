<?php
require_once __DIR__ . '/../../../helpers/session.php';
include_once __DIR__ . '/../../../config/conn.php';

class ReservaModel
{
    private $conect;

    public function __construct()
    {
        $this->conect = new Conection();
    }

    public function insertReserva(array $data=[], array $elements=[]) {
        $conn = $this->conect->getConnect();



        try {
            $conn->begin_transaction();

            //Primera transacción, insertar los registros en el prestamo.
            $presSql = "INSERT INTO prestamos (pres_fch_slcitud,pres_fch_reserva,pres_hor_inicio,pres_hor_fin,pres_fch_entrega,pres_observacion,pres_destino,pres_estado,tp_pres,pres_rol) VALUES (NOW(),?,?,?,?,?,?,?,?,?)";

            $stmtPres = $conn->prepare($presSql);

            extract($data);
            $stmtPres->bind_param(
                'ssssssiii',
                $pres_fch_reserva,
                $pres_hor_inicio,
                $pres_hor_fin,
                $pres_fch_entrega,
                $pres_observacion,
                $pres_destino,
                $pres_estado,
                $tp_pres,
                $pres_rol
            );

            if (!$stmtPres->execute()) {
                return null;
            }

            




            //Segunda transacción insertar los registros en la tabla prestamos_elementos.



            //$conn->commit();
        } catch (\Throwable $th) {
            $conn->rollback();
        }


    }

    public function updateReserva() {}

    //Función para finalizar la reserva y todos los elementos cambiar sus respectivos estados.
    public function endReserva() {}


    //Función para traer los elementos, posiblemente deba implementarla en el modelo de elementos, no en el modelo de reserva.
    public function selectElements(int $page = 1)
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
                el.elm_cod_estado = 1 AND el.elm_cod_tp_elemento = 1 AND ar.ar_status = 1";

            $stmtCount = $conn->prepare($countElements);

            if (!$stmtCount->execute()) {
                return null;
            }

            $resultCount = $stmtCount->get_result();
            $rows = $resultCount->fetch_assoc()['Total'];

            $limit = 10;

            //Numero de páginas en base a la cantidad de elementos, redondeo hacía el número más grande.
            
            /**
             * @var $page - Es el parámetro que le mando a los elementos.
             */
            $offset = ($page - 1) * $limit;
            //Cantidad de páginas.
            $pages = (int) ceil($rows / $limit);
            //var_dump($pages);
            $sql = "SELECT
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
            el.elm_cod_estado = 1 AND el.elm_cod_tp_elemento = 1 AND ar.ar_status = 1 ORDER BY el.elm_cod ASC LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param('ii',$limit, $offset);

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
                'pages' => $pages
            ];

            return $results;
            //var_dump($data);
        } catch (\Throwable $th) {
            $conn->rollback();
            $conn->close();
            return  $th->getMessage();
        }
    }

    //Función apra traer los elementos, posiblemente da implementarla en el modelo de usuarios.
    public function selectUsers($pages){

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
            return $th->getMessage();
        }
    }
}
