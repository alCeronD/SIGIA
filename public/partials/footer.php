
<!-- tendero -->
<?php if ($rol == 1): ?>
  <footer class="mac-footer d-flex justify-content-around align-items-center">
    <a href="#" class="footer-icon text-center">
      <i class="fas fa-home"></i><br><small>Inicio</small>
    </a>
    <a href="#" class="footer-icon text-center">
      <i class="fa-solid fa-circle-user"></i></i><br><small>Perfil almacenista</small>
    </a>
    <a href="#" class="footer-icon text-center">
      <i class="fas fa-cog"></i><br><small>Config</small>
    </a>
    <a href="<?php echo getUrl("login", "login", "logout"); ?>" class="footer-icon text-center">
      <i class="fas fa-sign-out-alt"></i><br><small>cerrar sesion</small>
    </a>
  </footer>
<?php endif; ?>

<!-- admin     -->
<?php if ($rol == 2): ?>

  <footer class="">
    <nav id="mainMenu">
      <ul class="horizontalMenu">
        <!-- Inicio -->
        <li>
          <a href="../app/dashboard.php" class="footer-icon text-center">
            Inicio
          </a>
        </li>
        <!-- Modulo usuarios -->
        <li>
          <a href="#" id="">Usuarios</a>
          <ul class="verticalMenu">
            <!-- Consultar usuario -->
            <li>
              <a href="<?php echo getUrl("usuarios", "usuarios", "consultUser", false, 'dashboard'); ?>" class="footer-icon text-center">
                Consultar usuario
              </a>
            </li>
            <!-- Crear usuario -->
            <li>
              <a href="<?php echo getUrl("usuarios", "usuarios", "userView", false, 'dashboard'); ?>" class="footer-icon text-center">
                Crear usuario
              </a>
            </li>
          </ul>
        </li>
        <!-- Modulo prestamos -->
        <li>
          <a href="#">Prestamos</a>
          <ul class="verticalMenu">
            <li>
              <a href="<?php echo getUrl("reservaPrestamos", "reserva", "reservaView", false, 'dashboard'); ?>">Reserva</a>
            </li>
            <li>
              <a href="<?php echo getUrl("configModules", "configModules", "renderViewArea", false, 'dashboard'); ?>">Ver prestamos</a>
            </li>
          </ul>
        </li>
        <!-- Modulo elementos -->
         <li>
          <a href="#">Elementos</a>
          <ul class="verticalMenu">
            <li><a href="#">ver Elementos</a></li>
            <li><a href="#">Registrar elementos</a></li>
          </ul>
         </li>
        <!-- Configuración -->
        <li>
          <a href="#">Configuración</a>
          <!-- Elementos internos del menú -->
          <ul class="verticalMenu">
            <li>
              <a href="<?php echo getUrl("configModules", "configModules", "renderViewArea", false, 'dashboard'); ?>">Areas</a>
            </li>
            <li>
              <a href="<?php echo getUrl("configModules", "configModules", "renderViewTp", false, 'dashboard'); ?>">Tipo documento</a>
            </li>
            <li>
              <a href="<?php echo getUrl('configModules','configModules','renderViewMarca',false,'dashboard'); ?>">Marcas</a>
            </li>
            <li>
              <a href="<?php echo getUrl("roles", "roles", "mostrarRoles", false, 'dashboard'); ?>" class="footer-icon text-center">
                Roles
              </a>
            </li>
            <li>
              <a href="<?php echo getUrl("categorias", "categorias", "consultCategoriasView",false,'dashboard'); ?>">Categorias</a>
            </li>
            
          </ul>
        </li>
        <!-- Cerrar sesión -->
        <li>
          <a href="<?php echo getUrl("login", "login", "logout"); ?>" class="footer-icon text-center">Cerrar sesión</a>
        </li>
      </ul>
    </nav>
  </footer>
<?php endif; ?>

<!-- Instructor -->
<?php if ($rol == 4): ?>
  <footer class="">
    <nav id="mainMenu">
      <ul class="horizontalMenu">
        <!-- Inicio -->
        <li>
          <a href="../app/dashboard.php" class="footer-icon text-center">
            Inicio
          </a>
        </li>
        <!-- Modulo prestamos -->
        <li>
          <a href="#">Prestamos</a>
          <ul class="verticalMenu">
            <li>
              <a href="<?php echo getUrl("solicitudPrestamos", "solicitudPrestamos", "registrarPrestamosView", false, 'dashboard'); ?>">Solicitar Prestamo</a>
            </li>
            <li>
              <a href="<?php echo getUrl("solicitudPrestamos", "solicitudPrestamos", "consultarPrestamoViews", false, 'dashboard'); ?>">Ver prestamos</a>
            </li>
          </ul>
        </li>
        <!-- Cerrar sesión -->
        <li>
          <a href="<?php echo getUrl("login", "login", "logout"); ?>" class="footer-icon text-center">Cerrar sesión</a>
        </li>
      </ul>
    </nav>
  </footer>
<?php endif; ?>


<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>