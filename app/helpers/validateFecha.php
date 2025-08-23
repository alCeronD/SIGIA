<?php 
/**
 * Summary of validateFecha - 
 * @param string $date1 - fechaReservaElementos = Fecha de reserva los elementos registrados en la base de datos.
 * @param string $date3 - fechaDevolución = fecha devolución de los elementos registrados en la base de datos.
 * @param string $date2 - fechaReserva = Fecha que envía el usuario.
 * @param bool $isFormat - Si la fecha ya viene o no formateada, en caso de que no venga formateada, esta debe de aplicar el formato adecuado.
 * @param int $tpPrestamo -
 * @return bool
 */
function validateFecha(String $date1, String $date2, String $date3 = "",  bool $isFormat = false, int $tpPrestamo = 0){
    // $result = null;
    if ($isFormat) {
        $fechaReservaDB = (new DateTime($date1))->format('Y-m-d');
        $fechaReservaUser = (new DateTime($date2))->format('Y-m-d');
        $fechaDevolucionDB = (!empty($date3)) ? (new DateTime($date3))->format('Y-m-d') : "";

    }else{
        $fechaReservaDB = (new DateTime($date1))->getTimestamp() * 1000;
        $fechaReservaUser = (new DateTime($date2))->getTimestamp() * 1000;
    }

    // Si el tipo de prestamo es PRESTAMO INMEDIATO, validamos que la fecha de devolución sea mayor o igual a la fecha de reserva, si el elemento seleccionado tiene ese rango de fechas, devolver true y adicionar el elemento al arreglo para visualizar que el elemento debe de ser modificado.
    if ($tpPrestamo == 1) {
        
        $result = $fechaReservaUser >= $fechaReservaDB ? true : false;
    }

    if ($tpPrestamo == 2) {
        // var_dump("tpPrestamo2".$tpPrestamo);
        // $result = $fechaReservaDB === $fechaReservaUser ? true: false;
        if (($fechaReservaUser > $fechaReservaDB)  && ($fechaReservaUser < $fechaDevolucionDB)) {
            $result = true;
        }else{
            $result = false;
        }
    }

    // Retorno true si la fecha es igual.
    return $result;
    
}

?>