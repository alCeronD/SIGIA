<?php 
require_once __DIR__ ."/../Model/PermisosModel.php";
Class PermisosController{
    private $modelPermisos;

    public function __construct() {
        $this->modelPermisos = new PermisosModel();

    }

    public function gidIdModulo(String $nameModule = ""){
        $result = $this->modelPermisos->getModuleName($nameModule);
        $idNombreModulo = $result['data']['id_m'];
        return $idNombreModulo = empty($idNombreModulo) ? null : (int) $idNombreModulo;
    }


    public function getIdFuncion(String $functionName = "", String $modelName = "", Int $idModulo = 0){
        $result = $this->modelPermisos->getIdFuncion($functionName, $modelName, $idModulo);
        // dd($result);
        $idNombreFuncion = $result['data']['id_funcion'];
        // var_dump($idNombreFuncion);
        return $idNombreFuncion;
    }

    public function validateRolFuncion(int $rolId,int $idFuncion){
        // var_dump($rolId);
        // var_dump($idFuncion);
        $result = $this->modelPermisos->getPermisoFuncion($rolId, $idFuncion);
        if ($result['data'] == null) {
            return false;
        }else{
            $idPermisoFuncion = $result['data']['id_funcion'];
        }
        return $idPermisoFuncion;
    }

    public function renderMenu(){

    }
}
?>