<?php 
include_once '../proyecto_sigia/app/helpers/getUrl.php';

if (ajaxGeneral() || esPostman()) {
    resolve();
    exit;
}

if (isset($_GET['modulo']) && isset($_GET['controlador']) && isset($_GET['funcion'])) {
    resolve();
    exit;
}

// Solo redirigir si no es una llamada AJAX/POSTMAN y no hay ruta
header("Location: " . getUrl('login', 'login', 'index', false, false));
exit;



