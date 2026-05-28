<?php

require_once __DIR__ . '/../../..' . CR_ROUTE_CONST;
require_once __DIR__ . '/../Const/AreasConst.php';
require_once BASE_URL . '/Autoload.php';

class AreasController
{
  protected AreasModel $AModel;

  public function __construct()
  {
    $this->AModel = new AreasModel;
  }

  public function renderViewArea()
  {
    return include_once __DIR__ . URL_MAIN_VIEW;
  }
  public function insert() {}
}
