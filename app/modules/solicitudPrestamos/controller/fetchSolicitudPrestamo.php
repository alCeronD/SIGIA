<?php
require_once __DIR__ . '/../../../config/conn.php';
require_once __DIR__ . '/../controller/solicitudPrestamosController.php';

$conexion = new Conection(); // o Conection
$conn = $conexion->getConnect();
$controller = new solicitudPrestamosController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['pres_cod']) && isset($_GET['idCod'])) {
        $pres_cod = (int) $_GET['pres_cod'];
        $controller->verDetallePrestamo($pres_cod);
    } else {
        fail('Parámetros inválidos');
    }
}
