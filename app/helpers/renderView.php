<?php 

//Este archivo se va a encargar de extraer los nombres de los documentos que tenemos en ciertas carpetas para poder mostrar las vistas.

class RenderView{

    //Función para devolver la vista.
    public static function renderView(String $file){
        $path = __DIR__ . "/../modules/solicitudPrestamos/views/$file";

        if (!$file) {
            return;
        }

        if (file_exists($path)) {
            include_once $path;
        }
    }

    //Función para mapear los documentos, esto lo voy a usar para validar si el documento enviado es igual al que existe.
    private static function mapFiles(){
        $relativePath = __DIR__ . '/../modules/solicitudPrestamos/views/';
        $nameFiles = [];

        $fle = glob($relativePath . '*',GLOB_MARK);

        //var_dump($fle);

        foreach ($fle as $files) {
            $prestamosFiles = basename($files);
            $nameFiles[] = $prestamosFiles;
            //var_dump($nameFiles);
        }
    }

    //Todo: hacer función para matear los helpers.
    public static function mapAssets(String $folder, String $file){

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