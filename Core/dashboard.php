<?php
require_once __DIR__ . '/Helpers/Const.php';
require_once BASE_URL . CR_ROUTE_CONN;
require_once BASE_URL . '/'.CR_AUTOLOAD;


Session::validateSession();

$modulo = $_GET[CR_MODULO] ?? CR_DASHBOARD;
$controllerFile = new ScanFiles($modulo);
$css = $controllerFile->addUrl($modulo);

// Ejecutar actualización automática de estados de prestamos
$conexion = new Conn();
$conn = $conexion->getConnect();


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


if ($css) {
    $_SESSION['css'] = $css;
} else {
    unset($_SESSION['css']);
}

require_once CR_ROUTE_HEADER;
?>

<div class="container bg-light-pattern">
    <?php
    Router::ExecuteFunction();?>
</div>

<?php require_once CR_ROUTE_FOOTER; ?>