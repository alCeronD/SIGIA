<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIGIA</title>
  <link rel="icon" type="image/x-icon" href="../public/assets/image/sSigia.ico">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../public/assets/libraries/materialize/css/materialize.css">
  <link rel="stylesheet" href="../public/assets/css/main.css">
  <link rel="stylesheet" href="<?php echo $_SESSION['css']; ?>">

</head>
<nav class="header">
  <div class="nav-wrapper">
    <a href="<?php echo getUrl('dashboard', 'Dashboard', 'dashboard', false, 'dashboard'); ?>" class="brand-logo logo center">
    </a>
    <ul id="" class="right">
      <li class="user-dropdown">
        <div class="contentUser" id="userDropdownToggle">
          <!-- <i class="material-icons large">account_circle</i> -->
          <a href="<?php echo getUrl('usuarios', 'usuarios', 'actualizarDatosView',false,'dashboard'); ?>">
            <i class="material-icons large">account_circle</i>
          </a>
          <span id="rolText"> <?php echo $_SESSION['usuario']['rol_nombre']; ?> </span> <i class="material-icons">arrow_drop_down</i>
        </div>
        <ul class="submenu" id="userDropdownMenu">
          <li>
            <a href="<?php echo getUrl('usuarios', 'usuarios', 'actualizarDatosView',false,'dashboard'); ?>">
              <i class="material-icons left">edit</i>Actualizar datos
            </a>
          </li>
        </ul>
      </li>
  </div>
  </li>
  </ul>
 
  </div>
</nav>

<body>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggle = document.getElementById('userDropdownToggle');
      const menu = document.getElementById('userDropdownMenu');

      toggle.addEventListener('click', function(e) {
        e.stopPropagation();
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
      });

      document.addEventListener('click', function() {
        menu.style.display = 'none';
      });
    });
  </script>