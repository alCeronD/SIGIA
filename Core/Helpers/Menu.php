<?php
class Menu
{
  protected UsuariosModel $usuModel;
  protected UsuariosRolesModel $usuRolModel;
  protected RolesModel $rModel;
  protected ModulosModel $mModel;
  protected RolesFuncionesModel $rfModel;
  protected TipoFuncionModel $tpModel;
  protected FuncionesModel $fModel;

  public function __construct()
  {
    $this->usuModel = new UsuariosModel();
    $this->usuRolModel = new UsuariosRolesModel();
    $this->rModel = new RolesModel();
    $this->mModel = new ModulosModel();
    $this->rfModel = new RolesFuncionesModel();
    $this->tpModel = new TipoFuncionModel();
    $this->fModel = new FuncionesModel();
  }
  /**
   * Funcionalidad para crear el menu del usuario basado en sus permisos
   *
   * @param array $rolIsActive - array con todos los datos del rol del usuario.
   * @return array
   */
  public function createMenu(array $rolIsActive = [])
  {
    try {
      if ($rolIsActive['rl_status'] === 1 && !empty($usu)) {
        session_start();
        session_regenerate_id(true);
      }
      $rolId = $rolIsActive['rl_id'];
      # Paso 5.1 - Traer los modulos asociados basados en el rol del usuario.
      $sqlMmodel = [
        "DISTINCT mo.id_m AS 'idModulo'",
        "mo.nombre_modulo AS 'nombreModulo'",
        "mo.icono AS 'iconModulo'"
      ];

      $sqlMModelPrepare[CR_DATA] = [
        'r_rl_id' => $rolId
      ];

      $modulesFromUser = $this->mModel->select($sqlMmodel)
        ->from("{$this->mModel->getTable()} mo")
        ->innerJoin("{$this->fModel->getTable()} fu", "fu.id_modulo", "=", "mo.id_m")
        ->innerJoin("{$this->rfModel->getTable()} rof", "rof.rlp_id_funcion", "=", "fu.id_funcion")
        ->innerJoin("{$this->rModel->getTable()} r", "r.rl_id", "=", "rof.rlp_id_rl")
        ->where(["r.rl_id", '=', $rolId])->prepareSql($sqlMModelPrepare)->get();

      // ids de los modulos que el usuario esta asociado
      $idsModulosFromUser = array_column($modulesFromUser, 'idModulo');

      # Paso 5.2 - traer los modulos que esten asociados al usuario
      $sqlFModel = [
        " DISTINCT fu.nombre_funcion_user AS 'nombreFuncionUser'",
        "fu.id_funcion AS 'idFunción'",
        "fu.nombre_funcion AS 'nombreFuncionController'",
        "fu.is_main_view AS 'isMainView'",
        "fu.nameController AS 'controlador'",
        "mo.nombre_modulo AS 'nombreModulo'",
        "mo.icono AS 'icono'"
      ];

      $rolId = $rolIsActive['rl_id'];

      $referencesIn = [];

      $modAssocPrepare[CR_DATA] = [
        'tpf_id_tp_funcion' => 1,
        'r_rl_id' => $rolId,
      ];
      foreach ($idsModulosFromUser as $key => $idM) {
        $reference = "mo_id_m{$key}";
        $modAssocPrepare[CR_DATA][$reference] = $idM;
        // $referencesIn[] = $reference;
        $referencesIn[$reference] = $idM;
      }

      # Consulta del paso 5.2
      $modAssoc = $this->fModel->select($sqlFModel)
        ->from("{$this->fModel->getTable()} fu")
        ->innerJoin("{$this->tpModel->getTable()} tpf", "tpf.{$this->tpModel->getKeyName()}", '=', "fu.tp_funcion")
        ->innerJoin("{$this->rfModel->getTable()} rof", "rof.rlp_id_funcion", "=", "fu.{$this->fModel->getKeyName()}")
        ->innerJoin("{$this->rModel->getTable()} r", "r.{$this->rModel->getKeyName()}", "=", "rof.rlp_id_rl")
        ->innerJoin("{$this->mModel->getTable()} mo", "mo.{$this->mModel->getKeyName()}", "=", "fu.id_modulo")
        ->where(['tpf.id_tp_funcion', '=', 1])
        ->where(['r.rl_id', '=', $rolIsActive['rl_id']])
        ->whereIn($referencesIn, 'mo.id_m')
        ->prepareSql($modAssocPrepare)
        ->get(); //en el parametro IN LE ENVIAMOS EL ARREGLO CON LAS REFERENCIAS, es decir, mo_id_m1, mo_id_m2, mo_id_mN;


      // AHORA QUE YA TENEMOS TODO, NECESITAMOS CREAR LAS VISTAS.
      # 5.3 - Crear las vistas
      $nameModules = array_column($modulesFromUser, 'nombreModulo');

      $menu = [];
      foreach ($modAssoc as $key => $value) {

        $nombreModulo = $value['nombreModulo'] ?? null;
        $controlador = $value['controlador'] ?? null;
        $funcion = $value['nombreFuncionController'] ?? null;
        $icono = $value['icono'] ?? null;
        $nombreFuncionUser = $value['nombreFuncionUser'] ?? null;

        $isMainView = $value['isMainView'] ?? null;

        if ($isMainView === 1) {
          $menu[$nombreModulo]['mainView'] = [
            'labelFuncion' => $nombreFuncionUser,
            'titleModule' => $nombreModulo,
            'url' => Router::createRoute($nombreModulo, $controlador, $funcion, false, CR_DASHBOARD_LOWER_CASE),
            'icono' => $icono
          ];
        }

        if ($isMainView === 0) {
          $menu[$nombreModulo]['secondView'][] = [
            'titleModule' => $nombreModulo,
            'nombreModulo' => $nombreModulo,
            'labelFuncion' => $nombreFuncionUser,
            'url' => Router::createRoute($nombreModulo, $controlador, $funcion, false, CR_DASHBOARD_LOWER_CASE),
            'icono' => $icono
          ];
        }
      }

      $menuMainView = [];
      $menuSecondView = [];
      // extraemos las vistas del menu dependiendo de si son primarias o secundarias.
      foreach ($menu as $key => $views) {

        if (array_key_exists('mainView', $views)) {
          $menuMainView[] = $views['mainView'];
        }


        if (array_key_exists('secondView', $views)) {
          array_push($menuSecondView, ...$views['secondView']);
        }
      }

      // var_dump($menuMainView);
      // var_dump($menuSecondView);
      // die();

      return [
        'status' => true,
        'menuMainView' => $menuMainView,
        'menuSecondView' => $menuSecondView
      ];
    } catch (Exception $th) {
      //throw $th;
    }
  }
}
