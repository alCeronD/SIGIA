<?php
require_once __DIR__ . '/../../../Helpers/Autoload.php';
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


    // public function gidIdModulo(String $nameModule = ""){
    //     $result = $this->modelPermisos->getModuleName($nameModule);
    //     $idNombreModulo = $result['data']['id_m'];
    //     return $idNombreModulo = empty($idNombreModulo) ? null : (int) $idNombreModulo;
    // }

    // public function getIdFuncion(String $functionName = "", String $modelName = "", Int $idModulo = 0){
    //     $result = $this->modelPermisos->getIdFuncion($functionName, $modelName, $idModulo);
    //     $idNombreFuncion = $result['data']['id_funcion'];

    //     if (empty($result['data']['id_funcion'])) {
    //         return null;
    //     }
    //     return $result['data']['id_funcion'];
    // }

    // public function validateRolFuncion(int $rolId,int $idFuncion){

    //     $result = $this->modelPermisos->getPermisoFuncion($rolId, $idFuncion);
    //     if ($result['data'] == null) {
    //         return false;
    //     }else{
    //         $idPermisoFuncion = $result['data']['id_funcion'];
    //     }
    //     return $idPermisoFuncion;
    // }

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
