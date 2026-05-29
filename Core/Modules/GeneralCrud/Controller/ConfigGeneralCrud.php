<?php

/**
 * Clase de configuracion para el rastro de miga.
 */
class ConfigGeneralCrud
{

  protected array $routes;

  public function createRoutes()
  {
    $this->routes = [
      "dashboard" => "<a href=" . Router::createRoute('Dashboard', 'Dashboard', 'dashboard', false, 'dashboard') . ">Dashboard</a>",
    ];

    foreach ($this->routes as $key => $value) {
      $_SESSION['rastro'][$key] = $value;
    }
  }
}
