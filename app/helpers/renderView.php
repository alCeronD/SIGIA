<?php 

//Este archivo se va a encargar de extraer los nombres de los documentos que tenemos en ciertas carpetas para poder mostrar las vistas.

class ScanFiles{

    private array $filesCss;

    private array $nameAssetsFiles;

    private $urlRender;

    private $module;
    private $file;

    public function __construct(String $module = '', String $file = ''){
        $this->module = $module;
        $this->file = $file;

    }

    //Función para devolver la ruta.
    public function returnPath(String $modules, String $file){
    self::mapFiles($modules);

    if ($modules == 'configModules' && $file == 'areaView.php') {
        $path = __DIR__ . "/../modules/$modules/areas/views/$file";
        return $path;
    }

    if ($modules == 'configModules' && $file == 'tpDocumentoView.php') {
        $path = __DIR__ . "/../modules/$modules/tipoDocumento/views/$file";
        return $path;
    }
    $path = __DIR__ . "/../modules/$modules/views/$file";
    if (!$file) {
        return null;
    }

    if (file_exists($path)) {
        return $path;
    }

    return null;
}

    //Función para mapear las vistas de todos los modulos
    private static function mapFiles(String $modules){
        $relativePath = __DIR__ . "/../modules/$modules/views/";
        $nameFiles = [];

        $fle = glob($relativePath . '*',GLOB_MARK);

        foreach ($fle as $files) {
            $prestamosFiles = basename($files);
            $nameFiles[] = $prestamosFiles;
        }
    }

    //Función que sirve para mapear los css o javascript, solo mapea hasta que llegue a la carpeta.
    public function mapAssets(String $folder){

        //Ruta relativa para renderizar los assets de javascript.
        //$relativePathAssets = __DIR__ ."/../public/assets/$folder/$file";
        $relativePathAssets = __DIR__ . "/../../public/assets/$folder/";

        
        $fileAssets = glob($relativePathAssets. '*',GLOB_MARK);
        /**
         * me devuelve esto: C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/solicitudPrestamos\
         * 
         * array(8) {
         *   [0]=>
         *   string(80) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/configModules\"
         *   [1]=>
         *   string(76) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/elementos\"
         *   [2]=>
         *   string(77) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/inventario\"
         *   [3]=>
         *   string(76) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/libraries\"
         *   [4]=>
         *   string(73) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/main.js"
         *   [5]=>
         *   string(76) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/prestamos\"
         *   [6]=>
         *   string(85) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/solicitudPrestamos\"
         *   [7]=>
         *   string(75) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/usuarios\"
         * }
         */
        //var_dump($fileAssets);

        foreach ($fileAssets as $key => $value) {
            $filesAssets = basename($value);

            /**
             * me muestra:
             * 
             * string(13) "configModules"
             * string(9) "elementos"
             * string(10) "inventario"
             * string(9) "libraries"
             * string(7) "main.js"
             * string(9) "prestamos"
             * string(18) "solicitudPrestamos"
             * string(8) "usuarios"
             */
            //var_dump($filesAssets);
            
            /**
             * me muestra: 
             * string(80) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/configModules\"
             *  string(76) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/elementos\"
             *  string(77) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/inventario\"
             *  string(76) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/libraries\"
             *  string(73) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/main.js"
             *  string(76) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/prestamos\"
             *  string(85) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/solicitudPrestamos\"
             *  string(75) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/js/usuarios\"
             * 
             */
            //var_dump($value);

            /**
             * me muestra:
             * 
             * int(0)
             * int(1)
             * int(2)
             * int(3)
             * int(4)
             * int(5)
             * int(6)
             * int(7)
             */

             //var_dump($key);


            //Como tengo las carpetas y a su ves en la carpeta raiz tiene un main.js, lo cual es un archivo, valido con esta condicional si el último registro tiene un .js, si lo tiene, no lo agregue al arreglo.
            if (!str_contains($filesAssets,'.css')) {
                /**
                 * me devuelve:
                 * ["usuarios"]=> string(76) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/css/usuarios\"
                 */
                $this->nameAssetsFiles[$filesAssets] = $fileAssets[$key];
            }
            /**
             * me guarda las rutas y las guarda en el arreglo.
             */
        }
        //var_dump($this->nameAssetsFiles);

        self::addUrl();
    }

    public function addUrl(){
        $htmlHeader = '';

        //Traigame los nombres de los archivos de esa ruta y guardelo en un arreglo.
        $nameFiles = [];

        foreach ($this->nameAssetsFiles as $key => $value) {
            // $relativePathAssets. '*'
            //var_dump($value);
            $ruta = glob($value.'*',GLOB_MARK);
            
            // $data[$key] = basename(implode(',',$ruta));
            $nameFiles[$key] = array_diff(scandir($value),array('.','..'));

            //["prestamos"]=> string(77) "C:\xampp\htdocs\proyecto_sigia\app\helpers/../../public/assets/css/prestamos\"
            // var_dump($this->nameAssetsFiles);
            //$url[$key] = $data;
        }
        var_dump($nameFiles);

        //var_dump($url);
        
    } 
}

$object = new ScanFiles('','');

$object->mapAssets('css');
?>