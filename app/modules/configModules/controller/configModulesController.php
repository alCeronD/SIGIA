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
    public function updateRow(array $data=[]){
        //Ciclar la consulta.
        $values = $data['values'];
        //Este es un arreglo en donde tiene las llaves del arreglo de los valores.
        $keysValues = array_keys($values);
        //arreglo en donde están las llaves.
        $pk = $data['pk'];
        //Acceso específicamente al nombre de la tabla.
        $tableName = $data['tableName'][0];

        $sql = "UPDATE `$tableName` SET ";
        $set =[];


        //Separo los valores por un guión y lo guardo en un arreglo.
        foreach ($keysValues as $keys) {
            $val= addslashes($values[$keys]);
            // var_dump($val);
            $set [] = "`$keys` = '".addslashes($val)."'";
        }

        //concateno el arreglo set con la consulta sql.
        $sql .= implode(',',$set);
        //Extraigo el valor de la clave primaria, estaba usando array_keys y values pero me devolvía los valores en arreglos.
        $pkValue = reset($pk);
        $pkRow = key($pk);
        $sql .= " WHERE $pkRow = $pkValue";

        var_dump($sql);


        //var_dump($pk);
        //Concateno el where.
        //$sql .= "WHERE $pk = $pk";

        


        //concateno la primary key para validar la consulta.
        //var_dump($data);        
        
        // $sql .= " WHERE $pk = $pk";
        // var_dump($sql);






        //var_dump($values);
        //var_dump($pk);



        return null;
    }

    //Función para Inhabilitar la información de un registro en base a la tabla.


}


?>