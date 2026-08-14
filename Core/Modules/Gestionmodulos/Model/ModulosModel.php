<?php

class ModulosModel extends Crud
{
  protected $id = 'id_m';
  protected $table = 'modulos';
  protected $campos = [
    'nombre_modulo', //nombre del modulo
    'icono', // icono representativo del modulo
    'descripcion', // descripcion previa del modulo,
    'status_modulo', //estado del modulo (se usa tinyint (1) true, (0) false),
    'created_at',
    'updated_at'
  ];
}
