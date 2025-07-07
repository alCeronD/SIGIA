<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login SIGIA</title>

  <!-- Materialize CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  
  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="/proyecto_sigia/public/assets/css/login/login.css">
</head>
<body>
  <div class="login-container">
    <div class="login-card z-depth-3">
      <div class="brand-logo">
        <img src="/proyecto_sigia/public/assets/image/login/logo_sena.png" width="80" alt="Logo SENA">
      </div>
      <h5 class="center-align">Iniciar sesión</h5>

      <form id="loginForm" action="<?php echo getUrl("login","login","login"); ?>" method="POST">
        <div class="input-field">
          <input id="docum" name="docum" type="number" min="0" class="validate" required>
          <label for="docum">No. Documento</label>
        </div>

        <div class="input-field">
          <input id="pass" name="pass" type="password" class="validate" required>
          <label for="pass">Contraseña</label>
        </div>

        <button type="submit" class="btn waves-effect waves-light green darken-1" style="width: 100%;">
          Iniciar sesión
        </button>
      </form>
    </div>
  </div>

  <!-- Materialize JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  
  <!-- JS personalizado -->
  <script type="module" src="/proyecto_sigia/public/assets/js/login/login.js"></script>
</body>
</html>