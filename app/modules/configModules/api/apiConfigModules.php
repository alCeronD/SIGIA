<?php
// Este documento recibe todas las solicitudes de ajax.
//Incluyo el modelo para solicitar la data.

require_once __DIR__ . '/../model/configModulesModel.php';

$method = (isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : 'GET';

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$tableName = $_GET['tableName'];

$generalCrud = new ConfigModulesModel();

switch ($method) {
    case 'GET':
        $data = $generalCrud->selectTable($tableName);
        http_response_code(200);
        echo json_encode([
            'status' => true,
            'data' => $data

        ]);
        break;
    case 'POST':
        # code...
        break;
    default:

        break;
}


?>