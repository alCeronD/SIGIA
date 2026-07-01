
<?php
require_once BASE_URL . CR_ROUTE_CONN;
require_once BASE_URL . '/Autoload.php';
require_once __DIR__ . '/../Const/DashboardConst.php';

class DashboardController extends ConfigController
{

    protected array $files = [
        "css" => [
            'dashboard' => ['Dashboard.css']
        ],
        "js"  => [
            'dashboard' => ['Dashboard.js']
        ]
    ];

    public function createRoutes() {} //Function abstract

    public function __construct() {}

    // function de vista
    public function dashboard()
    {
        $path = BASE_URL . DS_ROUTE_DASHBOARD_VIEW;
        Parent::renderView($path, __FUNCTION__);
    }
}


?>