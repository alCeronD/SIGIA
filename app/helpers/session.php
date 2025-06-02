<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto_sigia/index.php");
    exit();
}

include_once 'getUrl.php';

?>