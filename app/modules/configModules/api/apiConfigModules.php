<?php

//TODO: Si esto crece, separar tdas las responsabilidades por diferentes archivos, si es para la tabla areas, crear su respectivo archivo como también para las otras tablas.
// Este documento recibe todas las solicitudes de ajax.
header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);
require_once __DIR__ . '/../controller/configModulesController.php';
$configController = new ConfigModulesController('', '');


$tables = ['areas', 'roles', 'categorias', 'marcas'];

//SELECT
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //creo el objeto del controlador.

    //Creo arreglo para validar que el valor enviado es igual al que voy a solicitar en la base de datos.
    $tableName = $_GET['tableName'];
    $status = $_GET['status'];

    if (!in_array($tableName, $tables)) {
        exit();
    }

    //Si el estatus es false, es decir, algo inactivo, terminar el script.
    //TODO:Arreglar esta parte, si yo quiero traer solamente los elementos activos, inactivos o todos los elementos, con esto podemos dar un mayor acceso y re usabilidad.
    if (!in_array($status, ['0', '1'])) {
        exit();
    }
    //var_dump($data);

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
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
    /**
     * Esta función sirve para análizar toda la url que recibo y las variables que se encuentren ahí se guardan en un arreglo con su clave y valor.
     */
    parse_str($_SERVER['QUERY_STRING'], $params);

    $idPk = $params['idPk'] ?? null;
    $status = $params['status'] ?? null;
    $tableName = $params['tableName'] ?? null;

    //Si el nombre de la tabla no está en el arreglo de tablas, este debe detener el script.
    if (!in_array($tableName,$tables)) {
       exit();
    }

    $statusCols = [
        'areas' => ['status' => 'ar_status', 'pk' => 'ar_cod'],
        'roles' => ['status' => 'rl_status', 'pk' => 'rl_cod'],
        'categorias' => ['status' => 'ca_status', 'pk' => 'ca_cod'],
        'marcas' => ['status' => 'ma_status', 'pk' => 'ma_cod'],
    ];

    $statusCol = $statusCols[$tableName]['status'];
    $pkCol = $statusCols[$tableName]['pk'];

    // Crear estructura esperada
    $data = [
        'tableName' => $tableName,
        'values' => [$statusCol => (int)$status],
        'pk' => ['column' => $pkCol, 'value' => (int)$idPk]
    ];



    // $data = $configModulesController->deleteRow($params);
    $configController->deleteRow($data);

}