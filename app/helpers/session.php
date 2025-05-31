<?php
session_start();
include_once 'getUrl.php';

// if (!isset($_SESSION['value'])) {
//     header("Location: /proyecto_sigia/index.php");
//     //value = $_SESSION['value'];
//     exit();
// }

if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto_sigia/index.php");
    exit();
}


?>