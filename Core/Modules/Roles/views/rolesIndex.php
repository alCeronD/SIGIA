<!-- Vista principal del modulo roles -->
<div class="content">
  <div class="option-card  z-depth-1 div4">
    <div class="icons">
      <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_ROLES, CR_ROLES, 'mostrarRoles', false, CR_DASHBOARD_LOWER_CASE); ?>">
        <i class="material-icons small green-text text-darken-2 center-align">person
        </i>
      </a>
    </div>
    <div class="modalName">
      <h5>Roles</h5>
      <p>Listado de roles del sistema</p>
    </div>
  </div>
  <div class="option-card  z-depth-1 div4">
    <div class="icons">
      <a class="btnGetUrl" href="<?php echo Router::createRoute(CR_ROLES, RL_FILE_ROLES_FUNCIONES_CONTROLLER, 'mostrarFuncionesAssoc', false, CR_DASHBOARD_LOWER_CASE); ?>">
        <i class="material-icons small green-text text-darken-2 center-align">functions
        </i>
      </a>
    </div>
    <div class="modalName">
      <h5>Funciones asociadas</h5>
      <p>Listado de funciones asociadas a los roles</p>
    </div>
  </div>
</div>