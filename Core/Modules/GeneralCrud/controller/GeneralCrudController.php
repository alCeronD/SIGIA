<?php

#Incluir los modelos, la conexión.
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../const/ConstGeneralCrud.php';
require_once BASE_URL .'/Crud.php';
require_once BASE_URL . '/..'.GC_ROUTE_MODEL_GENERAL_CRUD;


class GeneralCrudController{
  protected $modelGeneralCrud;
  # Incluimos todos los modelos que vamos a usar
  public function __construct() {
    $this->modelGeneralCrud = new GeneralCrudModel();
  }

  public function renderGeneralView(){

    $this->modelGeneralCrud->insert(['QuintaPruebaAadicional','QuintaSextaPruebaddd15']);
    $result = $this->modelGeneralCrud->get();
    if($result){
      $this->modelGeneralCrud->select();
      $data = $this->modelGeneralCrud->get();
    }
    return include_once __DIR__ . '/../view/generalView.php';
  }
}