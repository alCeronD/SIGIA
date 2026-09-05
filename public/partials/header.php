<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIGIA</title>
  <link rel="icon" type="image/x-icon" href="../public/assets/image/sSigia.ico">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../public/assets/libraries/materialize/css/materialize.min.css">
  <link rel="stylesheet" href="../public/assets/css/main.css">
  <?php
  // la variable routesCss esta definida en el controlador de cada vista.
  if (isset($routesCss) && is_array($routesCss)): ?>
    <?php foreach ($routesCss as $styleUrl): ?>
      <link rel="stylesheet" href="<?= $styleUrl; ?>">
    <?php endforeach; ?>
  <?php endif; ?>

</head>

<body>
  <nav class="header">
    <div class="nav-wrapper">
      <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>" class="brand-logo logo center">
      </a>
      <!-- Icono de usuario -->
      <ul id="" class="right">
        <li class="user-dropdown">
          <div class="contentUser" id="userDropdownToggle">
            <a href="<?php echo Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'actualizarPersonalData', false, CR_DASHBOARD_LOWER_CASE); ?>">
              <i class="material-icons large">account_circle</i>
            </a>
            <span id="rolText"> <?php echo $_SESSION['usuario']['rol_nombre']; ?> </span> <i class="material-icons"></i>
          </div>

        </li>
      </ul>
      <!-- Botón de cerrar sesión -->
      <ul id="" class="left">
        <li class="user-dropdown">
          <div class="contentUser" id="userDropdownToggle">
            <span id="btnCerrarSesion" data-logOut='logOut' data-Url='<?php echo Router::createRoute('Login', 'Login', 'logout', false, 'dashboard') ?>' data-btnClose="dataBtnClose">
              Salir
            </span>
            <br>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <?php require_once __DIR__ . '/../../Core/Helpers/modalConfirmation.php';
  ?>