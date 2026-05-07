<?php

#Incluir los modelos, la conexión.
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../const/ConstGeneralCrud.php';
require_once BASE_URL .'/Crud.php';
require_once BASE_URL . '/..'.GC_ROUTE_MODEL_GENERAL_CRUD;
require_once BASE_URL . '/Response.php';


class GeneralCrudController{
  protected $modelGeneralCrud;
  # Incluimos todos los modelos que vamos a usar
  public function __construct() {
    $this->modelGeneralCrud = new GeneralCrudModel();
  }

  public function renderGeneralView(){
    return include_once __DIR__ . '/../view/generalView.php';
  }

  public function selectData(){
    header(CONTENT_TYPE);

    $this->modelGeneralCrud->select();
    $result = $this->modelGeneralCrud->get();

    success('registros', $result);
  }
}