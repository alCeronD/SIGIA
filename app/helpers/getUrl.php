<?php



    // $_SESSION['value'] = (int) 0;

    function redirect($url){
        echo "<script type='text/javascript'>"
        ."window.location.href='$url'"
        ."</script>";
    }

    function dd($var){
        echo "<pre>";
        print_r($var);
        die();
    }
    

    function getUrl (String $modulo, String $controlador, String $funcion, $parametros=false,$pagina=false){
        
        //Colocar validaciones a los tipo de datos
        if(!is_string($modulo)){
        
            return;
        }
    
        if ($pagina==false) {
            $pagina="index";
        }

        $url="$pagina.php?modulo=$modulo&controlador=$controlador&funcion=$funcion";
        if ($parametros) {
            foreach ($parametros as $key => $value){
                $url.="&$key=$value";
            }
        }
        return $url;
    }
    
    function resolve($modulo = 'dashboard', $controlador = 'dashboard',$funcion = 'dashboard'){

        if (isset($_GET['modulo'])) {
            $modulo = $_GET['modulo'];
            $controlador = $_GET['controlador'];
            $funcion = $_GET['funcion'];
        }

        // dd("$modulo $controlador $funcion");

        $controllerPath = __DIR__ . "/../modules/$modulo/controller/{$controlador}Controller.php";
        if (is_dir(__DIR__ . "/../modules/$modulo")){
            if (is_file($controllerPath)) {
            
                include_once $controllerPath;
                $nombreClase = $controlador . "Controller";
                
                include_once __DIR__. '/../config/conn.php'; 
                $conexion = (new Conection())->getConnect();
                $objeto = new $nombreClase($conexion);

                if (method_exists($objeto, $funcion)) {

                    $objeto->$funcion();
                } else {
                    echo "La función no existe";
                }
            } else {
                throw new Exception("El controlador $controllerPath no existe.");
            }
        } else {
            echo "El módulo no existe";
        }
    }
    
    function ajaxGeneral(){
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }


    
    
    ?>