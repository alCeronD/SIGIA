<?php

// modelo de la tabla roles_funciones
class RolesFuncionesModel extends Crud
{
  protected $id = 'rlp_id';
  protected $table = 'roles_funciones';
  protected $campos = [
    'rlp_id_rl', //id de la tabla rol (llave foranea)
    'rlp_id_funcion' //id de la tabla funciones (llave foranea)
  ];
}
