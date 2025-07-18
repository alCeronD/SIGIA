<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIGIA</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../public/assets/libraries/materialize/css/materialize.css">
  <link rel="stylesheet" href="../public/assets/css/main.css">
  <link rel="stylesheet" href="<?php echo $_SESSION['css']; ?>">

</head>
<!-- <div class="header">Sigia - Servicio Nacional de Aprendizaje</div> -->
  <nav class="header">
    <div class="nav-wrapper">
      <a href="<?php echo getUrl('dashboard', 'Dashboard','dashboard',false,'dashboard'); ?>" class="brand-logo logo center">
      </a>
      <ul id="" class="right">
        <li>
          <div class="contentUser">
            <span id="userText"><?php echo $_SESSION['usuario']['nombre'] ?> <?php echo $_SESSION['usuario']['apellido'];?></span>
            <span id="rolText"><?php echo $_SESSION['usuario']['rol_nombre'];?></span>
          </div>
          </li>
      </ul>
      <!-- Todo: se puede cambiar con javascript. -->
      <ul class="left">
        <div class="contentUser">
          <a href="<?php echo getUrl('login','login','logout'); ?>">
            <span id="userText">cerrar Sesión</span>
          </a>
        </div>
      </ul>
    </div>
  </nav>


<body>