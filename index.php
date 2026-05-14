<?php

// ------------------------ DE ANTERIOR ENRUTADOR ------------------


// include_once __DIR__.'/Core/Helpers/GetUrl.php';
// if (ajaxGeneral()) {
//     resolve();
//     exit;
// }else {
//     if (isset($_GET['modulo'])){
//         // echo "<div class='container'>";
//         resolve();
//     }else{
//         redirect(getUrl('login','login','index',false,false));
//     }
// }

// ---------------------- NUEVO ENRUTADOR
include_once __DIR__.'/Core/Helpers/ExecuteFunction.php';
include_once __DIR__ .'/Core/Helpers/Redirect.php';
include_once __DIR__.'/Core/Helpers/CreateRoute.php';


// if(!isset($_GET['modulo'])){
//     redirect(createRoute('Login','Login','index', false,'index'));
// }else{
//     var_dump($_GET);
// }


// Forzamos ver errores
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// // Depuración directa al navegador (esto romperá el JSON pero nos dirá la verdad)
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     echo "--- DATOS RECIBIDOS ---\n";
//     echo "Metodo: " . $_SERVER['REQUEST_METHOD'] . "\n";
//     echo "X-Requested-With: " . ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? 'NO EXISTE') . "\n";
//     print_r($_POST);

//     // Si quieres que el enrutador intente ejecutar la función a pesar de todo:
//     ExecuteFunction();
//     exit;
// }

if (isAjax()) {
    ExecuteFunction();
    exit;
}

if (isset($_GET['modulo'])) {
    // echo "<div class='container'>";
    ExecuteFunction();
    exit;
}
redirect(createRoute('Login', 'Login', 'index', false, 'index'));