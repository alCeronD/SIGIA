<?php
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once __DIR__ . '/../Const/RolesConst.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;

/**
 * Clase de servicio para conectar con otro controlador, en este caso, para solicitar unos datos.
 */
class ServicesRoles
{
  protected RolesModel $rlModel; //Implementamos el modelo para solicitar al servicio los datos.
  protected FuncionesModel $fModel;

  public function __construct()
  {
    $this->rlModel = new RolesModel();
    $this->fModel = new FuncionesModel();
  }

  public function getAllRoles()
  {
    return $this->rlModel->select()->from()->prepareSql()->get();
  }

  /**
   * Funcion para obtener las funciones ya asociadas al rol.
   *
   * @param integer $rol
   * @return array
   */
  public function getSetRolesFunciones(int $rol = 1)
  {
    $dataRol = ['ro_rl_id' => $rol];
    $dataSelect[CR_DATA] = $dataRol;
    $columns = [
      'fu.id_funcion as "idFuncion"',
      'fu.nombre_funcion as "nombreFunción"',
    ];
    return $this->fModel->select($columns)->from('funciones fu')->innerJoin('roles_funciones rf', 'fu.id_funcion', '=', 'rf.rlp_id_funcion')->innerJoin('roles ro', 'ro.rl_id', '=', 'rf.rlp_id_rl')->innerJoin('modulos mo', 'mo.id_m', '=', 'fu.id_modulo')->where(['ro.rl_id', '=', $rol])->prepareSql($dataSelect)->get();
  }
}
