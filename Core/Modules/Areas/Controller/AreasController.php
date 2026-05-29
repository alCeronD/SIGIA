<?php

require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once __DIR__ . '/../Const/AreasConst.php';
require_once BASE_URL . '/Autoload.php';

class AreasController
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
    // $data = UtilsFunctions::returnGetDecode();
    $page = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;
    $limit = (isset($_GET['limit'])) ? (int) $_GET['limit'] : LIMIT;
    $resultCount = $this->AreasModel->getCount()->prepareSql()->get();
    // execute paginate
    $resultPaginate = UtilsFunctions::executePaginate($resultCount, $limit, $page);

    $dataSql['data'] = [
      'limit'           => $limit,
      'offset' => (int) $resultPaginate[CR_OFFSET]
    ];

    $resultSelect = $this->AreasModel->select()->orderBy()->limit()->offset()->prepareSql($dataSql)->get();

    if ($resultPaginate) {
      Response::success('registros', [
        CR_TOTAL_REGISTROS => $resultCount,
        CR_PAGINA_ACTUAL => $page,
        CR_REGISTROS => $resultSelect
      ]);
    }
  }
  public function insert() {}
}
