<?php

use function PHPUnit\Framework\matches;
use function PHPUnit\Framework\throwException;

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
        CR_JS => ['categoriasView' => ['Categorias.js', 'SelectorsCategorias.js']]
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
                throw new Exception(CA_MSG_EMPTY_DATA, HttpStatus::BAD_REQUEST);
            $data = UtilsFunctions::deleteSpace($data);
            // arreglo con campos obligatorios para validar que deben ser diligenciados
            $mapCampos = [CA_VAR_NOMBRE => CA_NOMBRE_CATEGORIA];
            $resultValidateCampos = UtilsFunctions::validateCampos($data, $mapCampos);
            if (!$resultValidateCampos[CR_STATUS])
                throw new Exception($resultValidateCampos[CR_MESSAGE], $resultValidateCampos[CR_CODE_RESPONSE]);

            // validar que el nombre no sea duplicado.
            $duplicate = $this->sC->validateDuplicate("ca_nombre", $data[CA_VAR_NOMBRE]);

            if (!empty($duplicate)) {
                if ($duplicate[0]['ca_nombre'] === $data['ca_nombre']) {

                    throw new Exception("El nombre '{$data[CA_VAR_NOMBRE]}' de la categoria ya existe en la base de datos, no se permite duplicado", HttpStatus::BAD_REQUEST);
                }
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
        try {
            header(CONTENT_TYPE);
            $data = UtilsFunctions::returnGetDecode();
            if (empty($data)) throw new Exception(CA_MSG_EMPTY_DATA, HttpStatus::BAD_REQUEST);
            // arreglo con campos obligatorios para validar que deben ser diligenciados
            $mapCampos = [CA_VAR_NOMBRE => CA_NOMBRE_CATEGORIA];
            $resultValidateCampos = UtilsFunctions::validateCampos($data, $mapCampos);
            if (!$resultValidateCampos[CR_STATUS])
                throw new Exception($resultValidateCampos[CR_MESSAGE], $resultValidateCampos[CR_CODE_RESPONSE]);

            $duplicate = $this->sC->validateDuplicate("ca_nombre", $data[CA_VAR_NOMBRE]);
            $data = UtilsFunctions::deleteSpace($data);

            if (!empty($duplicate)) {

                if ($duplicate[0]['ca_id'] != $data['ca_id']) {
                    throw new Exception("La categoria '{$data[CA_VAR_NOMBRE]}' ya está asociado a otro registro identificado con el ID {$duplicate[0]['ca_id']}, no se permiten duplicados.", HttpStatus::BAD_REQUEST);
                }
            }


            $dataPrepare[CR_DATA] = $data;
            $resultSave = $this->cm->update($data)
                ->where()
                ->prepareSql($dataPrepare)
                ->get();

            if (!$resultSave[CR_STATUS]) {
                $messageHandler = DatabaseHandler::validateResponse($resultSave);
                throw new Exception($messageHandler[CR_MESSAGE], $messageHandler[CR_CODE_RESPONSE]);
            }

            Response::responseRequest(HttpStatus::OK, true, CA_MSG_SAVE_CATEGORY, []);
        } catch (\Exception $th) {
            Response::responseRequest($th->getCode(), false, $th->getMessage());
        }
    }

    public function delete()
    {
        try {
            header(CONTENT_TYPE);
            $data = UtilsFunctions::returnGetDecode();

            if (empty($data)) throw new Exception(CA_MSG_EMPTY_DATA, HttpStatus::BAD_REQUEST);
            // validar que el id existe.
            $idExists = $this->sC->validateDuplicate(CA_VAR_ID, $data[CA_VAR_ID]);
            if (empty($idExists)) throw new Exception(CA_MSG_NO_ID, HttpStatus::NOT_FOUND);


            $dataPrepare[CR_DATA] = $data;
            $resultDelete = $this->cm->delete()
                ->where([CA_VAR_ID, "=", $data[CA_VAR_ID]])
                ->prepareSql($dataPrepare)
                ->get();

            if (!$resultDelete[CR_STATUS]) {
                $messageHandler = DatabaseHandler::validateResponse($resultDelete);
                throw new Exception($messageHandler[CR_MESSAGE], $messageHandler[CR_CODE_RESPONSE]);
            }
            Response::responseRequest(HttpStatus::OK, true, CA_MSG_DELETE_CATEGORY, []);
        } catch (\Exception $th) {
            Response::responseRequest($th->getCode(), false, $th->getMessage());
        }
    }

    public function changeStatus()
    {
        try {
            header(CONTENT_TYPE);
            $data = UtilsFunctions::returnGetDecode();

            if (empty($data)) throw new Exception(CA_MSG_EMPTY_DATA, HttpStatus::BAD_REQUEST);

            // validamos que exista el registro antes de validar el cambio de estado
            $validateCategoria = $this->sC->validateDuplicate(CA_VAR_ID, $data[CA_VAR_ID]);

            if (empty($validateCategoria)) throw new Exception(CA_MSG_NO_DATA, HttpStatus::NOT_FOUND);

            $dataPrepare[CR_DATA] = $data;
            $resultChangeStatus = $this->cm->update($data)
                ->where([CA_VAR_ID, "=", "{$data[CA_VAR_ID]}"])
                ->prepareSql($dataPrepare)
                ->get();

            $responseMessage = match ($data[CA_VAR_STATUS]) {
                1 => CA_MSG_ENABLED,
                2 => CA_MSG_DISABLE,
                default => "Sin estado"
            };


            if (!$resultChangeStatus[CR_STATUS]) {
                $messageHandler = DatabaseHandler::validateResponse($resultChangeStatus);
                throw new Exception($messageHandler[CR_MESSAGE], $messageHandler[CR_CODE_RESPONSE]);
            }

            Response::responseRequest(HttpStatus::OK, true, $responseMessage);
        } catch (\Throwable $th) {
            Response::responseRequest($th->getCode(), false, $th->getMessage());
        }
    }
}
