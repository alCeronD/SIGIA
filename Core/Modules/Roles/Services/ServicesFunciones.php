<?php

class ServicesFunciones
{
  protected FuncionesModel $fModel;
  protected TipoFuncionModel $tpFunctionModel;

  public function __construct()
  {
    $this->fModel = new FuncionesModel();
    $this->tpFunctionModel = new TipoFuncionModel();
  }

  public function getAllFunctions()
  {
    return $this->fModel->select()->from()->prepareSql()->get();
  }

  /**
   * Consulta para devolver las funcionalidades asociadas al modulo.
   *
   * @return FuncionesModel|array
   */
  public function getAllFunctionsFromModule(array $sql = [], array $condition = [])
  {
    $tpFuncionTableName = "{$this->tpFunctionModel->getTable()} tpf";
    $tpFuncionPrimaryKey = "tpf.{$this->tpFunctionModel->getKeyName()}";
    return $this->fModel->select($sql)
      ->from('funciones f')
      ->innerJoin($tpFuncionTableName, 'f.tp_funcion', '=', $tpFuncionPrimaryKey)
      ->where([$condition[0], $condition[1], $condition[2]])
      ->orderBy('f.id_funcion', true);
  }

  public function getIdFunction(String $function)
  {
    $dataPrepare[CR_DATA] = ['nombre_funcion' => $function];
    return $this->fModel->select(['id_funcion'])
      ->from()
      ->where(['nombre_funcion', '=', $function])
      ->prepareSql($dataPrepare)->get();
  }
}
