<div class="contentCategorias contentLayout">
  <div class="titleCategorias menuTitle">
    <span id="textTitleAreas">Categorias</span>
    <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
      class="close-btn"
      title="Volver al dashboard">&times;</a>
  </div>
  <div class="formCta">
    <form action="<?= getUrl('categorias', 'categorias', 'createCategoria') ?>" method="POST" class="formLayout" id="formCategoria">
      <div class="row g-3 align-items-center">
        <div class="col-md-4 contentNombreCa">
          <label for="ca_nombre" class="form-label">Nombre</label>
          <input type="text" name="ca_nombre" id="ca_nombre" class="form-control" required>
        </div>
        <div class="col-md-5 contentDescripCa">
          <label for="ca_descripcion" class="form-label">Descripción</label>
          <input type="text" name="ca_descripcion" id="ca_descripcion" class="form-control" required>
        </div>
        <div class="col-md-3 d-flex align-items-end contentBtnCa">
          <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-plus-circle"></i> Crear Categoría
          </button>
        </div>
      </div>
    </form>
  </div>
  <div class="tblCategoria">
    <table class="table table-bordered table-hover align-middle table-responsive">
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
