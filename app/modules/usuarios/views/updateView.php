<div class="content">
  <div class="menuTitle">
    <span id="textTitle">Editar Usuario</span>
    <a href="<?php echo getUrl('usuarios', 'usuarios', 'getAll'); ?>" class="close-btn" title="Volver al listado">&times;</a>
  </div>

  <div class="formUser">
    <form method="POST"
          action="<?= getUrl('usuarios', 'usuarios', 'updateUser', false, 'dashboard') ?>"
          id="formUpdateUser">

      <input type="hidden" name="usu_id" value="<?= htmlspecialchars($usuarioUpdate['usu_id']) ?>">

      <div class="inputContentUpdate cedula">
        <label class="labelForm" for="usu_docum">Documento:</label>
        <input type="text" name="usu_docum" id="usu_docum" class="inputForm"
               value="<?= htmlspecialchars($usuarioUpdate['usu_docum']) ?>" disabled>
      </div>

      <div class="inputContentUpdate nombres">
        <label class="labelForm" for="usu_nombres">Nombres:</label>
        <input type="text" name="usu_nombres" id="usu_nombres" class="inputForm"
               value="<?= htmlspecialchars($usuarioUpdate['usu_nombres']) ?>" required>
      </div>

      <div class="inputContentUpdate apellidos">
        <label class="labelForm" for="usu_apellidos">Apellidos:</label>
        <input type="text" name="usu_apellidos" id="usu_apellidos" class="inputForm"
               value="<?= htmlspecialchars($usuarioUpdate['usu_apellidos']) ?>" required>
      </div>

      <div class="inputContentUpdate email">
        <label class="labelForm" for="usu_email">Correo electrónico:</label>
        <input type="email" name="usu_email" id="usu_email" class="inputForm"
               value="<?= htmlspecialchars($usuarioUpdate['usu_email']) ?>" required>
      </div>

      <div class="inputContentUpdate telefono">
        <label class="labelForm" for="usu_telefono">Teléfono:</label>
        <input type="text" name="usu_telefono" id="usu_telefono" class="inputForm"
               value="<?= htmlspecialchars($usuarioUpdate['usu_telefono']) ?>" required>
      </div>

      <div class="inputBtn">
        <button type="submit">Actualizar Usuario</button>
      </div>
    </form>
  </div>
</div>
