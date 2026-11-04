<?php
require_once __DIR__ . '/../../../Helpers/Autoload.php';
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/MarcasConst.php';
class ServicesMarcas
{

  protected MarcasModel $Marcas;
  public function __construct()
  {
    UtilsFunctions::validateMethod('GET');
    $this->Marcas = new MarcasModel();
  }

  public function getMarcas()
  {
    try {
      header(CONTENT_TYPE);

      $dataPrepare[CR_DATA] = [
        'ma_status' => 1
      ];

      $dataMarcas = $this->Marcas
        ->select([
          'ma_id',
          'ma_nombre',
        ])->from()
        ->where(['ma_status', '=', 1])
        ->prepareSql($dataPrepare)
        ->get();

      if (empty($dataMarcas)) {
        $dataMarcas = [];
      }
      Response::responseRequest(HttpStatus::OK, true, "Marcas", $dataMarcas);
    } catch (Exception $th) {
      Response::responseRequest($th->getCode(), true, $th->getMessage());
    }
  }
}
