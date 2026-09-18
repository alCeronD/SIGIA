<?php

include_once __DIR__ . '/../../../Config/Conn.php';
include_once __DIR__ . '/../../../Helpers/Const.php';
include_once __DIR__ . '/../Const/CategoriasConst.php';
include_once BASE_PATH . '/Autoload.php';

class CategoriasController extends ConfigController implements CrudInterface
{
    protected ServicesCategorias $sC;
    protected CategoriasModel $cm;
    protected array $files = [
        CR_CSS => ['categoriasView' => ['Categorias.css']],
        CR_JS => []
    ];
    public function __construct()
    {
        $this->createRoutes();
        $this->sC = new ServicesCategorias();
        $this->cm = new CategoriasModel();
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

    public function getData()
    {
        try {
            header(CONTENT_TYPE);
            $page = (isset($_GET[CR_PAGINA])) ? (int) $_GET['pagina'] : 1;
            $limit = (isset($_GET[CR_WORD_LIMIT])) ? (int) $_GET['limit'] : LIMIT;

            $countCategorias = $this->sC->getCount()['rowCounts'];
            // Ejecutar paginacion

            $paginate = UtilsFunctions::executePaginate($countCategorias, $limit, $page);

            $responseCategories = $this->sC->getCategorias(true);
            $dataPrepare[CR_DATA] = [
                CR_WORD_LIMIT => $limit,
                CR_OFFSET => (int) $paginate[CR_OFFSET]
            ];
            $result = $responseCategories->orderBy()
                ->limit()
                ->offset()
                ->prepareSql($dataPrepare)
                ->get();

            if (count($result) > 0) {
                Response::responseRequest(HttpStatus::OK, true, "Registros", [
                    CR_TOTAL_REGISTROS => $countCategorias,
                    CR_PAGINA_ACTUAL => ($page > $paginate[CR_TOTAL_PAGINAS]) ? $paginate[CR_TOTAL_PAGINAS] : $page, //Aca devolvemos la pagina, pero cuando se borra el ultimo registro de una pagina estamos devolviendo la pagina que recibimos desde la peticion, cuando hacemos la paginacion, si la pagina ES MAYOR A LA CANTIDAD DE PAGINAS TOTALES, NO DEVOLVEMOS LA PAGINA RECIBIDA, SINO LA ULTIMA PAGINA. esto para poder renderizar de forma correcta la informacion.
                    CR_CANTIDAD_PAGINAS => $paginate[CR_TOTAL_PAGINAS],
                    CR_DATA => $result
                ]);
            }
        } catch (\Exception $th) {
            Response::responseRequest($th->getCode(), false, $th->getMessage(), []);
        }
    }

    public function store()
    {
        try {
            header(CONTENT_TYPE);
            $data = UtilsFunctions::returnGetDecode();
            $data[CA_VAR_STATUS] = 1;
            if (empty($data))
                throw new Exception("No se permiten campos vacios", HttpStatus::BAD_REQUEST);
            $data = UtilsFunctions::deleteSpace($data);
            // arreglo con campos obligatorios para validar que deben ser diligenciados
            $mapCampos = [CA_VAR_NOMBRE => CA_NOMBRE_CATEGORIA];
            $resultValidateCampos = UtilsFunctions::validateCampos($data, $mapCampos);
            if (!$resultValidateCampos[CR_STATUS])
                throw new Exception($resultValidateCampos[CR_MESSAGE], $resultValidateCampos[CR_CODE_RESPONSE]);

            // validar que el nombre no sea duplicado.
            $duplicate = $this->sC->validateDuplicate("ca_nombre", $data[CA_VAR_NOMBRE]);

            // true si es igual, false en caso contrario
            if ($duplicate) {
                throw new Exception("El nombre '{$data[CA_VAR_NOMBRE]}' de la categoria ya existe en la base de datos, no se permite duplicado", HttpStatus::BAD_REQUEST);
            }

            $dataPrepare[CR_DATA] = $data;
            $resultStore = $this->cm->insert($data)
                ->prepareSql($dataPrepare)
                ->get();
            if (!$resultStore[CR_STATUS]) {
                $responseHandler = DatabaseHandler::validateResponse($resultStore);
                throw new Exception($responseHandler[CR_MESSAGE], $responseHandler[CR_CODE_RESPONSE]);
            }

            Response::responseRequest(HttpStatus::OK, true, CA_MSG_CREATE_CATEGORY, []);
        } catch (\Exception $th) {
            Response::responseRequest($th->getCode(), false, $th->getMessage());
        }
    }

    public function save()
    {
        throw new \Exception('Not implemented');
    }

    public function delete()
    {
        throw new \Exception('Not implemented');
    }
}
