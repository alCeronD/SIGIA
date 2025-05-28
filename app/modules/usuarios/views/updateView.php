<?php
include_once '../proyecto_sigia/app/helpers/session.php';
include_once '../proyecto_sigia/public/partials/header.php';
?>


<main class="flex-grow-1 d-flex align-items-center justify-content-center">
  <div class="container text-center">
<form method="POST" action="<?php echo getUrl("usuarios","usuarios","updateUser"); ?>">
    <input type="hidden" name="usu_id" value="<?= htmlspecialchars($usuarioUpdate['usu_id']) ?>">

    <label>Documento:</label>
    <input type="text" name="usu_docum" value="<?= isset($usuarioUpdate['usu_docum']) ? htmlspecialchars($usuarioUpdate['usu_docum']) : '' ?>" required disabled>
    <br />

    <label>Nombres:</label>
    <input type="text" name="usu_nombres" value="<?= htmlspecialchars($usuarioUpdate['usu_nombres']) ?>" required>
    <br />

    <label>Apellidos:</label>
    <input type="text" name="usu_apellidos" value="<?= htmlspecialchars($usuarioUpdate['usu_apellidos']) ?>" required>
    <br />

    <label>Correo electrónico:</label>
    <input type="email" name="usu_email" value="<?= htmlspecialchars($usuarioUpdate['usu_email']) ?>" required>
    <br />

    <label>Teléfono:</label>
    <input type="text" name="usu_telefono" value="<?= htmlspecialchars($usuarioUpdate['usu_telefono']) ?>" required>
    <br />

    <button type="submit">Actualizar</button>
</form>

  </div>
</main>

<?php include_once '../proyecto_sigia/public/partials/footer.php'; ?>
