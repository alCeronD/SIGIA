<?php

use ZipStream\Test\Util;

class RolesFuncionesController
{
  protected ServicesRoles $sRoles; // servicio para solicitar la data entre un los controladores roles y roles_funciones.
  protected RolesFuncionesModel $rfModel; // tabla roles_funciones
  protected array $allRoles;
  public function __construct()
  {
    $this->sRoles = new ServicesRoles();
    $this->rfModel = new RolesFuncionesModel();
  }

  /**
   * Vista de las funciones asociadas a los roles.
   *
   * @return void
   */
  public function mostrarFuncionesAssoc()
  {
    return include_once __DIR__ . '../../views/rolesFunciones.php';
  }


  /**
   * funcion para enviar la data al cliente para renderizar en el select.
   *
   * @return void
   */
  public function getRoles()
  {
    header(CONTENT_TYPE);
    $this->allRoles = $this->sRoles->getAllRoles();
    if (count($this->allRoles) > 0) {
      Response::responseRequest(HttpStatus::OK, true, CR_REGISTROS, $this->allRoles);
    }
  }

  /**
   * Funcion para obtener las funciones asociadas al rol seleccionado por el usuario y enviar como respuesta a la peticion
   *
   * @return void
   */
  public function getFuncionesAssocRoles()
  {
    header(CONTENT_TYPE);
    $idRol =  isset($_GET['idRol']) ? (int) $_GET['idRol'] : null;
    $limit =  isset($_GET['limit']) ? (int) $_GET['limit'] : LIMIT;
    $actualPage =  isset($_GET['actualPage']) ? (int) $_GET['actualPage'] : 1;

    if (empty($idRol)) {
      Response::responseRequest(HttpStatus::BAD_REQUEST, false, RL_MESSAGE_NO_ROL, []);
    }

    // columnas para la consulta.
    $columns = [
      "rf.rlp_id AS 'id'",
      "rf.rlp_id_funcion AS 'idFuncion'",
      "f.nombre_funcion AS 'nombreFuncion'",
      "rf.rlp_id_rl AS 'idRol'",
      "r.rl_nombre AS 'nombreRol'",
      "m.cod_nombre_m AS 'moduloAsociado'"
    ];
    // cuando hay alias, poo no lee el punto, asi que si o si debemos de enviar el arreglo en vez de un punto, un guion.
    $dataSelect['data'] = [
      'rf_rlp_id_rl' => $idRol
    ];

    $countData = $this->rfModel->select($columns)->from('roles_funciones rf')->leftJoin('roles r', 'r.rl_id', '=', 'rf.rlp_id_rl')->leftJoin('funciones f', 'rf.rlp_id_funcion', '=', 'f.id_funcion')->leftJoin('modulos m', 'f.id_modulo', '=', 'm.id_m')->where(['rf.rlp_id_rl', '=', $idRol])->prepareSql($dataSelect)->get();

    $paginate = UtilsFunctions::executePaginate(count($countData), $limit, $actualPage);

    $dataSql['data'] = [
      'rf_rlp_id_rl' => $idRol,
      'limit'           => $limit,
      'offset' => (int) $paginate[CR_OFFSET]
    ];

    $resultQuery = $this->rfModel->select($columns)->from('roles_funciones rf')->leftJoin('roles r', 'r.rl_id', '=', 'rf.rlp_id_rl')->leftJoin('funciones f', 'rf.rlp_id_funcion', '=', 'f.id_funcion')->leftJoin('modulos m', 'f.id_modulo', '=', 'm.id_m')->where(['rf.rlp_id_rl', '=', $idRol])->orderBy('rf.rlp_id')->limit()->offset()->prepareSql($dataSql)->get();

    if (count($resultQuery) > 0) {
      $dataQuery = [
        CR_TOTAL_REGISTROS => count($countData),
        CR_PAGINA_ACTUAL => $actualPage,
        CR_CANTIDAD_PAGINAS => $paginate[CR_TOTAL_PAGINAS],
        CR_DATA => $resultQuery
      ];
      Response::responseRequest(HttpStatus::OK, true, CR_REGISTROS, $dataQuery);
    } else {
      $dataQuery = [
        CR_DATA => []
      ];
      Response::responseRequest(HttpStatus::OK, true, RL_MESSAGE_WITHOUT_ROLES, $dataQuery);
    }
  }
}
