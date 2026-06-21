<?php

class FuncionesModel extends Crud
{
  protected $id = 'id_funcion';
  protected $table = 'funciones';
  protected $campos = [
    'nombre_funcion', //Nombre de la funcion que esta en el controlador.
    'nombre_funcion_user', //Nombre de la funcion para el usuario final.
    'id_modulo', // id del modulo al que pertenece la funcion
    'tp_funcion' // tipo de la funcion
  ];
}
