<?php 

//Este archivo se va a encargar de extraer los nombres de los documentos que tenemos en ciertas carpetas para poder mostrar las vistas.

class RenderView{

    private $urlRender;

    private $module;
    private $file;

    public function __construct(String $module = '', String $file = ''){
        $this->module = $module;
        $this->file = $file;

    }

    //Función para devolver la ruta.
    public function renderView(String $modules, String $file){
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

        //var_dump($fle);

        foreach ($fle as $files) {
            $prestamosFiles = basename($files);
            $nameFiles[] = $prestamosFiles;
        }
    }

    //Todo: hacer función para matear los helpers.
    private static function mapAssets(String $folder, String $file){

        //Ruta relativa para renderizar los assets de javascript.
        //$relativePathAssets = __DIR__ ."/../public/assets/$folder/$file";
        $relativePathAssets = $_SERVER['DOCUMENT_ROOT'] . "/proyecto_sigia/public/assets/$folder/";

        $fileAssets = glob($relativePathAssets. '*',GLOB_MARK);

        var_dump($fileAssets);

        //var_dump($fileAssets);
        foreach ($fileAssets as $key => $value) {
            $filesAssets = basename($value);
            $nameAssetsFiles [] = $fileAssets[$key];
            var_dump($nameAssetsFiles);
        }
    }
}


?>