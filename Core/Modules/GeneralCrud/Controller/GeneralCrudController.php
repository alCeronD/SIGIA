<?php

require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_URL . '/Autoload.php';
require_once __DIR__ . '/../Const/ConstGeneralCrud.php';
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
    Parent::createRoutes();
    return include_once __DIR__ . '/../View/generalView.php';
  }

  public function selectData()
  {
    header(CONTENT_TYPE);
    $paginaActual = (int) $_GET[GC_PAGE] ?? 1;
    $limit =  $_GET[GC_LIMIT] ?? LIMIT;

    // Cantidad de paginas
    $resultCount = $this->modelGeneralCrud->getCount()->prepareSql()->get();
    // lógica paginado
    $resultPaginate = UtilsFunctions::executePaginate($resultCount['rowCounts'], $limit, $paginaActual);
    $dataSql = [];
    $dataSql['data'] = [
      'limit'           => $limit,
      'offset' => $resultPaginate[GC_OFFSET]
    ];

    $resultSelect = $this->modelGeneralCrud->select()->from()->orderBy()->limit()->offset()->prepareSql($dataSql)->get();
    $response =  [
      GC_ITEMS => $resultSelect,
      GC_LIMIT => (int) $limit,
      GC_CANTIDAD_PAGINAS => $resultPaginate[GC_TOTAL_PAGINAS],
      GC_TOTAL_REGISTROS => $resultCount,
      GC_PAGINA_ACTUAL => $paginaActual
    ];

    Response::responseRequest(HttpStatus::OK, true, 'Registros', $response);
  }

  public function insert()
  {

    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $data['gc_status'] = 1;
    $dataSql = [];
    $dataSql['data'] = $data;

    $this->modelGeneralCrud->insert($data);
    $resultInsert = $this->modelGeneralCrud->prepareSql($dataSql)->get();
    if (!$resultInsert['status']) Response::responseRequest(HttpStatus::INTERNAL_SERVER_ERROR, false, MSG_ERROR_EJECUTAR_PROCESO, []);
    Response::responseRequest(HttpStatus::CREATED, true, 'Registro exitoso',  [$resultInsert]);
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

    $this->modelGeneralCrud->update($data)->where();
    $responseUpdate = $this->modelGeneralCrud->prepareSql($dataUpdateSql)->get();

    if ($responseUpdate['status']) Response::responseRequest(HttpStatus::OK, true, GC_SUCCESS_UPDATE, [$responseUpdate]);
  }

  public function delete()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $deleteSql['data'] = $data;
    $resultDelete = $this->modelGeneralCrud->delete()->where()->prepareSql($deleteSql)->get();
    if ($resultDelete['status']) {
      Response::responseRequest(HttpStatus::NO_CONTENT, true, GC_DELETE_SUCCESS, [$resultDelete]);
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
    $resultChangeStatus = $this->modelGeneralCrud->update($data)->where()->prepareSql($dataUpdateSql)->get();
    if ($resultChangeStatus['status']) Response::responseRequest(HttpStatus::OK, true, GC_SUCCESS_UPDATE, [$resultChangeStatus]);
  }
}
