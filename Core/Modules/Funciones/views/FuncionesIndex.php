<!-- vista principal del modulo FUNCIONES, aca hay varias funciones adicionales. -->
<div class="container">
  <div class="contentPermisos">
    <?php include_once BASE_URL . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="titlePermisos menuTitle">
      <span id="textTitleAreas" class="textTitleSpan">Funciones</span>
      <a href="<?php echo Router::createRoute('Dashboard', 'Dashboard', 'dashboard', false, 'dashboard'); ?>"
        class="close-btn"
        title="Volver al dashboard">&times;</a>
    </div>
    <div class="cards">
      <!-- roles -->
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">group
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Funciones Registradas</h5>
          <p>Listado de Funciones registradas en el sistema</p>
        </div>
      </div>
      <!-- roles Funciones -->
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">person
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Funciones asociadas</h5>
          <p>Listado de funciones asociadas al rol</p>
        </div>
      </div>


    </div>
  </div>
</div>