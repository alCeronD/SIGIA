<?php
require_once __DIR__ . '/../../../Helpers/Autoload.php';
require_once BASE_PATH . '/../Modules/Roles/Const/RolesConst.php';
require_once BASE_PATH . '/../Modules/Funciones/Const/FuncionesConst.php';
require_once BASE_PATH . '/../Modules/Gestionmodulos/Const/GestionModulosConst.php';

class PermisosController extends ConfigController
{
    protected array $files = [
        'js' => [
            'permisosIndexView' => ['Permisos.js'],
            'mostrarRoles' => ['Roles.js', 'SelectorsRoles.js'],
            'modulosView' => ['Modulos.js', 'Selectors-Modulos.js'],
        ],
        'css' => [
            'permisosIndexView' => ['Permisos.css'],
            'mostrarRoles' => ['Roles.css'],
            'modulosView' => ['Modulos.css'],
            'funcionesIndexView' => ['funcionesIndexView.css'],
        ],
    ];

    public function __construct()
    {
        $this->createRoutes();
    }

    public function createRoutes()
    {
        $this->routes = [
            // inicio
            'dashboard' => ['label' => 'inicio', 'url' => Router::createRoute('Dashboard', 'Dashboard', 'dashboard', false, 'dashboard')],
            // funciones
            'permisosIndexView' => [
                'label' => 'Seguridad del sistema',
                'url' => Router::createRoute('Permisos', 'Permisos', 'permisosIndexView', false, 'dashboard'),
                'parent' => 'dashboard'
            ],
            'mostrarRoles' => [
                'label' => 'Ver Roles',
                'url' => Router::createRoute('Roles', 'Roles', 'mostrarRoles', false, 'dashboard'),
                'parent' => 'permisosIndexView'
            ],
            'funcionesIndexView' => [
                'label' => 'Funciones',
                'url' => Router::createRoute(CR_FUNCIONES, 'FuncionesMain', 'funcionesIndexView', false, 'dashboard'),
                'parent' => 'permisosIndexView'
            ],
            // vista de modulo
            'modulosView' => [
                'label' => 'Gestión de modulos',
                'url' => Router::createRoute(CR_GESTION_MODULOS, CR_GESTION_MODULOS, 'modulosView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => 'permisosIndexView'
            ],

        ];
    }

    public function permisosIndexView()
    {
        $path = BASE_PATH . '/../Modules/Permisos/View/PermisosView.php';
        Parent::renderView($path, __FUNCTION__);
    }

    /**
     * MODULO FUNCIONES - Vista principal del modulo funciones
     *
     * @return void
     */
    public function funcionesIndexView()
    {

        $path = BASE_PATH . F_ROUTE_FUNCIONES_MAIN_VIEW;
        Parent::renderView($path, __FUNCTION__);
    }

    /**
     * MODULO GESTION MODULOS - Vista principal que contiene el listado de los modulos.
     *
     * @return void
     */
    public function modulosView()
    {
        $path = BASE_PATH . GM_ROUTES_MODULES_VIEW;
        Parent::renderView($path, __FUNCTION__);
    }

    /**
     * MODULO ROLES - Vista que contiene el listado de los roles.
     *
     * @return void
     */
    public function mostrarRoles(): void
    {
        $path = BASE_PATH . RL_ROUTES_MOSTRAR_ROLES;
        Parent::renderView($path, __FUNCTION__);
    }
}
