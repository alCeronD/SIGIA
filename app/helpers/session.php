<?php
session_start();
$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_id'];


// dd($usuario);

if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto_sigia/index.php");
    exit();
}


?>