<div class="content">
    <div class="menuTitle">
        <span id="textTitle">Registrar Préstamo</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
    </div>
    <div class="mb-3">
      <label for="pres_nombre" class="form-label">Nombre del Solicitante</label>
      <input type="text" class="form-control" value="<?php echo $nombre ?>" name="pres_nombre" maxlength="50" required>
    </div>
    <div class="mb-3">
      <label for="pres_apellido" class="form-label">Apellido del Solicitante</label>
      <input type="text" class="form-control" value="<?php echo $apellido ?>" name="pres_apellido" maxlength="50" required>
    </div>
    <div class="mb-3">
      <label for="pres_rol" class="form-label">Rol del Solicitante</label>
      <input type="text" class="form-control" value="<?php echo $rol_nombre ?>" name="pres_rol" maxlength="50" required>
    </div>

    <div id="solicPrestamos">
        <form action="<?php echo getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamo'); ?>" method="POST" id="formSolicitudPrestamo">
            
            <div class="inputContent">
                <label for="pres_fch_slcitud" class="labelForm">Fecha de Solicitud *</label>
                <input type="date" class="inputForm" id="pres_fch_slcitud" name="pres_fch_slcitud" required>
            </div>

            <div class="inputContent">
                <label for="pres_fch_reserva" class="labelForm">Fecha de Reserva *</label>
                <input type="date" class="inputForm" id="pres_fch_reserva" name="pres_fch_reserva" required>
            </div>

            <div class="inputContent horaInicioFin">
                <div class="horaInicio">
                    <label for="pres_hor_inicio" class="labelForm">Hora de Inicio *</label>
                    <input type="time" class="inputForm" id="pres_hor_inicio" name="pres_hor_inicio" required>
                </div>
                <div class="horaFin">
                    <label for="pres_hor_fin" class="labelForm">Hora de Fin *</label>
                    <input type="time" class="inputForm" id="pres_hor_fin" name="pres_hor_fin" required>
                </div>
            </div>

            <div class="inputContent">
                <label for="pres_fch_entrega" class="labelForm">Fecha de Entrega *</label>
                <input type="date" class="inputForm" id="pres_fch_entrega" name="pres_fch_entrega" required>
            </div>

            <div class="inputContent">
                <label for="pres_destino" class="labelForm">Destino *</label>
                <input type="text" class="inputForm" id="pres_destino" name="pres_destino" maxlength="30" required>
            </div>

            <div class="inputObservaciones">
                <label for="pres_observacion" class="labelForm">Observaciones *</label><br>
                <textarea class="inputForm" id="pres_observacion" name="pres_observacion" rows="3" required></textarea>
            </div><br>
            
            <!-- Filtro por Área -->
          <div class="mb-3 col-12">
            <label for="filtro_area" class="form-label">Filtrar por Área</label>
            <select class="form-select" id="filtro_area" name="filtro_area">
              <option value="">Todas las áreas</option>
              <?php foreach ($areas as $area): ?>
                <option value="<?= $area['ar_cod']; ?>"><?= htmlspecialchars($area['ar_nombre']); ?></option>
              <?php endforeach; ?>
            </select>
          </div><br>
          
          <!-- Tabla de Elementos Devolutivos -->
          <div class="mb-3 col-12">
            <h4 class="mt-4">Elementos Devolutivos</h4>
            <table class="table table-bordered table-hover">
              <thead class="table-light">
              <br>
                <tr>
                  <th>Código: </th>
                  <th>Nombre Elemento: </th>
                  <th>Disponible: </th>
                  <th>Seleccionar: </th>
                </tr>
              </thead>
              <tbody id="tabla-elementos-devolutivos">
                <?php foreach ($elementos as $elemento): ?>
                  <tr data-area="<?= $elemento['ar_cod']; ?>">
                    <td><?= htmlspecialchars($elemento['elm_placa']); ?></td>
                    <td><?= htmlspecialchars($elemento['elm_nombre']); ?></td>
                    <td><?= htmlspecialchars($elemento['elm_existencia']); ?></td>
                    <td>
                      <input type="checkbox" name="elementos_seleccionados[]" value="<?= $elemento['elm_cod']; ?>">
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <br>
          <div class="inputBtn">
              <button type="submit" id="btnSubmit" class="btnForm">Registrar Préstamo</button>
          </div>
        </form>
    </div>
</div>
<!-- <script type="module" src="../../../../public/assets/js/solicitudPrestamos/solicitudPrestamos.js"></script> -->
<!-- <script type="module" src="/public//assets//js//solicitudPrestamos/solicitudPrestamos.js"></script> -->
<script>
document.getElementById('filtro_area').addEventListener('change', function () {
    const selectedArea = this.value;
    const filas = document.querySelectorAll('#tabla-elementos-devolutivos tr');

    filas.forEach(fila => {
      const area = fila.getAttribute('data-area');
      if (selectedArea === "" || area === selectedArea) {
        fila.style.display = "";
      } else {
        fila.style.display = "none";
      }
    });
  });

</script>