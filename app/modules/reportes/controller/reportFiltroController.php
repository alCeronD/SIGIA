<?php
$conexion    = new Conection();
$controlador = new ReportesController($conexion->getConnect());

// --- FILTRO TRAZABILIDAD ---
if (
    isset($_POST['fechaInicio'], $_POST['fechaFin']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
) {
    $controlador->filtrarTrazabilidadAjax();
    exit;
}

// --- FILTRO ELEMENTOS ---
if (
    isset($_POST['estadoElemento']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
) {
    $controlador->filtrarElementosAjax();
    exit;
}

// --- BLOQUEO POR DEFECTO ---
http_response_code(403);
echo json_encode(['error' => 'Acceso no permitido']);
exit;

