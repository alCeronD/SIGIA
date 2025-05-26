<?php 
$rol = $_SESSION['usuario']['rol_id'];
$nombre = $_SESSION['usuario']['nombre'];

?>


<main class="flex-grow-1 d-flex align-items-center justify-content-center">
  <div class="container text-center">
    <h1>Bienvenido, <?php echo $nombre; ?></h1>
    <?php if ($rol == 1):  ?>
        <p>Contenido para Almacenista</p>
    <?php elseif ($rol == 2):  ?>
        <p>Panel de administracion</p>
    <?php elseif ($rol == 4):  ?>
        <p>Vista para instructor</p>
    <?php else: ?>
        <p>No tienes permiso.</p>
    <?php endif; ?>
  </div>
</main>
