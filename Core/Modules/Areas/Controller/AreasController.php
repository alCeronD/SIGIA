<?php
require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once __DIR__ . '/../Const/AreasConst.php';
require_once BASE_URL . '/Autoload.php';

class AreasController
{
  protected AreasModel $AreasModel;

  public function __construct()
  {
    $this->AreasModel = new AreasModel();
  }

  public function renderViewArea()
  {
    return include_once __DIR__ . URL_MAIN_VIEW;
  }
  public function getData()
  {
    header(CONTENT_TYPE);
    $page = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;
    $limit = (isset($_GET['limit'])) ? (int) $_GET['limit'] : LIMIT;
    $resultCount = $this->AreasModel->getCount()->prepareSql()->get();
    // execute paginate
    $resultPaginate = UtilsFunctions::executePaginate($resultCount, $limit, $page);

    $dataSql['data'] = [
      'limit'           => $limit,
      'offset' => (int) $resultPaginate[CR_OFFSET]
    ];

    $resultSelect = $this->AreasModel->select()->orderBy()->limit()->offset()->prepareSql($dataSql)->get();

    if ($resultPaginate) {
      Response::success('registros', [
        CR_TOTAL_REGISTROS => $resultCount,
        CR_PAGINA_ACTUAL => $page,
        CR_CANTIDAD_PAGINAS => $resultPaginate[CR_TOTAL_PAGINAS],
        CR_REGISTROS => $resultSelect
      ]);
    }
  }
  public function createDepartment()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();
    $data[COLUMN_STATUS] = 1;
    $datosSelect[CR_DATA] = $data;
    // Validar que el nombre del item no sea unico, devolver mensaje indicando que el valor ya existe.
    $getDataExist = $this->AreasModel->select()->where([COLUMN_NOMBRE, "=", $data[COLUMN_NOMBRE]])->prepareSql($datosSelect)->get();
    $nombre = (!empty($getDataExist)) ? $getDataExist[0][COLUMN_NOMBRE] : "";
    if ($nombre === $data[COLUMN_NOMBRE]) {
      Response::success(AR_MESSAGE_INFO . $data[COLUMN_NOMBRE] . AR_MESSAGE_INFO_2, []);
    }
    // Ejecutar insert y devolver mensaje de retorno.
    $resultInsert = $this->AreasModel->insert($data)->prepareSql($datosSelect)->get();
    if ($resultInsert) {
      Response::success(AR_MESSAGE_SUCCESS, []);
    }
  }
  public function changeStatus()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();

    $dataUpdateSql['data'] = [
      "ar_cod" => $data['ar_cod'],
      "ar_status" => ($data['ar_status'] === 1) ? 2 : 1,
    ];

    if (empty($data['ar_cod'])) Response::success(AR_MESSAGE_INFO_ITEM, []);

    // validar si existe el elemento a actualizar.
    $resultExists = $this->AreasModel->select()->where()->prepareSql($dataUpdateSql)->get();

    if (empty($resultExists[0])) {
      Response::success(AR_MESSAGE_INFO_NO_CODIGO, []);
    }

    // Devuelve la cantidad de filas afectadas, si es 0, es un error, si es mayor que 0 significa que la actualización se realizó correctamente.
    $updateResult = $this->AreasModel->update($data)->where()->prepareSql($dataUpdateSql)->get();

    if ($updateResult > 0) {
      Response::success(MSG_REGISTRO_CAMBIO_ESTADO, []);
    }
  }
  public function editDepartment()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();

    $editDepartmentData['data'] = $data;

    $resultExists = $this->AreasModel->select()->where()->prepareSql($editDepartmentData)->get();

    // Validar la existencia de la informacion que enviamos y el código exista en la bd.
    if (count($resultExists) === 0) {
      Response::success(AR_MESSAGE_INFO_NO_CODIGO, []);
    }

    // Validar si el nombre enviado es igual a alguno de los registros de la tabla para evitar duplicidad.
    if ($resultExists[0]['ar_nombre'] === $data['ar_nombre']) {
      Response::success(AR_MESSAGE_DUPLICATE_NAME, []);
    }
    // validar que el nombre no este vacio.
    if (empty($data['ar_nombre']) || empty($data['ar_cod'])) Response::success(AR_MESSAGE_NO_DEPARTMENT, []);
    $resultUpdate = $this->AreasModel->update($data)->where()->prepareSql($editDepartmentData)->get();
    if ($resultUpdate > 0) {
      Response::success(MSG_REGISTRO_ACTUALIZAOD, []);
    }
  }
  public function deleteDepartment()
  {
    header(CONTENT_TYPE);
    $data = UtilsFunctions::returnGetDecode();

    // Ejecutar proceso para eliminar el.
    $dataDelete['data'] = $data;
    $resultDelete = $this->AreasModel->delete()->where()->prepareSql($dataDelete)->get();
    if ($resultDelete > 0) {
      Response::success(AR_MESSAGE_DELETE_SUCESS, []);
    }
  }
}
