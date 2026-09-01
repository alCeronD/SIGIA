<?php

// Controlador que maneja las funciones asociadas al modulo
class FuncionesModuloController implements CrudInterface
{
  protected FuncionesModel $fModel;
  protected ServicesFunciones $sFunciones;
  protected RolesFuncionesModel $rfMode;
  protected ServicesGestionModulos $sgM;
  public function __construct()
  {
    $this->fModel = new FuncionesModel();
    $this->rfMode = new RolesFuncionesModel();
    $this->sgM = new ServicesGestionModulos();
    $this->sFunciones = new ServicesFunciones();
  }
  // no se le esta dando utilidad de momento.
  public function getData()
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
      if (!$filesControllers['status']) {
        // capturo la exception que me devuelve y la encadeno al router.
        /** @var \Exception $th */
        $th = $filesControllers['throw'];
        throw new Exception($th->getMessage(), $th->getCode());
      }

      // accedo a los controladores escaneados y elimino ciertos caracteres innecesarios.
      $filesControllers = str_replace(".php", "", $filesControllers['controlls']);
      if (empty($id_m)) throw new Exception("id del modulo incorrecto", HttpStatus::UNPROCESSABLE_ENTITY);

      // Usamos COALESCE para definir un valor por defecto en caso de que el valor sea NULL, los campos VACIOS NO SON CONSIDERADOS VACIOS, POR ENDE, SE VALIDA USANDO NULLIF SI EL CAMPO TIENE COMILLA SIMPLE (''), ES DECIR, QUE ESTE VACIO.
      $sql = [
        "f.id_funcion AS 'idFuncion'",
        "f.id_modulo AS 'nombreModulo'",
        "f.nombre_funcion AS 'nombreFuncion'",
        "f.nombre_funcion_user AS 'nombreFuncionLabel'",
        "f.tp_funcion AS 'tipoFuncion'",
        "COALESCE(NULLIF(f.nameController, ''), 'No asociado') AS 'controladorAsociado'",
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
  public function delete()
  {
    try {

      $this->fModel->beginTransaction();
      header(CONTENT_TYPE);
      $data = UtilsFunctions::returnGetDecode();
      $dataDelete[CR_DATA] = $data;
      $dtaDelete[CR_DATA] = [
        'rlp_id_funcion' => $data['id_funcion']
      ];

      // proceso para eliminar la funcion de la tabla rolesFunciones.
      $dltRolesFunciones = $this->rfMode->delete()->where(['rlp_id_funcion', '=', $data['id_funcion']])->prepareSql($dtaDelete)->get();

      if (!$dltRolesFunciones[CR_STATUS]) {
        $responseDta = DatabaseHandler::validateResponse($dltRolesFunciones);
        throw new Exception($responseDta[CR_MESSAGE], $responseDta[CR_CODE_RESPONSE]);
      }

      // funcion para eliminar en la funcion de la tabla funciones
      $dltFuncion = $this->fModel->delete()->where()->prepareSql($dataDelete)->get();

      if (!$dltFuncion['status']) {
        $responseDta = DatabaseHandler::validateResponse($dltFuncion);
        throw new Exception($responseDta[CR_MESSAGE], $responseDta[CR_CODE_RESPONSE]);
      }
      $this->rfMode->commit();
      Response::responseRequest(HttpStatus::OK, true, "Función eliminada correctamente", []);
    } catch (\Exception $th) {
      $this->rfMode->rollback();
      Response::responseRequest($th->getCode(), false, $th->getMessage());
    }
  }
  /**
   * Guardar funciones en la base de datos.
   *
   * @return void
   */
  public function store()
  {
    try {
      header(CONTENT_TYPE);
      $data = UtilsFunctions::returnGetDecode();
      if (empty($data)) throw new Exception("Datos enviados incorrectos", HttpStatus::BAD_REQUEST);
      $file = $data['nameController'];
      // unset($data['file']);

      $responseValidateFunction = $this->validateFunction($data['id_modulo'], $file, $data['nombre_funcion']);
      if (!$responseValidateFunction['status']) {
        throw new Exception($responseValidateFunction['message'], $responseValidateFunction['codeResponse']);
      }
      $dataPrepare[CR_DATA] = $data;

      $dataInsert = $this->fModel->insert($data)->prepareSql($dataPrepare)->get();
      if (!$dataInsert['status']) {
        $responseHander = DatabaseHandler::validateResponse($dataInsert);
        throw new Exception($responseHander[CR_MESSAGE], $responseHander[CR_CODE_RESPONSE]);
      }

      Response::responseRequest(HttpStatus::CREATED, true, 'Funcionalidad creada exitosamente', []);
    } catch (\Exception $th) {
      Response::responseRequest($th->getCode(), false, $th->getMessage());
    }
  }
  public function save()
  {
    try {
      header(CONTENT_TYPE);
      $data = UtilsFunctions::returnGetDecode();
      $data['tp_funcion'] = $data['tp_funcion'] === "render" ? (int) 1 : (int) 2;


      if (empty($data)) throw new Exception("Faltan datos para procesar la solicitud", HttpStatus::BAD_REQUEST);
      $responseValidateFunction = $this->validateFunction($data['id_modulo'], $data['nameController'], $data['nombre_funcion']);

      if (!$responseValidateFunction['status']) {
        throw new Exception($responseValidateFunction['message'], $responseValidateFunction['codeResponse']);
      }
      $dataUpdate[CR_DATA] = $data;
      $responseUpdate = $this->fModel->update($data)->where()->prepareSql($dataUpdate)->get();


      if (!$responseUpdate['status']) {
        $databaseResponse = DatabaseHandler::validateResponse($responseUpdate);
        throw new Exception($databaseResponse[CR_MESSAGE], $databaseResponse[CR_CODE_RESPONSE]);
      }

      Response::responseRequest(HttpStatus::OK, true, "Datos de funcionalidad actualizados correctamente");
    } catch (\Exception $th) {
      Response::responseRequest($th->getCode(), false, $th->getMessage(), []);
    }
  }



  /**
   * Function para validar si la funcionalidad existe en el sistema, se re utiliza en la funcion store y save.
   *
   * @param integer $idModulo
   * @param string $file
   * @param string $nombreFunction
   * @return array
   */
  protected function validateFunction(int $idModulo, String $file = '', String $nombreFunction = ''): array
  {
    try {

      $moduleName = $this->sgM->getNameModule($idModulo)[0]['nombre_modulo'];
      if (empty($moduleName)) throw new Exception("Modulo incorrecto", HttpStatus::BAD_REQUEST);
      $classFile = realpath(BASE_PATH . "/../Modules/{$moduleName}/Controller/{$file}.php");
      if (!$classFile || !is_file($classFile)) {
        throw new Exception("El archivo del controlador no existe físicamente", HttpStatus::BAD_REQUEST);
      }

      if (!class_exists($file)) throw new Exception("La clase no existe en el sistema", HttpStatus::BAD_REQUEST);
      if (!method_exists($file, $nombreFunction)) throw new Exception("La funcionalidad no está disponible", HttpStatus::INTERNAL_SERVER_ERROR);

      // hacemos una instancia previa del metodo, validamos si es publico para poder implementarlo en la base de datos.
      $insanceItem = new ReflectionMethod($file, $nombreFunction);
      if (!$insanceItem->isPublic()) throw new Exception("Solo se pueden validar funcionalidades públicas", HttpStatus::BAD_REQUEST);

      return [
        CR_STATUS => true,
        CR_CODE_RESPONSE => 1,
        CR_MESSAGE => 'Ok'
      ];
    } catch (\Throwable $th) {
      return [
        CR_STATUS => false,
        CR_CODE_RESPONSE => $th->getCode(),
        CR_MESSAGE => $th->getMessage()
      ];
    }
  }
}
