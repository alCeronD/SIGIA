
<div class="content">
  <h4 class="center-align">Reporte General</h4>

  <!-- BOTONES DE FILTRO -->
  <div class="switch-container">
    <label>
      <input class="with-gap" name="filtroSelector" type="radio" id="btnFiltroElementos" checked />
      <span>Filtro por Elementos</span>
    </label>
    <label>
      <input class="with-gap" name="filtroSelector" type="radio" id="btnFiltroTrazabilidad" />
      <span>Filtro por Entradas/Salidas</span>
    </label>
    <label>
      <input class="with-gap" name="filtroSelector" type="radio" id="btnFiltroElementoMovimiento" />
      <span>Movimiento por Placa</span>
    </label>
  </div>

  <div class="row">
    <!-- FILTRO ELEMENTOS -->
    <div class="col s12 m5" id="filtroElementos">
      <div class="card card-filtros">
        <!-- Tipo -->
        <div class="input-field">
          <select id="tipoElemento">
            <option value="">Todos los tipos</option>
            <?php foreach ($tipos as $tipo): ?>
              <option value="<?= $tipo['tp_el_cod']; ?>"><?= htmlspecialchars($tipo['tp_el_nombre']); ?></option>
            <?php endforeach; ?>
          </select>
          <label for="tipoElemento">Filtrar por Tipo</label>
        </div>

        <!-- Estado -->
        <div class="input-field">
          <select id="estadoElemento">
            <option value="">Todos los estados</option>
            <?php foreach ($estados as $estado): ?>
              <option value="<?= $estado['est_el_cod']; ?>"><?= htmlspecialchars($estado['est_nombre']); ?></option>
            <?php endforeach; ?>
          </select>
          <label for="estadoElemento">Filtrar por Estado</label>
        </div>

        <!-- Botón Descargar -->
        <div class="center-align">
          <a id="btnDescargar" href="<?= getUrl('reportes', 'reportes', 'generarReporteExcel'); ?>" class="btn waves-effect">
            <i class="material-icons left">description</i>Descargar Reporte
          </a>
        </div>
      </div>
    </div>

    <!-- FILTRO TRAZABILIDAD -->
    <div class="col s12 m5" id="filtroTrazabilidad" style="display: none;">
      <div class="card card-filtros">
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
          <a id="btnDescargarTrazabilidad" href="#" class="btn waves-effect" style="margin-left:10px">
            <i class="material-icons left">description</i>Descargar
          </a>
        </div>
      </div>
    </div>

    <!-- FILTRO POR PLACA -->
    <div class="col s12 m5" id="filtroMovimientoElemento" style="display: none;">
      <div class="card card-filtros">
        <div class="input-field">
          <input type="text" id="placaElemento">
          <label for="placaElemento" class="active">Placa del Elemento</label>
        </div>

        <div class="center-align">
          <button id="btnBuscarPorPlaca" type="button" class="btn waves-effect teal darken-1">
            <i class="material-icons left">search</i>Buscar
          </button>
          <a id="btnDescargarMovimientoPlaca" href="#" class="btn waves-effect" style="margin-left:10px">
            <i class="material-icons left">description</i>Descargar
          </a>
        </div>
      </div>
    </div>

    <!-- TABLA -->
    <div class="col s12 m7">
      <h5 class="center-align">Previsualización</h5>
      <!-- <table id="tabla-previa" class="striped responsive-table highlight centered"> -->
      <table id="tabla-previa" class="striped highlight centered">
        <thead id="tabla-previa-head"></thead>
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

<!-- RUTAS JS -->
<script>
window.RUTAS_REPORTE = {
  filtrarElementos        : "<?= getUrl('reportes','reportes','filtrarElementosAjax',false,'dashboard'); ?>",
  reporteExcel            : "<?= getUrl('reportes','reportes','generarReporteExcel'); ?>",

  filtrarTrazabilidad     : "<?= getUrl('reportes','reportes','filtrarTrazabilidadAjax',false,'dashboard'); ?>",
  reporteTrazabilidad     : "<?= getUrl('reportes','reportes','generarReporteTrazabilidad'); ?>",

  filtrarPorPlaca         : "<?= getUrl('reportes','reportes','filtrarPorPlacaAjax',false,'dashboard'); ?>",
  
  reporteMovimientoPlaca  : "<?= getUrl('reportes','reportes','generarReportePorPlaca'); ?>"
};
</script>




<script type="module" src="../public/assets/js/reportes/reportes.js"></script>
