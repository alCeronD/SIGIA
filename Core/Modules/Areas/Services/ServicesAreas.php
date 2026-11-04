<?php


require_once __DIR__ . '/../../../Helpers/Autoload.php';
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/AreasConst.php';
class ServicesAreas
{
  protected AreasModel $Areas;
  public function __construct()
  {
    UtilsFunctions::validateMethod('GET');
    $this->Areas = new AreasModel();
  }

  /**
   * Function para solicitar el codigo y nombre de los detapartamentos del sistema
   *
   * @return void
   */
  public function getAreas()
  {
    try {
      header(CONTENT_TYPE);

      $dataPrepare[CR_DATA] = [
        'ar_status' => 1
      ];

      $Areas = $this->Areas
        ->select(['ar_cod', 'ar_nombre'])
        ->from()
        ->where(['ar_status', '=', 1])
        ->prepareSql($dataPrepare)
        ->get();
      if (empty($Areas)) {
        $Areas = [];
      }

      Response::responseRequest(HttpStatus::OK, true, "Areas", $Areas);
    } catch (\Throwable $th) {
      Response::responseRequest($th->getCode(), false, $th->getMessage());
    }
  }
}
