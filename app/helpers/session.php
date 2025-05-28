<?php
session_start();
include_once 'getUrl.php';
<<<<<<< HEAD
if (!isset($_SESSION['usuario'])) {
=======

if (isset($_SESSION['usuario'])) {
>>>>>>> 54726b9 (cambios)
    header("Location: /proyecto_sigia/index.php");
    exit();
}
$usuario = $_SESSION['usuario'];
// dd($usuario);
$rol = $usuario['rol_id'];
?>