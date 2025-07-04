<div class="content">
  <div class="titleUsuarios">
    <span id="textTitle" class="teal-text text-darken-4">Registrar usuario</span>
    <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close btn-flat red-text" title="Volver al dashboard">&times;</a>
  </div>

  <div class="registrarUsuario">
    <form id="formSolicitudPrestamo" method="POST" action="<?= getUrl('usuarios','usuarios','createUser'); ?>">
      <div class="inputContent tipoDocumento input-field">
        <select name="usu_tp_id" id="usu_tp_id" class="" required>
          <label for="usu_tp_id">Tipo documento:</label>
          <option value="">Seleccione tipo de documento</option>
          <?php foreach ($rowTp as $tp): ?>
            <option class="input-field" value="<?= $tp['tp_id']; ?>"><?= htmlspecialchars($tp['tp_sigla'] . " - " . $tp['tp_nombre']); ?></option>
            <?php endforeach; ?>
          </select>
      </div>
      <div class="inputContent cedula input-field">
        <input type="text" id="usu_docum" name="usu_docum" class="validate" required>
        <label for="usu_docum">Documento:</label>
      </div>

      <div class="inputContent rol ">
        <label for="rol_id">Rol:</label>
          <select name="rol_id" id="rol_id" class="" required>
          <option value="">Seleccione un rol</option>
          <?php foreach ($roles as $roli): ?>
            <option class="input-field" value="<?= $roli['rl_id']; ?>"><?= htmlspecialchars($roli['rl_nombre']); ?></option>
          <?php endforeach; ?>
        </select>
        </div>
      <div class="inputContent nombres input-field">
        <input type="text" id="usu_nombres" name="usu_nombres" class="validate" required >
        <label for="usu_nombres">Nombres:</label>
      </div>

      <div class="inputContent apellidos input-field">
        <input type="text" id="usu_apellidos" name="usu_apellidos" class="validate" required >
        <label for="usu_apellidos">Apellidos:</label>
      </div>

      <div class="inputContent telefono input-field">
        <input type="tel" id="usu_telefono" name="usu_telefono" class="validate" required >
        <label for="usu_telefono">Teléfono:</label>
      </div>

      <div class="inputContent password input-field">
        <input type="password" id="usu_password" name="usu_password" class="validate" required >
        <label for="usu_password">Contraseña:</label>
      </div>

      <div class="inputContent email input-field">
        <input type="email" id="usu_email" name="usu_email" class="validate" required >
        <label for="usu_email">Correo electrónico:</label>
      </div>

      <div class="inputContent direccion input-field">
        <input type="text" id="usu_direccion" name="usu_direccion" class="validate" required >
        <label for="usu_direccion">Dirección:</label>
      </div>

      <div class="inputContent observaciones input-field">
        <textarea name="observaciones" id="observaciones" class="materialize-textarea"> </textarea>
        <label for="observaciones">Notas adicionales al usuario:</label>
      </div>


      <div class="inputBtn">
        <button type="submit" class="btn waves-effect waves-light teal darken-3"> <i class="Medium material-icons">save</i></button>
      </div>

    </form>
  </div>
</div>

<script type="module" src="../public/assets/js/main.js"></script>
<script type="module" src="../public/assets/js/usuarios/usuarios.js"></script>