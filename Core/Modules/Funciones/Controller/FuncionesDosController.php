<?php
require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once __DIR__ . '/../Const/FuncionesConst.php';
require_once BASE_PATH . '/Autoload.php';
class FuncionesDosController extends ConfigController
{
  protected array $files = [
    'css' => [
      'mostrarFuncionesAssoc' => ['RolesFunciones.css'],
      'funcionesIndex' => ['funcionesIndex.css']
    ],
    'js' => [
      'mostrarFuncionesAssoc' => ['RolesFunciones.js']
    ]
  ];
  public function __construct()
  {
    $this->createRoutes();
  }
  public function createRoutes()
  {
    $this->routes = [
      'dashboard' => ['label' => 'inicio', 'url' => Router::createRoute('Dashboard', 'Dashboard', 'dashboard', false, 'dashboard')],
      'permisosIndexView' => [
        'label' => 'Seguridad del sistema',
        'url' => Router::createRoute('Permisos', 'Permisos', 'permisosIndexView', false, CR_DASHBOARD_LOWER_CASE),
        'parent' => 'dashboard'
      ],
      'funcionesIndex' => [
        'label' => 'Funciones',
        'url' => Router::createRoute(CR_FUNCIONES, 'FuncionesDos', 'funcionesIndex', false, 'dashboard'),
        'parent' => 'permisosIndexView'
      ]
    ];
  }

  /**
   * Vista principal del modulo funciones
   *
   * @return void
   */
  public function funcionesIndex()
  {

    $path = BASE_PATH . F_ROUTE_FUNCIONES_MAIN_VIEW;
    Parent::renderView($path, __FUNCTION__);
  }

  /**
   * Vista de las funciones asociadas a los roles.
   *
   * @return void
   */
  public function mostrarFuncionesAssoc(): void
  {

    $path = BASE_PATH . RL_ROUTES_ROLES_FUNCIONES;
    Parent::renderView($path, __FUNCTION__);
  }
}
