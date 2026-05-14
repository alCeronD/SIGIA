<?php

/**
 * Undocumented function
 *
 * @return boolean
 */
function isAjax(){
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Function para ejecutar una function usando el rutador
 *
 */

function ExecuteFunction(){

  $modulo = $_GET['modulo'] ?? $_POST['modulo'] ?? null;
  $controlador = $_GET['controlador'] ?? $_POST['controlador'] ?? null;
  $function = $_GET['function'] ?? $_POST['function'] ?? null;


  if (!$modulo || !$controlador || !$function) {

      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Faltan parámetros de enrutamiento']);
      exit;
  }


  $controladorFile = ucfirst($controlador)."Controller.php";

  $rutaFile = __DIR__ ."/../Modules/$modulo/controller/$controladorFile";

  if(!is_file($rutaFile)){
    echo json_encode(['success' => false, 'message' => "No existe el controlador en $rutaFile"]);
    exit;
  }

  include_once $rutaFile;

  // Crear el nombre de la clase
  $nameController = $controlador."Controller";

  $objController = new $nameController();

  if(method_exists($objController, $function)) {
      $objController->$function();
  } else {
      echo json_encode(['success' => false, 'message' => "La función $function no existe en el controlador"]);
  }
}
