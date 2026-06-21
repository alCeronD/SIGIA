<?php

class ServicesModulos
{
  protected ModulosModel $mModel;
  public function __construct()
  {
    $this->mModel = new ModulosModel();
  }

  public function getAllModulos()
  {
    return $this->mModel->select()->from()->prepareSql()->get();
  }
}
