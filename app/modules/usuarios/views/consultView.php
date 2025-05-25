<?php
include_once '../proyecto_sigia/public/partials/header.php';
?>

<main class="flex-grow-1 d-flex align-items-center justify-content-center">
  <div class="container text-center">
    <div class="w-100 mx-auto text-start">
      <h2 class="mb-4 text-center">Usuarios Registrados</h2>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-dark text-center">
            <tr>
              <th>Nombres</th>
              <th>Apellidos</th>
              <th>Rol</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($usuarios)): ?>
              <?php foreach ($usuarios as $usuario): ?>
                <tr class="text-center">
                  <td><?= htmlspecialchars($usuario['usu_nombres']) ?></td>
                  <td><?= htmlspecialchars($usuario['usu_apellidos']) ?></td>
                  <td><?= htmlspecialchars($usuario['rl_nombre']) ?></td>
                  <td>
                    <a href="<?= getUrl('usuarios', 'usuarios', 'updateUser') ?>" class="btn btn-sm btn-warning me-1">
                      <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a href="<?= getUrl('usuarios', 'usuarios', 'delete') ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                      <i class="bi bi-trash"></i> Eliminar
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center">No hay usuarios registrados.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<?php
include_once '../proyecto_sigia/public/partials/footer.php';
?>
