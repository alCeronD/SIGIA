<?php
require_once __DIR__ . '/Helpers/Const.php';
require_once BASE_URL . '/' . CR_AUTOLOAD;
require_once BASE_URL . CR_ROUTE_CONN;

Session::validateSession();

$modulo = $_GET[CR_MODULO] ?? CR_DASHBOARD;
$assetsFiles = (new ScanFiles($modulo))->mapAssets($modulo);
// Ejecutar actualización automática de estados de prestamos
$conn = (new Conn())->getConnect();
if (UtilsFunctions::ajaxGeneral()) {
    Router::ExecuteFunction();
    exit;
}

$solicitudService = new ServicesSolicitudPrestamos();
$solicitudService->callTask();

$prestamoController = new SolicitudPrestamosController($conn);
// $prestamoController->actualizarEstadosPorFecha();
$reservaServices = new ServicesReservas();
$reservaServices->callTask();


if (!empty($assetsFiles)) {
    $_SESSION['css'] = $assetsFiles['css'][$modulo];
    $_SESSION['js'] = $assetsFiles['js'][$modulo];
} else {
    unset($_SESSION['css']['css']);
}


require_once CR_ROUTE_HEADER;
?>

<div class="container bg-light-pattern">
    <?php Router::ExecuteFunction();
    ?>
</div>

<?php require_once CR_ROUTE_FOOTER; ?>