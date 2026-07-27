<div class="container">
  <div class="contentRolesFunciones">
    <?php include_once BASE_URL . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="titleRolesRunciones menuTitle">
      <span id="textTitleAreas" class="textTitleSpan">Gestión de funciones asociadas al rol</span>
      <a href="<?php echo Router::createRoute('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
        class="close-btn"
        title="Volver al dashboard">&times;</a>
    </div>
    <div class="selectRol input-field">
      <select id="selectRol">
      </select>
      <label>Roles:</label>
    </div>

    <!-- tabla -->
    <div class="tblRolesFunciones">
      <table class="table table-bordered table-striped table-responsive" id="tableRolesFunciones">
        <thead class="table-dark" id="headerRoles">
          <tr>
            <th id="id">ID</th>
            <th id="idFuncion">id función</th>
            <th id="nombreFuncion">nombre función</th>
            <th id="moduloAsociado">modulo asociado</th>
            <th id="">acciones</th>
          </tr>
        </thead>
        <tbody id="bodyRolesFunciones">
          <!-- Renderizado con javascript -->
        </tbody>
        <tfoot id="footerRolesFunciones"></tfoot>
      </table>
    </div>

  </div>

</div>