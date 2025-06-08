<?php

include_once __DIR__ . '/../../../config/conn.php';

class ReservaModel
{
    private $conect;

    public function __construct()
    {
        $this->conect = new Conection();
    }

    public function addReserva() {}

    public function updateReserva() {}

    //Función para finalizar la reserva y todos los elementos cambiar sus respectivos estados.
    public function endReserva() {}

    public function selectElements()
{
    $conn = $this->conect->getConnect();

    try {
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
            el.elm_cod_estado = 1 AND el.elm_cod_tp_elemento = 1 AND ar.ar_status = 1";

        $stmt = $conn->prepare($sql);

        if (!$stmt->execute()) {
            echo json_encode(["error" => "Error al ejecutar la consulta"]);
            exit();
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $conn->close();

        return $data;

        } catch (\Throwable $th) {
            return  $th->getMessage();
        }
    }
    
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

            //Cambiar, se ejecuta pero no debe de ir un exit, debe de ir un return.
            $stmtCount->execute();

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
            $limit = 4;
            $page = ceil($registros / $limit);

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
                WHERE r.rl_id != 2 AND r.rl_status = 1 AND us.usu_id_estado = 1 LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii',$pages,$limit);
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

            //var_dump($data);
        } catch (\Throwable $th) {
            $conn->rollback();
            return $th->getMessage();
        }
    }
}

// $objPrueba = new ReservaModel();
// $objPrueba->selectUsers();