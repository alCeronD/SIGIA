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
                    el.elm_nombre AS Elemento,
                    tp.tp_el_nombre AS tipoElemento
                FROM elementos el
                INNER JOIN tipo_elemento tp ON tp.tp_el_cod = el.elm_cod_tp_elemento
                INNER JOIN estados_elementos esl ON esl.est_el_cod = el.elm_cod_estado
                WHERE el.elm_cod_estado = 1 AND el.elm_cod_tp_elemento = 1";

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

        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();

        } catch (\Throwable $th) {
            echo json_encode(["error" => "Excepción: " . $th->getMessage()]);
            exit();
        }
    }
}
