<?php

class ServicesCategorias
{
  protected CategoriasModel $cm;
  public function __construct()
  {
    $this->cm = new CategoriasModel();
  }

  /**
   * Funcion para consumir los registros de la tabla categorias
   *
   * @param boolean $paginate - flag para determinar si se trae los registros sin paginar o paginados
   * @return CategoriasModel|array
   */
  public function getCategorias(bool $paginate = true)
  {
    // devolvemos el modelo para implementar la paginacion
    if ($paginate) {
      $categorias = $this->cm->select([
        "ca_id AS 'ca_id'",
        "ca_nombre AS 'ca_nombre'",
        "ca_descripcion AS 'ca_descripcion'"
      ])
        ->raw("CASE
          WHEN ca_status = 1 THEN 'Activo'
          WHEN ca_status = 2 THEN 'Inactivo'
          ELSE 'Sin estado'
        END AS 'ca_status'")
        ->from();
    } else {
      // devolvemos todos los registros sin necesidad de paginar.
      $categorias = null;
    }
    return $categorias;
  }

  public function validateDuplicate(String $column = "", String $value = ""): mixed
  {
    $dataPrepare[CR_DATA] = [
      $column => $value,
      CR_WORD_LIMIT => 1
    ];
    $duplicate = $this->cm->select()
      ->from()
      ->where(["{$column}", "=", "{$value}"])
      ->limit()
      ->prepareSql($dataPrepare)
      ->get();

    return $duplicate;
  }

  public function getCount()
  {
    return $this->cm->getCount()->prepareSql()->get();
  }
}
