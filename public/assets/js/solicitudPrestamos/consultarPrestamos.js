import { closeModal, openModal } from '../utils/cases.js';

const modalDetalle = document.querySelector('#modalDetalle');
const contenidoDetalle = document.getElementById('contenidoDetalle');
const btnCerrarModal = document.querySelector('.closeModalBtn');

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
});


const nombreCampos = {
  pres_cod: 'Código del préstamo',
  pres_fch_slcitud: 'Fecha de solicitud',
  pres_fch_reserva: 'Fecha de reserva',
  pres_hor_inicio: 'Hora de inicio',
  pres_hor_fin: 'Hora de fin',
  pres_fch_entrega: 'Fecha de entrega',
  pres_observacion: 'Observación',
  pres_destino: 'Destino del préstamo',
  pres_estado_nombre: 'Estado',
  tp_pres_nombre: 'Tipo de préstamo',
  pres_rol_nombre: 'Rol que solicitó'
};

document.addEventListener('click', async (e) => {
  if (e.target && e.target.classList.contains('btn-ver-detalle')) {
    e.preventDefault();
    const id = e.target.getAttribute('data-id');

    openModal(modalDetalle);
    const modalTitle = document.querySelector('#modalTitle');
    modalTitle.innerHTML = `Detalle del préstamo #${id}`;

    const setParameter = new URLSearchParams();
    setParameter.append('pres_cod', id);
    setParameter.append('idCod', 1);

    try {
      const response = await fetch(`modules/solicitudPrestamos/controller/solicitudPrestamosController.php?${setParameter.toString()}`, {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
      });

      const data = await response.json();
      const info = data.data;
      const itemsContent = document.querySelector('.itemsContent');
      itemsContent.innerHTML = '';

      Object.entries(info).forEach(([key, value]) => {
        // Omitir campos no deseados
        if (
          key === 'elementos' ||
          key === 'pres_estado' ||
          key === 'tp_pres' ||
          key === 'pres_rol'
        ) return;

        const titleDetail = document.createElement('p');
        titleDetail.setAttribute('class', 'titleDetail');
        titleDetail.innerText = nombreCampos[key] || key;

        const div = document.createElement('div');
        div.setAttribute('class', 'rowDetails');
        itemsContent.appendChild(div);

        const valueDetail = document.createElement('span');
        valueDetail.setAttribute('class', 'valueDetail');
        valueDetail.innerText = value;

        div.append(titleDetail, valueDetail);
      });

      // Tabla de elementos
      if (info.elementos && Array.isArray(info.elementos)) {
        const title = document.createElement('h4');
        title.classList.add('mt-3');
        title.textContent = 'Elementos del préstamo';
        itemsContent.appendChild(title);

        const table = document.createElement('table');
        table.classList.add('table', 'table-bordered', 'mt-2');

        const thead = document.createElement('thead');
        thead.innerHTML = `
          <tr>
            <th>Nombre</th>
            <th>Placa</th>
            <th>Categoría</th>
          </tr>`;
        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        info.elementos.forEach(el => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${el.elm_nombre}</td>
            <td>${el.elm_placa ?? 'Sin placa'}</td>
            <td>${el.categoria}</td>`;
          tbody.appendChild(tr);
        });

        table.appendChild(tbody);
        itemsContent.appendChild(table);
      }

    } catch (error) {
      console.error('Error al obtener el detalle del préstamo:', error);
    }
  }
});

// Proce para cancelar préstamo
document.addEventListener('click', function (e) {
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
              location.reload();
            } else {
              alert('Error al cancelar: ' + data.message);
            }
          } catch (e) {
            console.error('No es JSON válido:', e.message);
          }
        })

    }
  }
});



// Cerrar el modal
closeModal(modalDetalle, btnCerrarModal);
