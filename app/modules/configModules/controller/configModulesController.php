<?php 

// incluyo la clase para usar el renderizado de la vista.
require_once __DIR__ . '/../model/configModulesModel.php';
require_once __DIR__ . '/../../../helpers/renderView.php';


class ConfigModulesController{
    private $configModel;

    private $render;

    private $module;
    private $file;

    public function __construct(){

        // $this->module = $module;
        // $this->file= $file;
        // $this->render = new RenderView($module,$file);
    
    }

    //Me devuelve la ruta de la vista del modulo de areas.
    public function renderViewArea(){
        //return $this->render->renderView($this->module, $this->file);
        return include_once  __DIR__ . '/../areas/views/areaView.php';
    }
    public function renderViewTp(){

        //return $this->render->renderView($this->module, $this->file);
        return include_once  __DIR__ . '/../tipoDocumento/views/tpDocumentoView.php';
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

    //Creo la consulta sql.
    public function deleteRow(array $data=[]){

        //Validar en el arreglo si el dato enviado corresponde a uno de los status.
        $status = [0, 1];
        //validar si está definido.
        if (!$data) {
            exit();
        }
        //Captura nombre de la tabla.
        $tableName = (String) $data['tableName'];

        //TODO: Esto debe ser dinámico
        //Me captura el valor del estatus
        if ($tableName == 'tipo_documento') {
            $valueColumn = (int) $data['values']['tp_status'];
        }
        
        if ($tableName == 'areas') {
            $valueColumn = (int) $data['values']['ar_status'];
        }

        //Capturar nombre de la columa
        $nameColum = (String) key($data['values']);
        //Capturar el nombre de la columna de la primary key
        $pkColumName = (String) $data['pk']['column'];
        //Capturar el valor de la primary key.
        $pkValue = (int) $data['pk']['value'];

        if ($valueColumn == 1) {
            $valueColumn = 0;
        }elseif ($valueColumn == 0) {
            $valueColumn = 1;
        }

        $sql = "UPDATE `$tableName` SET `$nameColum` = ? WHERE `$pkColumName` = ?";
        $types = "ii"; // ambos son enteros
        $values = [$valueColumn, $pkValue];
        $model = new ConfigModulesModel();
        $dataResult = $model->delete($sql, $types, $values);
        return $dataResult;
    }

    public function addRow(array $data=[]){

        if (!$data) {
            exit();
        }


        //Nombres de las columnas de la tabla
        $keysValues = array_keys($data['values']);



        //var_dump($keysValues);
        //var_dump($values);
        //Nombre de la columna de la primary key
        //$pkNameColum = $data['pkNameColum'];

        //Nombre de la columna del status segun la tabla.

        //Extraigo el nombre de la tabla, en este caso me interesa el el value de tableName, no su clave
        $tableName = $data['tableName'];
        //Los valores de las filas con sus respectivas columnas.
        $dataValues = $data['values'];
        //Valores de las columnas 

        $val = array_values($dataValues);

        $sql = "INSERT INTO `$tableName` SET ";
        //Estructura SET de consulta.
        $set = [];
        //Tipos de valores.
        foreach ($dataValues as $keys => $values) {
            $set[] = "`$keys` = ?";
        }

        
        
        //Agrego el statusName Como ultimo registro.
        //$set[] = "`$statatusNameColum` = ?";
        
        //Tipos de datos
        $types = "ssi";
        
        $set2 = implode(', ',$set);
        
        $sql .= $set2;
        
        $model = new ConfigModulesModel();
        //$data = $model->insert($sql,$types,$values, $tableName, );
        $data = $model->insert($sql,$types,$val);

        return $data;

    
    }
}


?>