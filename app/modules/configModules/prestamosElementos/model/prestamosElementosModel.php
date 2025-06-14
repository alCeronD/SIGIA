<?php


class prestamoElementos {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function create($pres_cod, $usuario_id ,$elm_cod) {
        $pres_cod = (int) $pres_cod;
        $elm_cod = (int) $elm_cod;
        $usua_id = (int) $usuario_id;

        $query = "INSERT INTO prestamos_elementos (pres_cod, pres_el_usu_id, pres_el_elem_cod ) VALUES ($pres_cod, $usua_id, $elm_cod)";
        // dd($query);
        return $this->conn->query($query);
    }
}



?>