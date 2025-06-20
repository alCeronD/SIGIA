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

        // fetch(`<?= getUrl('solicitudPrestamos', 'solicitudPrestamos', 'verDetallePrestamo', false) ?>&pres_cod=${id}`).then((response) => response.json()).then((data)=> console.log(data));
        fetch(`modules/solicitudPrestamos/controller/solicitudPrestamosController.php`)
        .then((response) => response.json())
        .then((data)=> console.log(data));
          // .then(res => res.text())
          // .then(html => contenidoDetalle.innerHTML = html)
          // .catch(() => contenidoDetalle.innerHTML = "<p>Error al cargar el detalle</p>");
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