<?php
require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once __DIR__ . '/../Const/TpConst.php';
require_once BASE_URL . '/Autoload.php';
class TipoDocumentoController
{
  // implementar el modulo
  protected TipoDocumentoModel $tpModel;

  // constructor
  public function __construct()
  {
    $this->tpModel = new TipoDocumentoModel();
  }

  // Vista
  public function renderViewTp()
  {
    return include_once __DIR__ . '/../Views/tpDocumentoView.php';
  }


  public function getData()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $page = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;
    $limit = (isset($_GET['limit'])) ? (int) $_GET['limit'] : LIMIT;
    $resultCount = $this->tpModel->getCount()->prepareSql()->get();
    $resultPaginate = UtilsFunctions::executePaginate($resultCount, $limit, $page);

    $dataSql['data'] = [
      'limit'           => $limit,
      'offset' => (int) $resultPaginate[CR_OFFSET]
    ];

    $resultSelect = $this->tpModel->select()->orderBy()->limit()->offset()->prepareSql($dataSql)->get();

    // consulta select basica de momento.
    if ($resultPaginate) {
      Response::responseRequest(HttpStatus::OK, true, "Registros", [
        CR_TOTAL_REGISTROS => $resultCount,
        CR_PAGINA_ACTUAL => $page,
        CR_CANTIDAD_PAGINAS => $resultPaginate[CR_TOTAL_PAGINAS],
        CR_DATA => $resultSelect
      ]);
    } else if (count($resultSelect) > 0) {
      Response::responseRequest(HttpStatus::OK, true, "Registros", $resultSelect);
    }
  }

  public function deleteItem()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $dataDelete['data'] = $data;
    $responseDelete = $this->tpModel->delete()->where()->prepareSql($dataDelete)->get();
    if ($responseDelete) {
      Response::responseRequest(HttpStatus::OK, true, "Recurso eliminado con exito", []);
    }
  }

  public function updateItem()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $dataUpdate['data'] = $data;
    // update data.
    $update = $this->tpModel->update($data)->where()->prepareSql($dataUpdate)->get();

    // update devuelve la cantidad de filas afectadas, si es mayor a 0 significa que hubo un cambio de estado
    if ($update > 0) {
      Response::responseRequest(HttpStatus::OK, true, "Recurso actualizado con exito", []);
    }
  }
}
