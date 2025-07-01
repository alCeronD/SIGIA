        ///////////////////////////////// 
         //MODAL ELEMENTOS DEVOLUTIVOS 
       ///////////////////////////////// 
document.addEventListener('DOMContentLoaded', function () {
  M.Modal.init(document.querySelectorAll('.modal'));
  M.FormSelect.init(document.querySelectorAll('select'));
  M.Datepicker.init(document.querySelectorAll('.datepicker'), { format: 'yyyy-mm-dd' });

  const filtroArea = document.getElementById('filtro_area_modal');
  const paginacion = document.getElementById('paginacion');
  const itemsPorPagina = 5;

  let filasOriginales = [];
  let filasFiltradas = [];

  function inicializarFilas() {
    filasOriginales = Array.from(document.querySelectorAll('#tabla-elementos-devolutivos-modal tr'));
    filasFiltradas = [...filasOriginales];
    generarPaginacion();
  }

  filtroArea.addEventListener('change', () => {
    const selectedArea = filtroArea.value;
    filasFiltradas = filasOriginales.filter(fila => {
      const area = fila.getAttribute('data-area');
      return selectedArea === "" || area === selectedArea;
    });
    generarPaginacion();
  });

  function actualizarTabla(pagina) {
    filasOriginales.forEach(fila => fila.style.display = 'none');
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;
    filasFiltradas.slice(inicio, fin).forEach(fila => fila.style.display = 'table-row');
  }

  function generarPaginacion() {
    paginacion.innerHTML = '';
    const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);

    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('waves-effect');
      li.innerHTML = `<a href="#!">${i}</a>`;
      li.addEventListener('click', (e) => {
        e.preventDefault();
        actualizarTabla(i);
        document.querySelectorAll('#paginacion li').forEach(el => el.classList.remove('active'));
        li.classList.add('active');
      });
      paginacion.appendChild(li);
    }

    if (totalPaginas > 0) {
      paginacion.firstChild.classList.add('active');
      actualizarTabla(1);
    }
  }

  const modalTrigger = document.querySelector('.modal-trigger');
  if (modalTrigger) {
    modalTrigger.addEventListener('click', () => {
      setTimeout(() => {
        inicializarFilas();
      }, 100);
    });
  }
});


       ///////////////////////////////// 
         //MODAL ELEMENTOS CONSUMIBLES 
       ///////////////////////////////// 
//Informacion para modal de elementos consumibles
  const filtroAreaConsumibles = document.getElementById('filtro_area_modal_consumibles');
  const paginacionConsumibles = document.getElementById('paginacion_consumibles');
  const filasConsumiblesOriginales = Array.from(document.querySelectorAll('#tabla-elementos-consumibles-modal tr'));
  let filasConsumiblesFiltradas = [...filasConsumiblesOriginales];
  const itemsPorPagina = 5;

  filtroAreaConsumibles.addEventListener('change', () => {
    const selectedArea = filtroAreaConsumibles.value;
    filasConsumiblesFiltradas = filasConsumiblesOriginales.filter(fila => {
      const area = fila.getAttribute('data-area');
      return selectedArea === "" || area === selectedArea;
    });
    generarPaginacionConsumibles();
  });

  function actualizarTablaConsumibles(pagina) {
    filasConsumiblesOriginales.forEach(fila => fila.style.display = 'none');
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;
    filasConsumiblesFiltradas.slice(inicio, fin).forEach(fila => fila.style.display = 'table-row');
  }

  function generarPaginacionConsumibles() {
    paginacionConsumibles.innerHTML = '';
    const totalPaginas = Math.ceil(filasConsumiblesFiltradas.length / itemsPorPagina);

    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('waves-effect');
      li.innerHTML = `<a href="#!">${i}</a>`;
      li.addEventListener('click', (e) => {
        e.preventDefault();
        actualizarTablaConsumibles(i);
        document.querySelectorAll('#paginacion_consumibles li').forEach(el => el.classList.remove('active'));
        li.classList.add('active');
      });
      paginacionConsumibles.appendChild(li);
    }

    if (totalPaginas > 0) {
      paginacionConsumibles.firstChild.classList.add('active');
      actualizarTablaConsumibles(1);
    }
  }

  // Inicializar cuando se abre el modal de consumibles
  const modalTriggerConsumibles = document.querySelector('a[href="#modalSeleccionConsumibles"]');
  if (modalTriggerConsumibles) {
    modalTriggerConsumibles.addEventListener('click', () => {
      setTimeout(() => {
        filasConsumiblesFiltradas = [...filasConsumiblesOriginales];
        generarPaginacionConsumibles();
      }, 100);
    });
  }




















