<?php

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
        // dd($url);
        return $url;
    }
    
    function resolve(){
        $modulo = $_GET['modulo'];
        $controlador = $_GET['controlador'];
        $funcion = $_GET['funcion'];
    
        $controllerPath = "app/modules/$modulo/controller/{$controlador}Controller.php";
        // dd($controllerPath);
    
        if (is_dir("app/modules/$modulo")){
            if (is_file($controllerPath)) {
            
                include_once $controllerPath;
                $nombreClase = $controlador . "Controller";
                // dd($modulo);
                
                include_once 'app/config/conn.php'; 
                $conexion = (new Conection())->getConnect();
                $objeto = new $nombreClase($conexion);
                
                if (method_exists($objeto, $funcion)) {
                    $objeto->$funcion();
                } else {
                    echo "La función no existe";
                }
            } else {
                echo "El controlador no existe";
            }
        } else {
            echo "El módulo no existe";
        }
    }
    
    
    ?>  
