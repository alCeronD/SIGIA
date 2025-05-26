<?php 
// include_once '../app/helpers/session.php';

session_start();
include_once '../app/helpers/getUrl.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto_sigia/index.php");
    exit();
}
$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_id'];

// var_dump($usuario);

include('../public/partials/header.php');
include('../public/partials/body.php');
include('../public/partials/footer.php');
?>
