import { closeModal,openModal } from '../libraries/cases.js';

    // Modal Detalle
const modalDetalle = document.querySelector('#modalDetalle');
const contenidoDetalle = document.getElementById('contenidoDetalle');
const btnCerrarModal = document.querySelector('.closeModalBtn');
const btnOpenModal = document.querySelector('#btnVerDetalle');
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
    
    //Función generada desde libraries/case.js
    //closeModal(modalDetalle,btnCerrarModal);

  });












let data ={};
document.addEventListener('click', async (e)=>{
  e.stopPropagation();
  e.preventDefault();
  if (e.target && e.target.classList.contains('btn-ver-detalle')) {

    let id = e.target.getAttribute('data-id');
    console.log(id);
    openModal(modalDetalle);
    // modalDetalle.style.display = 'flex';
    const setParameter = new URLSearchParams();
    setParameter.append('pres_cod', id);
    setParameter.append('idCod', 1);

    try {
      const response = await fetch(`modules/solicitudPrestamos/controller/solicitudPrestamosController.php?${setParameter.toString()}`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json'
        }
      });

      data = await response.json();

      console.log('Datos cargados:', data);

      //Abrir el modal y el evento e.target significa que lo ejecuta el evento que se disparo.
    } catch (error) {
      console.error('Datos no encontrados', error);
    }
  }

});


//Cerrar el modal en el evento click.
closeModal(modalDetalle,btnCerrarModal);