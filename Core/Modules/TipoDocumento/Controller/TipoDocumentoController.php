<?php
require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once __DIR__ . '/../Const/TpConst.php';
require_once BASE_URL . '/Autoload.php';
class TipoDocumentoController extends ConfigController implements CrudInterface
{
  // implementar el modulo
  protected TipoDocumentoModel $tpModel;
  protected array $files = [
    "css" => [
      'renderViewTp' => ['TipoDocumento.css']
    ],
    "js"  => [
      'renderViewTp' => ['TipoDocumento.js']
    ]
  ];

  // constructor
  public function __construct()
  {
    $this->tpModel = new TipoDocumentoModel();
  }

  public function createRoutes()
  {
    throw new \Exception('Not implemented');
  }

  // Vista principal
  public function renderViewTp()
  {
    $path = BASE_URL . TP_ROUTE_MAIN_VIEW;
    Parent::renderView($path, __FUNCTION__);
  }

  public function getData()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $page = (isset($_GET[CR_PAGINA])) ? (int) $_GET[CR_PAGINA] : 1;
    $limit = (isset($_GET['limit'])) ? (int) $_GET['limit'] : LIMIT;
    $resultCount = $this->tpModel->getCount()->prepareSql()->get();
    $resultPaginate = UtilsFunctions::executePaginate($resultCount[CR_ROW_COUNTS], $limit, $page);

    $dataSql[CR_DATA] = [
      'limit'           => $limit,
      CR_OFFSET => (int) $resultPaginate[CR_OFFSET]
    ];

    $resultSelect = $this->tpModel->select()->from()->orderBy()->limit()->offset()->prepareSql($dataSql)->get();

    // consulta select basica de momento.
    if (count($resultSelect) > 0) {
      Response::responseRequest(HttpStatus::OK, true, CR_REGISTROS, [
        CR_TOTAL_REGISTROS => $resultCount,
        CR_PAGINA_ACTUAL => $page,
        CR_CANTIDAD_PAGINAS => $resultPaginate[CR_TOTAL_PAGINAS],
        CR_DATA => $resultSelect
      ]);
    } else if (count($resultSelect) > 0) {
      Response::responseRequest(HttpStatus::OK, true, CR_REGISTROS, $resultSelect);
    }
  }

  public function store()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $data = UtilsFunctions::deleteSpace($data);
    $tp_status = 1;
    $data[VAR_TP_STATUS] = $tp_status;
    $insertData[CR_DATA] = $data;
    $responseCreate = $this->tpModel->insert($data)->prepareSql($insertData)->get();

    if (!$responseCreate[CR_STATUS]) {

      $dataResponse = DatabaseHandler::validateResponse($responseCreate);
      Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
      return;
    }
    Response::responseRequest(HttpStatus::OK, true, MSG_SUCCESS_CREATE, []);
  }

  public function delete()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $dataDelete['data'] = $data;
    $responseDelete = $this->tpModel->delete()->where()->prepareSql($dataDelete)->get();
    if (!$responseDelete[CR_STATUS]) {
      $dataResponse = DatabaseHandler::validateResponse($responseDelete);
      Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
      return;
    }
    Response::responseRequest(HttpStatus::OK, true, MSG_REGISTRO_ELIMINADO, []);
  }

  public function save()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $dataUpdate[CR_DATA] = $data;
    $update = $this->tpModel->update($data)->where()->prepareSql($dataUpdate)->get();
    if (!$update[CR_STATUS]) {
      $dataResponse = DatabaseHandler::validateResponse($update);
      Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
      return;
    }
    Response::responseRequest(HttpStatus::OK, true, MSG_TP_SUCCESS_UPDATE, []);
  }

  public function changeStatus()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $dataUpdate[CR_DATA] = $data;
    $changeStatus = $this->tpModel->update($data)->where()->prepareSql($dataUpdate)->get();
    $responseMessage = $data[VAR_TP_STATUS] === 2 ? MSG_SUCCESS_DISABLED : MSG_SUCCESS_ENABLED;
    if (!$changeStatus[CR_STATUS]) {
      $dataResponse = DatabaseHandler::validateResponse($changeStatus);
      Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
      return;
    }
    Response::responseRequest(HttpStatus::OK, true, $responseMessage, []);
  }
}
