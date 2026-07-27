<?php
require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once BASE_URL . '/Autoload.php';

class ServicesGestionModulos
{
  protected ModulosModel $mModel;
  public function __construct()
  {
    $this->mModel = new ModulosModel();
  }

  /**
   * Function para devolver el listado de los modulos.
   *
   * @param boolean $paginate
   * @return ModulosModel|array
   */
  public function getAllModulos(bool $paginate = false, array $query = [])
  {
    if (!$paginate) {
      return $this->mModel->select()->from()->prepareSql()->get();
    } else if (!empty($query)) { //validamos si no esta vacia la consulta para evitar el error.
      return $this->mModel->select($query)->from()->orderBy()->limit()->offset();
    }
  }
}
