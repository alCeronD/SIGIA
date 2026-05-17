<?php

#Incluir los modelos, la conexión.
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_URL . '/Autoload.php';
require_once __DIR__ . '/../const/ConstGeneralCrud.php';



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

    Response::success('registros', $result);
  }

  public function insert(){

    header(CONTENT_TYPE);

    if (ob_get_length()) ob_clean();

    $json = file_get_contents("php://input");

    $data = json_decode($json, true);


    $this->modelGeneralCrud->insert($data);



    // Ya recibe la data, AHORA CREAR EL CONTROLADOR PARA INSERTAR LOS DATOS.
    // Response::success('respuesta', $data);
  }
}