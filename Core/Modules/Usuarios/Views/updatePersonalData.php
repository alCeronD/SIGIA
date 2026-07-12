<div class="container">
  <div class="contentUpdatePersonalData">
    <?php include_once BASE_URL . '/../../public/partials/breadCrumbs.php'; ?>

    <div class="titleUsuarios">
      <span id="textTitle" class="teal-text text-darken-4">Gestión de información personal.</span>
      <a href="<?= Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
    </div>
    <div class="formUpdatePersonalData">
      <div class="form">
        <form id="formUpdateUserView" data-url="<?php echo Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'savePersonalData', false, CR_DASHBOARD_LOWER_CASE); ?>">
          <input type="hidden" name="usu_id" value="">
          <div class="row">
            <div class="input-field col s12 m6">
              <i class="material-icons prefix">badge</i>
              <input id="usu_docum" type="text" name="usu_docum" value="" readonly>
              <label for="usu_docum" class="active">Documento</label>
            </div>

            <div class="input-field col s12 m6">
              <i class="material-icons prefix">person</i>
              <input id="usu_nombres" type="text" name="usu_nombres" value="">
              <label for="usu_nombres" class="active">Nombres *</label>
            </div>

            <div class="input-field col s12 m6">
              <i class="material-icons prefix">person_outline</i>
              <input id="usu_apellidos" type="text" name="usu_apellidos" value="">
              <label for="usu_apellidos" class="active">Apellidos *</label>
            </div>

            <div class="input-field col s12 m6">
              <i class="material-icons prefix">email</i>
              <input id="usu_email" type="email" name="usu_email" value="">
              <label for="usu_email" class="active">Correo electrónico *</label>
            </div>

            <div class="input-field col s12 m6">
              <i class="material-icons prefix">location_on</i>
              <input id="usu_direccion" type="text" name="usu_direccion" value="">
              <label for="usu_direccion" class="active">Dirección *</label>
            </div>


            <div class="input-field col s12 m6">
              <i class="material-icons prefix">phone</i>
              <input id="usu_telefono" type="text" name="usu_telefono" value="">
              <label for="usu_telefono" class="active">Teléfono *</label>
            </div>

            <div class="input-field col s12 m12 ">
              <i class="material-icons prefix">edit_note</i>
              <textarea name="usu_observacion" id="usu_observacion" class="materialize-textarea"></textarea>
              <label for="usu_observacion">Notas adicionales al usuario:</label>
            </div>
          </div>

          <div class="center-align">

            <button type="submit" class="btn waves-effect waves-light btnInfo">
              <i class="Medium material-icons">save</i>
            </button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>