<?php



class CategoriasModel extends Crud
{
  protected $id = "ca_id";
  protected $table = "categorias";
  protected $campos = [
    'ca_nombre',
    'ca_descripcion',
    'ca_status',
    'created_at',
    'updated_at'
  ];
}
