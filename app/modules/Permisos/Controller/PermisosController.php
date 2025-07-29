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
        $idNombreFuncion = $result['data']['id_funcion'];
        return $idNombreFuncion;
    }

    public function validateRolFuncion(int $rolId){
        $result = $this->modelPermisos->getPermisoFuncion($rolId);
        $idPermisoFuncion = $result['data']['id_funcion'];
        return $idPermisoFuncion;
    }

    public function renderMenu(){
        
    }
}
?>