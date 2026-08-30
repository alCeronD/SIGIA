
<?php
require_once BASE_PATH . CR_ROUTE_CONN;
require_once BASE_PATH . '/Autoload.php';
require_once __DIR__ . '/../Const/DashboardConst.php';

class DashboardController extends ConfigController
{

    protected array $files = [
        "css" => [
            'dashboard' => []
        ],
        "js"  => [
            'dashboard' => []
        ]
    ];

    public function createRoutes() {} //Function abstract

    public function __construct() {}

    // Funtion para implementar la vista inicial dependiendo del usuario logueado
    public function dashboard()
    {
        $this->getCss(__FUNCTION__);
        $nombreRol = Session::getRol()['rol_nombre'];
        $nombreRol = str_replace(" ", "", $nombreRol);
        $path = match ($nombreRol) {
            'SuperAdministrador' => BASE_PATH . DS_ROUTE_SUPER_ADMIN_VIEW,
            'Administrador' => BASE_PATH . DS_ROUTE_ADMIN_VIEW,
        };
        Parent::renderView($path, __FUNCTION__);
    }

    // Function para extraer el nombre del archivo css dependiendo del rol del usuario, esto para solamente asignar a la clave CSS el archivo dependiendo del rol.
    protected function getCss(String $nameFunction = "")
    {
        $nombreRol = Session::getRol()['rol_nombre'];
        $nombreRol = str_replace(" ", "", $nombreRol);
        // validamos que la clave exista.
        if (isset($this->files['css'][$nameFunction])) {
            // creamos la ruta con el nombre del rol, el nombre del rol debe de ser igual al del css.
            $pathCss = realpath(BASE_PATH . "/../Modules/Dashboard/Css/{$nombreRol}.css");
            if (is_file($pathCss)) {
                $this->files['css'][$nameFunction] = ["{$nombreRol}.css"];
            } else {
                $this->files['css'][$nameFunction] = ["Dashboard.css"];
            }
        }
    }
}


?>