<div class="contentCategoria contentLayout">
  <div class="menuTitle">
    <span id="textTitle" class="textTitleSpan">Gestión de Categorías</span>
    <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
  </div>

  <div class="categoriaGrid">
  <!-- Formulario Crear dentro de una card -->
  <div class="card z-depth-3">
    <div class="card-content">
      <span class="card-title">Crear Categoría</span>

      <form id="formCreateCategoria" method="POST" action="<?= getUrl('categorias', 'categorias', 'createCategoria') ?>" novalidate>
        <div class="input-field">
          <input type="text" name="ca_nombre" id="ca_nombre" required>
          <label for="ca_nombre">Nombre:</label>
        </div>

        <div class="input-field">
          <textarea name="ca_descripcion" id="ca_descripcion" class="materialize-textarea" required></textarea>
          <label for="ca_descripcion">Descripción:</label>
        </div>

        <div class="input-field center-align">
          <button type="submit" class="waves-effect waves-light btn" id="btnSubmit">
            <i class="material-icons left">send</i> Crear
          </button>
        </div>
      </form>

      <!-- Mensaje de éxito -->
      <div id="mensajeCategoria" style="margin-top: 10px; color: green;"></div>
    </div>
  </div>
</div>

    <!-- Tabla -->
    <div class=" tablaCategoria highlight striped responsive-table">
      <table class="tblConfigModules ">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $categoria):?>
              <tr>
                <td><?= htmlspecialchars($categoria['ca_nombre']) ?></td>
                <td><?= htmlspecialchars($categoria['ca_descripcion']) ?></td>
                <td data-statusTd="<?php echo $categoria['ca_status']; ?>"><?= $categoria['ca_status'] == '1' ? 'Activo' : 'Inactivo' ?></td>
                <td class="accionesUsuarios">
                  <button type="button"
                     class="btnEditarCategoria waves-effect waves-light btn"
                     data-id="<?= $categoria['ca_id'] ?>"
                     data-nombre="<?= htmlspecialchars($categoria['ca_nombre']) ?>"
                     data-descripcion="<?= htmlspecialchars($categoria['ca_descripcion']) ?>"
                     data-status="<?= $categoria['ca_status'] ?>">
                     <i class="material-icons">edit</i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4">No hay categorías registradas.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  
</div>

<!-- Modal Personalizado -->
<div id="modalEditarCategoria" class="modal">
  <div class="modal-content">
    <span id="modalTitle" class="textTitleSpan">Editar categoria</span>
      <button type="button" class="closeModalBtn">
        <span class="close-modal">&times;</span>
      </button>
    <!-- <h4>Editar Categoría</h4> -->
    <form id="formUpdateCategoria" method="POST" action="<?= getUrl('categorias', 'categorias', 'updateCategoria', false, 'dashboard') ?>">
      <input type="hidden" name="ca_id" id="modal_ca_id">

      <div class="input-field">
        <input type="text" id="modal_ca_nombre" name="ca_nombre" required>
        <label for="modal_ca_nombre">Nombre</label>
      </div>

      <div class="input-field">
        <input type="text" id="modal_ca_descripcion" name="ca_descripcion" required>
        <label for="modal_ca_descripcion">Descripción</label>
      </div>

      <div class="input-field">
        <select id="modal_ca_status" name="ca_status" required>
          <option value="1">Activo</option>
          <option value="0">Inactivo</option>
        </select>
        <label for="modal_ca_status">Estado</label>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn waves-effect waves-light">
          <i class="material-icons left">save</i> Actualizar
        </button>
      </div>
    </form>
  </div>
</div>

<script type="module" src="../public/assets/js/categorias/categoriasUpdateModal.js"></script>
<script type="module" src="../public/assets/js/categorias/categoriasRegistrar.js"></script>
