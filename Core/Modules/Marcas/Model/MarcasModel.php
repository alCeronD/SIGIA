<?php

class MarcasModel extends Crud
{
  protected $id = "ma_id";
  protected $table = "marcas";
  protected $campos = [
    'ma_nombre',
    'ma_descripcion',
    'ma_status'
  ];
}
