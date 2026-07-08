<div class="container">
  <div class="content">
    <!-- rastro de miga. -->
    <?php include_once BASE_URL . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="cards">
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'createUserView', false, CR_DASHBOARD_LOWER_CASE); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">group_add
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Crear Usuario</h5>
          <p></p>
        </div>
      </div>
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'actualizarDatosView', false, CR_DASHBOARD_LOWER_CASE); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">account_box
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Mis datos</h5>
          <p>Visualizar mis datos registrados en el sistema</p>
        </div>
      </div>
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'actualizarDatosView', false, CR_DASHBOARD_LOWER_CASE); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">group
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Listado de usuarios</h5>
          <p>Visualizar usuarios registrados en la base de datos</p>
        </div>
      </div>
      <div class="option-card  z-depth-1 div4">
        <div class="icons">
          <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'actualizarDatosView', false, CR_DASHBOARD_LOWER_CASE); ?>">
            <i class="material-icons small green-text text-darken-2 center-align">functions
            </i>
          </a>
        </div>
        <div class="modalName">
          <h5>Auditorias</h5>
          <p>Auditorias de usuario</p>
        </div>
      </div>
    </div>
  </div>
</div>