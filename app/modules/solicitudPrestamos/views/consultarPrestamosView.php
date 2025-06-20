<!-- Vista: consultarPrestamos (actualizada con tabla grid restaurada correctamente) -->
<div class="contentSolicitud">
    <div class="solicitudTitle">
      <h2 class="">Préstamos Registrados</h2>
      <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>" class="close-btn" title="Volver al dashboard">&times;</a>
    </div>
    <div class="tableDetalle">
      <table class="table-responsive">
        <thead class="table-dark text-center" id="consultPrestamoHead">
          <tr>
            <th>ID</th>
            <th>Nombre Usuario</th>
            <th >Fecha de Solicitud</th>
            <th >Estado</th>
            <th >Acciones</th>
          </tr>
        </thead>
        <tbody id="tabla-prestamos">
          <?php if (!empty($prestamos)): ?>
            <?php foreach ($prestamos as $prestamo): ?>
              <tr class="" >
                <td>
                  <?= htmlspecialchars($prestamo['pres_cod']) ?>
                </td>
                <td >
                  <?= htmlspecialchars($nombre) ?>
                </td>
                <td >
                  <?= htmlspecialchars($prestamo['pres_fch_reserva']) ?>
                </td>
                <td >
                  <?= htmlspecialchars($prestamo['tipo_prestamo']) ?>
                </td>
                <td >
                  <button class="btn-ver-detalle" id="btnVerDetalle" data-id="<?= $prestamo['pres_cod'] ?>">Ver detalle</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center" id="noDataResult">
                No hay préstamos registrados.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <div class="page container-fluid col-12">
        <ul id="paginacion-prestamos" class="pagination justify-content-center"></ul>
      </div>
    </div>
</div>

<!-- Modal Detalle del Préstamo -->
<?php include_once 'modalVerDetalle.php'; ?>
<!-- JavaScript de paginación y modal -->

<script type="module" src="../public/assets/js/solicitudPrestamos/consultarPrestamos.js"></script>