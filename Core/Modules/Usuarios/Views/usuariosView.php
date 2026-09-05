<div class="container">
  <div class="contentUsuarios">
    <?php include_once BASE_PATH . CR_ROUTE_BREADCRUMBS; ?>
    <div class="titleUsuarios">
      <span id="textTitle"></span>
      <a href="<?= Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
    </div>

    <div class="filtroUsuarios">
      <div class="input-field col s4">
        <select id="tipoFiltro" class="">
          <option value="" selected disabled>Filtro usuarios</option>
          <option value="documento">Filtrar por Documento</option>
          <option value="nombre">Filtrar por Nombre</option>
          <option value="estado">Filtrar por Estado</option>
        </select>
        <!-- <label for="tipoFiltro">Filtros<span class="red-text">*</span></label> -->
        <span>Filtros</span>
      </div>

      <div class="input-field col s4" id="contenedorInputFiltro">
        <!-- Aquí se agregará dinámicamente el input/select -->

      </div>
    </div>

    <div class="tblUsuarios">
      <table id="tableConfig">
        <thead id="tHeadUsuarios">
          <tr>
            <th id="nroDocumento">No documento</th>
            <th id="nombreCompleto">Nombres</th>
            <th id="apellidos">Apellidos</th>
            <th id="rl_nombre">Rol</th>
            <th id="estado_usuario">Estado</th>
            <th id="">Acciones</th>
          </tr>
        </thead>
        <!-- renderizar los datos del usuario. -->
        <tbody id="tbodyUsuarios">
        </tbody>
        <!-- se renderiza la paginacion -->
        <tfoot id="tFooterUsers"></tfoot>
      </table>
    </div>
  </div>
</div>


<!-- Modal -->
<div id="modalEditarUsuario" class="modal-custom card z-depth-3">
  <div class="modal-content-custom card-content">
    <span class="close-modal btn-flat red-text right" title="Cerrar">&times;</span>
    <h5 class="teal-text text-darken-3">Editar Informacion Usuario</h5>

    <form id="formUpdateDataUser">
      <input type="hidden" name="usu_id" id="usu_id">

      <div class="input-field docum">
        <label for="usu_docum" class="active">Documento</label>
        <input type="text" name="usu_docum" id="usu_docum" disabled>
      </div>

      <div class="input-field nombres">
        <label for="usu_nombres" class="active">Nombres *</label>
        <input type="text" name="usu_nombres" id="usu_nombres">
      </div>

      <div class="input-field apellidos">
        <label for="usu_apellidos" class="active">Apellidos *</label>
        <input type="text" name="usu_apellidos" id="usu_apellidos">
      </div>

      <div class="input-field email">
        <label for="usu_email" class="active">Correo *</label>
        <input type="email" name="usu_email" id="usu_email">
      </div>

      <div class="input-field telefono">
        <label for="usu_telefono" class="active">Teléfono *</label>
        <input type="text" name="usu_telefono" id="usu_telefono">
      </div>

      <div class="input-field direccion">
        <label for="usu_direccion" class="active">Dirección </label>
        <input type="text" name="usu_direccion" id="usu_direccion">
      </div>

      <div class="input-field observacion">
        <textarea name="usu_observacion" id="usu_observacion" class="materialize-textarea"></textarea>
        <label for="usu_observacion">Notas adicionales al usuario:</label>
      </div>

      <div class="input-field password">
        <label for="usu_password" class="active">Nueva contraseña (opcional)</label>
        <input type="password" name="usu_password" id="usu_password">
      </div>

      <div class="input-field rol">
        <label for="rol_id" class="active">Rol</label>
        <select name="rol_id" id="rol_id" class="browser-default">
          <!-- renderizado con javascript -->
          <option value="">Seleccione un rol</option>
        </select>
      </div>

      <div class="inputBtn btn-update">
        <button type="submit" class="btn  waves-effect btnInfo">
          <i class="material-icons">save</i>
        </button>
      </div>
    </form>

  </div>
</div>