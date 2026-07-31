<?php
require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once BASE_URL . '/Autoload.php';

class ServicesTipoDocumento
{
  protected TipoDocumentoModel $tpModel;
  public function __construct()
  {
    $this->tpModel = new TipoDocumentoModel();
  }


  /**
   * Obtiene los registros de la tabla tipo de documento.
   *
   * @param boolean $flagPaginate Indica si el resultado debe venir paginado, true si es paginado, false si es completo
   * @return TipoDocumentoModel|array[]|mixed[]
   */
  public function getAllTps(bool $flagPaginate = false)
  {
    if ($flagPaginate) {
      // Devuelve la misma instancia del modelo para complementar la consulta.
      return $this->tpModel->select()->from()->orderBy()->limit()->offset();
    } else {
      // Devuelve el arreglo con los resultados de la consulta
      return $this->tpModel->select()->from()->prepareSql()->get();
    }
  }
}
