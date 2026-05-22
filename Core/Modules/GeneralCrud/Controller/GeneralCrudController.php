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
    $dataSql = [];

    $dataSql['data'] = [
      'limit'           => LIMIT,
      'resultPaginated' => $resultPaginate[GC_OFFSET]
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

    $gc_nombre = (string) $data['gc_nombre'];
    $gc_descrip = (string) $data['gc_descrip'];

    $dataSql = [];

    $dataSql['data'] = $data;

    $this->modelGeneralCrud->insert($data);
    $resultInsert = $this->modelGeneralCrud->prepareSql($dataSql)->get();
    if (!$resultInsert) Response::fail('Error al ejecutar el procedimiento', [$resultInsert]);
    Response::success('Registro exitoso', [$resultInsert]);
  }

  public function update()
  {
    header(CONTENT_TYPE);

    $data = UtilsFunctions::returnGetDecode();
    $primaryKey = $this->modelGeneralCrud->getPrimaryKey();
    $dataUpdateSql = [];

    $keyData = [];
    # extraigo el primary key del arreglo.
    foreach ($data as $key => $value) {
      if ($key === $primaryKey) {
        $keyData = [
          $key => $value
        ];
      }
    }
    #Elimino el primary que independiente de donde este en el arreglo que llega desde el front
    unset($data[$primaryKey]);
    #Agrego el primary key al final del arreglo para mantener la nomenclatura requerida para enviar los datos.
    $data[$primaryKey] = $keyData[$primaryKey];
    $dataUpdateSql[GC_DATA] = $data;

    $this->modelGeneralCrud->update($data)->where($data);
    $responseUpdate = $this->modelGeneralCrud->prepareSql($dataUpdateSql)->get();

    if ($responseUpdate) Response::success(GC_SUCCESS_UPDATE, [$responseUpdate]);
  }

  public function delete()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $deleteSql['data'] = $data;
    $resultDelete = $this->modelGeneralCrud->delete()->where($data)->prepareSql($deleteSql)->get();
    if ($resultDelete) {
      Response::success(GC_DELETE_SUCCESS, [$resultDelete]);
    }
  }

  public function changeStatusItem()
  {
    header(CONTENT_TYPE);

    // traer la data
    $data = UtilsFunctions::returnGetDecode();
    $primaryKey = $this->modelGeneralCrud->getPrimaryKey();

    $keyData = [];
    # extraigo el primary key del arreglo.
    foreach ($data as $key => $value) {
      if ($key === $primaryKey) {
        $keyData = [
          $key => $value
        ];
      }
    }
    #Elimino el primary que independiente de donde este en el arreglo que llega desde el front
    unset($data[$primaryKey]);
    #Agrego el primary key al final del arreglo para mantener la nomenclatura requerida para enviar los datos.
    $data[$primaryKey] = $keyData[$primaryKey];
    $dataUpdateSql[GC_DATA] = $data;
    $resultChangeStatus = $this->modelGeneralCrud->update($data)->where($data)->prepareSql($dataUpdateSql)->get();

    if ($resultChangeStatus) Response::success(GC_SUCCESS_UPDATE, [$resultChangeStatus]);
  }
}
