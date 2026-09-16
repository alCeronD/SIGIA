<?php
require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once BASE_PATH . '/Autoload.php';

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
      return $this->tpModel->select([
        "tp_id AS 'tp_id'",
        "tp_sigla AS 'tp_sigla'",
        "tp_nombre AS 'tp_nombre'"
      ])->raw("CASE
          WHEN tp_status = 1 THEN 'Activo'
          WHEN tp_status = 2 THEN 'Inactivo'
          ELSE 'Sin estado'
        END AS 'tp_status'")
        ->from()
        ->orderBy()
        ->limit()
        ->offset();
    } else {
      // Devuelve el arreglo con los resultados de la consulta
      return $this->tpModel->select()
        ->from()
        ->prepareSql()
        ->get();
    }
  }
}
