<div class="content">
  <div class="titleUsuarios">
    <span id="textTitle">Usuarios Registrados</span>
      <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
  </div>
  <div class="registrarUsuario">
    <form id="formSolicitudPrestamo" method="POST" action="<?php echo getUrl('usuarios','usuarios','createUser'); ?>">

      <div class="inputContent tipoDocumento">
        <label for="usu_tp_id" class="labelForm">Tipo documento:</label>
        <select name="usu_tp_id" id="usu_tp_id" class="inputForm" required>
          <option value="">Seleccione tipo de documento</option>
          <?php foreach ($rowTp as $tp): ?>
            <option value="<?php echo $tp['tp_id']; ?>"><?php echo htmlspecialchars($tp['tp_sigla'] . " - " . $tp['tp_nombre']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="inputContent cedula">
        <label for="usu_docum" class="labelForm">Documento:</label>
        <input type="text" class="inputForm" name="usu_docum" id="usu_docum" required placeholder="Número de documento...">
      </div>

      <div class="inputContent rol">
        <label for="rol_id" class="labelForm">Rol:</label>
        <select name="rol_id" id="rol_id" class="inputForm" required>
          <option value="">Seleccione un rol</option>
          <?php foreach ($roles as $roli): ?>
            <option value="<?php echo $roli['rl_id']; ?>"><?php echo htmlspecialchars($roli['rl_nombre']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="inputContent nombres">
        <label for="usu_nombres" class="labelForm">Nombres:</label>
        <input type="text" class="inputForm" name="usu_nombres" id="usu_nombres" required placeholder="Nombres...">
      </div>

      <div class="inputContent apellidos">
        <label for="usu_apellidos" class="labelForm">Apellidos:</label>
        <input type="text" class="inputForm" name="usu_apellidos" id="usu_apellidos" required placeholder="Apellidos...">
      </div>

      <div class="inputContent telefono">
        <label for="usu_telefono" class="labelForm">Teléfono:</label>
        <input type="tel" class="inputForm" name="usu_telefono" id="usu_telefono" required placeholder="Teléfono...">
      </div>

      <div class="inputContent password">
        <label for="usu_password" class="labelForm">Contraseña:</label>
        <input type="password" class="inputForm" name="usu_password" id="usu_password" required placeholder="Contraseña...">
      </div>

      <div class="inputContent email">
        <label for="usu_email" class="labelForm">Correo electrónico:</label>
        <input type="email" class="inputForm" name="usu_email" id="usu_email" required placeholder="Correo electrónico...">
      </div>

      <div class="inputContent direccion">
        <label for="usu_direccion" class="labelForm">Dirección:</label>
        <input type="text" class="inputForm" name="usu_direccion" id="usu_direccion" required placeholder="Dirección...">
      </div>

      <div class="inputContent observaciones">
        <label class="labelForm">Observaciones:</label>
        <textarea class="inputForm" name="observaciones" placeholder="Notas adicionales del usuario..."></textarea>
      </div>

      <div class="inputBtn">
        <button type="submit">Registrar</button>
      </div>

    </form>
  </div>
</div>
