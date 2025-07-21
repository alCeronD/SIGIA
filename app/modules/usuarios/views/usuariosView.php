<div class="content">
  <div class="titleUsuarios">
    <span id="textTitle" class="teal-text text-darken-4">Registrar usuario</span>
    <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close btn-flat red-text" title="Volver al dashboard">&times;</a>
  </div>

  <div class="registrarUsuario">
    <form id="formSolicitudPrestamo">
  <div class="inputContent tipoDocumento input-field">
    <label for="usu_tp_id">Tipo documento: <span class="red-text">*</span></label>
    <select name="usu_tp_id" id="usu_tp_id" required>
      <option value="">Seleccione tipo de documento</option>
      <?php foreach ($rowTp as $tp): ?>
        <option value="<?= $tp['tp_id']; ?>"><?= htmlspecialchars($tp['tp_sigla'] . " - " . $tp['tp_nombre']); ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="inputContent cedula input-field">
    <input type="text" id="usu_docum" name="usu_docum" class="validate" required>
    <label for="usu_docum">Número de identificación: <span class="red-text">*</span></label>
  </div>

  <div class="inputContent rol input-field">
    <label for="rol_id">Rol: <span class="red-text">*</span></label>
    <select name="rol_id" id="rol_id" required>
      <option value="">Seleccione un rol</option>
      <?php foreach ($resultado as $roli): ?>
        <option value="<?= $roli['rl_id']; ?>"><?= htmlspecialchars($roli['rl_nombre']); ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="inputContent nombres input-field">
    <input type="text" id="usu_nombres" name="usu_nombres" class="validate" required>
    <label for="usu_nombres">Nombres: <span class="red-text">*</span></label>
  </div>

  <div class="inputContent apellidos input-field">
    <input type="text" id="usu_apellidos" name="usu_apellidos" class="validate" required>
    <label for="usu_apellidos">Apellidos: <span class="red-text">*</span></label>
  </div>

  <div class="inputContent telefono input-field">
    <input type="tel" id="usu_telefono" name="usu_telefono" class="validate" required>
    <label for="usu_telefono">Teléfono: <span class="red-text">*</span></label>
  </div>

  <div class="inputContent password input-field">
    <input type="password" id="usu_password" name="usu_password" class="validate" required>
    <label for="usu_password">Contraseña: <span class="red-text">*</span></label>
  </div>

  <div class="inputContent email input-field">
    <input type="email" id="usu_email" name="usu_email" class="validate" required>
    <label for="usu_email">Correo electrónico: <span class="red-text">*</span></label>
  </div>

  <div class="inputContent direccion input-field">
    <input type="text" id="usu_direccion" name="usu_direccion" class="validate">
    <label for="usu_direccion">Dirección:</label>
  </div>

  <div class="inputContent observaciones input-field">
    <textarea name="usu_observacion" id="observaciones" class="materialize-textarea"></textarea>
    <label for="observaciones">Notas adicionales al usuario:</label>
  </div>

  <div class="inputBtn">
    <button type="submit" class="btn waves-effect waves-light btnInfo">
      <i class="Medium material-icons">save</i>
    </button>
  </div>
</form>
  </div>
</div>

<script type="module" src="../public/assets/js/main.js"></script>
<script type="module" src="../public/assets/js/usuarios/usuarios.js"></script>