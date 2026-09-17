<div class="container">
  <div class="contentCategoria">
    <div class="headerContent">
      <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
      <div class="menuTitle">
        <span id="textTitle" class="textTitleSpan">Gestión de Categorías</span>
        <a href="<?= Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
      </div>
    </div>

    <div class="formCategoria">
      <span class="card-title">Crear Categoría</span>
      <form id="formCreateCategoria" method="POST" data-url=<?= Router::createRoute(CR_CATEGORIAS, CR_CATEGORIAS, CR_STORE, false, CR_DASHBOARD_LOWER_CASE) ?>">
        <div class=" input-field">
          <input type="text" name="ca_nombre" id="ca_nombre" required>
          <label for="ca_nombre">Nombre:</label>
        </div>

        <div class="input-field">
          <textarea name="ca_descripcion" id="ca_descripcion" class="materialize-textarea" required></textarea>
          <label for="ca_descripcion">Descripción:</label>
        </div>

        <div class="input-field center-align">
          <button type="submit" class="waves-effect waves-light btn" id="btnSubmit">
            <i class="material-icons left">send</i>
          </button>
        </div>
      </form>
    </div>
    <div class="tablaCategoria highlight striped responsive-table">
      <table class="tblConfigModules">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tbodyCategorias">

        </tbody>
      </table>
      <div class="center-align" style="margin-top: 20px;">
        <ul id="paginacion-categorias" class="pagination"></ul>
      </div>

    </div>
  </div>
</div>


<!-- MODAL Editar Categoría -->
<div id="modalEditarCategoria" class="modal">
  <div class="modal-content">
    <span id="modalTitle" class="textTitleSpan">Editar Categoría</span>
    <button type="button" class="closeModalBtn">
      <span class="close-modal">&times;</span>
    </button>

    <form id="formUpdateCategoria" method="POST" action="<?= Router::createRoute('categorias', 'categorias', 'updateCategoria', false, 'ajax') ?>">
      <input type="hidden" name="ca_id" id="modal_ca_id">

      <div class="input-field">
        <input type="text" id="modal_ca_nombre" name="ca_nombre" required>
        <label for="modal_ca_nombre" class="active">Nombre</label>
      </div>

      <div class="input-field">
        <input type="text" id="modal_ca_descripcion" name="ca_descripcion" required>
        <label for="modal_ca_descripcion" class="active">Descripción</label>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn waves-effect waves-light">
          <i class="material-icons left">save</i> Actualizar
        </button>
      </div>

      <div id="mensajeEditarCategoria" style="margin-top: 10px;"></div>
    </form>
  </div>
</div>

<!--
<script type="module" src="../public/assets/js/Categorias/categoriasUpdateModal.js"></script>
<script type="module" src="../public/assets/js/Categorias/categoriasRegistrar.js"></script> -->


<script>
  const categoriasUrl = "<?= Router::createRoute('categorias', 'categorias', 'listarCategoriasAjax') ?>";
</script>
<!-- <script type="module" src="../public/assets/js/Categorias/categoriaPaginado.js"></script> -->
<!-- <script type="module" src="../public/assets/js/Categorias/categoriasDesactivar.js"></script> -->