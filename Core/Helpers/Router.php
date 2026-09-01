<?php

/**
 * Clase para definir la ruta y la ejecución de funciones
 */
class Router
{

  public static function createRoute(String $modulo = "Login", String $controller = "Login", String $function = "index", Bool $parameters = false, String $pagina = "index")
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

    try {
      $modulo = $_GET['modulo'] ?? $_POST['modulo'] ?? null;
      $controlador = $_GET['controlador'] ?? $_POST['controlador'] ?? null;
      $function = $_GET['function'] ?? $_POST['function'] ?? null;
      $_SESSION['url_anterior'] = $_SERVER['HTTP_REFERER'] ?? '';
      if (!$modulo || !$controlador || !$function) {
        header(CONTENT_TYPE);
        Response::responseRequest(HttpStatus::BAD_REQUEST, false, "Faltan parámetros de ejecución");
      }

      $controladorFile = ucfirst($controlador) . "Controller.php";
      $rutaFile = realpath(BASE_PATH . "/../Modules/$modulo/Controller/$controladorFile");
      if (!is_file($rutaFile)) {
        throw new Exception("No existe el controlador", HttpStatus::NOT_FOUND);
      }

      include_once $rutaFile;

      // Crear el nombre de la clase
      $nameController = $controlador . "Controller";

      $objController = new $nameController();

      $reflectionClass = new ReflectionClass($objController);

      // hacemos un reflection para validar si el metodo existe.
      if (!$reflectionClass->hasMethod($function)) {
        // throw new Exception("La función '{$function}' no existe en el controlador '{$nameController}'", HttpStatus::NOT_FOUND);
        throw new Exception("La acción solicitada no existe", HttpStatus::NOT_FOUND);
      }

      $instanceItem = $reflectionClass->getMethod($function);
      // validamos que sea publico
      if (!$instanceItem->isPublic()) {
        throw new Exception("No se puede ejecutar esta acción porque es una acción interna del sistema", HttpStatus::UNAUTHORIZED);
      }

      $validatePermisos = (new ValidatePermisos())->validateAccess($modulo, $function);

      if (!$validatePermisos['status']) {
        throw new Exception($validatePermisos[CR_MESSAGE], $validatePermisos[CR_CODE_RESPONSE]);
      }
      // Ejecutamos la función
      $objController->$function();
    } catch (\Throwable $th) {
      $appDebug = UtilsFunctions::validateEnvironment();
      if ($appDebug) {
        // personalizamos el mensaje visible para el usuario dependiendo del código de respuesta
        $message = match ($th->getCode()) {
          401 => "{$th->getMessage()}",
          404 => "{$th->getMessage()} - {$th->getFile()} - {$th->getLine()}",
          403 => "{$th->getMessage()}",
          500 => "{$th->getMessage()} - {$th->getFile()} - {$th->getLine()}",
          default => "{$th->getMessage()}",
        };
        $trace = $th->getTrace();
      } else {
        // produccion
        $message = "Ha ocurrido un error interno en el servidor. Por favor contacte al soporte.";
        $trace = null;
        error_log("[ERROR SIGIA] " . $th->getMessage() . " en " . $th->getFile() . ":" . $th->getLine() . "\n" . $th->getTraceAsString());
      }
      // capturamos el codigo de respuesta de la exception, definimos un 500 en caso de que este vacio, es decir, que sea un error propio de php como errores de sintaxis.
      $codeResponse = empty($th->getCode()) ? 500 : $th->getCode();


      // validamos si es una peticion http mediante fetch o ajax o en su defecto una re direccion directa.
      if (UtilsFunctions::ajaxGeneral()) {
        $data =  ['previewRoute' => $_SESSION['url_anterior']];
        // validamos si mostramos el error siendo una peticion
        if ($appDebug) {
          $data['trace'] = $trace;
        }

        Response::responseRequest(
          $codeResponse,
          false,
          $message,
          $data
        );
      } else {
        Response::responseTemplate($codeResponse, $message, ['previewRoute' => $_SESSION['url_anterior']]);
      }
    }
  }
}
