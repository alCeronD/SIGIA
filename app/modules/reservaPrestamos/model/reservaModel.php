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
            echo json_encode(["error" => "Excepción: " . $th->getMessage()]);
            exit();
        }
    }
}
