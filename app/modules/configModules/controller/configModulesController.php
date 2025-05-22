<?php 

// Controlador que me va a indicar que va a ejecutar del modelo.

require_once '../model/configModulesModel.php';

//Capturar los datos desde javascript.
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);


?>