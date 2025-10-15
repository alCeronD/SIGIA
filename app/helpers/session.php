<?php
// Se puede transformar en una función.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto_sigia/index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_id'];
