<div class="content">
  <div class="menuTitle">
    <span id="textTitle">Registrar Solicitud</span>
    <a href="<?= getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
  </div>

    <div class="formCategoria">
      <form action="<?= getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamo'); ?>" method="POST" id="formSolicitudPrestamo">
  
        <!-- Fila 1: Nombre, Apellido, Rol -->
        <div class="inputContent">
          <label class="labelForm">Nombre del Solicitante</label>
          <input type="text" class="inputForm" value="<?= $nombre ?>" name="pres_nombre" maxlength="50" readonly>
        </div>
        
        <div class="inputContent">
          <label class="labelForm">Apellido del Solicitante</label>
          <input type="text" class="inputForm" value="<?= $apellido ?>" name="pres_apellido" maxlength="50" readonly>
        </div>
        
        <div class="inputContent">
          <label class="labelForm">Rol del Solicitante</label>
          <input type="text" class="inputForm" value="<?= $rol_nombre ?>" name="pres_rol" maxlength="50" readonly>
        </div>
        
        <!-- Fila 2: Fecha de Solicitud, Reserva, Entrega -->
        <div class="inputContent">
          <label class="labelForm">Fecha de Reserva *</label>
          <input type="date" class="inputForm" name="pres_fch_reserva" required>
        </div>
        
        <div class="inputContent">
          <label class="labelForm">Fecha de Solicitud *</label>
          <input type="date" class="inputForm" name="pres_fch_entrega" required>
        </div>
        
        <!-- Fila 3: Destino (col span 2), Observaciones (col span 3) -->
        <div class="inputContent" style="grid-column: span 2;">
          <label class="labelForm">Destino *</label>
          <input type="text" class="inputForm" name="pres_destino" maxlength="30" required>
        </div>
        
        <div class="inputObservaciones">
          <label class="labelForm">Observaciones *</label>
          <textarea class="inputForm" name="pres_observacion" rows="3" required></textarea>
        </div>
        <div class="contenedorElementos">
          <!-- Filtro por área -->
          <div class="filtroCentral">
            <label class="labelForm" for="filtro_area">Filtrar por Área</label>
            <select class="inputForm" id="filtro_area" name="filtro_area">
              <option value="">Todas las áreas</option>
              <?php foreach ($areas as $area): ?>
                <option value="<?= $area['ar_cod']; ?>"><?= htmlspecialchars($area['ar_nombre']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        
          <!-- Tabla de elementos -->
          <div class="tablaElementos">
            <h4 class="mt-4 mb-2" id="tabla-elementos">Elementos Devolutivos</h4>
            <table class="table table-bordered table-hover">
              <thead class="table-light">
                <tr>
                  <th>Código</th>
                  <th>Nombre Elemento</th>
                  <th>Disponible</th>
                  <th>Seleccionar</th>
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
        
            <!-- Paginación -->
            <div class="page container-fluid col-12">
              <ul id="paginacion" class="pagination justify-content-center"></ul>
            </div>
          </div>
        
          <!-- Botón -->
          <div class="inputBtn">
            <button type="submit"  class="btnForm">Registrar Préstamo</button>
          </div>
        </div>
      </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const filtroArea = document.getElementById('filtro_area');
  const filasOriginales = Array.from(document.querySelectorAll('#tabla-elementos-devolutivos tr'));
  const paginacion = document.getElementById('paginacion');
  const itemsPorPagina = 5;
  let filasFiltradas = [];

  filtroArea.addEventListener('change', () => {
    const selectedArea = filtroArea.value;
    filasFiltradas = filasOriginales.filter(fila => {
      const area = fila.getAttribute('data-area');
      return selectedArea === "" || area === selectedArea;
    });
    actualizarTabla(1);
    generarPaginacion();
  });

  function actualizarTabla(pagina) {
    filasOriginales.forEach(fila => fila.style.display = 'none');

    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;

    const paginaActual = filasFiltradas.slice(inicio, fin);
    paginaActual.forEach(fila => fila.style.display = '');
  }

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

    if (paginacion.firstChild) {
      paginacion.firstChild.classList.add('active');
      actualizarTabla(1);
    }
  }

  filasFiltradas = filasOriginales;
  generarPaginacion();
});
</script>
