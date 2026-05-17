<?php

#Incluir los modelos, la conexión.
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_URL . '/Autoload.php';
require_once __DIR__ . '/../const/ConstGeneralCrud.php';



class GeneralCrudController
{
  protected $modelGeneralCrud;
  # Incluimos todos los modelos que vamos a usar
  public function __construct()
  {
    $this->modelGeneralCrud = new GeneralCrudModel();
  }

  public function renderGeneralView()
  {
    return include_once __DIR__ . '/../view/generalView.php';
  }

  public function selectData()
  {
    header(CONTENT_TYPE);

    $this->modelGeneralCrud->select();
    $resultPrepared = $this->modelGeneralCrud->prepareSql();
    $result = $this->modelGeneralCrud->get($resultPrepared);

    Response::success('registros', $result);
  }

  public function insert()
  {

    header(CONTENT_TYPE);

    if (ob_get_length()) ob_clean();

    $json = file_get_contents("php://input");

    $data = json_decode($json, true);

    // caso el tipo de dato para despues pasar por referencia
    $types = $this->modelGeneralCrud->castParam();

    // TODO:: Crear una posible function para castear la informacion, podemos re utilizar esta estructura
    $gc_nombre = (string) $data['gc_nombre'];
    $gc_descrip = (string) $data['gc_descrip'];

    $dataSql = [];

    $dataSql['types'] = $types;
    $dataSql['data'][] = $gc_nombre;
    $dataSql['data'][] = $gc_descrip;

    $this->modelGeneralCrud->insert($data);
    $sqlPrepared = $this->modelGeneralCrud->prepareSql($dataSql);

    $resultGet = $this->modelGeneralCrud->get($sqlPrepared);

    if (!$resultGet || is_string($resultGet)) {
      Response::fail('Error', [$resultGet]);
    }

    Response::success('Registro exitoso', [$resultGet]);
  }
}
