<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIGIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/proyecto_sigia/public/assets/css/login/login.css">

</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="brand-logo">
                 <img src="/proyecto_sigia/public/assets/image/login/logo_sena.png" width="80">
            </div>
            <h2>Iniciar sesión</h2>
            <form id="loginForm" action="<?php echo getUrl("login","login","login"); ?>" method="POST">
                <div class="mb-3">
                    <input type="text" class="form-control" id="docum" name="docum" placeholder="No. Documento" >
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Contraseña" >
                </div>
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </form>
            <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<script type="module" src="/proyecto_sigia/public/assets/js/login/login.js"></script>

