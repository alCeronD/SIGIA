<?php

use Dba\Connection;

//Este modelo me sirve para llamar a los elementos que esten disponibles, la idea es llevarlos a la vista de solicitud de prestamos.
include_once __DIR__ . '/../../../config/conn.php';
class elements
{

    private array $data;
    private $pdo;

    public function __construct() {}

    public function fetchElements()
    {
        $this->pdo = new Conection();

        $conn = $this->pdo->getConnect();

        //Consulta elementos
        $sql = "SELECT SELECT 
            e.elm_cod AS 'Codigo',
            e.elm_nombre AS 'Nombre',
            e.elm_existencia AS 'Cantidad',
            es.est_el_cod AS 'Estado',
            tp.tp_el_nombre AS 'TipoElemento'
            FROM elementos e
            INNER JOIN estados_elementos es ON es.est_el_cod = e.elm_cod_estado
            INNER JOIN tipo_elemento tp ON tp.tp_el_cod = e.elm_cod_tp_elemento
            WHERE e.elm_cod_tp_elemento IN (1,2)";

        $stmtFetch = $conn->prepare($sql);

        if (!$stmtFetch->execute()) {
            return null;
        }

        $result = $stmtFetch->get_result();

        $this->data = [];

        while ($row = $result->fetch_assoc()) {
            $this->data[] = $row;
        }

        //Cerrar la consulta.
        $conn->close();

        return $this->data;
    }
}
