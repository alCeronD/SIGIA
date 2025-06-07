<?php 

/**
 * Archivo que contiene 2 funciones para visualizar la respuesta del json y enviarlo al front y visualizarlo.
 * TODO: puedo mejorar estas 2 funciones en 1, en donde la estructura es la misma y solamente le paso el código de respuesta por parámetros, 200 para true, 400 para false.
 */

 function success(String $value = '', array $data = []){
    header('Content-Type: application/json');
    $data = [
        'status' => true,
        'message' => $value,
        'data' => $data
    ];
    http_response_code(200);
    echo json_encode($data,JSON_PRETTY_PRINT);
    exit();
}

function fail(String $value = '', array $data = []){
    header('Content-Type: application/json');
    $data = [
        'status' => false,
        'message' => $value,
        'data' => $data
    ];
    http_response_code(400);
    echo json_encode($data,JSON_PRETTY_PRINT);
    exit();
}

?>