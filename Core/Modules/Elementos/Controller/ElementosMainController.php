<?php
// Controlador principal que contiene las vistas del modulo elementos.
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_PATH . '/Autoload.php';
require_once BASE_PATH . '/../Modules/Elementos/Const/ElementosConst.php';


class ElementosMainController extends ConfigController
{
  // Archivos css y js de las diferences vistas.
  protected array $files = [
    "css" => [
      "elementosMainView" => ['ElementosMain.css'],
      "elementosView" => ['ElementosView.css']
    ],
    "js" => [
      "elementosMainView" => ['ElementosMain.js'],
      "elementosView" => ['ElementosView.js', 'SelectorsElementosView.js']
    ]
  ];

  public function __construct()
  {
    $this->createRoutes();
  }

  public function createRoutes()
  {
    $this->routes = [
      'dashboard' => ['label' => 'Inicio', 'url' => Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE)],
      'elementosMainView' => [
        'label' => 'Elementos',
        'url' => Router::createRoute(CR_ELEMENTOS, ELM_ELEMENTOS_MAIN, ELM_FU_ELMMAIN, false, CR_DASHBOARD_LOWER_CASE),
        'parent' => 'dashboard'
      ],
      'elementosView' => [
        'label' => 'Elementos registrados',
        'url' => Router::createRoute(CR_ELEMENTOS, ELM_ELEMENTOS_MAIN, ELM_FU_ELMVIEW, false, CR_DASHBOARD_LOWER_CASE),
        'parent' => 'elementosMainView'
      ]
    ];
  }

  /**
   * Vista principal del modulo elementos
   *
   * @return void
   */
  public function elementosMainView()
  {
    $path = BASE_PATH . ELM_ROUTE_MAIN_VIEW;
    Parent::renderView($path, __FUNCTION__);
  }

  /**
   * Fuction para renderizar la vista que contiene el listado de los elementos
   *
   * @return void
   */
  public function elementosView()
  {
    $path = BASE_PATH . ELM_ROUTE_ELMS_VIEW;
    Parent::renderView($path, __FUNCTION__);
  }

  /**
   * Vista de auditoria de los elementos.
   *
   * @return void
   */
  public function elementosAuditoriaView()
  {
    $path = BASE_PATH;
    Parent::renderView($path, __FUNCTION__);
  }
}
