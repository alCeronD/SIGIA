<?php
$conexion    = new Conection();
$controlador = new ReportesController($conexion->getConnect());

if (
    isset($_GET['estadoElemento']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
) {
    $controlador->filtrarElementosAjax();
    exit;
}


http_response_code(403);
echo json_encode(['error' => 'Acceso no permitido']);
exit;

