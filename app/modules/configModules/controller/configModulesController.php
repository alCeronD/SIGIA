<?php 

// incluyo la clase para usar el renderizado de la vista.
require_once __DIR__ . '/../../../helpers/renderView.php';

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




}


?>