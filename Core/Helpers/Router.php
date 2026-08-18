<?php

/**
 * Clase para definir la ruta y la ejecución de funciones
 */
class Router
{
  public static function createRoute(String $modulo = "Login", String $controller = "Login", String $function = "prueba", Bool $parameters = false, String $pagina = 'index')
  {

    // Valida tipos de datos
    if (!is_string($modulo)) return;
    if (!is_string($controller)) return;
    if (!is_string($function)) return;

    if ($pagina != 'dashboard') {
      $url = "$pagina.php?modulo=$modulo&controlador=$controller&function=$function";
    } else {
      $url = $pagina . ".php?modulo=$modulo&controlador=$controller&function=$function";
    }

    return $url;
  }

  public static function ExecuteFunction()
  {

    $modulo = $_GET['modulo'] ?? $_POST['modulo'] ?? null;
    $controlador = $_GET['controlador'] ?? $_POST['controlador'] ?? null;
    $function = $_GET['function'] ?? $_POST['function'] ?? null;
    if (!$modulo || !$controlador || !$function) {
      header(CONTENT_TYPE);
      echo json_encode(['success' => false, 'message' => "Faltan parámetros de ejecución"]);
      exit;
    }

    $controladorFile = ucfirst($controlador) . "Controller.php";
    $rutaFile = realpath(BASE_URL . "/../Modules/$modulo/Controller/$controladorFile");
    if (!is_file($rutaFile)) {
      echo json_encode(['success' => false, 'message' => "No existe el controlador en $rutaFile"]);
      exit;
    }

    include_once $rutaFile;

    // Crear el nombre de la clase
    $nameController = $controlador . "Controller";

    $objController = new $nameController();

    // if (($modulo !== 'Login' && $function !== 'logout') && ($modulo !== 'Login' && $function !== 'login') || $function !== 'index' || $function === 'dashboard') {

    //   var_dump($modulo, $function);
    //   (new ValidatePermisos())->validateAccess($modulo, $function);
    // }


    // if (($function !== 'logout' && $modulo !== 'Login') || $function !== 'index' || $function === 'dashboard') {
    // (new ValidatePermisos())->validateAccess($modulo, $function);

    // return;
    // }

    if (method_exists($objController, $function)) {
      $objController->$function();
    } else {
      echo json_encode(['success' => false, 'message' => "La función $function no existe en el controlador"]);
    }
  }
}
