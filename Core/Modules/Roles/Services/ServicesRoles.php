<?php
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/RolesConst.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;

/**
 * Clase de servicio para conectar con otro controlador, en este caso, para solicitar unos datos.
 */
class ServicesRoles
{
  protected RolesModel $rlModel; //Implementamos el modelo para solicitar al servicio los datos.

  public function __construct()
  {
    $this->rlModel = new RolesModel();
  }

  public function getAllRoles()
  {
    return $this->rlModel->select()->prepareSql()->get();
  }
}
