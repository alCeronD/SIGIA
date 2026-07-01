<?php

//Este archivo se va a encargar de extraer los nombres de los documentos que tenemos en ciertas carpetas para poder traer sus respectivos assets.

class ScanFiles
{

    protected static array $nameAssetsFiles = [];
    protected array $nameFilesCss = [];
    protected $modulo;
    protected $file;

    public function __construct(String $modulo = 'Dashboard')
    {
        $this->modulo = $modulo;
    }

    //Función que sirve para mapear los css o javascript, solo mapea hasta que llegue a la carpeta.
    public static function mapAssets(String $module = 'Dashboard', array $files = [])
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
                self::$nameAssetsFiles[$module] = $fileAssets[$key];
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

    /**
     * Function para renderizar los recursos css y javascript.
     *
     * @param string $modulo - nombre del modulo al cual se requieren los recursos
     * @param array $filesjs - arreglo con los nombres de los archivos js requeridos para ser renderizados con el contenedor
     * @param array $filescss - arreglo con los nombres de los archivos css requeridos para ser renderizados con el contenedor.
     * @return void
     */
    public static function renderJs(String $modulo = '', array $filesjs = [])
    {
        // limpiar primero la variable de session.
        $files = self::mapAssets($modulo);
        $jsFiles = $files['js'][$modulo];

        foreach ($jsFiles as $key => $value) {
            if (in_array("{$key}", $filesjs)) {
                $rutaLimpia = htmlspecialchars("/../../Core/Modules/$modulo/Js/{$key}", ENT_QUOTES, 'UTF-8');
                echo '<script type="module" src="' . $rutaLimpia . '"></script>' . PHP_EOL;
            }
        }
    }

    public static function renderCss(string $modulo = "", array $filesView = [])
    {
        $files = self::mapAssets($modulo);
        // var_dump($files);
        $cssFiles = $files['css'][$modulo] ?? [];
        // $cssFiles = $files['css'] ?? [];
        $cssToLoad = [];

        foreach ($cssFiles as $key => $value) {
            // Buscamos si el archivo de la vista actual está configurado
            if (in_array("{$key}", $filesView)) {
                $rutaLimpia = htmlspecialchars("/Core/Modules/$modulo/Css/{$key}", ENT_QUOTES, 'UTF-8');
                $cssToLoad[] = $rutaLimpia;
            }
        }

        return $cssToLoad; // Retornamos el array con los CSS que sí corresponden
    }
}
