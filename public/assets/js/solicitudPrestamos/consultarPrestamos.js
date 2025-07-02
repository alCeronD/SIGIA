import { closeModal, instanceModal, options } from '../utils/cases.js';

const btnCerrarModal = document.querySelector('.closeModalBtn');
let modalDetalle = instanceModal('#modalDetalle', options);

document.addEventListener('DOMContentLoaded', () => {
  const filas = Array.from(document.querySelectorAll('#tabla-prestamos tr'));
  const paginacion = document.getElementById('paginacion-prestamos');
  const itemsPorPagina = 5;
  let totalPaginas = Math.ceil(filas.length / itemsPorPagina);

  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;

    filas.forEach((fila, index) => {
      fila.style.display = index >= inicio && index < fin ? 'table-row' : 'none';
    });

    document.querySelectorAll('#paginacion-prestamos li').forEach(el => el.classList.remove('active'));
    const liActivo = document.querySelector(`#paginacion-prestamos li[data-pagina="${pagina}"]`);
    if (liActivo) liActivo.classList.add('active');
  }

  function generarPaginacion() {
    paginacion.innerHTML = '';
    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('waves-effect');
      li.setAttribute('data-pagina', i);
      li.innerHTML = `<a href="#!">${i}</a>`;
      li.addEventListener('click', (e) => {
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

// Mostrar detalle del préstamo
document.addEventListener('click', async (e) => {
  if (e.target && e.target.classList.contains('btn-ver-detalle')) {
    e.preventDefault();

    const id = e.target.getAttribute('data-id');
    modalDetalle.open();

    document.getElementById('modalTitle').textContent = `Detalle del préstamo #${id}`;

    const setParameter = new URLSearchParams();
    setParameter.append('pres_cod', id);
    setParameter.append('idCod', 1);

    try {
      const response = await fetch(`modules/solicitudPrestamos/controller/solicitudPrestamosController.php?${setParameter.toString()}`, {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
      });

      const { data } = await response.json();
      const info = data;

      // Campos texto
      Object.entries(info).forEach(([key, value]) => {
        if (['elementos', 'pres_estado', 'tp_pres', 'pres_rol'].includes(key)) return;
        const span = document.getElementById(`detalle-${key}`);
        if (span) span.textContent = value ?? '';
      });

      // Tabla de elementos
      if (info.elementos && Array.isArray(info.elementos)) {
        const tbody = document.querySelector('#tabla-elementos-prestamo tbody');
        tbody.innerHTML = '';

        info.elementos.forEach(el => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
          <td>${el.elm_nombre}</td>
          <td>${el.elm_placa ?? 'Sin placa'}</td>
          <td>${el.cantidad ?? '1'}</td>
        `;
          tbody.appendChild(tr);
        });
      }

    } catch (error) {
      console.error('Error al obtener el detalle del préstamo:', error);
    }
  }
});

// Cancelar préstamo
document.addEventListener('click', (e) => {
  if (e.target && e.target.classList.contains('btn-cancelar-prestamo')) {
    e.preventDefault();

    const id = e.target.getAttribute('data-id');
    const url = e.target.getAttribute('data-url');
    const confirmar = confirm(`¿Está seguro de que desea cancelar el préstamo #${id}?`);

    if (confirmar) {
      const formData = new FormData();
      formData.append('pres_cod', id);
      formData.append('accion', 'cancelar');

      fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(async response => {
          const data = await response.json();
          try {
            if (data.success) {
              alert(data.message);
            
              // Buscar la fila 
              const fila = e.target.closest('tr');
            
              // Actualizar la celda de estado
              const celdaEstado = fila.querySelector('td:nth-child(4)');
              if (celdaEstado) {
                celdaEstado.textContent = 'Cancelado';
              }
            
              // Opcional: Deshabilitar botón cancelar y cambiar texto
              e.target.disabled = true;
              e.target.textContent = 'Cancelado';
              e.target.classList.remove('red', 'lighten-1');
              e.target.classList.add('grey', 'darken-1');
            }
             else {
              alert('Error al cancelar: ' + data.message);
            }
          } catch (e) {
            console.error('No es JSON válido:', e.message);
          }
        });
    }
  }
});

// Cerrar modal
closeModal(modalDetalle, btnCerrarModal);
