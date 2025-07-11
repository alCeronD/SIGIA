<style>
  .content {
    background-color: #f9f9f9;
    padding: 30px;
    width: 100%;
  }

  .card-filtros {
    padding: 20px;
    border-radius: 10px;
    background-color: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  h4.center-align {
    margin-bottom: 30px;
    font-weight: bold;
    color: #2c3e50;
  }

  .btn {
    margin-top: 20px;
  }

  .striped thead {
    background-color: #2196f3;
    color: white;
  }

  .striped tbody tr:hover {
    background-color: #e3f2fd;
  }

  .pagination li.active {
    background-color: #2196f3;
  }

  .page {
    margin-top: 20px;
  }

  .switch-container {
    margin-bottom: 30px;
    text-align: center;
  }

  .switch-container label {
    margin: 0 15px;
  }
</style>

<div class="content">
  <h4 class="center-align">Reporte General</h4>

  <!-- botones para cambiar filtro -->
  <div class="switch-container">
    <label>
      <input class="with-gap" name="filtroSelector" type="radio" id="btnFiltroElementos" checked />
      <span>Filtro por Elementos</span>
    </label>
    <label>
      <input class="with-gap" name="filtroSelector" type="radio" id="btnFiltroTrazabilidad" />
      <span>Filtro por Entradas/Salidas</span>
    </label>
  </div>

  <div class="row">
    <!-- FILTROS ELEMENTOS -->
    <div class="col s12 m5" id="filtroElementos">
      <div class="card card-filtros">
        <!-- FILTRO TIPO -->
        <div class="input-field">
          <select id="tipoElemento">
            <option value="">Todos los tipos</option>
            <?php foreach ($tipos as $tipo): ?>
              <option value="<?= $tipo['tp_el_cod']; ?>"><?= htmlspecialchars($tipo['tp_el_nombre']); ?></option>
            <?php endforeach; ?>
          </select>
          <label for="tipoElemento">Filtrar por Tipo</label>
        </div>

        <!-- FILTRO ESTADO -->
        <div class="input-field">
          <select id="estadoElemento">
            <option value="">Todos los estados</option>
            <?php foreach ($estados as $estado): ?>
              <option value="<?= $estado['est_el_cod']; ?>"><?= htmlspecialchars($estado['est_nombre']); ?></option>
            <?php endforeach; ?>
          </select>
          <label for="estadoElemento">Filtrar por Estado</label>
        </div>

        <div class="center-align">
          <a id="btnDescargar" href="<?= getUrl('reportes', 'reportes', 'generarReporteExcel'); ?>" class="btn waves-effect blue">
            <i class="material-icons left">description</i>Descargar Reporte
          </a>
        </div>
      </div>
    </div>

    <!-- FILTROS TRAZABILIDAD -->
    <div class="col s12 m5" id="filtroTrazabilidad" style="display: none;">
      <div class="card card-filtros">
        <!-- tipo elemento -->
        <div class="input-field">
          <select id="trzTipoElemento">
            <option value="">Todos los tipos</option>
            <?php foreach ($tipos as $tipo): ?>
              <option value="<?= $tipo['tp_el_cod']; ?>"><?= htmlspecialchars($tipo['tp_el_nombre']); ?></option>
            <?php endforeach; ?>
          </select>
          <label for="trzTipoElemento">Tipo de Elemento</label>
        </div>

        <div class="input-field">
          <input type="date" id="trzFechaInicio">
          <label for="trzFechaInicio" class="active">Fecha Inicio</label>
        </div>
        <div class="input-field">
          <input type="date" id="trzFechaFin">
          <label for="trzFechaFin" class="active">Fecha Fin</label>
        </div>

        <div class="center-align">
          <button id="btnBuscarTrazabilidad" type="button" class="btn waves-effect teal darken-1">
            <i class="material-icons left">search</i>Buscar
          </button>
          
          <a id="btnDescargarTrazabilidad" href="#" class="btn waves-effect blue" style="margin-left:10px">
            <i class="material-icons left">description</i>Descargar
          </a>
        </div>
      </div>
    </div>

    <div class="col s12 m7">
      <h5 class="center-align">Previsualización</h5>
      <table id="tabla-previa" class="striped responsive-table highlight centered">
        <thead id="tabla-previa-head">
          <!-- Generate tabla jodaaaaa "dinamica pero no quiere funcionar"-->
        </thead>
      </thead>
        <tbody id="tabla-elementos-body">
          <tr><td colspan="5" class="grey-text">Seleccione filtros para ver los elementos</td></tr>
        </tbody>
      </table>
      <div class="page container-fluid col-12">
        <ul id="paginacion-elementos" class="pagination center-align"></ul>
      </div>
    </div>
  </div>
</div>


<!-- <script type="module" src="../public/assets/js/reportes/reportes.js"></script> Organizar para hacer las peticiones desde el fetch-->
<script>
document.addEventListener('DOMContentLoaded', function () {

  M.FormSelect.init(document.querySelectorAll('select'));

  /* --------- Referencias DOM --------- */
  const selectTipo     = document.getElementById('tipoElemento');
  const selectEstado   = document.getElementById('estadoElemento');
  const tablaHead      = document.getElementById('tabla-previa-head');  
  
  const tablaBody      = document.getElementById('tabla-elementos-body');
  const paginacion     = document.getElementById('paginacion-elementos');
  const btnDescargar   = document.getElementById('btnDescargar');

  const rbElementos    = document.getElementById('btnFiltroElementos');
  const rbTrazabilidad = document.getElementById('btnFiltroTrazabilidad');

  const contFiltroElem = document.getElementById('filtroElementos');
  const contFiltroTraz = document.getElementById('filtroTrazabilidad');

  const trzTipo        = document.getElementById('trzTipoElemento');
  const trzFechaInicio = document.getElementById('trzFechaInicio');
  const trzFechaFin    = document.getElementById('trzFechaFin');
  const btnDescTraz    = document.getElementById('btnDescargarTrazabilidad');
  const btnBuscarTraz  = document.getElementById('btnBuscarTrazabilidad');

  let elementos = [];
  let visibles  = [];
  const itemsPorPagina = 10;

  /* --------- Encabezado dinámico --------- */
  function renderizarEncabezadoTabla(esTrazabilidad = false) {
    tablaHead.innerHTML = `
      <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Placa</th>
        ${esTrazabilidad ? '<th>Tipo movimiento</th>' : ''}
        <th>Existencia</th>
        <th>${esTrazabilidad ? 'Fecha' : 'Estado'}</th>
      </tr>`;
  }

  /* --------- Paginación / Render --------- */
  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin    = inicio + itemsPorPagina;
    tablaBody.innerHTML = '';

    visibles.slice(inicio, fin).forEach(e => {
      tablaBody.innerHTML += `
        <tr>
          <td>${e.codigoElemento}</td>
          <td>${e.nombreElemento}</td>
          <td>${e.placa || '—'}</td>
          ${rbTrazabilidad.checked ? `<td>${e.tipoMovimiento}</td>` : ''}
          <td>${e.cantidad || 0}</td>
          <td>${rbTrazabilidad.checked ? e.fechaMovimiento : e.estadoElemento}</td>
        </tr>`;
    });

    document.querySelectorAll('#paginacion-elementos li').forEach(li => li.classList.remove('active'));
    const activo = document.querySelector(`#paginacion-elementos li[data-pagina="${pagina}"]`);
    if (activo) activo.classList.add('active');
  }

  function generarPaginacion(totalPaginas) {
    paginacion.innerHTML = '';
    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('waves-effect');
      li.dataset.pagina = i;
      li.innerHTML = `<a href="#!">${i}</a>`;
      li.addEventListener('click', e => {
        e.preventDefault();
        mostrarPagina(i);
      });
      paginacion.appendChild(li);
    }
    if (totalPaginas > 0) mostrarPagina(1);
    else {
      const colSpan = rbTrazabilidad.checked ? 6 : 5;
      tablaBody.innerHTML =
        `<tr><td colspan="${colSpan}" class="red-text">No se encontraron elementos</td></tr>`;
    }
  }

  /* --------- Consultas --------- */
  function cargarElementos(tipo = '', estado = '') {
    const url = "<?= getUrl('reportes','reportes','filtrarElementosAjax',false,'dashboard'); ?>";
    const fd  = new FormData();
    fd.append('tipoElemento', tipo);
    fd.append('estadoElemento', estado);

    fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }})
      .then(r => r.json())
      .then(data => {
        renderizarEncabezadoTabla(false);
        elementos = data;
        visibles  = [...elementos];
        generarPaginacion(Math.ceil(visibles.length / itemsPorPagina));
        btnDescargar.href = `<?= getUrl('reportes','reportes','generarReporteExcel'); ?>`
                          + `&tipoElemento=${encodeURIComponent(tipo)}`
                          + `&estadoElemento=${encodeURIComponent(estado)}`;
      })
      .catch(err => {
        console.error(err);
        const colSpan = 5;
        tablaBody.innerHTML =
          `<tr><td colspan="${colSpan}" class="red-text">Error al cargar elementos</td></tr>`;
      });
  }

  function cargarTrazabilidad() {
    const tipo  = trzTipo.value;
    const fi    = trzFechaInicio.value;
    const ff    = trzFechaFin.value;

    if (!fi || !ff) {
      M.toast({ html: 'Seleccione un rango de fechas válido' });
      return;
    }

    const url = "<?= getUrl('reportes','reportes','filtrarTrazabilidadAjax',false,'dashboard'); ?>";
    const fd  = new FormData();
    fd.append('tipoElemento', tipo);
    fd.append('fechaInicio',  fi);
    fd.append('fechaFin',     ff);

    fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }})
      .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
      .then(data => {
        renderizarEncabezadoTabla(true);
        elementos = data;
        visibles  = [...elementos];
        generarPaginacion(Math.ceil(visibles.length / itemsPorPagina));

        btnDescTraz.href = `<?= getUrl('reportes','reportes','generarReporteTrazabilidad'); ?>`
                         + `&tipoElemento=${encodeURIComponent(tipo)}`
                         + `&fi=${encodeURIComponent(fi)}&ff=${encodeURIComponent(ff)}`;
      })
      .catch(err => {
        console.error(err);
        const colSpan = 6;
        tablaBody.innerHTML =
          `<tr><td colspan="${colSpan}" class="red-text">Error al cargar trazabilidad</td></tr>`;
      });
  }

  /* --------- Listeners --------- */
  rbElementos.addEventListener('change', () => {
    if (rbElementos.checked) {
      contFiltroElem.style.display = '';
      contFiltroTraz.style.display = 'none';
      cargarElementos(selectTipo.value, selectEstado.value);
    }
  });

  rbTrazabilidad.addEventListener('change', () => {
    if (rbTrazabilidad.checked) {
      contFiltroElem.style.display = 'none';
      contFiltroTraz.style.display = '';
      renderizarEncabezadoTabla(true);
      const colSpan = 6;
      tablaBody.innerHTML =
        `<tr><td colspan="${colSpan}" class="grey-text">Seleccione filtros para ver los elementos</td></tr>`;
      paginacion.innerHTML = '';
    }
  });

  selectTipo  .addEventListener('change', () => rbElementos.checked && cargarElementos(selectTipo.value, selectEstado.value));
  selectEstado.addEventListener('change', () => rbElementos.checked && cargarElementos(selectTipo.value, selectEstado.value));
  btnBuscarTraz.addEventListener('click', cargarTrazabilidad);

  /* --------- Carga inicial --------- */
  renderizarEncabezadoTabla(false);
  cargarElementos();
});
</script>
