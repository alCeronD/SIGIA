<script src="/proyecto_sigia/public/assets/libraries/materialize/js/materialize.min.js"></script>

<?php if ($rol == 2 || $rol == 4): ?>
  <div class="fixed-action-btn direction-top">
    <a class="btn-floating btn-large teal darken-2 tooltipped" data-position="left" data-tooltip="Menú principal">
      <i class="material-icons">menu</i>
    </a>
    <ul>
      <!-- Inicio -->
      <li>
        <a href="/proyecto_sigia/app/dashboard.php" class="btn-floating blue " data-tooltip="Inicio">
          <i class="material-icons">home</i>
        </a>
      </li>

      <?php if ($rol == 2): ?>
        <!-- Admin: Usuarios -->
        <li>
          <a class="btn-floating green  submenu-trigger" data-tooltip="Usuarios" data-submenu="submenu-usuarios">
            <i class="material-icons">person</i>
          </a>
        </li>
        <!-- Admin: Préstamos -->
        <li>
          <a class="btn-floating orange  submenu-trigger" data-tooltip="Préstamos" data-submenu="submenu-prestamos">
            <i class="material-icons">assignment</i>
          </a>
        </li>
        <!-- Admin: Elementos -->
        <li>
          <a class="btn-floating purple  submenu-trigger" data-tooltip="Elementos" data-submenu="submenu-elementos">
            <i class="material-icons">local_see</i>
          </a>
        </li>
        <!-- Admin: Configuración -->
        <li>
          <a class="btn-floating teal  submenu-trigger" data-tooltip="Configuración" data-submenu="submenu-config">
            <i class="material-icons">settings</i>
          </a>
        </li>
      <?php endif; ?>

      <?php if ($rol == 4): ?>
        <!-- Instructor: Préstamos -->
        <li>
          <a class="btn-floating orange  submenu-trigger" data-tooltip="Préstamos" data-submenu="submenu-instructor">
            <i class="material-icons">assignment</i>
          </a>
        </li>
      <?php endif; ?>

      <!-- Reportes -->
    <li>
      <a class="btn-floating cyan  submenu-trigger" data-tooltip="Reportes" data-submenu="submenu-reportes">
        <i class="material-icons">bar_chart</i>
      </a>
    </li>


      <!-- Cerrar sesión -->
      <li>
        <a href="<?php echo getUrl('login','login','logout'); ?>" class="btn-floating red " data-tooltip="Cerrar sesión">
          <i class="material-icons">exit_to_app</i>
        </a>
      </li>
    </ul>
  </div>

  <!-- Condi para ingresar a submenu -->
  <?php if ($rol == 2): ?>
    <div id="submenu-usuarios" class="submenu hidden">
      <a href="<?php echo getUrl('usuarios','usuarios','consultUser',false,'dashboard'); ?>">Consultar usuario</a>
      <a href="<?php echo getUrl('usuarios','usuarios','userView',false,'dashboard'); ?>">Crear usuario</a>
      <a href="<?php echo getUrl('usuarios','usuarios','userPermView',false,'dashboard'); ?>">Permisos roles</a>
    </div>
    <div id="submenu-prestamos" class="submenu hidden">
      <a href="<?php echo getUrl('reservaPrestamos','reserva','reservaView',false,'dashboard'); ?>">Reserva</a>
      <a href="<?php echo getUrl('reservaPrestamos','reserva','consultaReservaView',false,'dashboard'); ?>">Ver préstamos</a>
    </div>
    <div id="submenu-elementos" class="submenu hidden">
      <a href="<?php echo getUrl('elementos','elementos','renderViewElements',false,'dashboard'); ?>">Ver elementos</a>
    </div>
    <div id="submenu-config" class="submenu hidden">
      <a href="<?php echo getUrl('configModules','configModules','renderViewArea',false,'dashboard'); ?>">Áreas</a>
      <a href="<?php echo getUrl('configModules','configModules','renderViewTp',false,'dashboard'); ?>">Tipo documento</a>
      <a href="<?php echo getUrl('configModules','configModules','renderViewMarca',false,'dashboard'); ?>">Marcas</a>
      <a href="<?php echo getUrl('roles','roles','mostrarRoles',false,'dashboard'); ?>">Roles</a>
      <a href="<?php echo getUrl('categorias','categorias','consultCategoriasView',false,'dashboard'); ?>">Categorías</a>
    </div>
    <div id="submenu-reportes" class="submenu hidden">
      <a href="<?php echo getUrl('reportes', 'reportes', 'genReporteView', false, 'dashboard'); ?>">Reporte general</a>
    </div>

  <?php endif; ?>

  <?php if ($rol == 4): ?>
    <div id="submenu-instructor" class="submenu hidden">
      <a href="<?php echo getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamosView',false,'dashboard'); ?>">Solicitar préstamo</a>
      <a href="<?php echo getUrl('solicitudPrestamos','solicitudPrestamos','consultarPrestamosView',false,'dashboard'); ?>">Ver préstamos</a>
    </div>
  <?php endif; ?>
<?php endif; ?>

<style>
  .submenu {
  position: absolute;
  background: white;
  padding: 12px 16px;
  border-radius: 6px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  display: none;
  flex-direction: column;
  z-index: 999;
  min-width: 180px;
  max-height: 190px;       
  overflow-y: auto;       
}
.submenu a {
  padding: 6px 10px;
  color: #333;
  text-decoration: none;
  border-radius: 4px;
}
.submenu a:hover {
  background: #f2f2f2;
}
.submenu.visible {
  display: flex;
}
.hidden {
  display: none;
}

</style>


<script>
  document.addEventListener('DOMContentLoaded', () => {
    M.FloatingActionButton.init(document.querySelectorAll('.fixed-action-btn'), {
      direction: 'top',
      hoverEnabled: false
    });
    // M.Tooltip.init(document.querySelectorAll('.tooltipped'));

    const triggers = document.querySelectorAll('.submenu-trigger');
    triggers.forEach(trigger => {
      const submenuId = trigger.dataset.submenu;
      const submenu = document.getElementById(submenuId);

      trigger.addEventListener('mouseenter', () => {
        document.querySelectorAll('.submenu').forEach(el => el.classList.remove('visible'));

        const rect = trigger.getBoundingClientRect();
        submenu.style.top = `${rect.top + window.scrollY}px`;
        submenu.style.left = `${rect.left - submenu.offsetWidth - 190}px`; //Ajusto menu <<
        submenu.classList.add('visible');
      });

      trigger.addEventListener('mouseleave', () => {
        setTimeout(() => {
          if (!submenu.matches(':hover') && !trigger.matches(':hover')) {
            submenu.classList.remove('visible');
          }
        }, 200);
      });


      submenu.addEventListener('mouseleave', () => submenu.classList.remove('visible'));
    });

    document.addEventListener('click', e => {
      if (!e.target.closest('.fixed-action-btn') && !e.target.closest('.submenu')) {
        document.querySelectorAll('.submenu').forEach(el => el.classList.remove('visible'));
      }
    });
  });
</script>

</body>
</html>
