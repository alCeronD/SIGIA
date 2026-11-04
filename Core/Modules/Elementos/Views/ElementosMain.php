<!-- vista principal del modulo -->
<div class="container">
  <div class="contentElementos">
    <div class="headerContent">
      <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
      <div class="titleElementos menuTitle">
        <span id="textTitleElm" class="textTitleSpan">Modulo Elementos</span>
        <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false,  CR_DASHBOARD_LOWER_CASE); ?>"
          class="close-btn"
          title="Volver al dashboard">&times;</a>
      </div>
    </div>

    <!-- renderizar las diferentes opciones del modulo -->
    <div class="cards">
      <?php RenderHelper::renderSecondView(CR_ELEMENTOS); ?>
    </div>

  </div>

</div>