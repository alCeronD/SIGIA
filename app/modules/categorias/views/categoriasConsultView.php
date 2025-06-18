<div class="content">
  <div class="menuTitle">
    <span id="textTitle">Gestión de Categorías</span>
    <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
  </div>

  <div class="categoriaGrid">
    <!-- Formulario Crear -->
    <div class="formCategoria">
      <form id="formCreateCategoria" method="POST" action="<?= getUrl('categorias', 'categorias', 'createCategoria') ?>" novalidate>
        <div class="inputContent nombreCategoria">
          <label for="ca_nombre" class="labelForm">Nombre:</label>
          <input type="text" name="ca_nombre" id="ca_nombre" class="inputForm" placeholder="Nombre de la categoría..." required>
        </div>

        <div class="inputContent descripcionCategoria">
          <label for="ca_descripcion" class="labelForm">Descripción:</label>
          <input type="textarea" name="ca_descripcion" id="ca_descripcion" class="inputForm" placeholder="Descripción..." required>
        </div>

        <div class="inputBtn">
          <button type="submit" id="btnSubmit">
            <i class="bi bi-plus-circle"></i> Crear Categoría
          </button>
        </div>
      </form>
      <!-- Contenedor para mensajes -->
      <div id="mensajeCategoria" style="margin: 10px 0; color: green;"></div>

    </div>

    <!-- Tabla -->
    <div class="tablaCategoria">
      <table class="tableCategoria">
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
            <?php foreach ($categorias as $categoria): ?>
              <tr>
                <td><?= htmlspecialchars($categoria['ca_nombre']) ?></td>
                <td><?= htmlspecialchars($categoria['ca_descripcion']) ?></td>
                <td><?= $categoria['ca_status'] ? 'Activo' : 'Inactivo' ?></td>
                <td class="accionesUsuarios">
                  <a href="#"
                     class="btnEditarCategoria"
                     data-id="<?= $categoria['ca_id'] ?>"
                     data-nombre="<?= htmlspecialchars($categoria['ca_nombre']) ?>"
                     data-descripcion="<?= htmlspecialchars($categoria['ca_descripcion']) ?>"
                     data-status="<?= $categoria['ca_status'] ?>">
                     Editar
                  </a>
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
</div>

<!-- Modal Personalizado -->
<div id="modalEditarCategoria" class="modal-custom">
  <div class="modal-content-custom">
    <span class="close-modal">&times;</span>
    <h3>Editar Categoría</h3>
    <form method="POST" action="<?= getUrl('categorias', 'categorias', 'updateCategoria', false, 'dashboard') ?>" id="formUpdateCategoria">
      <input type="hidden" name="ca_id" id="modal_ca_id">
      <div class="inputContent">
        <label for="modal_ca_nombre" class="labelForm">Nombre:</label>
        <input type="text" name="ca_nombre" id="modal_ca_nombre" class="inputForm" required>
      </div>
      <div class="inputContent">
        <label for="modal_ca_descripcion" class="labelForm">Descripción:</label>
        <input type="text" name="ca_descripcion" id="modal_ca_descripcion" class="inputForm" required>
      </div>
      <div class="inputContent">
        <label for="modal_ca_status" class="labelForm">Estado:</label>
        <select name="ca_status" id="modal_ca_status" class="inputForm" required>
          <option value="1">Activo</option>
          <option value="0">Inactivo</option>
        </select>
      </div>
      <div class="inputBtn">
        <button type="submit">
          <i class="bi bi-pencil-square"></i> Actualizar
        </button>
      </div>
    </form>
  </div>
</div>

<script type="module" src="../public/assets/js/categorias/categoriasUpdateModal.js"></script>
<script type="module" src="../public/assets/js/categorias/categoriasRegistrar.js"></script>