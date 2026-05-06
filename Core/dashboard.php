<?php
require_once __DIR__ . '/Helpers/Const.php';
require_once BASE_URL . CR_ROUTE_CONN;
require_once BASE_URL . '/GetUrl.php';
require_once BASE_URL . '/'.CR_FILE_SESSION;
require_once BASE_URL . '/'.CR_FILE_SCAN;
require_once BASE_URL . '/..'.CR_ROUTE_SOLICITUD_PRESTAMOS_CONTROLLER;
require_once BASE_URL . '/..'.CR_ROUTE_SERVICES_RESERVA;
require_once BASE_URL . '/..'.CR_ROUTE_SERVICES_SOLICITUD;

$modulo = $_GET[CR_MODULO] ?? CR_DASHBOARD;
$controllerFile = new ScanFiles($modulo);
$css = $controllerFile->addUrl($modulo);

// Ejecutar actualización automática de estados de prestamos
$conexion = new Conn();
$conn = $conexion->getConnect();
$solicitudService = new servicesSolicitudPrestamos();
$solicitudService->callTask();

$prestamoController = new solicitudPrestamosController($conn);
// $prestamoController->actualizarEstadosPorFecha();
$reservaServices = new ServicesReservas();
$reservaServices->callTask();

if (ajaxGeneral()) {
    resolve();
    exit;
}

if ($css) {
    $_SESSION['css'] = $css;
} else {
    unset($_SESSION['css']);
}

require_once CR_ROUTE_HEADER;
?>

<div class="container bg-light-pattern">
    <?php resolve(); ?>
</div>

<?php require_once CR_ROUTE_FOOTER; ?>
