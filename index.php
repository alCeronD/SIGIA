<?php

// ------------------------ DE ANTERIOR ENRUTADOR ------------------

include_once __DIR__ .'/Core/Helpers/Autoload.php';

if (UtilsFunctions::ajaxGeneral()) {
    Router::ExecuteFunction();
    exit;
}

if (isset($_GET['modulo'])) {
    // echo "<div class='container'>";
    Router::ExecuteFunction();
    exit;
}
Rect::redirectTo(Router::createRoute('Login', 'Login', 'index', false, 'index'));