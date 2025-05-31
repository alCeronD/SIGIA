<?php

//TODO: Si esto crece, separar tdas las responsabilidades por diferentes archivos, si es para la tabla areas, crear su respectivo archivo como también para las otras tablas.
// Este documento recibe todas las solicitudes de ajax.
header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);

require_once __DIR__ . '/../controller/configModulesController.php';
$configController = new ConfigModulesController();


//Cambiar statusCols y tables por $schema.
$statusCols = [
    'areas' => ['status' => 'ar_status', 'pk' => 'ar_cod'],
    'roles' => ['status' => 'rl_status', 'pk' => 'rl_cod'],
    'categorias' => ['status' => 'ca_status', 'pk' => 'ca_cod'],
    'marcas' => ['status' => 'ma_status', 'pk' => 'ma_cod'],
    'tipo_documento' => ['status' => 'tp_status' , 'pk' => 'tp_id']
];

//Variable para comparar que existan las tablas.
$tables = ['areas', 'roles', 'categorias', 'marcas', 'tipo_documento'];

$schema = [
    'areas' => [
        'pk' => 'ar_cod',
        'filas' => ['ar_nombre', 'ar_descripcion'],
        'status' => 'ar_status',
    ],
    'tipo_documento' => [
        'pk' => 'tp_id',
        'filas' => ['tp_sigla', 'tp_nombre'],
        'status' => 'tp_status',
    ],
    'marcas' =>[
        'pk' => 'ma_id',
        'filas' => ['ma_nombre','ma_descripcion'],
        'status' => 'ma_status'
    ]
];




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
    //var_dump($tableName, $status);

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

    $tableName = isset($input['tableName']) ? $input['tableName'] : null;

    //Tabla que voy a usar para hacer las operaciones.
    $tableDef = $schema[$tableName];

    $data = [];
    foreach ($tableDef['filas'] as $field) {
        $data[$field] = $input[$field] ?? null;

    }

    if ($tableName == 'areas') {
        $nombreField = $data['ar_nombre'];
        $descripcionField = $data['ar_descripcion'];
        $codField = $input['ar_cod'];
    }

    if ($tableName == 'tipo_documento') {
        $nombreField = $data['tp_sigla'];
        $descripcionField = $data['tp_nombre'];
        $codField = $input['tp_id'];   
    }

    //$keys=['values','tableName',$input['ar_cod'],['ar_nombre','ar_descripcion']];
    $values=[];

        $values = [
        'values' => [
            $schema[$tableName]['filas'][0] => $nombreField,
            $schema[$tableName]['filas'][1] => $descripcionField
        ],
        'tableName' => [$tableName],
        'pk' => [
            $schema[$tableName]['pk'] => $codField
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

    $tableDef = $schema[$tableName];

    $data = [];
    // foreach ($tableDef['filas'] as $field) {
    //     $data[$field] = $input[$field] ?? null;

    // }

    if ($tableName == 'areas') {
        $statusCol = $statusCols[$tableName]['status'];
        $pkCol = $statusCols[$tableName]['pk'];
    }

    if ($tableName == 'tipo_documento') {
        $statusCol = $statusCols[$tableName]['status'];
        $pkCol = $statusCols[$tableName]['pk'];
    }

    // Crear estructura esperada
    $data = [
        'tableName' => $tableName,
        'values' => [$statusCol => (int)$status],
        'pk' => ['column' => $pkCol, 'value' => (int)$idPk]
    ];

    //var_dump($data);

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

//INSERT
if ($method === 'POST') {
    //$ar_nombre = $input['ar_nombre'];
    //$ar_descripcion = $input['ar_descripcion'];


    //Extraigo el nombre de la tabla.
    $tableName = $input['tableName'];

    $schemaTable = $schema[$tableName];

    $datas = [];
    //En base a la tabla traigo sus valores y lo guardo en otro arreglo que es el arreglo que voy a mandar a crear la consulta.
    foreach ($schemaTable['filas'] as $value) {
        $datas[$value] = $input[$value] ?? null;
    }


    if (in_array($tableName, $tables)) {
        $statusField = $schemaTable['status'];
        $datas[$statusField] = 1; 
    }

    if (!in_array($tableName,$tables)) {
       exit();
    }

    //Comparo si el valor esta para asociar el valor de la pk

    $data = [
    'tableName' => $tableName,
    'values' => $datas,
    ];
    // var_dump($data);


    if ($data = $configController->addRow($data)) {

        http_response_code(200);

        echo json_encode([
            'status'=>true,
            'message'=>'Area registrada correctamente',
            'data' => $data
        ]);

    }else{
        http_response_code(500);
        echo json_encode([
            'status'=>false,
            'message'=>'Error al registrar el nuevo elemento'
        ]);
    }

}