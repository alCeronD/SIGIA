<!-- Estilos CSS PUROS integrados al archivo -->
<style>
  footer, #mainMenu {
    background-color: #2c5e42;
    padding: 10px 0;
    position: fixed;
    bottom: 0;
    width: 100%;
    z-index: 999;
    font-family: sans-serif;
    color: white;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
  }

  .mac-footer {
    display: flex;
    justify-content: space-around;
    align-items: center;
  }

  .footer-icon {
    color: white;
    text-decoration: none;
    font-size: 13px;
    padding: 5px;
  }

  .footer-icon small {
    display: block;
    font-size: 13px;
  }

  .footer-icon:hover {
    color: #88e0b5;
  }

  .horizontalMenu {
    display: flex;
    justify-content: space-around;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .horizontalMenu > li {
    position: relative;
  }

  .horizontalMenu > li > a {
    color: white;
    text-decoration: none;
    padding: 10px;
    display: block;
    font-size: 14px;
  }

  .horizontalMenu > li:hover > a {
    color: #88e0b5;
  }

  .verticalMenu {
    display: none;
    position: absolute;
    bottom: 100%;
    background-color: #3a6c52;
    padding: 5px 0;
    list-style: none;
    min-width: 160px;
    border-radius: 4px;
  }

  .verticalMenu li a {
    display: block;
    padding: 8px 12px;
    color: white;
    text-decoration: none;
    font-size: 13px;
  }

  .verticalMenu li a:hover {
    background-color: #4c8068;
  }

  .horizontalMenu > li:hover .verticalMenu {
    display: block;
  }
</style>

<!-- tendero -->
<?php if ($rol == 1): ?>
  <footer class="mac-footer d-flex justify-content-around align-items-center">
    <a href="#" class="footer-icon text-center">
      <i class="fas fa-home"></i><br><small>Inicio</small>
    </a>
    <a href="#" class="footer-icon text-center">
      <i class="fa-solid fa-circle-user"></i><br><small>Perfil almacenista</small>
    </a>
    <a href="#" class="footer-icon text-center">
      <i class="fas fa-cog"></i><br><small>Config</small>
    </a>
    <a href="<?php echo getUrl("login", "login", "logout"); ?>" class="footer-icon text-center">
      <i class="fas fa-sign-out-alt"></i><br><small>cerrar sesion</small>
    </a>
  </footer>
<?php endif; ?>

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
