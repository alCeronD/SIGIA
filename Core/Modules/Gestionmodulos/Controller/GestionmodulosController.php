<?php

use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;
use ZipStream\Test\Util;

require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once __DIR__ . '/../Const/GestionModulosConst.php';
require_once BASE_URL . '/Autoload.php';

class GestionmodulosController extends ConfigController implements CrudInterface
{
  protected ServicesGestionModulos $sModulos;
  protected ServicesFunciones $sFunciones;
  protected ModulosModel $modulosModel;
  protected FuncionesModel $funcionesModel;
  protected array $files = [
    "css" => [
      'modulosView' => ['Modulos.css'],
      'functionsAssocByModulosView' => ['FunctionsAssoc.css']
    ],
    "js"  => [
      'modulosView' => ['Modulos.js', 'Selectors-Modulos.js'],
      'functionsAssocByModulosView' => ['FunctionsAssoc.js', 'Selectors-FunctionsAssoc.js']
    ]
  ];

  public function __construct()
  {
    $this->sModulos = new ServicesGestionModulos();
    $this->modulosModel = new ModulosModel();
    $this->funcionesModel = new FuncionesModel();
    $this->sFunciones = new ServicesFunciones();

    $this->createRoutes();
  }
  public function createRoutes()
  {
    $this->routes = [
      // inicio
      'dashboard' => ['label' => 'Inicio', 'url' => Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE)],
      'permisosIndexView' => [
        'label' => 'Seguridad del sistema',
        'url' => Router::createRoute('Permisos', 'Permisos', 'permisosIndexView', false, CR_DASHBOARD_LOWER_CASE),
        'parent' => 'dashboard'
      ],
      // vista de modulo
      'modulosView' => [
        'label' => 'Gestión de modulos',
        'url' => Router::createRoute(CR_GESTION_MODULOS, CR_GESTION_MODULOS, 'modulosView', false, CR_DASHBOARD_LOWER_CASE),
        'parent' => 'permisosIndexView'
      ],
      // vista de funciones asociadas al modulo
      'functionsAssocByModulosView' => [
        'label' => 'Funciones asociadas',
        'url' => Router::createRoute(CR_GESTION_MODULOS, CR_GESTION_MODULOS, 'getFunctionsAssoc', false, CR_DASHBOARD_LOWER_CASE),
        'parent' => 'modulosView'
      ]
    ];
  }

  /**
   * Vista principal que contiene el listado de los modulos.
   *
   * @return void
   */
  public function modulosView()
  {
    $path = BASE_URL . GM_ROUTES_MODULES_VIEW;
    Parent::renderView($path, __FUNCTION__);
  }

  /**
   * Vista en la que se visualizan las funciones asociadas al modulo, se accede desde modulosView.
   *
   * @return void
   */
  public function functionsAssocByModulosView()
  {
    $path = BASE_URL . GM_ROUTES_FUNCTIONS_ASSOC_VIEW;
    Parent::renderView($path, __FUNCTION__);
  }

  // get
  public function getData()
  {
    header(CONTENT_TYPE);

    $page = (isset($_GET[CR_PAGINA])) ? (int) $_GET[CR_PAGINA] : 1;
    $limit = (isset($_GET[CR_WORD_LIMIT])) ? (int) $_GET[CR_WORD_LIMIT] : LIMIT;

    $countModules = $this->modulosModel->getCount()->prepareSql()->get();
    // execute paginate
    $resultPaginate = UtilsFunctions::executePaginate($countModules['rowCounts'], $limit, $page);

    $dataSql[CR_DATA] = [
      CR_WORD_LIMIT           => $limit,
      CR_OFFSET => (int) $resultPaginate[CR_OFFSET]
    ];

    $sql = [
      'id_m AS `id_m`',
      'nombre_modulo AS `nombre_modulo`',
      'icono AS `icono`',
      'descripcion AS `descripcion`',
      'IF(status_modulo = 1, "Activo", "Inactivo" ) AS `status_modulo`'
    ];

    $queryModules = ($this->sModulos->getAllModulos(true, $sql))->prepareSql($dataSql)->get(); //enviamos flag true para continuar con la consulta, false para devolver el arreglo con todos los modulos.
    if (count($resultPaginate) > 0) {
      Response::responseRequest(HttpStatus::OK, true, "Registros", [
        CR_TOTAL_REGISTROS => count($countModules),
        CR_PAGINA_ACTUAL => ($page > $resultPaginate[CR_TOTAL_PAGINAS]) ? $resultPaginate[CR_TOTAL_PAGINAS] : $page, //Aca devolvemos la pagina, pero cuando se borra el ultimo registro de una pagina estamos devolviendo la pagina que recibimos desde la peticion, cuando hacemos la paginacion, si la pagina ES MAYOR A LA CANTIDAD DE PAGINAS TOTALES, NO DEVOLVEMOS LA PAGINA RECIBIDA, SINO LA ULTIMA PAGINA. esto para poder renderizar de forma correcta la informacion.
        CR_CANTIDAD_PAGINAS => $resultPaginate[CR_TOTAL_PAGINAS],
        CR_DATA => $queryModules
      ]);
    }
  }

  // update
  public function save()
  {
    try {
      header(CONTENT_TYPE);
      $data = UtilsFunctions::returnGetDecode();

      if (empty($data)) throw new Exception(GM_MESSAGE_MODULE_EMPTY, HttpStatus::BAD_REQUEST);


      // validamos si los campos obligatorios no estan vacios.
      $mapCampos = [
        GM_VAR_NOMBRE_MODULO => GM_WORDS_NOMBRE_MODULO,
        GM_VAR_ICONO => GM_ICONO_MODULO
      ];
      $validateCampos = UtilsFunctions::validateCampos($data, $mapCampos);
      //validamos los campos obligatorios y capturamos el catch en caso de que los campos obligatorios no se registren.
      if (!$validateCampos[CR_STATUS]) throw new Exception($validateCampos[CR_MESSAGE], $validateCampos[CR_CODE_RESPONSE]);

      $nameModule = (string) ucfirst(strtolower(trim($data[GM_VAR_NOMBRE_MODULO])));
      $data[GM_VAR_NOMBRE_MODULO] = $nameModule;
      // extramos el id para validar si ya existe un registro diferente pero con el mismo nombre del modulo

      $id_m = (int) $data['id_m'];


      // validar que el nombre del modulo no exista en la base de datos usando el valor diferente, es decir, excluir nuestro propio registro, para asi validar el directorio.
      $dataSelect[CR_DATA] = $data;
      $resultExistModule = $this->modulosModel->select()->from()->where([GM_VAR_NOMBRE_MODULO, '=', $nameModule])->where([GM_ID_MODULO, '<>', $id_m])->prepareSql($dataSelect)->get();


      // como el sistema encontro que ya existe el nombre de ese modulo y esta con OTRO ID, entonces lo marcamos como error.
      if (!empty($resultExistModule)) {
        // Capturamos el error y evitamos hacer el UPDATE
        throw new Exception(
          "El nombre '{$nameModule}' ya está en uso por el módulo ID {$resultExistModule[0]['id_m']}",
          HttpStatus::CONFLICT
        );
      }

      // validamos que el nombre del modulo ya tenga instalado el directorio fisico.
      $moduleDir = $this->validateDir($nameModule);

      if (!$moduleDir['status']) throw new Exception($moduleDir[CR_MESSAGE], $moduleDir[CR_CODE_RESPONSE]);

      $dataUpdatePrepare[CR_DATA] = $data;
      $responseUpdateModule = $this->modulosModel->update($data)->where()->prepareSql($dataUpdatePrepare)->get();

      if (!$responseUpdateModule['status']) {
        $dbaMessageHandler = DatabaseHandler::validateResponse($responseUpdateModule);
        throw new Exception($dbaMessageHandler[CR_MESSAGE], $dbaMessageHandler[CR_CODE_RESPONSE]);
      }

      Response::responseRequest(HttpStatus::OK, true, GM_MESSAGE_MODULE_UPDATE, $responseUpdateModule);
    } catch (\Exception $th) {
      Response::responseRequest($th->getCode(), false, $th->getMessage(), []);
    }
  }

  // insert
  public function store()
  {

    try {
      header(CONTENT_TYPE);
      $data = UtilsFunctions::returnGetDecode();
      $data[GM_STATUS_MODULO] = 1; //Estado 1.
      $nameModule = (string) ucfirst(strtolower(trim($data[GM_VAR_NOMBRE_MODULO])));
      $data[GM_VAR_NOMBRE_MODULO] = $nameModule;
      $dataInsert[CR_DATA] = $data;
      if (empty($data)) throw new Exception("Datos vacios", HttpStatus::UNPROCESSABLE_ENTITY);

      // mapeamos los campos obligatorios para validar en caso de que esten vacios.
      $mapCampos = [
        GM_VAR_NOMBRE_MODULO => GM_WORDS_NOMBRE_MODULO,
        GM_VAR_ICONO => GM_ICONO_MODULO
      ];
      $validateCampos = UtilsFunctions::validateCampos($data, $mapCampos);
      //validamos los campos obligatorios y capturamos el catch en caso de que los campos obligatorios no se registren.
      if (!$validateCampos[CR_STATUS]) throw new Exception($validateCampos[CR_MESSAGE], $validateCampos[CR_CODE_RESPONSE]);

      // validar que solo se permitan letras.
      if (!Regex::validarLetras($nameModule)) throw new Exception("El nombre del modulo contiene caracteres especiales", HttpStatus::BAD_REQUEST);

      // validamos que el nombre del modulo sea un directorio.
      $validateDir = $this->validateDir($nameModule);

      if (!$validateDir['status']) throw new Exception($validateDir['message'], $validateDir['codeResponse']);

      $responseCreateModule = $this->modulosModel->insert($data)->prepareSql($dataInsert)->get();

      if (!$responseCreateModule[CR_STATUS]) {
        $DbaResponse = DatabaseHandler::validateResponse($responseCreateModule);
        //captura el error y lo envia al catch.
        throw new Exception($DbaResponse[CR_MESSAGE], $DbaResponse[CR_CODE_RESPONSE]);
      }

      Response::responseRequest(HttpStatus::CREATED, true, GM_MESSAGE_MODULE_CREATE, []);
    } catch (\Exception $e) {
      Response::responseRequest($e->getCode(), false, $e->getMessage(), []);
    }
  }

  public function delete()
  {
    throw new \Exception('Not implemented');
  }

  public function changeStatus()
  {
    try {
      header(CONTENT_TYPE);
      $data = UtilsFunctions::returnGetDecode();
      if (empty($data)) throw new Exception(GM_MESSAGE_MODULE_EMPTY, HttpStatus::BAD_REQUEST);
      $finalMessage = ((int) $data[GM_STATUS_MODULO] === 1) ? GM_MESSAGE_MODULE_ENABLED : GM_MESSAGE_MODULE_DISABLED;
      $data[GM_STATUS_MODULO] = $data[GM_STATUS_MODULO] === 1 ? 0 : 1; //invertimos los estados recibidos por el usuario para asi validarlo y ejecutarlo.

      $dataChangeStatusPrepare[CR_DATA] = $data;
      $responseChangeStatus = $this->modulosModel->update($data)->where()->prepareSql($dataChangeStatusPrepare)->get();

      if (!$responseChangeStatus[CR_STATUS]) {
        $responseHandler = DatabaseHandler::validateResponse($responseChangeStatus);
        throw new Exception($responseHandler[CR_MESSAGE], $responseHandler[CR_CODE_RESPONSE]);
      }

      Response::responseRequest(HttpStatus::OK, true, $finalMessage, []);
    } catch (Exception $th) {
      Response::responseRequest($th->getCode(), false, $th->getMessage(), []);
    }
  }


  /**
   * Function para validar si el modulo enviado existe, se crea en forma de function para re validar en la function store y save
   *
   * @param string $nameModule
   * @return array
   */
  public static function validateDir(string $nameModule = ""): array
  {
    // usamos realpath para eliminar los saltos de directorios como /../
    $routeModules =  realpath(BASE_URL . "/../Modules/{$nameModule}");

    // validar que el nombre del modulo exista en fisico EN EL SISTEMA.
    if (!is_dir($routeModules) || !$nameModule) {
      return [
        'status' => false,
        'message' => "El nombre del módulo '{$nameModule}' no existe en el directorio fisico del sistema",
        'codeResponse' => HttpStatus::CONFLICT
      ];
    }

    return [
      'status' => true,
      'message' => "",
    ];
  }

  /**
   * Function para solicitar los datos de las funcionalidades
   *
   * @return void
   */
  public function getFunctionsAssoc(): void
  {
    try {
      header(CONTENT_TYPE);
      $page = (isset($_GET[CR_PAGINA])) ? (int) $_GET[CR_PAGINA] : 1;
      $limit = (isset($_GET[CR_WORD_LIMIT])) ? (int) $_GET[CR_WORD_LIMIT] : LIMIT;
      $id_m = (isset($_GET['idModulo'])) ? (int) $_GET['idModulo'] : null;
      //obtener el nombre del modulo.
      $moduloName = (isset($_GET['nombreModulo'])) ? (string) $_GET['nombreModulo'] : null;
      // obtenemos los nombres de los controladores del sistema
      $filesControllers = ScanFiles::getControllers($moduloName);
      $filesControllers = str_replace(".php", "", $filesControllers);
      if (empty($id_m)) throw new Exception("id del modulo incorrecto", HttpStatus::UNPROCESSABLE_ENTITY);

      $sql = [
        "f.id_funcion AS 'idFuncion'",
        "f.id_modulo AS 'nombreModulo'",
        "f.nombre_funcion AS 'nombreFuncion'",
        "f.nombre_funcion_user AS 'nombreFuncionLabel'",
        "f.tp_funcion AS 'tipoFuncion'",
        "tpf.nombre_tp_funcion AS 'tipoDeFuncion'"
      ];
      $conditions = ["f.id_modulo",  "=", $id_m];
      $data = [
        'f_id_modulo' => $id_m
      ];
      $dataPrepare[CR_DATA] = $data;
      // aplicamos el count a la funcionalidad del servicio porque este nos devuelve el modelo, el motivo es porque necesitamos hacer paginacion a la cantidad de registros basada en el modulo, no a todo el registro de la tabla.
      $countFunciones = count($this->sFunciones->getAllFunctionsFromModule($sql, $conditions)->prepareSql($dataPrepare)->get());
      $paginateFunctions = UtilsFunctions::executePaginate($countFunciones, $limit, $page);
      $data = [
        'f_id_modulo' => $id_m,
        CR_OFFSET => $paginateFunctions[CR_OFFSET],
        CR_WORD_LIMIT => $limit
      ];
      $dataPrepare[CR_DATA] = $data;

      // aca implementamos el limite y offset porque usamos la misma funcion previamente para contar las funciones basadas en el modulo.
      $getSelectFunctions = $this->sFunciones->getAllFunctionsFromModule($sql, $conditions)
        ->limit()
        ->offset()
        ->prepareSql($dataPrepare)->get();

      if (count($getSelectFunctions) > 0) {
        Response::responseRequest(HttpStatus::OK, true, "Registros", [
          CR_TOTAL_REGISTROS => $countFunciones,
          CR_PAGINA_ACTUAL => ($page > $paginateFunctions[CR_TOTAL_PAGINAS]) ? $paginateFunctions[CR_TOTAL_PAGINAS] : $page, //Aca devolvemos la pagina, pero cuando se borra el ultimo registro de una pagina estamos devolviendo la pagina que recibimos desde la peticion, cuando hacemos la paginacion, si la pagina ES MAYOR A LA CANTIDAD DE PAGINAS TOTALES, NO DEVOLVEMOS LA PAGINA RECIBIDA, SINO LA ULTIMA PAGINA. esto para poder renderizar de forma correcta la informacion.
          CR_CANTIDAD_PAGINAS => $paginateFunctions[CR_TOTAL_PAGINAS],
          CR_DATA => $getSelectFunctions,
          CR_FILES => $filesControllers
        ]);
      } else {
        Response::responseRequest(HttpStatus::OK, true, "NO hay registros", [
          CR_TOTAL_REGISTROS => $countFunciones,
          CR_DATA => $getSelectFunctions,
          CR_FILES => $filesControllers
        ]);
      }
    } catch (\Exception $th) {
      Response::responseRequest($th->getCode(), false, $th->getMessage(), []);
    }
  }
}
