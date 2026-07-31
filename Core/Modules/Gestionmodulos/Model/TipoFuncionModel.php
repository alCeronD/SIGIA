<?php

class TipoFuncionModel extends Crud
{
  protected $id = 'id_tp_funcion';
  protected $table = 'tipo_funcion';
  protected $campos = [
    'nombre_tp_funcion', //nombre del del tipo de la funcion (funcion visual o logica)
    'desc_tp_funcion', // campo descripcion.
  ];
}
