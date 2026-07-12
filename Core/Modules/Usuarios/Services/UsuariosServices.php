<?php

// clase de servicio para obtener el usuario segun el id.
class UsuariosServices
{
  protected UsuariosModel $userModel;
  public function __construct()
  {
    $this->userModel = new UsuariosModel();
  }
  // Function para extraer usuario usando el documento de identidad.
  public function getUserByDocum(int $docum)
  {
    if (!is_int($docum)) return;
    $dataUser[CR_DATA] = [
      'usu_docum' => $docum
    ];
    $userData = $this->userModel->select()->from()->where(['usu_docum', '=', $docum])->prepareSql($dataUser)->get();
    return $userData;
  }
}
