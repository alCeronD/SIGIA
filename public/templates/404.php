<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404</title>

  <!-- Materialize CSS -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
  <link rel="stylesheet" href="/../../../../public/assets/libraries/materialize/css/materialize.min.css">
  <link rel="stylesheet" href="/../../../../public/assets/css/template.css">

  <link rel="icon" type="image/x-icon" href="public/assets/image/sSigia.ico">
</head>

<body>
  <div class="backGround bg-light-pattern">

    <div class="login-container">
      <div class="login-card z-depth-3">
        <div class="brand-logo">
          <img src="../public/assets/image/login/sigiaS.svg" width="100px" alt="Logo SENA" id="logoSena">
          <img src="../public/assets/image/login/logo_sena.png" width="100px" alt="Logo SENA" id="logoSigia">
        </div>

        <div class="input-field">
          <!-- <h1>Error 404</h1> -->
          <h5 class="center-align">Not found - <?php echo $codeResponseTemplate; ?></h5>
          <h2><?php echo $messageToTemplate ?></h2>
        </div>

        <a href="<?php echo $_SESSION['url_anterior']; ?>" class="btn waves-effect waves-light green darken-1">
          Volver
        </a>
        <!-- <button type="submit" class="btn waves-effect waves-light green darken-1" style="width: 100%;">
          Volver
        </button> -->
      </div>
    </div>
  </div>

  <!-- JS personalizado -->
  <script src="/../../../../public/assets/libraries/materialize/js/materialize.min.js"></script>

</body>

</html>