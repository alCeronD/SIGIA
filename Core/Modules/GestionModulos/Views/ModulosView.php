<div class="container">
  <div class="contentModulos">
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
          <form id="formModulos" class="formLayout">
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
  </div>
</div>