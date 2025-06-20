<div class="contentUsuarios">
  <div class="titleUsuarios">
    <span id="textTitle">Usuarios Registrados</span>
      <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
  </div>

  <div class="table">
    <table id="tableConfig">
      <thead>
        <tr>
          <th>Nombres</th>
          <th>Apellidos</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($usuarios)): ?>
          <?php foreach ($usuarios as $usuario): ?>
            <tr>
              <td><?= htmlspecialchars($usuario['usu_nombres']) ?></td>
              <td><?= htmlspecialchars($usuario['usu_apellidos']) ?></td>
              <td><?= htmlspecialchars($usuario['rl_nombre']) ?></td>
              <td><?= htmlspecialchars($usuario['estado_usuario']) ?></td>
              <td>
                <a href="#"
                   class="btnEditarUsuario"
                   data-id="<?= $usuario['usu_id'] ?>"
                   data-nombres="<?= htmlspecialchars($usuario['usu_nombres']) ?>"
                   data-apellidos="<?= htmlspecialchars($usuario['usu_apellidos']) ?>"
                   data-email="<?= htmlspecialchars($usuario['usu_email']) ?>"
                   data-telefono="<?= htmlspecialchars($usuario['usu_telefono']) ?>"
                   data-documento="<?= htmlspecialchars($usuario['usu_docum']) ?>">
                   Editar
                </a>
                |
                <a href="<?= getUrl('usuarios', 'usuarios', 'cambiarEstadoUsuario', ['usu_id' => $usuario['usu_id']], 'dashboard') ?>" onclick="return confirm('¿Estás seguro de que deseas cambiar el estado del usuario?');">
                  Activar/Desactivar
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4">No hay usuarios registrados.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div id="modalEditarUsuario" class="modal-custom">
  <div class="modal-content-custom">
    <span class="close-modal" onclick="cerrarModalUsuario()">&times;</span>
    <h3>Editar Usuario</h3>
    <form method="POST" action="<?= getUrl('usuarios', 'usuarios', 'updateUser', false, 'dashboard') ?>" id="formUpdateUser">
      <input type="hidden" name="usu_id" id="usu_id">

      <div class="inputContentUpdate cedula">
        <label class="labelForm" for="usu_docum">Documento:</label>
        <input type="text" name="usu_docum" id="usu_docum" class="inputForm" disabled>
      </div>

      <div class="inputContentUpdate nombres">
        <label class="labelForm" for="usu_nombres">Nombres:</label>
        <input type="text" name="usu_nombres" id="usu_nombres" class="inputForm" required>
      </div>

      <div class="inputContentUpdate apellidos">
        <label class="labelForm" for="usu_apellidos">Apellidos:</label>
        <input type="text" name="usu_apellidos" id="usu_apellidos" class="inputForm" required>
      </div>

      <div class="inputContentUpdate email">
        <label class="labelForm" for="usu_email">Correo:</label>
        <input type="email" name="usu_email" id="usu_email" class="inputForm" required>
      </div>

      <div class="inputContentUpdate telefono">
        <label class="labelForm" for="usu_telefono">Teléfono:</label>
        <input type="text" name="usu_telefono" id="usu_telefono" class="inputForm" required>
      </div>

      <div class="inputBtn">
        <button type="submit">Actualizar Usuario</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".btnEditarUsuario").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      // Cargar los datos
      document.getElementById("usu_id").value = btn.dataset.id;
      document.getElementById("usu_docum").value = btn.dataset.documento;
      document.getElementById("usu_nombres").value = btn.dataset.nombres;
      document.getElementById("usu_apellidos").value = btn.dataset.apellidos;
      document.getElementById("usu_email").value = btn.dataset.email;
      document.getElementById("usu_telefono").value = btn.dataset.telefono;
      // Mostrar modal
      document.getElementById("modalEditarUsuario").style.display = "flex";
    });
  });
});

function cerrarModalUsuario() {
  document.getElementById("modalEditarUsuario").style.display = "none";
}
</script>
