<!-- vista de auditorias de las acciones que ejecuto el usuario -->
<div class="container">
  <div class="contentAuditorias">
    <div class="headerContent">
      <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php';  ?>
      <div class="titleUsuarios menuTitle">
        <span id="textTitle">Auditoria de usuarios</span>
        <a href="<?= Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
      </div>
    </div>

  </div>
</div>