<?php
require_once __DIR__ . "/response.php";

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

    if (session_status() == PHP_SESSION_NONE && $modulo != "login" && $funcion == "index"){
        $url = "$pagina.php?modulo=index&controlador=$controlador&funcion=$funcion";
    }

    $url = "$pagina.php?modulo=$modulo&controlador=$controlador&funcion=$funcion";
    if ($parametros) {
        foreach ($parametros as $key => $value) {
            $url .= "&$key=$value";
        }
    }
    return $url;
}

function resolve($modulo = 'login', $controlador = 'login', $funcion = 'index')
{
    if (isset($_GET['modulo'])) {
        $modulo = $_GET['modulo'];
        $controlador = $_GET['controlador'];
        $funcion = $_GET['funcion'];

    }

    // Rutas públicas que no necesitan sesión
    $publicRoutes = [
        'login' => ['index', 'login', 'logout']
    ];

    // Validamos que las rutas que el usuario ha seleccionado sean públicas para evitar su navegación.
    $isPublic = isset($publicRoutes[$modulo]) && in_array($funcion, $publicRoutes[$modulo]);
    $controllerPath = __DIR__ . "/../modules/$modulo/controller/{$controlador}Controller.php";
    if (!is_file($controllerPath)) {
        echo "El controlador no existe.";
        return;
    }else{
        include_once $controllerPath;

    }

    include_once __DIR__ . '/../config/conn.php';
    $conexion = (new Conection())->getConnect();
    $nombreClase = $controlador . "Controller";
    require_once __DIR__ . "/../Modules/Permisos/Controller/PermisosController.php";
    $objPermisos = new PermisosController();
    // Si no es pública, validamos la sesión y permisos
    if (!$isPublic) {
        if (!isset($_SESSION['usuario'])) {
            redirect(getUrl('login', 'login', 'index'));
        }

        $rolId = $_SESSION['usuario']['rol_id'] ?? 0;

        $idModulo = $objPermisos->gidIdModulo($modulo);

        $idFuncion = $objPermisos->getIdFuncion($funcion, $modulo, $idModulo);

        if (!$idFuncion || !$objPermisos->validateRolFuncion($rolId, $idFuncion)) {
            echo "<script>alert('No tienes permisos para acceder.'); window.history.back();</script>";
            return;
        }
    }
    // Llamamos al controlador y la función
    $objeto = new $nombreClase($conexion);

    if (method_exists($objeto, $funcion)) {
        $objeto->$funcion();

    } else {
        echo "La función '$funcion' no existe.";
    }
}

function ajaxGeneral()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

}