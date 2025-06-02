<main class="flex-grow-1">
  <div class="w-100 mx-auto text-start">
    <div class="card p-4 my-5 shadow-sm">
      <h5 class="mb-4 text-center">Editar Categoría</h5>

      <form method="POST" action="<?= getUrl('categorias', 'categorias', 'updateCategoria', false, 'dashboard') ?>">
        <input type="hidden" name="ca_id" value="<?= htmlspecialchars($resultado['ca_id']) ?>">

        <div class="row g-3">
          <div class="col-md-4">
            <label for="ca_nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="ca_nombre" id="ca_nombre"
              value="<?= htmlspecialchars($resultado['ca_nombre']) ?>" required>
          </div>

          <div class="col-md-5">
            <label for="ca_descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" name="ca_descripcion" id="ca_descripcion"
              value="<?= htmlspecialchars($resultado['ca_descripcion']) ?>" required>
          </div>

          <div class="col-md-3">
            <label for="ca_status" class="form-label">Estado</label>
            <select class="form-select" name="ca_status" id="ca_status" required>
              <option value="1" <?= $resultado['ca_status'] == 1 ? 'selected' : '' ?>>Activo</option>
              <option value="0" <?= $resultado['ca_status'] == 0 ? 'selected' : '' ?>>Inactivo</option>
            </select>
          </div>
        </div>

        <div class="mt-4 text-end">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Actualizar Categoría
          </button>
        </div>
      </form>
    </div>
  </div>
</main>
