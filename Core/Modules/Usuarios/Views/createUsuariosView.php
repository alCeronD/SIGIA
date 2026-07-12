<div class="container">
  <div class="contentCreateUsuarios">
    <?php include_once BASE_URL . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="titleUsuarios">
      <span id="textTitle" class="teal-text text-darken-4">Registrar usuario</span>
      <a href="<?= Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
    </div>
    <div class="registrarUsuario">
      <form id="formCreateUser">
        <div class="inputContent tipoDocumento input-field">
          <i class="material-icons prefix">badge</i>
          <select name="usu_tp_id" id="usu_tp_id" class="validate">
          </select>
          <label for="usu_tp_id">Tipo documento: <span class="red-text">*</span></label>
          <span class="helper-text" data-error="" data-success=""></span>
        </div>

        <div class="inputContent cedula input-field">
          <i class="material-icons prefix">badge</i>
          <input type="text" id="usu_docum" name="usu_docum" class="validate" required>
          <label for="usu_docum">Número de identificación: <span class="red-text">*</span></label>
          <span class="helper-text" data-error="" data-success=""></span>
        </div>

        <div class="inputContent rol input-field">
          <i class="material-icons prefix">admin_panel_settings</i>
          <select name="usr_rl_id" id="usr_rl_id" class="validate">
          </select>
          <label for="usr_rl_id">Rol: <span class="red-text">*</span></label>
          <span class="helper-text" data-error="" data-success=""></span>
        </div>

        <div class="inputContent nombres input-field">
          <i class="material-icons prefix">person</i>
          <input type="text" id="usu_nombres" name="usu_nombres" class="validate" required>
          <label for="usu_nombres">Nombres: <span class="red-text">*</span></label>
          <span class="helper-text" data-error="" data-success=""></span>
        </div>

        <div class="inputContent apellidos input-field">
          <i class="material-icons prefix">person_outline</i>
          <input type="text" id="usu_apellidos" name="usu_apellidos" class="validate" required>
          <label for="usu_apellidos">Apellidos: <span class="red-text">*</span></label>
          <span class="helper-text" data-error="" data-success=""></span>
        </div>

        <div class="inputContent telefono input-field">
          <i class="material-icons prefix">phone</i>
          <input type="tel" id="usu_telefono" name="usu_telefono" class="validate" required>
          <label for="usu_telefono">Teléfono: <span class="red-text">*</span></label>
          <span class="helper-text" data-error="" data-success=""></span>
        </div>

        <div class="inputContent password input-field">
          <i class="material-icons prefix">lock</i>
          <input type="password" id="usu_password" name="usu_password" class="validate" required>
          <label for="usu_password">Contraseña: <span class="red-text">*</span></label>
          <span class="helper-text" data-error="" data-success=""></span>
        </div>

        <div class="inputContent email input-field">
          <i class="material-icons prefix">email</i>
          <input type="email" id="usu_email" name="usu_email" class="validate" required>
          <label for="usu_email">Correo electrónico: <span class="red-text">*</span></label>
          <span class="helper-text" data-error="" data-success=""></span>
        </div>

        <div class="inputContent direccion input-field">
          <i class="material-icons prefix">location_on</i>
          <input type="text" id="usu_direccion" name="usu_direccion" class="validate">
          <label for="usu_direccion">Dirección:</label>
        </div>

        <div class="inputContent observaciones input-field">
          <i class="material-icons prefix">edit_note</i>
          <textarea name="usu_observacion" id="usu_observacion" class="materialize-textarea"></textarea>
          <label for="usu_observacion">Notas adicionales al usuario:</label>
        </div>

        <div class="inputBtn">
          <button type="submit" class="btn waves-effect waves-light btnInfo">
            <i class="Medium material-icons">save</i>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>