<?php

class AreasModel extends Crud
{
  protected $id = "ar_cod";
  protected $table = "departamentos";
  protected $typedCasted;
  protected $campos = [
    'ar_nombre',
    'ar_descripcion',
    'ar_status'
  ];
  protected $typeCampos = [
    'ar_nombre' => 's',
    'ar_descripcion' => 's',
    'ar_status' => 'i'
  ];
  protected $typeId = 'i';
}
