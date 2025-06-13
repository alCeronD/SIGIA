<style>
  .categorias-container {
    width: 100%;
    max-width: 1200px;
    background: rgb(240, 248, 255); 
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
</style>

    <div class="categorias-container">
      <h2 class="mb-4 text-center">Categorías Registradas</h2>

      <div class="card p-4 mb-4 shadow-sm">
        <h5 class="mb-3">Registrar Nueva Categoría</h5>
        <form action="<?= getUrl('categorias', 'categorias', 'createCategoria') ?>" method="POST">
          <div class="row g-3 align-items-center">
            <div class="col-md-4">
              <label for="ca_nombre" class="form-label">Nombre</label>
              <input type="text" name="ca_nombre" id="ca_nombre" class="form-control" required>
            </div>
            <div class="col-md-5">
              <label for="ca_descripcion" class="form-label">Descripción</label>
              <input type="text" name="ca_descripcion" id="ca_descripcion" class="form-control" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
              <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-plus-circle"></i> Crear Categoría
              </button>
            </div>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-dark text-center">
            <tr>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($categorias)): ?>
              <?php foreach ($categorias as $categoria): ?>
                <tr class="text-center">
                  <td><?= htmlspecialchars($categoria['ca_nombre']) ?></td>
                  <td><?= htmlspecialchars($categoria['ca_descripcion']) ?></td>
                  <td><?= $categoria['ca_status'] ? 'Activo' : 'Inactivo' ?></td>
                  <td>
                    <a href="<?= getUrl('categorias', 'categorias', 'updateCategoriaView', ['ca_id' => $categoria['ca_id']], 'dashboard') ?>" class="btn btn-sm btn-warning me-1">
                      <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a href="<?= getUrl('categorias', 'categorias', 'deleteCategoria', ['ca_id' => $categoria['ca_id']], 'dashboard') ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas cambiar el estado de esta categoría?');">
                      <i class="bi bi-trash"></i> Eliminar
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center">No hay categorías registradas.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
