<?php

/**
 *
 * Archivo que contiene 2 funciones para visualizar la respuesta del json y enviarlo al front y visualizarlo.
 * TODO: puedo mejorar estas 2 funciones en 1, en donde la estructura es la misma y solamente le paso el código de respuesta por parámetros, 200 para true, 400 para false.
 * TODO: Esto se puede transformar en una clase.
 */


/**
 * Función para enviar respuesta exitosa al front.
 *
 * @param string $message - mensaje de respuesta.
 * @param array $data - Arreglo con toda la data a enviar.
 * @return void
 */
 function success(String $message = '', array $data = []){
    header('Content-Type: application/json; charset=utf-8');
    $result = [
        'status' => true,
        'message' => $message,
        'data' => empty($data) ? [] : $data
    ];
    http_response_code(200);
    echo json_encode($result,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}

function fail(String $value = '', array $data = []){
    header('Content-Type: application/json');
    $statusData = $data['status'];
    $src = $data['data'];
    if ((count($src) === 0) && (!$statusData)) {
        $errorCode = 404;
    }else{
        $errorCode = 400;
    }

    $data = [
        'status' => false,
        'message' => $value,
        'data' => $data
    ];
    http_response_code($errorCode);
    echo json_encode($data,JSON_PRETTY_PRINT);
    exit();
}

function noResponse($data){
    http_response_code(204);

    echo json_encode(
        [
            'status'=> true,
            'message'=> 'elemento disponible para la fecha de reserva seleccionada',
            'data'=>$data,
        ]
    ),JSON_PRETTY_PRINT;

    exit();
}

?>