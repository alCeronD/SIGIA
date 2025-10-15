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
        $idNombreFuncion = $result['data']['id_funcion'];

                if (empty($result['data']['id_funcion'])) {
            return null;
        }
        return $result['data']['id_funcion'];
    }

    public function validateRolFuncion(int $rolId,int $idFuncion){

        $result = $this->modelPermisos->getPermisoFuncion($rolId, $idFuncion);
        if ($result['data'] == null) {
            return false;
        }else{
            $idPermisoFuncion = $result['data']['id_funcion'];
        }
        return $idPermisoFuncion;
    }
    
}
?>