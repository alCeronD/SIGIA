<?php

// Archivo para crear ruta

function createRoute(String $modulo = "Login", String $controller = "Login", String $function = "prueba",Bool $parameters = false, String $pagina = 'index'){

  // Valida tipos de datos
  if(!is_string($modulo)) return;
  if(!is_string($controller)) return;
  if(!is_string($function)) return;

  if($pagina != 'dashboard'){
    $url = "$pagina.php?modulo=$modulo&controlador=$controller&function=$function";
  }else{
    $url = $pagina.".php?modulo=$modulo&controlador=$controller&function=$function";
  }
  return $url;
}