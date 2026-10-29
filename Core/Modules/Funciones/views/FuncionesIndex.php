<!-- vista principal del modulo FUNCIONES, aca hay varias funciones adicionales. -->
<div class="container">
  <div class="contentPermisos">
    <div class="headerContent">
      <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
      <div class="titleFunciones menuTitle">
        <span id="textTitleFunciones" class="textTitleSpan">Funciones</span>
        <a href="<?php echo Router::createRoute('Dashboard', 'Dashboard', 'dashboard', false, 'dashboard'); ?>"
          class="close-btn"
          title="Volver al dashboard">&times;</a>
      </div>
    </div>

    <div class="cards">
      <?php RenderHelper::renderSecondView('Funciones'); ?>
    </div>
  </div>
</div>