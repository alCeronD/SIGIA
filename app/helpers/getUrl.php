<?php
function redirect($url)
{
    echo "<script type='text/javascript'>"
        . "window.location.href='$url'"
        . "</script>";
}

function dd($var)
{
    echo "<pre>";
    print_r($var);
    die();
}


function getUrl(String $modulo, String $controlador, String $funcion, $parametros = false, $pagina = false)
{

    //Colocar validaciones a los tipo de datos
    if (!is_string($modulo)) {

        return;
    }

    if ($pagina == false) {
        $pagina = "index";
    }

    $url = "$pagina.php?modulo=$modulo&controlador=$controlador&funcion=$funcion";
    if ($parametros) {
        foreach ($parametros as $key => $value) {
            $url .= "&$key=$value";
        }
    }
    return $url;
}

function resolve($modulo = 'dashboard', $controlador = 'dashboard', $funcion = 'dashboard')
{

    if (isset($_GET['modulo'])) {
        $modulo = $_GET['modulo'];
        $controlador = $_GET['controlador'];
        $funcion = $_GET['funcion'];
    }

    $controllerPath = __DIR__ . "/../modules/$modulo/controller/{$controlador}Controller.php";
    if (is_dir(__DIR__ . "/../modules/$modulo")) {
        if (is_file($controllerPath)) {

            include_once $controllerPath;
            $nombreClase = $controlador . "Controller";

            include_once __DIR__ . '/../config/conn.php';
            $conexion = (new Conection())->getConnect();

            require_once __DIR__ ."/../Modules/Permisos/Controller/PermisosController.php";
            $objPermisos = new PermisosController();
            /**
             * Primera consulta
             * traer el id del modulo usando el nombre del modulo.
             */
            $idNombreModulo = $objPermisos->gidIdModulo($modulo);

            /**
             * Segunda consulta
             * traer el id de la función basadao en su nombre de la función y ID De la función.
             */
            $idFuncion = $objPermisos->getIdFuncion($funcion,$modulo, $idNombreModulo);

            /**
             * Tercera consulta
             * valido que el ROL DEL USUARIO TENGA EL PERMISO ADECUADO PARA ACCEDER A ESA FUNCIÓN, BASADO EN ESA FUNCIÓN PODEMOS USAR EL MODULO.
             */
            $rolId = $_SESSION['usuario']['rol_id'];
            // para validar que la función si a ejecutar este asociada al ROL.
            $isValidate = $objPermisos->validateRolFuncion($rolId);
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

function ajaxGeneral()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}