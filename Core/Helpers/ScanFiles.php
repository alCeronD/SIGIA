<?php

//Este archivo se va a encargar de extraer los nombres de los documentos que tenemos en ciertas carpetas para poder traer sus respectivos assets.

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

        $relativePathAssetsCss = __DIR__ . "/../Modules/{$module}/Css/";
        $relativePathAssetsJs = __DIR__ . "/../Modules/{$module}/Js/";
        $allFileAssets['css'] = glob($relativePathAssetsCss . "*", GLOB_MARK);
        $allFileAssets['js'] = glob($relativePathAssetsJs . "*", GLOB_MARK);
        $fileAssets = glob($relativePathAssetsCss . '*', GLOB_MARK);
        foreach ($fileAssets as $key => $value) {
            $filesAssets = basename($value);
            if (str_contains($filesAssets, '.css')) {
                // guarde las urls en donde la key sea el nombre del modulo.
                $this->nameAssetsFiles[$module] = $fileAssets[$key];
            }
        }

        $pruebaAssetsFiles = [];
        // se cicla por la primera unidad que es la clave css y la clave js
        foreach ($allFileAssets as $key => $value) {
            // se ciclan las rutas.
            foreach ($value as $key2 => $value2) {
                $fileAssets = basename($value2);
                $finalRoute = strstr($value2, 'Modules/');

                if (str_contains($fileAssets, '.css')) {
                    $pruebaAssetsFiles['css'][$module][$fileAssets] = $finalRoute;
                }
                // Solo importamos el archivo que contenga el nombredelmodulo.js
                if (str_contains($fileAssets, '.js') && str_contains($fileAssets, $module)) {
                    $pruebaAssetsFiles['js'][$module][$fileAssets] = $finalRoute;
                }
            }
        }

        return $pruebaAssetsFiles;
    }
}
