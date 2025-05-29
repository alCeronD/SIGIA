
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
            <a href="<?php echo getUrl("login","login","logout"); ?>" class="footer-icon text-center">
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
              <a href="<?php echo getUrl("usuarios", "usuarios", "consultUser",false,'dashboard'); ?>" class="footer-icon text-center">
                Consultar usuario
              </a>
            </li>
            <!-- Crear usuario -->
            <li>
              <a href="<?php echo getUrl("usuarios", "usuarios", "userView",false,'dashboard'); ?>" class="footer-icon text-center">
                Crear usuario
              </a>
            </li>
          </ul>
        </li>
        <!-- Configuración -->
        <li>
          <a href="#">Configuración</a>
          <!-- Elementos internos del menú -->
          <ul class="verticalMenu">
            <li>
              <a href="dashboard.php?value=7">Areas</a>
            </li>
            <li>
              <a href="dashboard.php?value=8">Tipo documento</a>
            </li>
            <li>
              <a href="#">Marcas</a>
            </li>
            <li>
              <a href="#">Categorias</a>
            </li>
            <li>
                <a href="<?php echo getUrl("roles", "roles", "mostrarRoles",false,'dashboard'); ?>" class="footer-icon text-center">
                  Roles
              </a>
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
<?php if ($rol == 3): ?>
  <footer class="mac-footer d-flex justify-content-around align-items-center">
    <a href="#" class="footer-icon text-center">
      <i class="fas fa-home"></i><br><small>Inicio Instructor</small>
    </a>
    <a href="#" class="footer-icon text-center">
      <i class="fa-solid fa-circle-user"></i></i><br><small>Perfil</small>
    </a>
    <a href="#" class="footer-icon text-center">
      <i class="fas fa-cog"></i><br><small>Config</small>
    </a>
    <a href="<?php echo getUrl("login", "login", "logout"); ?>" class="footer-icon text-center">
      <i class="fas fa-sign-out-alt"></i><br><small>cerrar sesion</small>
    </a>
  </footer>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<<<<<<< HEAD

</html>
=======
</html>
>>>>>>> d99da80 (commit brahiam)
