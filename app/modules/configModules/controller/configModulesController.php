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
    public function getData(String $tableName, String $status){
        
        $model = new ConfigModulesModel();
        //las tablas, las voy a comparar segun el area crear la consulta.
        $statusTables = ['areas','tipo_documento','roles','categorias','marcas'];
        $statusColum ='';
        if (in_array($tableName, $statusTables)) {
            
            switch ($tableName) {
                case 'areas':
                    $statusColum = 'ar_status';
                    break;
                case 'tipo_documento':
                    $statusColum = 'tp_status';
                    break;
                case 'roles':
                    $statusColum = 'rl_status';
                    break;
                case 'categorias':
                    $statusColum = 'ca_status';
                    break;
                case 'marcas':
                    $statusColum = 'ma_status';
                    break;
                default:
                    exit();
            }
        }
        //Traigame todos los registros de la tabla cuando su estado sea activo(1) e inactivo(0).
        $sql ="SELECT * FROM $tableName WHERE $statusColum IN (1,0)";
        
        return $model->select($sql);
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

        //Tipos de datos para preparar la consulta

        //Valores para bind_param
        $prepareValues = [];
        $types=[];
        $sql = "UPDATE `$tableName` SET ";
        $set =[];



        //Recorro los valores para validar su tipo, creo el tipo de valor al cual voy a asignar y guardo los datos en un arreglo específico
        foreach ($keysValues as $keys) {
            $set[] = "`$keys` = ?";
            //extraigo el valor expecífico para validar su tipo
            $val = $values[$keys];
            if (is_int($val)) {
                $types []= 'i';
            }elseif (is_float($val) || is_double($val)){
                $types []= 'd';

            }else{
                $types []='s';
            }

            //Guardo el valor en el arreglo para enviar los datos a actualizar
            $prepareValues[] = $val;

        }

        //Creo un string en donde cada posición del arreglo está separada por una coma (,) para concatenar.
        $set2 = implode(',',$set);
        //concateno el arreglo set con la consulta sql.
        $sql .= $set2;
        
        // //Extraigo el valor de la clave primaria, estaba usando array_keys y values pero me devolvía los valores en arreglos.
        $pkValue = reset($pk);
        $pkRow = key($pk);
        $sql .= " WHERE $pkRow = ?";

        //Agregar la pk.
        $types []="i";

        //Envio la primary key
        $prepareValues [] = $pkValue;


        $model = new ConfigModulesModel();

        //Por ahora devuelve un true.
        /**
         * @var $sql - consulta concatenada
         * @var $prepareValues - los valores para actualizar
         * @var $types - los tipos de datos según $prepareValues.
         */
        $data = $model->update($sql,$prepareValues,$types);


        return $data;
    }

    
    public function deleteRow(){



    }



}


?>