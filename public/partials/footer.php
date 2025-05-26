<?php
if (isset($rol)) {
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
    $rol = $_SESSION['usuario']['rol_id'] ?? null;
  }

  ?>
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

  <footer class="mac-footer d-flex justify-content-around align-items-center">
          <a href="../app/dashboard.php" class="footer-icon text-center">
            <i class="fas fa-home"></i><br><small>Inicio</small>
          </a>
          <a href="<?php echo getUrl("usuarios","usuarios","consultUser"); ?>" class="footer-icon text-center">
            <i class="fa-solid fa-circle-user"></i></i><br><small>Consultar usuarios</small>
          </a>
          <a href="<?php echo getUrl("usuarios","usuarios","userView"); ?>" class="footer-icon text-center">
            <i class="fa-solid fa-user-plus"></i></i><br><small>Crear usuario</small>
          </a>
          <a href="<?php echo getUrl("roles","roles","mostrarRoles"); ?>" class="footer-icon text-center">
            <i class="fa-solid fa-users-gear"></i></i><br><small>Permisos - Rol</small>
          </a>
          <a href="#" class="footer-icon text-center">
            <i class="fas fa-cog"></i><br><small>Config</small>
          </a>
          <a href="<?php echo getUrl("login","login","logout"); ?>" class="footer-icon text-center">
            <i class="fas fa-sign-out-alt"></i><br><small>cerrar sesion</small>
          </a>
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
          <a href="<?php echo getUrl("login","login","logout"); ?>" class="footer-icon text-center">
            <i class="fas fa-sign-out-alt"></i><br><small>cerrar sesion</small>
          </a>
  </footer>
  <?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
