<div class="content">
  <div class="menuTitle">
    <span id="textTitle">Registrar solicitud</span>
    <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
  </div>

  <div class="solicPrestamos">
    <form id="formSolicitudPrestamo" method="POST" action="<?= getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamo'); ?>" class="row">

      <div class="input-field nombre">
        <label for="pres_nombre" class="active  fontInfo">
          Nombre del Solicitante: <span class="black-text "><?php echo $nombre." ".$apellido;?></span>
        </label>
      </div>

      <div class="input-field rol">
        <label for="pres_rol" class="active fontInfo">
          Rol del Solicitante <span class="black-text"><?php echo $rol_nombre; ?></span>
        </label>
      </div>

      <div class="input-field fechaReserva">
        <input type="text" id="pres_fch_reserva" name="pres_fch_reserva" class="datepicker" required>
        <label for="pres_fch_reserva" class="active">Fecha de Reserva *</label>
      </div>

      <div class="input-field fechaEntrega">
        <input type="text" id="pres_fch_entrega" name="pres_fch_entrega" class="datepicker" required>
        <label for="pres_fch_entrega" class="active">Fecha de Devolución *</label>
      </div>

      <div class="input-field destino">
        <input type="text" id="pres_destino" name="pres_destino" maxlength="30" required>
        <label for="pres_destino">Destino *</label>
      </div>

      <div class="input-field inputObservaciones">
        <textarea id="pres_observacion" name="pres_observacion" class="materialize-textarea" required></textarea>
        <label for="pres_observacion">Observaciones *</label>
      </div>
      
      
      
      <!-- SELECCIONAR EL MODAL QUE SE QUEIERE ABRIR -->
      <div class="input-field inputAddElements">
        <a class="waves-effect waves-light btn modal-trigger" href="#modalSeleccionElementos">Selec. Elementos devolutivos</a>
        <a class="waves-effect waves-light btn modal-trigger" href="#modalSeleccionConsumibles">Selec. Elementos consumibles</a>
      </div>
      
      <!-- ///////////////////////////// -->
       <!-- MODAL ELEMENTOS DEVOLUTIVOSS -->
     <!-- ///////////////////////////// -->

      <div id="modalSeleccionElementos" class="modal">
        <div class="modal-content">
          <h5>Seleccionar Elementos</h5>

          <!-- Filtro por área -->
          <div class="input-field">
            <select id="filtro_area_modal" name="filtro_area_modal">
              <option value="" selected>Todas las áreas</option>
              <?php foreach ($areas as $area): ?>
                <option value="<?= $area['ar_cod']; ?>"><?= htmlspecialchars($area['ar_nombre']); ?></option>
              <?php endforeach; ?>
            </select>
            <label for="filtro_area_modal">Filtrar por área</label>
          </div>

          <!-- Tabla de elementos -->
          <table class="highlight responsive-table">
            <thead>
              <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Disponible</th>
                <th>Seleccionar</th>
              </tr>
            </thead>
            <tbody id="tabla-elementos-devolutivos-modal">
              <?php foreach ($elementos as $elemento): ?>
                <tr data-area="<?= $elemento['ar_cod']; ?>">
                  <td><?= htmlspecialchars($elemento['elm_placa']); ?></td>
                  <td><?= htmlspecialchars($elemento['elm_nombre']); ?></td>
                  <td><?= htmlspecialchars($elemento['elm_existencia']); ?></td>
                  <td>
                    <label>
                      <input type="checkbox" class="filled-in" name="elementos_seleccionados[]" value="<?= $elemento['elm_cod']; ?>">
                      <span></span>
                    </label>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <ul id="paginacion" class="pagination center-align"></ul>
        </div>

        <div class="modal-footer">
          <a href="#!" class="modal-close waves-effect waves-green btn-flat">Confirmar Selección</a>
        </div>
      </div>
      
      
      <!-- ///////////////////////////// -->
        <!-- MODAL ELEMENTOS CONSUMIBLES -->
      <!-- ///////////////////////////// -->
    <div id="modalSeleccionConsumibles" class="modal">
      <div class="modal-content">
        <h5>Seleccionar Elementos Consumibles</h5>
    
        <!-- Filtro para el área -->
        <div class="input-field">
          <select id="filtro_area_modal_consumibles" name="filtro_area_modal_consumibles">
            <option value="" selected>Todas las áreas</option>
            <?php foreach ($areas as $area): ?>
              <option value="<?= $area['ar_cod']; ?>"><?= htmlspecialchars($area['ar_nombre']); ?></option>
            <?php endforeach; ?>
          </select>
          <label for="filtro_area_modal_consumibles">Filtrar por área</label>
        </div>
    
        <!-- Tabla de elementos con campo de cantidad -->
        <table class="highlight responsive-table">
          <thead>
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Disponible</th>
              <th>Seleccionar</th>
              <th>Cantidad</th> <!-- Nueva columna -->
            </tr>
          </thead>
          <tbody id="tabla-elementos-consumibles-modal">
            <?php foreach ($elementos_consumibles as $elemento): ?>
              <tr data-area="<?= $elemento['ar_cod']; ?>">
                <td><?= htmlspecialchars($elemento['elm_placa']); ?></td>
                <td><?= htmlspecialchars($elemento['elm_nombre']); ?></td>
                <td><?= htmlspecialchars($elemento['elm_existencia']); ?></td>
                <td>
                  <label>
                    <input type="checkbox" class="filled-in" name="elementos_consumibles_seleccionados[]" value="<?= $elemento['elm_cod']; ?>">
                    <span></span>
                  </label>
                </td>
                <td>
                  <input type="number" min="1" max="<?= $elemento['elm_existencia']; ?>" class="cantConsu" name="cantidades_consumibles[<?= $elemento['elm_cod']; ?>]">
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
    
        <ul id="paginacion_consumibles" class="pagination center-align"></ul>
      </div>
    
      <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Confirmar Selección</a>
      </div>
    </div>

    
      <!-- ///////////////////////////// -->
          <!-- enviar solicitud -->
      <!-- ///////////////////////////// -->
      <div class="input-field center-align inputBtn">
        <button type="submit" class="btn blue">Solicitar</button>
      </div>
    </form>
  </div>
</div>

<script type="module" src="../public/assets/js/solicitudPrestamos/solicitudPrestamos.js"></script>
