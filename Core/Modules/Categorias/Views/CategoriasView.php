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
      <form id="formCreateCategoria" data-url=<?= Router::createRoute(CR_CATEGORIAS, CR_CATEGORIAS, CR_STORE, false, CR_DASHBOARD_LOWER_CASE) ?>">
        <div class=" input-field">
          <input type="text" name="ca_nombre" id="ca_nombre">
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
      <table class="tblConfigModules" id="tHeaderCategoria">
        <thead id="tHeaderCategoria">
          <tr>
            <th id="ca_id">id</th>
            <th id="ca_nombre">Nombre</th>
            <th id="ca_descripcion">Descripción</th>
            <th id="ca_status">Estado</th>
            <th id="">Acciones</th>
          </tr>
        </thead>
        <tbody id="tBodyCategoria">

        </tbody>
        <tfoot id="tFootCategoria"></tfoot>
      </table>
    </div>
  </div>
</div>

<!-- MODAL Editar Categoría -->
<div id="" class="modalEditarCategoria modal modal-overlay">
  <div class="modal-content .modal-closebtn" id="modalContentCategoria">
    <div class="titleSection">
      <span id="modalTitle">Actualizar registro</span>
      <button type="button" class="closeModalBtn">
        <span class="close-modal">&times;</span>
      </button>
    </div>
    <div class="formUpdate">
      <form id="formUpdateCategoria" data-url="<?= Router::createRoute(CR_CATEGORIAS, CR_CATEGORIAS, CR_SAVE, false, CR_DASHBOARD_LOWER_CASE) ?>" class="formUpdate">
        <input type="hidden" name="ca_id" id="modal_ca_id">
        <div class="input-field">
          <input type="text" id="modal_ca_nombre" name="ca_nombre">
          <label for="modal_ca_nombre" class="active">Nombre</label>
        </div>

        <div class="input-field">
          <input type="text" id="modal_ca_descripcion" name="ca_descripcion" required>
          <label for="modal_ca_descripcion" class="active">Descripción</label>
        </div>

        <button type="submit" class="btn waves-effect waves-light">
          <i class="material-icons left">save</i> Actualizar
        </button>
      </form>
    </div>
  </div>
</div>