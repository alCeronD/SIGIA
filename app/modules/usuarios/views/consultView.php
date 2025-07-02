<div class="contentUsuarios">
  <div class="titleUsuarios">
    <span id="textTitle">Consultar Usuarios</span>
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
              <div class="center-align">
                <a href="#"
                   class="btnEditarUsuario btn-small teal darken-1 white-text waves-effect waves-light"
                   data-id="<?= $usuario['usu_id'] ?>"
                   data-nombres="<?= htmlspecialchars($usuario['usu_nombres']) ?>"
                   data-apellidos="<?= htmlspecialchars($usuario['usu_apellidos']) ?>"
                   data-email="<?= htmlspecialchars($usuario['usu_email']) ?>"
                   data-telefono="<?= htmlspecialchars($usuario['usu_telefono']) ?>"
                   data-documento="<?= htmlspecialchars($usuario['usu_docum']) ?>">
                   <i class="material-icons left">edit</i>Editar
                </a>
            
                <a href="<?= getUrl('usuarios', 'usuarios', 'cambiarEstadoUsuario', ['usu_id' => $usuario['usu_id']], 'dashboard') ?>"
                   class="btn-small red lighten-1 white-text waves-effect waves-light"
                   onclick="return confirm('¿Estás seguro de que deseas cambiar el estado del usuario?');">
                   <i class="material-icons left">autorenew</i>Activar/Desactivar
                </a>
              </div>
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
    <div class="pagination-container center-align">
   <ul id="paginacion-usuarios" class="pagination"></ul>
</div>

  </div>
</div>

<!-- Modal -->
<div id="modalEditarUsuario" class="modal-custom card z-depth-3">
  <div class="modal-content-custom card-content">
    <span class="close-modal btn-flat red-text right" onclick="cerrarModalUsuario()" title="Cerrar" style="font-size: 1.5rem;">&times;</span>
    <h5 class="teal-text text-darken-3">Editar Usuario</h5>

    <form method="POST" action="<?= getUrl('usuarios', 'usuarios', 'updateUser', false, 'dashboard') ?>" id="formUpdateUser">
      <input type="hidden" name="usu_id" id="usu_id">

      <div class="input-field" style="margin-bottom: 20px;">
        <label for="usu_docum" class="active">Documento</label>
        <input type="text" name="usu_docum" id="usu_docum" disabled>
      </div>

      <div class="input-field" style="margin-bottom: 20px;">
        <label for="usu_nombres" class="active">Nombres</label>
        <input type="text" name="usu_nombres" id="usu_nombres" required>
      </div>

      <div class="input-field" style="margin-bottom: 20px;">
        <label for="usu_apellidos" class="active">Apellidos</label>
        <input type="text" name="usu_apellidos" id="usu_apellidos" required>
      </div>

      <div class="input-field" style="margin-bottom: 20px;">
        <label for="usu_email" class="active">Correo</label>
        <input type="email" name="usu_email" id="usu_email" required>
      </div>

      <div class="input-field" style="margin-bottom: 20px;">
        <label for="usu_telefono" class="active">Teléfono</label>
        <input type="text" name="usu_telefono" id="usu_telefono" required>
      </div>

      <div class="inputBtn center-align" style="margin-top: 25px;">
        <button type="submit" class="btn waves-effect teal darken-2">
          <i class="material-icons left">save</i>Actualizar Usuario
        </button>
      </div>
    </form>
  </div>
</div>


<script type="module" src="../public/assets/js/usuarios/usuarios.js"></script>

