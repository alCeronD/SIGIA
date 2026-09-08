<!-- vista principal del modulo FUNCIONES, aca hay varias funciones adicionales. -->
<div class="container">
  <div class="contentPermisos">
    <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="titlePermisos menuTitle">
      <span id="textTitleAreas" class="textTitleSpan">Funciones</span>
      <a href="<?php echo Router::createRoute('Dashboard', 'Dashboard', 'dashboard', false, 'dashboard'); ?>"
        class="close-btn"
        title="Volver al dashboard">&times;</a>
    </div>
    <div class="cards">
      <?php RenderHelper::renderSecondView('Funciones'); ?>
    </div>
  </div>
</div>