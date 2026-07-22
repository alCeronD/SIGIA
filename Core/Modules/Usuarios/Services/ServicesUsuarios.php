<?php

// clase de servicio para obtener el usuario segun el id.
class ServicesUsuarios
{
  protected UsuariosModel $userModel;
  public function __construct()
  {
    $this->userModel = new UsuariosModel();
  }
  // Function para extraer usuario usando el documento de identidad.
  public function getUserByDocum(int $docum)
  {
    if (!is_int($docum)) return;
    $dataUser[CR_DATA] = [
      'usu_docum' => $docum
    ];
    $userData = $this->userModel->select()->from()->where(['usu_docum', '=', $docum])->prepareSql($dataUser)->get();
    return $userData;
  }

  /**
   * Function para retornar la lista de los usuarios.
   *
   * @param string $filter - filtro dependiendo del criterio requerido por el usuario final.
   * @param string $valueFilter - valor del filtro.
   * @return UsuariosModel
   */
  public function getAllUsers(String $filter = '', String $valueFilter = '')
  {
    $responseUser = null;
    // las columnas de la consulta
    $columns = [
      'u.usu_id as "IdUsuario"',
      'u.usu_docum as "nroDocumento"',
      'u.usu_nombres as "nombreCompleto"',
      'u.usu_apellidos as "apellidos"',
      'u.usu_email as "email"',
      'u.usu_telefono as "telefono"',
      'u.usu_direccion as "direccion"',
      'u.usu_password AS "password"',
      'COALESCE(r.rl_nombre, "No asociado a rol") AS "rl_nombre"', #en caso de que me devuelva null, devolver el texto
      'COALESCE(ur.usr_rl_id, "Sin asignar") AS "rolIdUser"',
      'COALESCE(eu.est_nombre, "Sin estado") AS "estado_usuario"'
    ];
    $query = $this->userModel->select($columns)->from('usuarios u')->leftJoin('usuarios_roles ur', 'u.usu_id', '=', 'ur.usr_usu_id')->leftJoin('roles r', 'ur.usr_rl_id', '=', 'r.rl_id')->leftJoin('estados_usuarios eu', 'u.usu_id_estado', '=', 'eu.est_id');

    if (empty($filter) && empty($valueFilter)) {
      $responseUser = $query->orderBy()->limit()->offset();
    } else {
      $responseUser = $query->where([$filter, 'LIKE', "%$valueFilter%"])->orderBy($filter, true)->limit()->offset();
    }


    return $responseUser;
  }

  /**
   * Function para contar la cantidad de registros que hay dependiendo de los parámetros.
   *
   * @param string $filter
   * @param string $valueFilter
   * @return UsuariosModel
   */
  public function getCount(String $filter = '', String $valueFilter = '')
  {

    if (empty($filter) && empty($valueFilter)) {
      return $this->userModel->count()->from('usuarios u')->leftJoin('usuarios_roles ur', 'u.usu_id', '=', 'ur.usr_usu_id')->leftJoin('roles r', 'ur.usr_rl_id', '=', 'r.rl_id')->leftJoin('estados_usuarios eu', 'u.usu_id_estado', '=', 'eu.est_id')->orderBy();
    } else {
      return $this->userModel->count()->from('usuarios u')->leftJoin('usuarios_roles ur', 'u.usu_id', '=', 'ur.usr_usu_id')->leftJoin('roles r', 'ur.usr_rl_id', '=', 'r.rl_id')->leftJoin('estados_usuarios eu', 'u.usu_id_estado', '=', 'eu.est_id')->where([$filter, 'LIKE', "%$valueFilter%"])->orderBy($filter, true);;
    }
  }
}
