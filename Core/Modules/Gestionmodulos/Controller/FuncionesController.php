<?php

// estas funciones apuntan desde la vista: FUNCIONES ASOCIADAS AL MODULO, si en un futuro se requieren cambiar, mover el controlador o las funciones a otro modulo.

class FuncionesController implements CrudInterface
{
  protected FuncionesModel $fModel;
  protected ServicesGestionModulos $sgM;
  public function __construct()
  {
    $this->fModel = new FuncionesModel();
    $this->sgM = new ServicesGestionModulos();
  }
  // no se le esta dando utilidad de momento.
  public function getData()
  {
    throw new \Exception('Not implemented');
  }
  public function save()
  {
    throw new \Exception('Not implemented');
  }
  public function delete()
  {
    throw new \Exception('Not implemented');
  }
  public function store()
  {
    try {
      header(CONTENT_TYPE);
      $data = UtilsFunctions::returnGetDecode();
      if (empty($data)) throw new Exception("Datos enviados incorrectos", HttpStatus::BAD_REQUEST);
      $file = $data['file'];
      unset($data['file']);

      //obtener el nombre del modulo usando el id, indicamos 0 porque es un array asociativo lo que devuelve y la clave es nombre_modulo.
      $moduleName = $this->sgM->getNameModule($data['id_modulo'])[0]['nombre_modulo'];

      if (empty($moduleName)) throw new Exception("Modulo incorrecto", HttpStatus::BAD_REQUEST);

      // busca el archivo en donde debe de estar la funcion ya creada.
      $classFile = realpath(BASE_URL . "/../Modules/{$moduleName}/Controller/{$file}.php");

      if (!$classFile || !is_file($classFile)) {
        throw new Exception("El archivo del controlador no existe físicamente", HttpStatus::BAD_REQUEST);
      }

      if (!class_exists($file)) throw new Exception("La clase no existe en el sistema", HttpStatus::BAD_REQUEST);

      if (!method_exists($file, $data['nombre_funcion'])) throw new Exception("La funcionalidad no está disponible para su registro", HttpStatus::INTERNAL_SERVER_ERROR);

      // hacemos una instancia previa del metodo, validamos si es publico para poder implementarlo en la base de datos.
      $insanceItem = new ReflectionMethod($file, $data['nombre_funcion']);
      if (!$insanceItem->isPublic()) throw new Exception("Solo se pueden registrar funcionalidades publicas", HttpStatus::BAD_REQUEST);

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
    throw new \Exception('Not implemented');
  }
}
