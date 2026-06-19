<?php
include_once __DIR__ . '/../../../Helpers/Const.php';
include_once __DIR__ . '/../Const/MarcasConst.php';
include_once BASE_URL . '/Autoload.php';

class MarcasController
{
  protected MarcasModel $mModel;
  public function __construct()
  {
    $this->mModel = new MarcasModel();
  }
  public function renderViewMarca()
  {
    return include_once BASE_URL . MA_URL_MAIN_VIEW;
  }
  public function getData()
  {
    header(CONTENT_TYPE);
    $page = (isset($_GET[CR_PAGINA])) ? (int) $_GET[CR_PAGINA] : 1;
    $limit = (isset($_GET[CR_WORD_LIMIT])) ? (int) $_GET[CR_WORD_LIMIT] : LIMIT;
    $countMarcas = $this->mModel->getCount()->prepareSql()->get();
    $resultPaginate = UtilsFunctions::executePaginate($countMarcas, $limit, $page);
    $dataSql[CR_DATA] = [
      CR_WORD_LIMIT           => $limit,
      CR_OFFSET => (int) $resultPaginate[CR_OFFSET]
    ];
    // crear consulta de envio de datos.
    $responseGetData = $this->mModel->select()->from()->orderBy()->limit()->offset()->prepareSql($dataSql)->get();

    if ($resultPaginate) {
      Response::responseRequest(HttpStatus::OK, true, "Registros", [
        CR_TOTAL_REGISTROS => $countMarcas,
        CR_PAGINA_ACTUAL => $page,
        CR_CANTIDAD_PAGINAS => $resultPaginate[CR_TOTAL_PAGINAS],
        CR_DATA => $responseGetData
      ]);
    } else if (count($responseGetData) > 0) {
      Response::responseRequest(HttpStatus::OK, true, "Registros", $responseGetData);
    }
  }
  public function save()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $data['ma_id'] = (int) $data['ma_id'];
    $dataUpdate['data'] = $data;
    $responseUpdate = $this->mModel->update($data)->where()->prepareSql($dataUpdate)->get();
    // validamos si la cantidad de registros a actualizar es mayor a 0, singifica que si hubo un cambio
    if ($responseUpdate == 0) {
      Response::responseRequest(HttpStatus::NO_CONTENT, true, MA_UPDATE_NA, []);
    }

    if ($responseUpdate > 0) {
      Response::responseRequest(HttpStatus::OK, true, MA_UPDATE_SUCCESS, []);
    }
  }
  public function store()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $data['ma_status'] = 1;
    $dataInsert['data'] = $data;
    $responseInsert = $this->mModel->insert($data)->prepareSql($dataInsert)->get();

    if ($responseInsert) {
      Response::responseRequest(HttpStatus::OK, true, MA_INSERT_SUCCESS, []);
    }
  }
  public function delete()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $dataDelete['data'] = $data;
    $responseDelete = $this->mModel->delete()->where()->prepareSql($dataDelete)->get();
    if ($responseDelete) {
      Response::responseRequest(HttpStatus::OK, true, MA_DELETE_SUCCESS, []);
    }
  }
  public function changeStatus()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $dataChangeStatus['data'] = $data;
    $responseChangeStatus = $this->mModel->update($data)->where()->prepareSql($dataChangeStatus)->get();
    if ($responseChangeStatus > 0) {
      $message = $data['ma_status'] === 1 ? MA_CHANGE_DISABLED : MA_CHANGE_ENABLED;
      Response::responseRequest(HttpStatus::OK, true, $message, []);
    }
  }
}
