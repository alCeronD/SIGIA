<?php

#Incluir los modelos, la conexión.

use ZipStream\Test\Util;

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

    # Types del paginado
    $types = 'ii';

    // Cantidad de paginas
    $this->modelGeneralCrud->count();
    $preparedCount = $this->modelGeneralCrud->prepareSql();
    $resultCount = $this->modelGeneralCrud->get($preparedCount);

    # se coloca get pero esto vendrá como petición desde fetch de javascript
    $paginaActual = $_GET['page'] ?? 1;

    // lógica paginado
    $resultPaginate = UtilsFunctions::executePaginate($resultCount, LIMIT, $paginaActual);

    $dataSql = [
      'types' => $types,
      'data' => [LIMIT, $resultPaginate['offset']]
    ];

    $this->modelGeneralCrud->select(false);
    $this->modelGeneralCrud->orderBy('', true);
    $this->modelGeneralCrud->limit();
    $this->modelGeneralCrud->offset();
    $sqlPreparedSelect = $this->modelGeneralCrud->prepareSql($dataSql);
    $resultSelect = $this->modelGeneralCrud->get($sqlPreparedSelect);
    Response::success('registros', [
      'items' => $resultSelect,
      'limit' => LIMIT,
      'cantidadPaginas' => $resultPaginate['totalPaginas'],
      'totalRegistros' => $resultCount,
      'paginaActual' => $paginaActual
    ]);
  }

  public function insert()
  {

    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();

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
