<?php

// Función para buscar una clase automáticamente e implementarla para su uso.
use Dotenv\Dotenv;

require_once __DIR__ . '/UtilsFunctions.php';
require_once __DIR__ . '/Const.php';
require_once __DIR__ . '/../../vendor/autoload.php'; #El autoload de composer

// cargamos la libreria dotenv para leer las variables .env
$dotEnv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotEnv->load();

// validamos si el entorno es local o produccion.
$appDebug = UtilsFunctions::validateEnvironment();

if ($appDebug) {
  // si es local, se informa cualquier error o advertencia en pantalla.
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); # E_ALL = Reporta todos los errores existentes.
} else {
  ini_set('display_errors', 0);
  error_reporting(0);
}

// FUNCIón ANóNIMA PROPIA DE PHP= spl_autoload_register
spl_autoload_register(function ($className) {
  // Url del archivo para crear la clase
  $moduleNames = UtilsFunctions::getModulesNames();
  $moduleName = UtilsFunctions::getNameModule();

  $dir = [
    BASE_PATH . '/',

  ];

  // Creamos las rutas y las guardamos en el arreglo.
  foreach ($moduleNames as $nm) {
    // carpeta modulos
    $moduleFolder = BASE_PATH . '/../Modules/' . $nm . '/';
    $dir[] = $moduleFolder . 'Services/';
    $dir[] = $moduleFolder . 'Controller/';
    $dir[] = $moduleFolder . 'Model/';
  }

  // Ciclamos una sola vez el arreglo hasta que encuentre el archivo, lo incluya en en donde se necesite y se ejecute.
  $route = "";
  foreach ($dir as $value) {
    $route = realpath($value) . '/' . $className . ".php";
    if (is_file($route)) {
      include_once $route;
      return;
    }
  }
});
