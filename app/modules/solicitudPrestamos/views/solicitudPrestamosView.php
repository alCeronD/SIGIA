<!-- VISTA CON AJUSTES PARA FUNCIONAR CON MATERIALIZE -->
<div class="content">
  <div class="menuTitle">
    <span id="textTitle">Registrar solicitud</span>
    <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
  </div>

  <div class="solicPrestamos">
    <form id="formSolicitudPrestamo" method="POST" action="<?= getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamo'); ?>" class="row">

      <!-- Nombre, Apellido, Rol -->
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

      <!-- Fechas y destino -->
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

      <!-- Observaciones -->
      <div class="input-field inputObservaciones">
        <textarea id="pres_observacion" name="pres_observacion" class="materialize-textarea" required></textarea>
        <label for="pres_observacion">Observaciones *</label>
      </div>

      <!-- Botón abrir modal -->
      <div class="input-field inputAddElements">
        <a class="waves-effect waves-light btn modal-trigger" href="#modalSeleccionElementos">Seleccionar Elementos</a>
      </div>

      <!-- MODAL MATERIALIZE -->
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

      <!-- Botón enviar solicitud -->
      <div class="input-field center-align inputBtn">
        <button type="submit" class="btn blue">Solicitar</button>
      </div>
    </form>
  </div>
</div>

<!-- JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  M.Modal.init(document.querySelectorAll('.modal'));
  M.FormSelect.init(document.querySelectorAll('select'));
  M.Datepicker.init(document.querySelectorAll('.datepicker'), { format: 'yyyy-mm-dd' });

  const filtroArea = document.getElementById('filtro_area_modal');
  const paginacion = document.getElementById('paginacion');
  const itemsPorPagina = 5;

  let filasOriginales = [];
  let filasFiltradas = [];

  function inicializarFilas() {
    filasOriginales = Array.from(document.querySelectorAll('#tabla-elementos-devolutivos-modal tr'));
    filasFiltradas = [...filasOriginales];
    generarPaginacion();
  }

  filtroArea.addEventListener('change', () => {
    const selectedArea = filtroArea.value;
    filasFiltradas = filasOriginales.filter(fila => {
      const area = fila.getAttribute('data-area');
      return selectedArea === "" || area === selectedArea;
    });
    generarPaginacion();
  });

  function actualizarTabla(pagina) {
    filasOriginales.forEach(fila => fila.style.display = 'none');
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;
    filasFiltradas.slice(inicio, fin).forEach(fila => fila.style.display = 'table-row');
  }

  function generarPaginacion() {
    paginacion.innerHTML = '';
    const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);

    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('waves-effect');
      li.innerHTML = `<a href="#!">${i}</a>`;
      li.addEventListener('click', (e) => {
        e.preventDefault();
        actualizarTabla(i);
        document.querySelectorAll('#paginacion li').forEach(el => el.classList.remove('active'));
        li.classList.add('active');
      });
      paginacion.appendChild(li);
    }

    if (totalPaginas > 0) {
      paginacion.firstChild.classList.add('active');
      actualizarTabla(1);
    }
  }

  const modalTrigger = document.querySelector('.modal-trigger');
  if (modalTrigger) {
    modalTrigger.addEventListener('click', () => {
      setTimeout(() => {
        inicializarFilas();
      }, 100);
    });
  }
});
</script>
