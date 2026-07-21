<?php
// tabla usuarios_roles
class UsuariosRolesModel extends Crud
{

  protected $id = "usr_id";
  protected $table = "usuarios_roles";
  protected $campos = [
    'usr_usu_id',
    'usr_rl_id',
    'created_at',
    'updated_at'
  ];
}
