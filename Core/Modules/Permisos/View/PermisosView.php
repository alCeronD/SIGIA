<div class="container">
  <div class="contentPermisos">
    <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="titlePermisos menuTitle">
      <span id="textTitleAreas" class="textTitleSpan">Seguridad del sistema</span>
      <a href="<?php echo Router::createRoute('Dashboard', 'Dashboard', 'dashboard', false, 'dashboard'); ?>"
        class="close-btn"
        title="Volver al dashboard">&times;</a>
    </div>
    <div class="cards">
      <!-- roles -->
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_ROLES, CR_ROLES, 'mostrarRoles', false, CR_DASHBOARD_LOWER_CASE); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">group
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Roles</h5>
          <!-- <p>Listado de roles del sistema</p> -->
        </div>
      </div>
      <!-- roles Funciones -->
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_ROLES, 'RolesFunciones', 'mostrarFuncionesAssoc', false, CR_DASHBOARD_LOWER_CASE); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">person
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Funciones asociadas</h5>
          <!-- <p>Listado de funciones asociadas al rol</p> -->
        </div>
      </div>
      <!-- FUNCIONES -->
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_FUNCIONES, 'FuncionesDos', 'funcionesIndex', false, CR_DASHBOARD_LOWER_CASE); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">code
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Funciones</h5>
          <!-- <p>Gestión de modulos registrados en el sistema</p> -->

        </div>
      </div>
      <!-- Modulos -->
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_GESTION_MODULOS, CR_GESTION_MODULOS, 'modulosView', false, CR_DASHBOARD_LOWER_CASE); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">widgets
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Modulos</h5>
          <!-- <p>Gestión de modulos registrados en el sistema</p> -->
        </div>
      </div>
    </div>
  </div>
</div>