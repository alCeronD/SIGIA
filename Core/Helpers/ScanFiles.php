<?php

//Este archivo se va a encargar de extraer los nombres de los documentos que tenemos en ciertas carpetas para poder traer sus respectivos assets.

class ScanFiles
{

    protected static array $nameAssetsFiles = [];
    protected String $modulo;
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

        // rutas css con todos los elementos css del modulo que se recibe por parametro.
        $fileAssetsCss = glob($relativePathAssetsCss . '*', GLOB_MARK);

        // rutas js con todos los elementos del modulo.
        $fileAssetsJS = glob($relativePathAssetsJs . "*", GLOB_MARK);

        foreach ($fileAssetsCss as $key => $value) {

            // validamos si el valor escaneado es un director, en caso de ser asi, volvemos a re escanear los datos
            if (is_dir($value)) {
                # extraemos el nombre del folder
                $subFolder = basename($value);
                // EXTRAMOS todos los archivos que se encuentren dentro del sub folder
                $routesSubFiles = glob($value . "*", GLOB_MARK);
                $subFiles = array_map(fn($fn) => basename($fn), $routesSubFiles);
                self::$nameAssetsFiles['css'][$module]['folder'] = $routesSubFiles;
            } else {
                $filesAssets = basename($value);
                if (str_contains($filesAssets, '.css')) {
                    // guarde las urls en donde la key sea el nombre del modulo.
                    // self::$nameAssetsFiles['css'][$module][] = $fileAssetsCss[$key];
                    self::$nameAssetsFiles['css'][$module][] = $filesAssets;
                }
            }

            // $filesAssets = basename($value);
            // if (str_contains($filesAssets, '.css')) {
            //     // guarde las urls en donde la key sea el nombre del modulo.
            //     self::$nameAssetsFiles[$module] = $fileAssetsCss[$key];
            // }
        }

        // var_dump(self::$nameAssetsFiles);

        foreach ($fileAssetsJS as $key => $value) {

            $fileAssets =  basename($value); #extraigo el ultimo nombre del archivo de la ruta en caso de que el string termine con un slash dejamos la ruta.
            $finalRoute = strstr($value, 'Modules/');

            if (str_contains($fileAssets, '.js')) {
                // $pruebaAssetsFiles['js'][$module][$fileAssets] = $finalRoute;
                // self::$nameAssetsFiles['js'][$module][$fileAssets] = $finalRoute;
                self::$nameAssetsFiles['js'][$module][$fileAssets] = $fileAssetsJS[$key];
            }
        }


        return self::$nameAssetsFiles;

        $pruebaAssetsFiles = [];
        // se cicla por la primera unidad que es la clave css y la clave js
        // foreach ($allFileAssets as $key => $value) {

        //     // se ciclan las rutas.
        //     foreach ($value as $key2 => $value2) {

        //         // $fileAssets = basename($value2); #extraigo el ultimo nombre del archivo de la ruta
        //         $fileAssets =  basename($value2); #extraigo el ultimo nombre del archivo de la ruta en caso de que el string termine con un slash dejamos la ruta.
        //         $finalRoute = strstr($value2, 'Modules/');


        //         // en caso de que sean directorios
        //         if (is_dir($value2)) {
        //             $subFiles = glob($value2 . "*", GLOB_MARK);
        //             // var_dump($value2);
        //             var_dump($subFiles);
        //         } else {

        //             // archivos directos en la carpeta css o js de cada modulo
        //             if (str_contains($fileAssets, '.css')) {
        //                 $pruebaAssetsFiles['css'][$module][$fileAssets] = $finalRoute;
        //             }
        //             // Solo importamos el archivo que contenga el nombredelmodulo.js
        //             if (str_contains($fileAssets, '.js')) {
        //                 $pruebaAssetsFiles['js'][$module][$fileAssets] = $finalRoute;
        //             }
        //         }
        //     }
        // }


    }

    /**
     * Function para renderizar los recursos css y javascript.
     *
     * @param string $modulo - nombre del modulo al cual se requieren los recursos
     * @param array $filesjs - arreglo con los nombres de los archivos js requeridos para ser renderizados con el contenedor
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

    /**
     * Function para renderizar el css
     *
     * @param string $modulo - nombre del modulo
     * @param array $filesController - listado de archivos que se establecieron en el controlador del modulo
     * @return array
     */
    public static function renderCss(string $modulo = "", array $filesController = [])
    {

        $files = self::mapAssets($modulo);
        $cssFiles = $files['css'][$modulo] ?? [];
        $cssToLoad = [];
        foreach ($cssFiles as $key => $value) {
            if (!is_array($value)) {
                // Buscamos si el archivo de la vista actual está configurado
                if (in_array("{$value}", $filesController)) {
                    $rutaLimpia = htmlspecialchars("/Core/Modules/$modulo/Css/{$value}", ENT_QUOTES, 'UTF-8');
                    $cssToLoad[] = $rutaLimpia;
                }
            } else {
                // validamos que los archivos del controlador tengan las claves "folder" y "files"
                $folder = array_key_exists('folder', $filesController) ? $filesController['folder'] : [];
                // hacemos que pase al siguiente elemento del arreglo, esto sin dañar los renderizados adicionales.
                if (empty($folder)) continue;
                $nameFolder = $folder['nameFolder'];
                $files = $folder['files'];
                foreach ($files as $valueFiles) {
                    $rutaLimpia = htmlspecialchars("/Core/Modules/$modulo/Css/{$nameFolder}/{$valueFiles}", ENT_QUOTES, 'UTF-8');
                    $cssToLoad[] = $rutaLimpia;
                }
            }
        }
        return $cssToLoad;
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


// ScanFiles::mapAssets('Elementos');
