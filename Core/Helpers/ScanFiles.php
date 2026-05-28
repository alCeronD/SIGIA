<?php

//Este archivo se va a encargar de extraer los nombres de los documentos que tenemos en ciertas carpetas para poder traer sus respectivos assets.

use function PHPSTORM_META\type;

class ScanFiles
{

    protected array $nameAssetsFiles = [];
    protected array $nameFilesCss = [];
    protected $modulo;
    protected $file;

    public function __construct(String $modulo = 'Dashboard')
    {
        $this->modulo = $modulo;
    }

    //Función que sirve para mapear los css o javascript, solo mapea hasta que llegue a la carpeta.
    public function mapAssets(String $module = 'Dashboard')
    {

        if (!is_string($module)) return;

        $relativePathAssets = __DIR__ . "/../Modules/{$module}/Css/";
        $fileAssets = glob($relativePathAssets . '*', GLOB_MARK);
        foreach ($fileAssets as $key => $value) {
            $filesAssets = basename($value);


            if (str_contains($filesAssets, '.css')) {
                // guarde las urls en donde la key sea el nombre del modulo.
                $this->nameAssetsFiles[$module] = $fileAssets[$key];
            }
        }
    }

    //Funcion que sirve para crear arreglo en donde puedo guardar los archivos css según su modulo.
    public function addUrl(String $module = 'Dashboard')
    {

        //Se escanea los assets.
        $this->mapAssets($module);

        //Traigame los nombres de los archivos de esa ruta y guardelo en un arreglo.
        $nameFiles = [];
        foreach ($this->nameAssetsFiles as $key => $value) {
            $rutaCarpeta = dirname($value) . '/';
            $ruta = glob($rutaCarpeta . '/', GLOB_MARK);
            $nameFiles[$module] = array_diff(scandir($rutaCarpeta), array('.', '..'));
            $nameFiles[$module] = array_combine($nameFiles[$key], $nameFiles[$key]);
        }

        $path = "Modules/$module/Css/";
        $cssFile = reset($nameFiles[$module]);
        $cssFile = key($nameFiles[$module]);

        return $path . $cssFile;
    }
}
