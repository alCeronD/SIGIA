<div class="contentSolicitud">

  <div class="solicitudTitle">
    <h2 class="center-align" id= "tituloConsult">Préstamos Registrados</h2>
    <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
      class="close-btn"
      title="Volver al dashboard">
      &times;
    </a>
  </div>

  <div class="tableDetalle">

    <!-- Filtro por estado -->
    <div class="row" id="filtrosPrestamo">
      <div class="input-field col s4">
        <select id="filtro-estado" class="browser-default">
          <option value="">Filtro por estado</option>
          <?php foreach ($estados as $estado): ?>
            <option value="<?= htmlspecialchars($estado['es_pr_nombre']) ?>">
              <?= htmlspecialchars($estado['es_pr_nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!-- <table class="highlight striped centered responsive-table"> -->
    <table class="highlight striped centered">
      <thead class="teal darken-3 white-text" id="consultPrestamoHead">
        <tr>
          <th>ID</th>
          <th>Nombre Usuario</th>
          <th>Fecha de Solicitud</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tabla-prestamos">
        <?php if (!empty($prestamos)): ?>
          <?php  foreach ($prestamos as $prestamo): ?>
            <tr>
              <td><?= htmlspecialchars($prestamo['codigoSolicitud']) ?></td>
              <td><?= htmlspecialchars($nombre) ?></td>
              <td><?= htmlspecialchars($prestamo['fechaReserva']) ?></td>
              <td><?= htmlspecialchars($prestamo['estadoNombre']) ?></td>
              <td>
                <div class="center-align">
                  <button type="button"
                    class="btn btn-ver-detalle btn-small waves-teal white-text"
                    data-id="<?= $prestamo['codigoSolicitud'] ?>"
                    title="Ver detalle del préstamo">
                    <i class="material-icons">visibility</i>
                  </button>
                  <?php if ((strtolower($prestamo['estadoNombre']) !== 'cancelado') && strtolower($prestamo['estadoNombre']) !== 'finalizado'): ?>
                    <button type="button"
                      class="btn btn-cancelar-prestamo btn-small red lighten-1 white-text"
                      data-id="<?= $prestamo['codigoSolicitud'] ?>"
                      data-url="<?= getUrl('solicitudPrestamos', 'solicitudPrestamos', 'cancelarPrestamo', false, 'dashboard'); ?>"
                      title="Cancelar préstamo">
                      <i class="material-icons">cancel</i>
                    </button>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="center-align grey-text text-darken-2" id="noDataResult">
              No hay préstamos registrados.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- paginacion -->
    <div class="page container-fluid col-12">
      <ul id="paginacion-prestamos" class="pagination center-align"></ul>
    </div>

  </div>

</div>


<?php include_once 'modalVerDetalle.php'; ?>

<script type="module" src="../public/assets/js/solicitudPrestamos/consultarPrestamos.js"></script>