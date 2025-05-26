<?php

//TODO: Si esto crece, separar tdas las responsabilidades por diferentes archivos, si es para la tabla areas, crear su respectivo archivo como también para las otras tablas.
// Este documento recibe todas las solicitudes de ajax.
header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);

require_once __DIR__ . '/../controller/configModulesController.php';
$configController = new ConfigModulesController('', '');


$statusCols = [
    'areas' => ['status' => 'ar_status', 'pk' => 'ar_cod'],
    'roles' => ['status' => 'rl_status', 'pk' => 'rl_cod'],
    'categorias' => ['status' => 'ca_status', 'pk' => 'ca_cod'],
    'marcas' => ['status' => 'ma_status', 'pk' => 'ma_cod'],
];

$tables = ['areas', 'roles', 'categorias', 'marcas', 'tipo_documento'];


$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
    $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
}

//SELECT
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //creo el objeto del controlador.

    //Creo arreglo para validar que el valor enviado es igual al que voy a solicitar en la base de datos.
    $tableName = $_GET['tableName']??  null;
    $status = $_GET['status'] ?? null;

    if (!in_array($tableName, $tables)) {
        exit();
    }

    //Si el estatus es false, es decir, algo inactivo, terminar el script.
    //TODO:Arreglar esta parte, si yo quiero traer solamente los elementos activos, inactivos o todos los elementos, con esto podemos dar un mayor acceso y re usabilidad.
    if (!in_array($status, ['0', '1'])) {
        exit();
    }

    //aca se ejecuta todo.
    $data = $configController->getData($tableName, $status);

     //TODO: Crear una estructura de función para estandarizar las respuestas en para enviar a javascript.
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'data' => $data

    ]);
}

//UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    //LLAMAR A LOS CONTROLADORES Y CREAR EL GRUD GENERAL
    $ar_nombre = $input['ar_nombre'];
    $ar_descripcion = $input['ar_descripcion'];
    $ar_cod = $input['ar_cod'];
    $tableName = $input['tableName'];
    
    $keys=['values','tableName',$input['ar_cod'],['ar_nombre','ar_descripcion',]];
    $values=[];

    $values = [
        'values' => [
            'ar_nombre' => $ar_nombre,
            'ar_descripcion' => $ar_descripcion
        ],
        'tableName' => [$tableName],
        'pk' => [
            'ar_cod' => $ar_cod
        ]
    ];

    //Ejecuto la función, debe devolver un true, si es así devolver como respuesta a ajax.
    if ($configController->updateRow($values)) {
        http_response_code(200);

        echo json_encode([
            'status'=>true,
            'message'=>'Registro actualizado'
        ]);

    }

}

//DELETE
if ($method === 'DELETE') {

    $input = json_decode(file_get_contents('php://input'), true);

    $idPk = $input['idPk'] ?? null;
    $status = $input['status'] ?? null;
    $tableName = $input['tableName'] ?? null;

    //Si el nombre de la tabla no está en el arreglo de tablas, este debe detener el script.
    if (!in_array($tableName,$tables)) {
       exit();
    }

    $statusCol = $statusCols[$tableName]['status'];
    $pkCol = $statusCols[$tableName]['pk'];

    // Crear estructura esperada
    $data = [
        'tableName' => $tableName,
        'values' => [$statusCol => (int)$status],
        'pk' => ['column' => $pkCol, 'value' => (int)$idPk]
    ];

    if ($configController->deleteRow($data)) {

        http_response_code(200);

        echo json_encode([
            'status'=>true,
            'message'=>'Registro actualizado'
        ]);

    }else{
        http_response_code(500);
        echo json_encode([
            'status'=>false,
            'message'=>'Error al actualizar el registro.'
        ]);
    }
}