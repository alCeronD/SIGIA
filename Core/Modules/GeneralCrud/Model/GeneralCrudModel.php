<?php
class GeneralCrudModel extends Crud
{
  protected $id = 'gc_id';
  protected $table = 'GeneralCrud';
  protected $typedCasted;
  protected $campos = [
    'gc_nombre',
    'gc_descrip',
    'gc_status'
  ];
  protected $typeCampos = [
    'gc_nombre' => 's',
    'gc_descrip' => 's',
    'gc_status' => 'i'
  ];
  protected $typeId = 'i';
}
