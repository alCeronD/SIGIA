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
      <tbody id="tabla-prestamos">
        <?php if (!empty($prestamos)): ?>
          <?php foreach ($prestamos as $prestamo): ?>
            <tr class="text-center fila-prestamo">
              <td><?= htmlspecialchars($prestamo['pres_cod']) ?></td>
              <td><?= htmlspecialchars($nombre) ?></td>
              <td><?= htmlspecialchars($prestamo['pres_fch_slcitud']) ?></td>
              <td><?= htmlspecialchars($prestamo['tipo_prestamo']) ?></td>
              <td>
                <a href="<?= getUrl('solicitudPrestamos', 'solicitudPrestamos', 'verDetallePrestamo', ['pres_cod' => $prestamo['pres_cod']]) ?>" class="btn btn-sm btn-info me-1">
                    <i class="bi bi-eye"></i> Ver detalle
                </a>
                <br>
                <a href="<?= getUrl('solicitudPrestamos', 'solicitudPrestamos', 'eliminarPrestamo', ['pres_cod' => $prestamo['pres_cod']]) ?>" class="btn btn-sm btn-danger mt-1">
                    <i class="bi bi-x-circle"></i> Aprobar/No aprobar
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
    
    <div class="page container-fluid col-12">
      <ul id="paginacion-prestamos" class="pagination justify-content-center"></ul>
    </div>
    
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const filas = Array.from(document.querySelectorAll('.fila-prestamo'));
  const paginacion = document.getElementById('paginacion-prestamos');
  const itemsPorPagina = 5;
  let totalPaginas = Math.ceil(filas.length / itemsPorPagina);

  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;

    filas.forEach((fila, index) => {
      fila.style.display = index >= inicio && index < fin ? '' : 'none';
    });

    document.querySelectorAll('#paginacion-prestamos li').forEach(el => el.classList.remove('active'));
    const liActivo = document.querySelector(`#paginacion-prestamos li[data-pagina="${pagina}"]`);
    if (liActivo) liActivo.classList.add('active');
  }

  function generarPaginacion() {
    paginacion.innerHTML = '';
    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('page-item');
      li.setAttribute('data-pagina', i);
      li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
      li.addEventListener('click', function (e) {
        e.preventDefault();
        mostrarPagina(i);
      });
      paginacion.appendChild(li);
    }

    if (totalPaginas > 0) {
      mostrarPagina(1);
    }
  }

  generarPaginacion();
});
</script>
