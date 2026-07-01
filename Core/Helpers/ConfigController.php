<?php

// Clase que me va a servir para poder renderizar los archivos css y javascript pertinentes, vamos a heredar esta clase del modulocontrolador.
abstract class ConfigController implements ConfigInterface
{

  protected array $files = [
    'css' => [],
    'js' => []
  ];

  // haremos que el controlador que herede tenga si o si esta funcionalidad para asi poder implementar el rastro de miga.
  abstract public function createRoutes();


  public function getFilesCss(): array
  {
    return $this->files['css'] ?? [];
  }

  public function getFilesJs(): array
  {
    return $this->files['js'] ?? [];
  }
  /**
   * Funcion generica para renderizar la vista
   *
   * @param string $pathView - Ruta de la vista que se va a renderizar
   * @param string $nameFunction - Nombre de la function en donde se ejecuta el renderizado, se envia para poder extraer los archivos css y javascript que pertenecen a la vista.
   * @return void
   */
  public function renderView(string $pathView = '', string $nameFunction = '')
  {
    if (ob_get_length()) ob_clean();
    $allJs = $this->getFilesJs();
    $allCss = $this->getFilesCss();

    // Extraemos los recursos dependiendo de la function ejecutada.
    $specificCss = $allCss[$nameFunction] ?? [];
    $specificJs = $allJs[$nameFunction] ?? [];

    $routesCss = ScanFiles::renderCss($_GET[CR_MODULO], $specificCss); //Renderizamos los css y lo usamos en header.php
    include_once BASE_URL . CR_ROUTE_HEADER;
    include_once $pathView; //Ruta relativa de la vista
    ScanFiles::renderJs($_GET[CR_MODULO], $specificJs); //Renderizamos los js
    include_once BASE_URL . CR_ROUTE_FOOTER;
    exit;
  }
}
