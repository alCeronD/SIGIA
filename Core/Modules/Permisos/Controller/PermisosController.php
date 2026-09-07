<?php
require_once __DIR__ . '/../../../Helpers/Autoload.php';
require_once BASE_PATH . '/../Modules/Roles/Const/RolesConst.php';
class PermisosController extends ConfigController
{
    protected array $files = [
        'js' => [
            'permisosIndexView' => ['Permisos.js']
        ],
        'css' => [
            'permisosIndexView' => ['Permisos.css']
        ]
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
            ]
        ];
    }

    public function permisosIndexView()
    {
        $path = BASE_PATH . '/../Modules/Permisos/View/PermisosView.php';
        Parent::renderView($path, __FUNCTION__);
    }
}
