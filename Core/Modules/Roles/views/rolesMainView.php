<!-- vista principal del modulo de roles -->
<div class="container">
  <div class="headerContent">
    <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="titleRoles menuTitle">
      <span id="textTitleAreas" class="textTitleSpan">Modulo roles</span>
      <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>"
        class="close-btn"
        title="Volver al dashboard">&times;</a>
    </div>
  </div>

  <div class="cards">
    <!-- renderizamos las opciones del menu -->
    <?php RenderHelper::renderSecondView('Roles'); ?>
  </div>
</div>