<?php 

$value = $_SESSION['value'] ?? 0;


//solicitudPrestamosView = 2;
//consultarSolicitudView = 1;
$pathCss = '';
var_dump($value);
switch ($value) {
  case 1:

    $pathCss = '../public/assets/css/prestamos/consultPrestamos.css';

    break;

  case 2:

    break;
  
  default:
    $pathCss = '';
    break;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Estilo Mac</title>
  <link rel="stylesheet" href="../public/assets/css/main.css">
  <link rel="stylesheet" href="<?php echo $pathCss; ?>">
</head>
<body class="">
