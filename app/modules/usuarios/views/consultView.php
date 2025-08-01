<div class="contentUsuarios">
  <div class="titleUsuarios">
    <span id="textTitle">Consultar Usuarios</span>
    <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
  </div>

  <div class="filtroUsuarios">
    <div class="input-field col s4">
      <select id="tipoFiltro" class="browser-default">
        <option value="">-- Filtro usuarios --</option>
        <option value="documento">Filtrar por Documento</option>
        <option value="nombre">Filtrar por Nombre</option>
        <option value="estado">Filtrar por Estado</option>
      </select>
    </div>

    <div class="input-field col s4" id="contenedorInputFiltro">
      <!-- Aquí se agregará dinámicamente el input/select -->
    </div>
  </div>

  <div class="table">
    <table id="tableConfig">
      <thead>
        <tr>
          <th>No documento</th>
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
              <td><?= htmlspecialchars($usuario['usu_docum']) ?></td>
              <td><?= htmlspecialchars($usuario['usu_nombres']) ?></td>
              <td><?= htmlspecialchars($usuario['usu_apellidos']) ?></td>
              <td><?= htmlspecialchars($usuario['rl_nombre']) ?></td>
              <td><?= htmlspecialchars($usuario['estado_usuario']) ?></td>
              <td>
                <div class="center-align">
                  <a href="#"
                    class="btnEdit btn btnEditarUsuario white-text waves-effect waves-red"
                    data-id="<?= $usuario['usu_id'] ?>"
                    data-nombres="<?= htmlspecialchars($usuario['usu_nombres']) ?>"
                    data-apellidos="<?= htmlspecialchars($usuario['usu_apellidos']) ?>"
                    data-email="<?= htmlspecialchars($usuario['usu_email']) ?>"
                    data-telefono="<?= htmlspecialchars($usuario['usu_telefono']) ?>"
                    data-documento="<?= htmlspecialchars($usuario['usu_docum']) ?>"
                    data-direccion="<?= htmlspecialchars($usuario['usu_direccion'] ?? '') ?>"
                    data-rol="<?= htmlspecialchars($usuario['rolIdUser']) ?>">
                    <i class="material-icons">edit</i>
                  </a>
                  <a href="<?= getUrl('usuarios', 'usuarios', 'cambiarEstadoUsuario', ['usu_id' => $usuario['usu_id']], 'dashboard') ?>"
                    class="btn btnInvalida white-text waves-effect waves-dark"
                    onclick="return confirm('¿Estás seguro de que deseas cambiar el estado del usuario?');">
                    <i class="material-icons">autorenew</i>
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
    <h5 class="teal-text text-darken-3">Editar Informacion Usuario</h5>

    <form method="POST" action="<?= getUrl('usuarios', 'usuarios', 'updateUserJSON', false, 'dashboard') ?>" id="formUpdateUser">
      <input type="hidden" name="usu_id" id="usu_id">

      <div class="input-field docum">
        <label for="usu_docum" class="active">Documento</label>
        <input type="text" name="usu_docum" id="usu_docum" disabled>
      </div>

      <div class="input-field nombres">
        <label for="usu_nombres" class="active">Nombres</label>
        <input type="text" name="usu_nombres" id="usu_nombres" required>
      </div>

      <div class="input-field apellidos">
        <label for="usu_apellidos" class="active">Apellidos</label>
        <input type="text" name="usu_apellidos" id="usu_apellidos" required>
      </div>

      <div class="input-field email">
        <label for="usu_email" class="active">Correo</label>
        <input type="email" name="usu_email" id="usu_email">
      </div>

      <div class="input-field telefono">
        <label for="usu_telefono" class="active">Teléfono</label>
        <input type="text" name="usu_telefono" id="usu_telefono" required>
      </div>

      <div class="input-field direccion">
        <label for="usu_direccion" class="active">Dirección</label>
        <input type="text" name="usu_direccion" id="usu_direccion" required>
      </div>

      <div class="input-field password">
        <label for="usu_password" class="active">Nueva contraseña (opcional)</label>
        <input type="password" name="usu_password" id="usu_password">
      </div>

      <div class="input-field rol">
        <label for="rol_id" class="active">Rol</label>
        <select  name="rol_id" id="rol_id" class="browser-default" >
          <option value="">Seleccione un rol</option>
          <?php foreach ($resultado as $rol): ?>
            <option value="<?= $rol['rl_id'] ?>"><?= htmlspecialchars($rol['rl_nombre']) ?></option>
          <?php  endforeach; ?>
        </select>
      </div>

      <div class="inputBtn btn-update">
        <button type="submit" class="btn  waves-effect  btnInfo">
          <i class="material-icons">save</i>
        </button>
      </div>
    </form>

  </div>
</div>


<script type="module" src="../public/assets/js/usuarios/usuarios.js"></script>