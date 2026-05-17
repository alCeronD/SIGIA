<?php

// Función para buscar una clase automáticamente e implementarla para su uso.
require_once __DIR__ . '/UtilsFunctions.php';
require_once __DIR__ . '/Const.php';

// FUNCIón ANóNIMA PROPIA DE PHP= spl_autoload_register
spl_autoload_register(function ($className){
  // Url del archivo para crear la clase
  $moduleNames = UtilsFunctions::getModulesNames();
  $moduleName = UtilsFunctions::getNameModule();


  $dir = [
    BASE_URL.'/',

  ];

  // Creamos las rutas y las guardamos en el arreglo.
  foreach ($moduleNames as $nm) {
    // carpeta modulos
    $moduleFolder = BASE_URL . '/../Modules/'.$nm.'/';
    $dir[]= $moduleFolder.'Services/';
    $dir[]= $moduleFolder.'Controller/';
    $dir[]= $moduleFolder.'Model/';
  }



  // Ciclamos una sola vez el arreglo hasta que encuentre el archivo, lo incluya en en donde se necesite y se ejecute.
  $route = "";
  foreach ($dir as $value) {
    $route = realpath($value).'/'.$className.".php";
    if(is_file($route)){
      include_once $route;
      return;
    }

  }



});

?>