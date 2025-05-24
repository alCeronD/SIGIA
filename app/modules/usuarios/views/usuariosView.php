<?php
    session_start();
    include_once '../proyecto_sigia/public/partials/header.php';
    if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto_sigia/index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_id'];
?>

<main class="flex-grow-1 d-flex align-items-center justify-content-center">
  <div class="container text-center">
        <form method="POST" action="<?php echo getUrl('usuarios','usuarios','createUser'); ?>" class="w-50 mx-auto text-start">
      <div class="mb-3">
        <label for="usu_docum" class="form-label">Documento</label>
        <input type="text" class="form-control" id="usu_docum" name="usu_docum" required>
      </div>
      <div class="mb-3">
        <label for="usu_nombres" class="form-label">Nombres</label>
        <input type="text" class="form-control" id="usu_nombres" name="usu_nombres" required>
      </div>
      <div class="mb-3">
        <label for="usu_apellidos" class="form-label">Apellidos</label>
        <input type="text" class="form-control" id="usu_apellidos" name="usu_apellidos" required>
      </div>
      <div class="mb-3">
        <label for="usu_password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="usu_password" name="usu_password" required>
      </div>
      <div class="mb-3">
        <label for="usu_email" class="form-label">Email</label>
        <input type="email" class="form-control" id="usu_email" name="usu_email" required>
      </div>
      <div class="mb-3">
        <label for="usu_telefono" class="form-label">Teléfono</label>
        <input type="tel" class="form-control" id="usu_telefono" name="usu_telefono" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Enviar</button>
    </form>
  </div>
</main>

<?php
    include_once '../proyecto_sigia/public/partials/footer.php'
?>