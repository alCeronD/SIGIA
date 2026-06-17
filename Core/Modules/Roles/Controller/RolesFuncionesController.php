<?php

class RolesFuncionesController
{
  protected ServicesRoles $sRoles;
  protected array $allRoles;
  public function __construct()
  {
    $this->sRoles = new ServicesRoles();
  }

  /**
   * Vista de las funciones asociadas a los roles.
   *
   * @return void
   */
  public function mostrarFuncionesAssoc()
  {
    return include_once __DIR__ . '../../views/rolesFunciones.php';
  }


  /**
   * funcion para enviar la data al cliente para renderizar en el select.
   *
   * @return void
   */
  public function getRoles()
  {
    header(CONTENT_TYPE);
    $this->allRoles = $this->sRoles->getAllRoles();
    if (count($this->allRoles) > 0) {
      Response::responseRequest(HttpStatus::OK, true, CR_REGISTROS, $this->allRoles);
    }
  }
}
