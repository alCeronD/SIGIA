<?php 

// incluyo la clase para usar el renderizado de la vista.
require_once __DIR__ . '/../../../helpers/renderView.php';
require_once __DIR__ . '/../model/configModulesModel.php';
//Si su valor es diferente de false
// if (strpos($_SERVER['HTTP_ACCEPT'],'application/json') !== false) {


// }

class ConfigModulesController{
    private $configModel;

    private $render;

    private $module;
    private $file;

    public function __construct(String $module, String $file){

        $this->module = $module;
        $this->file= $file;
        $this->render = new RenderView($module,$file);
    }

    //Me devuelve la ruta de la vista.
    public function render(){
        return $this->render->renderView($this->module, $this->file);
    }

    //Función para traer la información de la tabla en específico.
    public function getData(String $tableName){
        $model = new ConfigModulesModel();
        return $model->select($tableName);
    }

    //Función para actualizar la información de un registro en base a la tabla.
    public function updateRow(){

        return null;
    }

    //Función para Inhabilitar la información de un registro en base a la tabla.


}


?>