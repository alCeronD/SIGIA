<?php
require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once __DIR__ . '/../Const/AreasConst.php';
require_once BASE_URL . '/Autoload.php';

class AreasController implements CrudInterface
{
  protected AreasModel $AreasModel;

  public function __construct()
  {
    $this->AreasModel = new AreasModel();
  }

  public function renderViewArea()
  {
    return include_once __DIR__ . URL_MAIN_VIEW;
  }
  public function getData()
  {
    header(CONTENT_TYPE);
    $page = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;
    $limit = (isset($_GET['limit'])) ? (int) $_GET['limit'] : LIMIT;
    $resultCount = $this->AreasModel->getCount()->prepareSql()->get();
    // execute paginate
    $resultPaginate = UtilsFunctions::executePaginate($resultCount['rowCounts'], $limit, $page);

    $dataSql['data'] = [
      'limit'           => $limit,
      'offset' => (int) $resultPaginate[CR_OFFSET]
    ];

    $resultSelect = $this->AreasModel->select()->from()->orderBy()->limit()->offset()->prepareSql($dataSql)->get();

    if (count($resultPaginate) > 0) {
      Response::responseRequest(HttpStatus::OK, true, "Registros", [
        CR_TOTAL_REGISTROS => $resultCount,
        CR_PAGINA_ACTUAL => $page,
        CR_CANTIDAD_PAGINAS => $resultPaginate[CR_TOTAL_PAGINAS],
        CR_DATA => $resultSelect
      ]);
    }
  }
  public function store()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $data[COLUMN_STATUS] = 1;
    $datosSelect[CR_DATA] = $data;

    // eliminamos espacios a los datos del arreglo
    $data = UtilsFunctions::deleteSpace($data);
    // validamos que el nombre del departamento no este vacio
    if (empty($data[COLUMN_NOMBRE])) {
      Response::responseRequest(HttpStatus::BAD_REQUEST, false, 'El nombre del departamento es obligatorio', []);
      return;
    }

    $resultInsert = $this->AreasModel->insert($data)->prepareSql($datosSelect)->get();
    // validamos la respuesta y devolvemos el mensaje de respuesta amigable para el usuario.
    if (!$resultInsert['status']) {
      $dataResponse = DatabaseHandler::validateResponse($resultInsert);
      Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
      return;
    }

    Response::responseRequest(HttpStatus::CREATED, true, AR_MESSAGE_SUCCESS, []);
  }
  public function changeStatus()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();

    $dataUpdateSql[CR_DATA] = [
      "ar_cod" => (int) $data['ar_cod'],
      "ar_status" => ((int) $data['ar_status'] === 1) ? 2 : 1,
    ];

    if (empty($data['ar_cod'])) Response::responseRequest(HttpStatus::NO_CONTENT, false, AR_MESSAGE_INFO_ITEM, []);

    // validar si existe el elemento a actualizar.
    $resultExists = $this->AreasModel->select()->from()->where()->prepareSql($dataUpdateSql)->get();

    if (empty($resultExists[0])) {
      Response::responseRequest(HttpStatus::NO_CONTENT, false, AR_MESSAGE_INFO_NO_CODIGO, []);
    }

    // Devuelve la cantidad de filas afectadas, si es 0, es un error, si es mayor que 0 significa que la actualización se realizó correctamente.
    $updateResult = $this->AreasModel->update($data)->where()->prepareSql($dataUpdateSql)->get();

    if (!$updateResult[CR_STATUS]) {

      $dataResponse = DatabaseHandler::validateResponse($updateResult);
      Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
      return;
    }
    Response::responseRequest(HttpStatus::OK, true, MSG_REGISTRO_CAMBIO_ESTADO, []);
  }
  public function save()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $data = UtilsFunctions::deleteSpace($data);
    if (empty($data[COLUMN_NOMBRE])) {
      Response::responseRequest(HttpStatus::BAD_REQUEST, false, AR_MESSAGE_NO_COD_EXISTS, ['status' => false]);
      return;
    }

    $editDepartmentData[CR_DATA] = $data;

    $resultExists = $this->AreasModel->select()->from()->where()->prepareSql($editDepartmentData)->get();
    // Validar la existencia de la informacion que enviamos y el código exista en la bd.
    if (count($resultExists) === 0) {
      Response::responseRequest(HttpStatus::NOT_FOUNT, false, AR_MESSAGE_INFO_NO_CODIGO, []);
    }

    $resultUpdate = $this->AreasModel->update($data)->where()->prepareSql($editDepartmentData)->get();
    if (!$resultUpdate[CR_STATUS]) {

      $dataResponse = DatabaseHandler::validateResponse($resultUpdate);
      Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
      return;
    }
    Response::responseRequest(HttpStatus::OK, true, MSG_REGISTRO_ACTUALIZAOD, []);
  }
  public function delete()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();

    $dataDelete[CR_DATA] = $data;
    $resultDelete = $this->AreasModel->delete()->where()->prepareSql($dataDelete)->get();

    if (!$resultDelete[CR_STATUS]) {

      $dataResponse = DatabaseHandler::validateResponse($resultDelete);
      Response::responseRequest($dataResponse['codeResponse'], false, $dataResponse['message'], []);
      return;
    }
    Response::responseRequest(HttpStatus::OK, true, AR_MESSAGE_DELETE_SUCESS, []);
  }
}
