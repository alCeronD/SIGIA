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
      'funcionesView' => ['Funciones.css']
    ],
    'js' => [
      'mostrarFuncionesAssoc' => ['RolesFunciones.js'],
      'funcionesView' => ['Funciones.js', 'VarsFunciones.js'],
      'funcionesAsociadasView' => ['RolesFunciones.js',],
    ]
  ];
  public function __construct()
  {
    $this->createRoutes();
  }
  public function createRoutes()
  {

    $vistasSecundarias = $_SESSION['renderMenu']['menuSecondView'];
    $modulos = array_column($vistasSecundarias, 'nombreModulo');

    $this->routes = [
      // inicio
      'dashboard' => ['label' => 'inicio', 'url' => Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE)],
      // esta ruta no esta en el controlador roles, es la que pertenece en el modulo PERMISOS, la implementamos para agregar el rastro de miga.
    ];

    if (in_array('Permisos', $modulos)) {
      $this->routes['permisosIndexView'] = [
        'label' => 'Seguridad del sistema',
        'url' => Router::createRoute('Permisos', 'Permisos', 'permisosIndexView', false, CR_DASHBOARD_LOWER_CASE),
        'parent' => 'dashboard'
      ];
    }

    $this->routes['funcionesIndexView'] = [
      'label' => 'Funciones',
      'url' => in_array('Permisos', $modulos) ? Router::createRoute('Permisos', 'Permisos', 'funcionesIndexView', false, 'dashboard') : Router::createRoute('Funciones', 'FuncionesMain', 'funcionesIndexView', false, CR_DASHBOARD_LOWER_CASE),
      'parent' => in_array('Permisos', $modulos) ? 'permisosIndexView' : 'dashboard'
    ];

    $this->routes['funcionesAsociadasView'] = [
      'label' => 'Funciones Asociadas',
      'url' => Router::createRoute(CR_FUNCIONES, 'FuncionesMain', 'funcionesAsociadasView', false, 'dashboard'),
      'parent' => 'funcionesIndexView'
    ];

    $this->routes['funcionesView'] = [
      'label' => 'Listado de funciones',
      'url' => Router::createRoute(CR_FUNCIONES, 'FuncionesMain', 'funcionesView', false, 'dashboard'),
      'parent' => 'funcionesIndexView'
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
