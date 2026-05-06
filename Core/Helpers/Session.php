<?php
// Se puede transformar en una función.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: /index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_id'];
