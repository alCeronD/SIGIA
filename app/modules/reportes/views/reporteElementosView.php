
<div class="content">
  <h4 class="center-align">Reporte General de Elementos</h4>

  <div class="row">

    <!-- FILTROS -->
    <div class="col s12 m5">
      <form method="GET" id="formFiltro" action="<?= getUrl('reportes', 'reportes', 'genReporteView'); ?>">
        <div class="input-field">
          <select name="estadoElemento" id="estadoElemento">
            <option value="" <?= empty($_GET['estadoElemento']) ? 'selected' : '' ?>>Todos los estados</option>
            <?php foreach ($estados as $estado): ?>
              <option value="<?= $estado['est_el_cod']; ?>" <?= (isset($_GET['estadoElemento']) && $_GET['estadoElemento'] == $estado['est_el_cod']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($estado['est_nombre']); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <label for="estadoElemento">Filtrar por Estado</label>
        </div>
      </form>
    
      <!-- Enlace para descargar el reporte (mantiene filtros) -->
      <div class="center-align" style="margin-top: 15px;">
        <a href="<?= getUrl('reportes', 'reportes', 'generarReporteExcel', $_GET); ?>" class="btn waves-effect blue">
          <i class="material-icons left">description</i>Descargar Reporte
        </a>
      </div>
    </div>



    <!-- TABLA DE PREVISUALIZACIÓN -->
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
        <tbody>
          <?php if (!empty($elementos)): ?>
            <?php foreach ($elementos as $elm): ?>
              <tr>
                <td><?= htmlspecialchars($elm['codigoElemento']); ?></td>
                <td><?= htmlspecialchars($elm['nombreElemento']); ?></td>
                <td><?= htmlspecialchars($elm['placa'] ?? '—'); ?></td>
                <td><?= htmlspecialchars($elm['cantidad'] ?? '0'); ?></td>
                <td><?= htmlspecialchars($elm['estadoElemento']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="red-text">No se encontraron elementos</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <ul id="paginacion-previa" class="pagination center-align"></ul>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
  
    // Envío automático al cambiar el select
    const filtroEstado = document.getElementById('estadoElemento');
    if (filtroEstado) {
      filtroEstado.addEventListener('change', function () {
        document.getElementById('formFiltro').submit();
      });
    }

    // Paginación
    const filas = Array.from(document.querySelectorAll('#tabla-previa tbody tr'));
    const paginacion = document.getElementById('paginacion-previa');
    const itemsPorPagina = 5;
    let paginaActual = 1;

    function mostrarPagina(pagina) {
      const inicio = (pagina - 1) * itemsPorPagina;
      const fin = inicio + itemsPorPagina;

      filas.forEach((fila, index) => {
        fila.style.display = index >= inicio && index < fin ? '' : 'none';
      });
    }

    function generarPaginacion() {
      paginacion.innerHTML = '';
      const totalPaginas = Math.ceil(filas.length / itemsPorPagina);

      for (let i = 1; i <= totalPaginas; i++) {
        const li = document.createElement('li');
        li.classList.add('waves-effect');
        li.innerHTML = `<a href="#!">${i}</a>`;
        if (i === paginaActual) li.classList.add('active');

        li.addEventListener('click', function (e) {
          e.preventDefault();
          paginaActual = i;
          mostrarPagina(paginaActual);
          document.querySelectorAll('#paginacion-previa li').forEach(el => el.classList.remove('active'));
          li.classList.add('active');
        });

        paginacion.appendChild(li);
      }

      if (totalPaginas > 0) mostrarPagina(paginaActual);
    }

    generarPaginacion();
  });
</script>

