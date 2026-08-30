<?php

// estas funciones apuntan desde la vista: FUNCIONES ASOCIADAS AL MODULO, si en un futuro se requieren cambiar, mover el controlador o las funciones a otro modulo.

class FuncionesController implements CrudInterface
{
  protected FuncionesModel $fModel;
  protected RolesFuncionesModel $rfMode;
  protected ServicesGestionModulos $sgM;
  public function __construct()
  {
    $this->fModel = new FuncionesModel();
    $this->rfMode = new RolesFuncionesModel();
    $this->sgM = new ServicesGestionModulos();
  }
  // no se le esta dando utilidad de momento.
  public function getData()
  {
    throw new \Exception('Not implemented');
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
      $file = $data['file'];
      unset($data['file']);

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
      $responseValidateFunction = $this->validateFunction($data['id_modulo'], $data['file'], $data['nombre_funcion']);
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
      $classFile = realpath(BASE_URL . "/../Modules/{$moduleName}/Controller/{$file}.php");
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
