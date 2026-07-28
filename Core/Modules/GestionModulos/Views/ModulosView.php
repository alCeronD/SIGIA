<div class="container">
  <div class="contentModulos contentLayout">
    <?php include_once BASE_URL . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="titleModulos menuTitle">
      <span id="textTitleAreas" class="textTitleSpan"><?php echo GM_TITLE ?></span>
      <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>"
        class="close-btn"
        title="Volver al dashboard">&times;</a>
    </div>
    <div class="contentFormModulos">
      <div class="card z-depth-2">
        <div class="card-content">
          <p class="flow-text card-title"><?php echo GM_TITLE_CREAR_MODULO; ?></p>
          <form id="formModuloCreate" class="formLayout">
            <div class="input-field contentAreaNem">
              <input type="text" name="nombre_modulo" id="nombre_modulo" class="validate">
              <label for="nombre_modulo"><?php echo GM_WORDS_NOMBRE_MODULO; ?></label>
            </div>
            <div class="input-field contentIcono">
              <textarea name="icono" id="icono" class="materialize-textarea"></textarea>
              <label for="icono"><?php echo CR_ICONO; ?></label>
            </div>
            <div class="input-field contentDescript">
              <textarea name="descripcion" id="descripcion" class="materialize-textarea"></textarea>
              <label for="descripcion"><?php echo GM_DESCRIP_MODULO; ?></label>
            </div>

            <div class="contentSubmit">
              <button type="submit" id="btnAreaSend" class=" waves-effect waves-light btn"><i class="material-icons">send</i></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="contentTableModulos">
      <table id="tableModulos" class="tableConfig tblConfigModules">
        <thead id="tblHeaderModulos">
          <tr>
            <th id="id_m">Id</th>
            <th id="nombre_modulo">Nombre modulo</th>
            <th id="icono">Icono</th>
            <th id="descripcion">Descripcion</th>
            <th id="status_modulo">Estado</th>
            <th id="">Acciones</th>
          </tr>
        </thead>
        <tbody id="tbodyModulos">
        </tbody>
        <!-- se renderiza la paginacion -->
        <tfoot id="tFooterModulos"></tfoot>
      </table>
    </div>
  </div>

  <!-- modal -->
  <div id="modalEditModulo" class="modal modal-overlay">
    <!-- Modal content -->
    <div class="modalContentModulos modal-content .modal-closebtn">
      <div class="titleSection">
        <span id="modalTitle">Actualizar registro</span>
        <button type="button" class="closeModalBtn">
          <span class="close-modal">&times;</span>
        </button>
      </div>
      <div class="formUpdate">
        <form id="formModuloUpdate" class="formLayout">
          <input type="hidden" name="id_m" id="idModulo">
          <div class="input-field mNombreUpdate">
            <label for="nombre_modulo">Nombre:</label>
            <input type="text" name="nombre_modulo" id="nombreModuloUpdate">
          </div>
          <div class="input-fiel mIcono">
            <label for="icono">Icono:</label>
            <input type="text" name="icono" id="iconoModulo">
          </div>
          <div class="input-field mDescripcionModulo">
            <textarea name="descripcion" id="descripcionAreaUpdate" class="materialize-textarea"></textarea>
            <label for="descripcion">Descripción:</label>
          </div>
          <div class="mBtnUpdate">
            <button type="submit" id="btnAreaUpdate" class="btnSubmit waves-effect waves-light btn"><i class="material-icons">save</i></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="modalAsingModulo" class="modal modal-overlay"></div>
</div>