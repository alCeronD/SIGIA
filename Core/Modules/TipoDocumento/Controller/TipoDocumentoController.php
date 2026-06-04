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
    $resultSelect = $this->tpModel->select()->prepareSql()->get();
    // consulta select basica de momento.
    if (count($resultSelect) > 0) {
      Response::responseRequest(HttpStatus::OK, true, "Registros", $resultSelect);
    }
  }
}
