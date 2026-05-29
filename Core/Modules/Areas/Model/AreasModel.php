<?php

class AreasModel extends Crud
{
  protected $id = "ar_cod";
  protected $table = "areas";
  protected $campos = [
    'ar_nombre',
    'ar_descripcion',
    'ar_status'
  ];
}
