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
</style>

<div class="content">
  <h4 class="center-align">Reporte General de Elementos</h4>

  <div class="row">
    <!-- FILTROS -->
    <div class="col s12 m5">
      <div class="card card-filtros">
        <!-- FILTRO TIPO DE ELEMENTO -->
        <div class="input-field">
          <select id="tipoElemento">
            <option value="">Todos los tipos</option>
            <?php foreach ($tipos as $tipo): ?>
              <option value="<?= $tipo['tp_el_cod']; ?>">
                <?= htmlspecialchars($tipo['tp_el_nombre']); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <label for="tipoElemento">Filtrar por Tipo</label>
        </div>

        <!-- FILTRO ESTADO DE ELEMENTO -->
        <div class="input-field">
          <select id="estadoElemento">
            <option value="">Todos los estados</option>
            <?php foreach ($estados as $estado): ?>
              <option value="<?= $estado['est_el_cod']; ?>">
                <?= htmlspecialchars($estado['est_nombre']); ?>
              </option>
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

    <!-- TABLA -->
    <div class="col s12 m7">
      <h5 class="center-align">Previsualización</h5>
      <table id="tabla-previa" class="striped responsive-table highlight centered">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Placa</th>
            <th>Existencia</th>
            <th>Estado</th>
          </tr>
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

  const selectTipo = document.getElementById('tipoElemento');
  const selectEstado = document.getElementById('estadoElemento');
  const tablaBody = document.getElementById('tabla-elementos-body');
  const paginacion = document.getElementById('paginacion-elementos');
  const btnDescargar = document.getElementById('btnDescargar');

  let elementos = [];
  let visibles = [];
  const itemsPorPagina = 10;

  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;

    tablaBody.innerHTML = '';
    visibles.slice(inicio, fin).forEach(e => {
      tablaBody.innerHTML += `
        <tr>
          <td>${e.codigoElemento}</td>
          <td>${e.nombreElemento}</td>
          <td>${e.placa || '—'}</td>
          <td>${e.cantidad || 0}</td>
          <td>${e.estadoElemento}</td>
        </tr>`;
    });

    document.querySelectorAll('#paginacion-elementos li').forEach(el => el.classList.remove('active'));
    const activo = document.querySelector(`#paginacion-elementos li[data-pagina="${pagina}"]`);
    if (activo) activo.classList.add('active');
  }

  function generarPaginacion(totalPaginas) {
    paginacion.innerHTML = '';

    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('waves-effect');
      li.setAttribute('data-pagina', i);
      li.innerHTML = `<a href="#!">${i}</a>`;
      li.addEventListener('click', (e) => {
        e.preventDefault();
        mostrarPagina(i);
      });
      paginacion.appendChild(li);
    }

    if (totalPaginas > 0) {
      mostrarPagina(1);
    } else {
      tablaBody.innerHTML = `<tr><td colspan="5" class="red-text">No se encontraron elementos</td></tr>`;
    }
  }

  function cargarElementos(tipo, estado) {
    const url = `<?= getUrl('reportes', 'reportes', 'filtrarElementosAjax', false, 'dashboard'); ?>`;
    const formData = new FormData();
    formData.append('estadoElemento', estado);
    formData.append('tipoElemento', tipo);

    fetch(url, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(data => {
      elementos = data;
      visibles = [...elementos];
      const totalPaginas = Math.ceil(visibles.length / itemsPorPagina);
      generarPaginacion(totalPaginas);

      btnDescargar.href = `<?= getUrl('reportes', 'reportes', 'generarReporteExcel'); ?>&tipoElemento=${encodeURIComponent(tipo)}&estadoElemento=${encodeURIComponent(estado)}`;
    })
    .catch(err => {
      console.error('Error al cargar elementos:', err);
      tablaBody.innerHTML = `<tr><td colspan="5" class="red-text">Error al cargar elementos</td></tr>`;
    });
  }

  // Inicial cargar todos
  cargarElementos('', '');

  selectTipo.addEventListener('change', () => {
    cargarElementos(selectTipo.value, selectEstado.value);
  });

  selectEstado.addEventListener('change', () => {
    cargarElementos(selectTipo.value, selectEstado.value);
  });
});
</script>