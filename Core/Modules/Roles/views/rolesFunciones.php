<!-- aca va la informacion -->
<div class="contentRolesFunciones">
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
          <th>ID</th>
          <th>id función</th>
          <th>nombre función</th>
          <th>modulo asociado</th>
          <th>acciones</th>
        </tr>
      </thead>
      <tbody id="bodyRolesFunciones">
        <!-- Renderizado con javascript -->
      </tbody>
      <tfoot id="footerRolesFunciones"></tfoot>
    </table>
  </div>

</div>
<!-- esto se debe de modificar. -->
<script type="module" src="/../../Core/Modules/Roles/Js/RolesFunciones.js"></script>