<?php
session_start();
include_once '../proyecto_sigia/public/partials/header.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto_sigia/index.php");
    exit();
}

$usuarioSesion = $_SESSION['usuario'];
$rolSesion = $usuarioSesion['rol_id'];
?>

<main class="flex-grow-1 d-flex align-items-center justify-content-center">
  <div class="container text-center">
    <form method="POST" action="<?= getUrl('usuarios','usuarios','updateUser') ?>" class="w-75 mx-auto text-start row g-3">
      <input type="hidden" name="usu_id" value="<?= htmlspecialchars($usuario['usu_id']) ?>">

      <!-- Bloque 1 -->
      <div class="col-md-6">
        <div class="mb-3">
          <label for="usu_docum" class="form-label">Documento</label>
          <input type="text" class="form-control" id="usu_docum" name="usu_docum" value="<?= htmlspecialchars($usuario['usu_docum']) ?>" required>
        </div>
        <div class="mb-3">
          <label for="usu_nombres" class="form-label">Nombres</label>
          <input type="text" class="form-control" id="usu_nombres" name="usu_nombres" value="<?= htmlspecialchars($usuario['usu_nombres']) ?>" required>
        </div>
        <div class="mb-3">
          <label for="usu_apellidos" class="form-label">Apellidos</label>
          <input type="text" class="form-control" id="usu_apellidos" name="usu_apellidos" value="<?= htmlspecialchars($usuario['usu_apellidos']) ?>" required>
        </div>
        <div class="mb-3">
          <label for="usu_password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="usu_password" name="usu_password" placeholder="Cambiar contraseña (opcional)">
        </div>
      </div>

      <!-- Bloque 2 -->
      <div class="col-md-6">
        <div class="mb-3">
          <label for="usu_email" class="form-label">Email</label>
          <input type="email" class="form-control" id="usu_email" name="usu_email" value="<?= htmlspecialchars($usuario['usu_email']) ?>" required>
        </div>
        <div class="mb-3">
          <label for="usu_telefono" class="form-label">Teléfono</label>
          <input type="tel" class="form-control" id="usu_telefono" name="usu_telefono" value="<?= htmlspecialchars($usuario['usu_telefono']) ?>" required>
        </div>
        <div class="mb-3">
          <label for="rol_id" class="form-label">Rol</label>
          <select class="form-select" id="rol_id" name="rol_id" required>
            <option value="">Seleccione un rol</option>
            <?php foreach ($roles as $roli): ?>
              <option value="<?= $roli['rl_id'] ?>" <?= $usuario['rol_id'] == $roli['rl_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($roli['rl_nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-success w-100">Actualizar</button>
      </div>
    </form>
  </div>
</main>

<?php include_once '../proyecto_sigia/public/partials/footer.php'; ?>
