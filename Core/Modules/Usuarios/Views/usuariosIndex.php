<div class="container">
  <div class="contentUsuariosIndex">
    <div class="headerContent">
      <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php';  ?>
      <div class="titleUsuarios menuTitle">
        <span id="textTitle">Modulo usuarios</span>
        <a href="<?= Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
      </div>
    </div>
    <?php
    include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="cards">
      <?php RenderHelper::renderSecondView(CR_USUARIOS); ?>
    </div>
  </div>
</div>