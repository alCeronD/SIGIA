<div class="content">  
  <h4 class="center-align">Generar Reporte de Elementos</h4>

  <div class="row">
    <!-- Columna izquierda: Filtros -->
    <div class="col s12 m5">
      <form method="GET" action="<?php echo getUrl('elementos', 'elementos', 'genReporteView'); ?>">
        <div class="row">
          <!-- Filtro Tipo -->
          <div class="col s12">
            <div class="input-field">
              <select name="tipoElemento">
                <option value="" <?= empty($_GET['tipoElemento']) ? 'selected' : '' ?>>Todos los tipos</option>
                <?php foreach ($tipoElem as $tipo): ?>
                  <option value="<?= $tipo['tip_cod']; ?>" <?= (isset($_GET['tipoElemento']) && $_GET['tipoElemento'] == $tipo['tip_cod']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tipo['tip_nombre']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="tipoElemento">Tipo de Elemento</label>
            </div>
          </div>

          <!-- Filtro Estado -->
          <div class="col s12">
            <div class="input-field">
              <select name="estadoElemento">
                <option value="" <?= empty($_GET['estadoElemento']) ? 'selected' : '' ?>>Todos los estados</option>
                <?php foreach ($estados as $estado): ?>
                  <option value="<?= $estado['est_el_cod']; ?>" <?= (isset($_GET['estadoElemento']) && $_GET['estadoElemento'] == $estado['est_el_cod']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($estado['est_nombre']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="estadoElemento">Estado del Elemento</label>
            </div>
          </div>
        </div>

        <div class="center-align">
          <button type="submit" class="btn green waves-effect waves-light">
            <i class="material-icons left">description</i>Generar Reporte
          </button>
        </div>
      </form>
    </div>

    <!-- Columna derecha: Previsualización con paginación -->
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
          <?php foreach ($elementos as $elm): ?>
            <tr>
              <td><?= $elm['codigoElemento']; ?></td>
              <td><?= $elm['nombreElemento']; ?></td>
              <td><?= $elm['placa'] ?? '—'; ?></td>
              <td><?= $elm['cantidad'] ?? '0'; ?></td>
              <td><?= $elm['estadoElemento']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <ul id="paginacion-previa" class="pagination center-align"></ul>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    M.FormSelect.init(document.querySelectorAll('select'));

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
