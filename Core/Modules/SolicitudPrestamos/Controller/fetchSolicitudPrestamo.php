<?php
require_once __DIR__ . '/Helpers/Const.php';
require_once BASE_URL.CR_ROUTE_CONN;
require_once BASE_URL . '/'.CR_AUTOLOAD;

$conexion = new Conn(); // o Conection
$conn = $conexion->getConnect();
$controller = new solicitudPrestamosController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['pres_cod']) && isset($_GET['idCod'])) {
        $pres_cod = (int) $_GET['pres_cod'];
        $controller->verDetallePrestamo($pres_cod);
    } else {
        Response::fail('Parámetros inválidos');
    }
}
