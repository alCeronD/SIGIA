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
        return $idNombreModulo;
    }

    public function getIdFuncion(String $functionName = "", String $modelName = "", Int $idModulo = 0){
        $result = $this->modelPermisos->getIdFuncion($functionName, $modelName, $idModulo);
        // dd($result);
        $idNombreFuncion = $result['data']['id_funcion'];
        return $idNombreFuncion;
    }

    public function validateRolFuncion(int $rolId,int $idFuncion){
        $result = $this->modelPermisos->getPermisoFuncion($rolId, $idFuncion);
        // dd($result);
        $idPermisoFuncion = $result['data']['id_funcion'];
        return !empty($idPermisoFuncion);
    }

    public function renderMenu(){

    }
}
?>