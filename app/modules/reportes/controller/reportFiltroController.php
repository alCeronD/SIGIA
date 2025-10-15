<?php
$conexion    = new Conection();
$ctrl        = new ReportesController($conexion->getConnect());

// --- FILTRO TRAZABILIDAD ---
if (isset($_POST['fechaInicio'], $_POST['fechaFin']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
    $ctrl->filtrarTrazabilidadAjax();
    exit;
}

// --- FILTRO ELEMENTOS ---
if (isset($_POST['estadoElemento']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
    $ctrl->filtrarElementosAjax();
    exit;
}

// --- FILTRO MOVIMIENTOS POR PLACA ---
if (isset($_POST['placa']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
    $ctrl->filtrarPorPlacaAjax();
    exit;
}

http_response_code(403);
echo json_encode(['error' => 'Acceso no permitido']);
exit;
?>