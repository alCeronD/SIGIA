<?php
// Este documento recibe todas las solicitudes de ajax.
header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);


//Aca debo llamar es el controlador
//require_once __DIR__ . '/../model/configModulesModel.php';

require_once __DIR__ . '/../controller/configModulesController.php';



//Método get
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //creo el objeto del controlador.
    $configController = new ConfigModulesController('', '');

    //Creo arreglo para validar que el valor enviado es igual al que voy a solicitar en la base de datos.
    $tables = ['areas', 'roles', 'categorias', 'marcas'];

    $tableName = $_GET['tableName'];
    if (!in_array($tableName, $tables)) {
        exit();
    }

    $data = $configController->getData($tableName);

    http_response_code(200);
    echo json_encode([
        'status' => true,
        'data' => $data

    ]);
}

//Actualizar
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    //LLAMAR A LOS CONTROLADORES Y CREAR EL GRUD GENERAL
    $ar_nombre = $input['ar_nombre'];
    $ar_descripcion = $input['ar_descripcion'];
    $ar_cod = $input['ar_cod'];
}

//Método PULL