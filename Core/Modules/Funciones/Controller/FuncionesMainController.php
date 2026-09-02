<?php
require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once __DIR__ . '/../Const/FuncionesConst.php';
require_once BASE_PATH . '/Autoload.php';
// Controlador en donde se muestran solo las vistas del modulo de funciones
class FuncionesMainController extends ConfigController
{
  protected array $files = [
    'css' => [
      'mostrarFuncionesAssoc' => ['RolesFunciones.css'],
      'funcionesIndexView' => ['funcionesIndexView.css'],
      'funcionesAsociadasView' => ['RolesFunciones.css'],
      'funcionesView' => ['Funciones.js', 'VarsFunciones.js']
    ],
    'js' => [
      'mostrarFuncionesAssoc' => ['RolesFunciones.js'],
      'funcionesAsociadasView' => ['RolesFunciones.js',],
      'funcionesView' => ['Funciones.css']
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
      'funcionesIndexView' => [
        'label' => 'Funciones',
        'url' => Router::createRoute(CR_FUNCIONES, 'FuncionesMain', 'funcionesIndexView', false, 'dashboard'),
        'parent' => 'permisosIndexView'
      ],
      'funcionesAsociadasView' => [
        'label' => 'Funciones Asociadas',
        'url' => Router::createRoute(CR_FUNCIONES, 'FuncionesMain', 'funcionesAsociadasView', false, 'dashboard'),
        'parent' => 'funcionesIndexView'
      ],
      'funcionesView' => [
        'label' => 'Listado de funciones',
        'url' => Router::createRoute(CR_FUNCIONES, 'FuncionesMain', 'funcionesView', false, 'dashboard'),
        'parent' => 'funcionesIndexView'
      ]
    ];
  }

  /**
   * Vista principal del modulo funciones
   *
   * @return void
   */
  public function funcionesIndexView()
  {

    $path = BASE_PATH . F_ROUTE_FUNCIONES_MAIN_VIEW;
    Parent::renderView($path, __FUNCTION__);
  }

  /**
   * Vista de las funciones asociadas a los roles.
   *
   * @return void
   */
  public function funcionesAsociadasView(): void
  {

    $path = BASE_PATH . RL_ROUTES_ROLES_FUNCIONES;
    Parent::renderView($path, __FUNCTION__);
  }

  /**
   * Function para renderizar las funciones registradas en el sistema y manipular sus respectivos
   *
   * @return void
   */
  public function funcionesView()
  {
    $path = BASE_PATH . F_ROUTE_FUNCIONES;
    Parent::renderView($path, __FUNCTION__);
  }
}
