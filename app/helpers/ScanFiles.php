<?php 

//Este archivo se va a encargar de extraer los nombres de los documentos que tenemos en ciertas carpetas para poder traer sus respectivos assets.

class ScanFiles{

    private array $nameAssetsFiles;

    private array $nameFilesCss;

    private $modulo;
    private $file;

    public function __construct(String $modulo = 'dashboard'){
        $this->modulo = $modulo;
    }

    //Función que sirve para mapear los css o javascript, solo mapea hasta que llegue a la carpeta.
    public function mapAssets(String $module = 'dashboard'){

        if (!is_string($module)) {
            return;
        }
        // $moduleNew = ucfirst($module);

        $relativePathAssets = __DIR__ . "/../../public/assets/css/$module";
        $fileAssets = glob($relativePathAssets. '*',GLOB_MARK);
        
        foreach ($fileAssets as $key => $value) {
            $filesAssets = basename($value);

            //var_dump($filesAssets);

            if (!str_contains($filesAssets,'.css')) {

                $this->nameAssetsFiles[$filesAssets] = $fileAssets[$key];
            }

        }

    }

    //Funcion que sirve para crear arreglo en donde puedo guardar los archivos css según su modulo.
    public function addUrl(String $module = 'dashboard'){

        //Se escanea los assets.
        $this->mapAssets($module);
        $htmlHeader = '';

        //Traigame los nombres de los archivos de esa ruta y guardelo en un arreglo.
        $nameFiles = [];

        foreach ($this->nameAssetsFiles as $key => $value) {
            $ruta = glob($value.'*',GLOB_MARK);

            $nameFiles[$key] = array_diff(scandir($value),array('.','..'));

            $nameFiles[$key] = array_combine($nameFiles[$key],$nameFiles[$key]);
        }
        $path = "../public/assets/css/$module/";
        $cssFile = reset($nameFiles[$module]);
        $cssFile = key($nameFiles[$module]);

        return $path . $cssFile;
    }
}



?>