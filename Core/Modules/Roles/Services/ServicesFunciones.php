<?php

class ServicesFunciones
{
  protected FuncionesModel $fModel;

  public function __construct()
  {
    $this->fModel = new FuncionesModel();
  }

  public function getAllFunctions()
  {
    return $this->fModel->select()->from()->prepareSql()->get();
  }
}
