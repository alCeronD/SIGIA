<!-- Vista: consultarPrestamos (actualizada con tabla grid restaurada correctamente) -->
<h2 class="mb-4 text-center">Préstamos Registrados</h2>
<div class="content">
  <div class="w-100 mx-auto text-start">
    <div class="table-responsive" style="overflow-x: auto;">
      <table class="table" style="width: 100%; display: grid; grid-template-columns: repeat(5, 1fr); border-collapse: collapse;">
        <thead class="table-dark text-center" style="display: contents;">
          <tr style="display: contents;">
            <th style="padding: 0.75rem; border-bottom: 1px solid #ddd; font-weight: bold; background-color: #eaeaea;">ID</th>
            <th style="padding: 0.75rem; border-bottom: 1px solid #ddd; font-weight: bold; background-color: #eaeaea;">Nombre Usuario</th>
            <th style="padding: 0.75rem; border-bottom: 1px solid #ddd; font-weight: bold; background-color: #eaeaea;">Fecha de Solicitud</th>
            <th style="padding: 0.75rem; border-bottom: 1px solid #ddd; font-weight: bold; background-color: #eaeaea;">Estado</th>
            <th style="padding: 0.75rem; border-bottom: 1px solid #ddd; font-weight: bold; background-color: #eaeaea;">Acciones</th>
          </tr>
        </thead>
        <tbody id="tabla-prestamos" style="display: contents;">
          <?php if (!empty($prestamos)): ?>
            <?php foreach ($prestamos as $prestamo): ?>
              <tr class="text-center fila-prestamo" style="display: contents;">
                <td style="padding: 0.75rem; border-bottom: 1px solid #ddd; text-align: center;">
                  <?= htmlspecialchars($prestamo['pres_cod']) ?>
                </td>
                <td style="padding: 0.75rem; border-bottom: 1px solid #ddd; text-align: center;">
                  <?= htmlspecialchars($nombre) ?>
                </td>
                <td style="padding: 0.75rem; border-bottom: 1px solid #ddd; text-align: center;">
                  <?= htmlspecialchars($prestamo['pres_fch_reserva']) ?>
                </td>
                <td style="padding: 0.75rem; border-bottom: 1px solid #ddd; text-align: center;">
                  <?= htmlspecialchars($prestamo['tipo_prestamo']) ?>
                </td>
                <td style="padding: 0.75rem; border-bottom: 1px solid #ddd; text-align: center;">
                  <button class="btn-ver-detalle" data-id="<?= $prestamo['pres_cod'] ?>">Ver detalle</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr style="display: contents;">
              <td colspan="5" class="text-center" style="padding: 0.75rem; border-bottom: 1px solid #ddd; text-align: center;">
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
</div>

<!-- Modal Detalle del Préstamo -->
 <?php include_once 'modalVerDetalle.php'; ?>
  

<!-- JavaScript de paginación y modal -->
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
        fila.style.display = index >= inicio && index < fin ? 'contents' : 'none';
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

    // Modal Detalle
    const modalDetalle = document.getElementById('modalDetalle');
    const contenidoDetalle = document.getElementById('contenidoDetalle');

    // Abrir el modal y cargar contenido por AJAX
    document.querySelectorAll('.btn-ver-detalle').forEach(btn => {
      btn.addEventListener('click', function () {
        const id = this.dataset.id;
        abrirModalDetalle(); // Usa función personalizada para abrir
        contenidoDetalle.innerHTML = "<p>Cargando información...</p>";

        fetch(`<?= getUrl('solicitudPrestamos', 'solicitudPrestamos', 'verDetallePrestamo', false, 'ajax') ?>&pres_cod=${id}`)
          .then(res => res.text())
          .then(html => contenidoDetalle.innerHTML = html)
          .catch(() => contenidoDetalle.innerHTML = "<p>Error al cargar el detalle</p>");
      });
    });
  });

  // Funciones reutilizables para abrir/cerrar modal (estilo consistente con otros modales)
  function abrirModalDetalle() {
    document.getElementById("modalDetalle").style.display = "block";
  }

  function cerrarModalDetalle() {
    document.getElementById("modalDetalle").style.display = "none";
  }

  window.onclick = function (event) {
    const modal = document.getElementById("modalDetalle");
    if (event.target === modal) {
      cerrarModalDetalle();
    }
  };
</script>