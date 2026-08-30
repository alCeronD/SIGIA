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
                // var_dump($fileAssets);

                if (str_contains($fileAssets, '.css')) {
                    $pruebaAssetsFiles['css'][$module][$fileAssets] = $finalRoute;
                }
                // Solo importamos el archivo que contenga el nombredelmodulo.js
                if (str_contains($fileAssets, '.js')) {
                    // var_dump($fileAssets);
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
        $files = self::mapAssets($modulo);
        // si no encuentra archivos basados en el modulo, retornar, significa que no estan creados
        $jsFiles = $files['js'][$modulo] ?? []; #Devolver arreglo vacio en caso de que no haya una clave llamada js

        // no renderizar si no hay archivos javascript para la vista.
        if (empty($jsFiles)) return;

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

    //Funcion para buscar en los directorios del core los controladores pertenecientes a un modulo


    public static function getControllers(String $modulo = '')
    {
        try {
            $ruta = realpath(BASE_PATH . "/../Modules/{$modulo}/Controller/");

            // se valida si la ruta existe, en caso de que no exista, capturar exception
            if (!$ruta) {
                throw new Exception("El módulo especificado no existe o la ruta es inválida.", HttpStatus::BAD_REQUEST);
            }

            $controllers = array_diff(scandir($ruta), array('.', '..'));
            if (empty($controllers)) {
                throw new Exception("No hay controladores disponibles", HttpStatus::BAD_REQUEST);
            }


            // asignamos la misma value a la key, porque la necesitamos para validar su dato.
            $controlls = [];
            foreach ($controllers as $key => $value) {
                $valueParse =  str_replace(".php", "", $value);
                $controlls[$valueParse] = $valueParse;
            }
            // return $controlls;
            return ['status' => true, 'controlls' => $controlls];
        } catch (\Exception $th) {
            // throw $th;
            return ['status' => false, 'throw' => $th];
        }
    }
}
