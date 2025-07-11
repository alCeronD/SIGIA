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

<!-- INYECTA RUTAS DINÁMICAS -->
<script>
  window.RUTAS_REPORTE = {
    filtrarElementos   : "<?= getUrl('reportes','reportes','filtrarElementosAjax',false,'dashboard'); ?>",
    filtrarTrazabilidad: "<?= getUrl('reportes','reportes','filtrarTrazabilidadAjax',false,'dashboard'); ?>",
    reporteExcel       : "<?= getUrl('reportes','reportes','generarReporteExcel'); ?>",
    reporteTrazabilidad: "<?= getUrl('reportes','reportes','generarReporteTrazabilidad'); ?>"
  };
</script>

<script type="module" src="../public/assets/js/reportes/reportes.js"></script>
