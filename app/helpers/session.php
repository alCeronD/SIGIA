<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto_sigia/index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_id'];
?>