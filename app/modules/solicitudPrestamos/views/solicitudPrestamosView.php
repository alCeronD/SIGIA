<div class="content">
  <div class="menuTitle">
    <span id="textTitle">Registrar solicitud</span>
    <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
  </div>

  <div class="solicPrestamos">
    <form id="formSolicitudPrestamo" method="POST" action="<?= getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamo'); ?>">

      <!-- Campos solicitante -->
      <div class="inputContent nombre">
        <label class="labelForm">Nombre del Solicitante</label>
        <input type="text" class="inputForm" value="<?= $nombre ?>" name="pres_nombre" readonly>
      </div>
      <div class="inputContent apellido">
        <label class="labelForm">Apellido del Solicitante</label>
        <input type="text" class="inputForm" value="<?= $apellido ?>" name="pres_apellido" readonly>
      </div>
      <div class="inputContent rol">
        <label class="labelForm">Rol del Solicitante</label>
        <input type="text" class="inputForm" value="<?= $rol_nombre ?>" name="pres_rol" readonly>
      </div>

      <!-- Fechas, destino, observación -->
      <div class="inputContent fechaReserva">
        <label class="labelForm">Fecha de Reserva *</label>
        <input type="date" class="inputForm" name="pres_fch_reserva" required>
      </div>
      <div class="inputContent fechaEntrega">
        <label class="labelForm">Fecha de devolución *</label>
        <input type="date" class="inputForm" name="pres_fch_entrega" required>
      </div>
      <div class="inputContent destino">
        <label class="labelForm">Destino *</label>
        <input type="text" class="inputForm" name="pres_destino" maxlength="30" required>
      </div>
      <div class="inputContent inputObservaciones">
        <label class="labelForm">Observaciones *</label>
        <textarea class="inputForm" name="pres_observacion" required rows="3"></textarea>
      </div>

      <!-- Botón abrir modal -->
      <div class="inputContent">
        <label class="labelForm">Elementos a prestar</label>
        <button type="button" class="btnForm" onclick="abrirModalElementos()">Seleccionar Elementos</button>
      </div>

      <!-- MODAL -->
      <div id="modalSeleccionElementos" class="modal-secElementos" style="display: none;">
        <div class="modal-content">
          <div class="modal-title">
            <span id="modalTitle">Seleccionar Elementos</span>
            <button class="closeModalBtn" type="button" onclick="cerrarModalElementos()">&times;</button>
          </div>

          <!-- Filtro por área -->
          <div class="inputContent filtroArea">
            <label class="labelForm" for="filtro_area_modal">Filtrar por Área</label>
            <select class="inputForm" id="filtro_area_modal" name="filtro_area_modal">
              <option value="">Todas las áreas</option>
              <?php foreach ($areas as $area): ?>
                <option value="<?= $area['ar_cod']; ?>"><?= htmlspecialchars($area['ar_nombre']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Tabla de elementos -->
          <div class="tableElements mt-3">
            <h4>Elementos Devolutivos</h4>
            <table class="table table-bordered table-hover">
              <thead class="table-light">
                <tr>
                  <th>Código</th>
                  <th>Nombre Elemento</th>
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
                      <label class="check-container" >
                        
                        <input type="checkbox" class="checkbox" name="elementos_seleccionados[]" value="<?= $elemento['elm_cod']; ?>">
                      </label>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <div class="page container-fluid col-12">
              <ul id="paginacion" class="pagination justify-content-center"></ul>
            </div>
          </div>

          <div class="inputBtn mt-3">
            <button type="button" class="btnForm" onclick="cerrarModalElementos()">Confirmar Selección</button>
          </div>
        </div>
      </div>

      <div class="inputBtn">
        <button type="submit" class="btnForm">Solicitar</button>
      </div>

    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const filtroArea = document.getElementById('filtro_area_modal');
  const paginacion = document.getElementById('paginacion');
  const itemsPorPagina = 5;

  let filasOriginales = [];
  let filasFiltradas = [];

  // Función para cargar filas del modal (cuando ya está visible)
  function inicializarFilas() {
    filasOriginales = Array.from(document.querySelectorAll('#tabla-elementos-devolutivos-modal tr'));
    filasFiltradas = [...filasOriginales];
    console.log("Total filas detectadas:", filasOriginales.length); // ✅ Debería mostrar > 0
    generarPaginacion();
  }


  // Filtrar area
    filtroArea.addEventListener('change', () => {
    const selectedArea = filtroArea.value;
    filasFiltradas = filasOriginales.filter(fila => {
      const area = fila.getAttribute('data-area');
      return selectedArea === "" || area === selectedArea;
    });
    generarPaginacion(); // Esto sí funciona si filasOriginales está bien cargado
  });

  // Paginar filas
  function actualizarTabla(pagina) {
    // Oculta todo
    filasOriginales.forEach(fila => fila.style.display = 'none');
    console.log(filasOriginales);
    // Muestra solo la página actual
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;
    const paginaActual = filasFiltradas.slice(inicio, fin);
    paginaActual.forEach(fila => fila.style.display = 'table-row');
  }



  // Crear botones de paginación
  function generarPaginacion() {
    paginacion.innerHTML = '';
    const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);
    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('page-item');
      li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
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

  // Mostrar el modal y cargar los datos
  window.abrirModalElementos = function () {
    const modal = document.getElementById("modalSeleccionElementos");
    modal.style.display = "block";
    modal.style.visibility = "visible";
    modal.style.position = "fixed";
  
    // Esperar al siguiente frame + 100ms
    requestAnimationFrame(() => {
      setTimeout(() => {
        inicializarFilas(); // Aquí sí detecta las filas del modal
      }, 100);
    });
  };


  
  window.cerrarModalElementos = function () {
    const modal = document.getElementById("modalSeleccionElementos");
    modal.style.display = "none";
    modal.style.visibility = "hidden";
    modal.style.position = "absolute";
  };


  // Cerrar modal si se hace clic fuera
  window.onclick = function (event) {
    const modal = document.getElementById("modalSeleccionElementos");
    if (event.target == modal) {
      cerrarModalElementos();
    }
  };
});
</script>
