<style>
  .consulta-container {
    width: 100%;
    background: rgb(240, 248, 255);
  }
</style>

    <div class="consulta-container">
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
                    <a href="<?= getUrl('usuarios', 'usuarios', 'updateUserView',['usu_id'=>$usuario['usu_id']]) ?>" class="btn btn-sm btn-warning me-1">
                      <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a href="<?= getUrl('usuarios', 'usuarios', 'cambiarEstadoUsuario',['usu_id'=>$usuario['usu_id']]) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                      <i class="bi bi-trash"></i> Desactivar/Activar
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
