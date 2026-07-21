<?php
class GeneralCrudModel extends Crud
{
  protected $id = 'gc_id';
  protected $table = 'GeneralCrud';
  protected $campos = [
    'gc_nombre',
    'gc_descrip',
    'gc_status',
    'created_at',
    'updated_at'
  ];
}
