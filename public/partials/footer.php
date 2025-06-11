

<!-- admin -->
<?php if ($rol == 2): ?>
  <footer>
    <nav id="mainMenu">
      <ul class="horizontalMenu">
        <!-- Inicio -->
        <li>
          <a href="../app/dashboard.php" class="footer-icon text-center">Inicio</a>
        </li>
        <!-- Modulo usuarios -->
        <li>
          <a href="#">Usuarios</a>
          <ul class="verticalMenu">
            <!-- Consultar usuario -->
            <li>
              <a href="<?php echo getUrl("usuarios", "usuarios", "consultUser", false, 'dashboard'); ?>">Consultar usuario</a>
            </li>
            <!-- Crear usuario -->
            <li>
              <a href="<?php echo getUrl("usuarios", "usuarios", "userView", false, 'dashboard'); ?>">Crear usuario</a>
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
            <li><a href="<?php echo getUrl("elementos", "elementos", "mostrarElementos", false, 'dashboard'); ?>">ver Elementos</a></li>
            <li><a href="#">Registrar elementos</a></li>
          </ul>
        </li>
        <!-- Configuración -->
        <li>
          <a href="#">Configuración</a>
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
              <a href="<?php echo getUrl("roles", "roles", "mostrarRoles", false, 'dashboard'); ?>" class="footer-icon text-center">Roles</a>
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
  <footer>
    <nav id="mainMenu">
      <ul class="horizontalMenu">
        <!-- Inicio -->
        <li>
          <a href="../app/dashboard.php" class="footer-icon text-center">Inicio</a>
        </li>
        <!-- Modulo prestamos -->
        <li>
          <a href="#">Prestamos</a>
          <ul class="verticalMenu">
            <li>
              <a href="<?php echo getUrl("solicitudPrestamos", "solicitudPrestamos", "registrarPrestamosView", false, 'dashboard'); ?>">Solicitar Prestamo</a>
            </li>
            <li>
              <a href="<?php echo getUrl("solicitudPrestamos", "solicitudPrestamos", "consultarPrestamosView", false, 'dashboard'); ?>">Ver prestamos</a>
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

</body>
</html>
