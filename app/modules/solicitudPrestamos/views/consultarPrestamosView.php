<div class="w-100 mx-auto text-start">
  <h2 class="mb-4 text-center">Préstamos Registrados</h2>
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark text-center">
        <tr>
          <th>ID: </th>
          <th>Nombre Usuario: </th>
          <th>Fecha de Solicitud: </th>
          <th>Estado: </th>
          <th>Acciones: </th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($prestamos)): ?>
          <?php foreach ($prestamos as $prestamo): ?>
            <tr class="text-center">
              <td><?= htmlspecialchars($prestamo['pres_cod']) ?></td>
              <td><?= htmlspecialchars($nombre) ?></td>
              <td><?= htmlspecialchars($prestamo['pres_fch_slcitud']) ?></td>
              <td><?= htmlspecialchars($prestamo['tipo_prestamo']) ?></td>
              <td>
                <a href="<?= getUrl('solicitudPrestamos', 'solicitudPrestamos', 'verDetallePrestamo', ['pres_cod' => $prestamo['pres_cod']]) ?>" class="btn btn-sm btn-info me-1">
                    <i class="bi bi-trash"></i> Ver detalle
                </a>
                <br>
                <a href="<?= getUrl('solicitudPrestamos', 'solicitudPrestamos', 'eliminarPrestamo', ['pres_cod' => $prestamo['pres_cod']]) ?>" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i> Aprobar/No aprobar
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">No hay préstamos registrados.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
