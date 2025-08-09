<?php 

function validateFecha(String $date1, String $date2, bool $isFormat = false){

    if ($isFormat) {
        $dateResultadoMs = (new DateTime($date1))->format('Y-m-d');
        $dateFechaReservaMs = (new DateTime($date2))->format('Y-m-d');

    }else{
        $dateResultadoMs = (new DateTime($date1))->getTimestamp() * 1000;
        $dateFechaReservaMs = (new DateTime($date2))->getTimestamp() * 1000;
    }

    // Retorno true si la fecha es igual.
    return $dateResultadoMs === $dateFechaReservaMs ? true: false;
    
}

?>