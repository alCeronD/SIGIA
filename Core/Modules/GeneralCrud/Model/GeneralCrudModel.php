<?php
class GeneralCrudModel extends Crud
{
  protected $id = 'gc_id';
  protected $table = 'GeneralCrud';
  protected $campos = [
    'gc_nombre',
    'gc_descrip'
  ];
  protected $typeCampos = [
    's',
    's'
  ];
}
