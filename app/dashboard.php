<?php

require_once __DIR__ . '/helpers/getUrl.php';
require_once __DIR__ . '/helpers/session.php';
require_once __DIR__ . '/helpers/ScanFiles.php';
require_once __DIR__ . '/config/conn.php';
require_once __DIR__ . '/modules/solicitudPrestamos/controller/solicitudPrestamosController.php';
require_once __DIR__ . '/modules/reservaPrestamos/services/ServicesReservas.php';
require_once __DIR__ . '/modules/solicitudPrestamos/services/servicesSolicitudPrestamos.php';

$modulo = $_GET['modulo'] ?? 'dashboard';
$controllerFile = new ScanFiles($modulo);
$css = $controllerFile->addUrl($modulo);

// Ejecutar actualización automática de estados de prstamos

$conexion = new Conection();
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

require_once '../public/partials/header.php';
?>

<div class="container bg-light-pattern">
    <?php resolve(); ?>
</div>

<?php require_once '../public/partials/footer.php'; ?>
