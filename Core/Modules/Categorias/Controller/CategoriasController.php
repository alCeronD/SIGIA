<?php
include_once __DIR__ . '/../../../Config/Conn.php';
include_once __DIR__ . '/../../../Helpers/Const.php';
include_once __DIR__ . '/../Const/CategoriasConst.php';
include_once BASE_PATH . '/Autoload.php';

class CategoriasController extends ConfigController
{
    protected array $files = [
        CR_CSS => ['categoriasView' => ['Categorias.css']],
        CR_JS => []
    ];
    public function __construct()
    {
        $this->createRoutes();
    }

    public function createRoutes()
    {
        $this->routes = [
            // inicio
            CR_DASHBOARD_LOWER_CASE => ['label' => 'inicio', 'url' => Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE)],
            // funciones
            'categoriasView' => [
                'label' => CA_TITLE_MODULE,
                'url' => Router::createRoute(CR_CATEGORIAS, CR_CATEGORIAS, 'categoriasView', false, CR_DASHBOARD_LOWER_CASE),
                'parent' => CR_DASHBOARD_LOWER_CASE
            ]
        ];
    }

    public function categoriasView()
    {

        $path = realpath(BASE_PATH . CA_MAIN_VIEW);
        Parent::renderView($path, __FUNCTION__);
    }

    // public function store()
    // {
    //     try {
    //         header(CONTENT_TYPE);
    //         $data = UtilsFunctions::returnGetDecode();
    //         var_dump($data);
    //         die();
    //     } catch (\Exception $th) {
    //         Response::responseRequest($th->getCode(), false, $th->getMessage());
    //     }
    // }
}
