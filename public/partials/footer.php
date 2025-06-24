
<script src="../public/assets/libraries/materialize/js/materialize.min.js"></script>

<!-- admin -->
 <?php if ($rol == 2): ?>
  <footer class="footer-nav">
    <nav id="mainMenu">
      <ul class="horizontalMenu">
        <li>
          <a href="../app/dashboard.php">Inicio</a>
        </li>
        <li>
          <a href="#">Usuarios <span class="arrow-down">▼</span></a>
          <ul class="verticalMenu">
            <li><a href="<?php echo getUrl("usuarios", "usuarios", "consultUser", false, 'dashboard'); ?>">Consultar usuario</a></li>
            <li><a href="<?php echo getUrl("usuarios", "usuarios", "userView", false, 'dashboard'); ?>">Crear usuario</a></li>
          </ul>
        </li>
        <li>
          <a href="#">Préstamos <span class="arrow-down">▼</span></a>
          <ul class="verticalMenu">
            <li><a href="<?php echo getUrl("reservaPrestamos", "reserva", "reservaView", false, 'dashboard'); ?>">Reserva</a></li>
            <li><a href="<?php echo getUrl("reservaPrestamos", "reserva", "consultaReservaView", false, 'dashboard'); ?>">Ver préstamos</a></li>
          </ul>
        </li>
        <li>
          <a href="#">Elementos <span class="arrow-down">▼</span></a>
          <ul class="verticalMenu">
            <li><a href="<?php echo getUrl("elementos", "elementos", "mostrarElementos", false, 'dashboard'); ?>">Ver elementos</a></li>
          </ul>
        </li>
        <li>
          <a href="#">Configuración <span class="arrow-down">▼</span></a>
          <ul class="verticalMenu">
            <li><a href="<?php echo getUrl("configModules", "configModules", "renderViewArea", false, 'dashboard'); ?>">Áreas</a></li>
            <li><a href="<?php echo getUrl("configModules", "configModules", "renderViewTp", false, 'dashboard'); ?>">Tipo documento</a></li>
            <li><a href="<?php echo getUrl('configModules','configModules','renderViewMarca',false,'dashboard'); ?>">Marcas</a></li>
            <li><a href="<?php echo getUrl("roles", "roles", "mostrarRoles", false, 'dashboard'); ?>">Roles</a></li>
            <li><a href="<?php echo getUrl("categorias", "categorias", "consultCategoriasView",false,'dashboard'); ?>">Categorías</a></li>
          </ul>
        </li>
        <li>
          <a href="<?php echo getUrl("login", "login", "logout"); ?>">Cerrar sesión</a>
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
