<div class="contentSolicitud">
  <div class="solicitudTitle">
    <h2 class="center-align">Préstamos Registrados</h2>
    <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn btn red lighten-1 white-text waves-effect waves-light" title="Volver al dashboard">&times;</a>
  </div>

  <div class="tableDetalle">
    <table class="highlight striped centered responsive-table">
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
          <?php foreach ($prestamos as $prestamo): ?>
            <tr>
              <td><?= htmlspecialchars($prestamo['codigoSolicitud']) ?></td>
              <td><?= htmlspecialchars($nombre) ?></td>
              <td><?= htmlspecialchars($prestamo['fechaReserva']) ?></td>
              <td><?= htmlspecialchars($prestamo['tipoPrestamo']) ?></td>
              <td>
                <button 
                  type="button" 
                  class="btn-small teal darken-1 white-text waves-effect waves-light btn-ver-detalle" 
                  data-id="<?= $prestamo['codigoSolicitud'] ?>">
                  Ver detalle
                </button>

                <button 
                  type="button" 
                  class="btn-small red lighten-1 white-text waves-effect waves-light btn-cancelar-prestamo" 
                  data-id="<?= $prestamo['codigoSolicitud'] ?>"
                  data-url="<?php echo getUrl('solicitudPrestamos','solicitudPrestamos','cancelarPrestamo',false,'dashboard'); ?>">
                  Cancelar
                </button>
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

    <!-- Paginación -->
    <div class="page container-fluid col-12">
      <ul id="paginacion-prestamos" class="pagination center-align"></ul>
    </div>
  </div>
</div>

<!-- Modal detalle -->
<?php include_once 'modalVerDetalle.php'; ?>

<!-- Script JS del módulo -->
<script type="module" src="../public/assets/js/solicitudPrestamos/consultarPrestamos.js"></script>
