<?php


require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_URL . '/Autoload.php';
require_once __DIR__ . '/../const/ConstGeneralCrud.php';


class GeneralCrudController extends ConfigGeneralCrud
{
  protected GeneralCrudModel $modelGeneralCrud;
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
    $paginaActual = $_GET[GC_PAGE] ?? 1;

    // Cantidad de paginas
    $resultCount = $this->modelGeneralCrud->count()->prepareSql()->get();

    // lógica paginado
    $resultPaginate = UtilsFunctions::executePaginate($resultCount, LIMIT, $paginaActual);

    $dataSql = [
      GC_DATA => [LIMIT, $resultPaginate[GC_OFFSET]]
    ];

    $this->modelGeneralCrud->select()->orderBy('', true)->limit()->offset();
    $resultSelect = $this->modelGeneralCrud->prepareSql($dataSql)->get();

    # Validar forma de envio dependiendo de lo que tenga la consulta, si tiene offset o limit validamos su re envio
    if (UtilsFunctions::validateContentString($this->modelGeneralCrud->showSql(), 'OFFSET') && UtilsFunctions::validateContentString($this->modelGeneralCrud->showSql(), 'LIMIT')) {
      $response =  [
        GC_ITEMS => $resultSelect,
        GC_LIMIT => LIMIT,
        GC_CANTIDAD_PAGINAS => $resultPaginate[GC_TOTAL_PAGINAS],
        GC_TOTAL_REGISTROS => $resultCount,
        GC_PAGINA_ACTUAL => $paginaActual
      ];
    } else {
      $response = [
        GC_ITEMS => $resultSelect,
        GC_TOTAL_REGISTROS => $resultCount
      ];
    }


    Response::success('registros', $response);
  }

  public function insert()
  {

    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();

    // caso el tipo de dato para despues pasar por referencia


    // TODO:: Crear una posible function para castear la informacion, podemos re utilizar esta estructura
    $gc_nombre = (string) $data['gc_nombre'];
    $gc_descrip = (string) $data['gc_descrip'];

    $dataSql = [];

    $dataSql['data'][] = $gc_nombre;
    $dataSql['data'][] = $gc_descrip;

    $this->modelGeneralCrud->insert($data);
    $types = $this->modelGeneralCrud->castParam();
    $dataSql['types'] = $types;
    $sqlPrepared = $this->modelGeneralCrud->prepareSql($dataSql);
    // $resultGet = $this->modelGeneralCrud->get($sqlPrepared);

    $resultInsert = $this->modelGeneralCrud->insert($dataSql)->prepareSql()->get();
    var_dump($resultInsert);
    die();
    // if (!$resultGet || is_string($resultGet)) {
    //   Response::fail('Error', [$resultGet]);
    // }

    // Response::success('Registro exitoso', [$resultGet]);

    // $this->modelGeneralCrud->insert($data);
    // $types = $this->modelGeneralCrud->castParam();
    // $dataSql['types'] = $types;
    // $sqlPrepared = $this->modelGeneralCrud->prepareSql($dataSql);
    // $resultGet = $this->modelGeneralCrud->get($sqlPrepared);

    // if (!$resultGet || is_string($resultGet)) {
    //   Response::fail('Error', [$resultGet]);
    // }

    // Response::success('Registro exitoso', [$resultGet]);
  }

  public function update()
  {
    header(CONTENT_TYPE);

    $data = UtilsFunctions::returnGetDecode();
    $primaryKey = $this->modelGeneralCrud->getPrimaryKey();
    $dataUpdateSql = [];

    // Implementamos todos los valores en el arreglo exceptuando la primary key.
    foreach ($data as $key => $value) {

      if ($primaryKey === $key) {
        continue;
      } else {
        $dataUpdateSql['data'][] = $value;
      }
    }
    $dataUpdateSql['data'][] = $data[$primaryKey];

    $this->modelGeneralCrud->update($data);
    $this->modelGeneralCrud->where($data);
    $typesUpdate = $this->modelGeneralCrud->castParam();
    $dataUpdateSql['types'] = $typesUpdate;

    $preparedSql = $this->modelGeneralCrud->prepareSql($dataUpdateSql);
    $responseUpdate = $this->modelGeneralCrud->get($preparedSql);

    if ($responseUpdate) {
      Response::success('Registro actualizado con exito', [$responseUpdate]);
    }
  }

  public function delete()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();

    $this->modelGeneralCrud->delete();
    $this->modelGeneralCrud->where($data);

    $deleteSql = [];

    $types = $this->modelGeneralCrud->castParam();

    $deleteSql['types'] = $types;
    foreach ($data as $key => $value) {
      $deleteSql['data'][] = $value;
    }
    $preapredSqlDelete = $this->modelGeneralCrud->prepareSql($deleteSql);
    $resultDelete = $this->modelGeneralCrud->get($preapredSqlDelete);

    if ($resultDelete) {
      Response::success('registro eliminado exitosamente', $resultDelete);
    }
  }
}
