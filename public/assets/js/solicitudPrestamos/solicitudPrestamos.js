///////////////////////////////// 
//MODAL ELEMENTOS DEVOLUTIVOS 
///////////////////////////////// 
document.addEventListener('DOMContentLoaded', function () {
  M.Modal.init(document.querySelectorAll('.modal'));
  M.FormSelect.init(document.querySelectorAll('select'));
  const presFchReserva = document.getElementById('pres_fch_reserva');
  const presFchEntrega = document.getElementById('pres_fch_entrega');

  if (presFchReserva && presFchEntrega) {
    // Inicializa datepicker de entrega por defecto
    M.Datepicker.init(presFchEntrega, {
      format: 'yyyy-mm-dd',
      minDate: new Date(),
      autoClose: true,
      i18n: {
        cancel: 'Cancelar',
        clear: 'Limpiar',
        done: 'Aceptar'
      }
    });

    // Inicializa datepicker de reserva con onSelect
    M.Datepicker.init(presFchReserva, {
      format: 'yyyy-mm-dd',
      minDate: new Date(),
      autoClose: true,
      i18n: {
        cancel: 'Cancelar',
        clear: 'Limpiar',
        done: 'Aceptar'
      },
      onSelect: (fechaReserva) => {
        const pickerEntrega = M.Datepicker.getInstance(presFchEntrega);
        if (pickerEntrega) {
          pickerEntrega.destroy();
        }
        M.Datepicker.init(presFchEntrega, {
          format: 'yyyy-mm-dd',
          minDate: new Date(fechaReserva.getTime() + 24 * 60 * 60 * 1000),
          autoClose: true,
          i18n: {
            cancel: 'Cancelar',
            clear: 'Limpiar',
            done: 'Aceptar'
          }
        });
        presFchEntrega.value = '';
      }
    });
  }


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

  // Capturar elementos devolutivos seleccionados antes de enviar el formulario
   document.getElementById('formSolicitudPrestamo').addEventListener('submit', async function (e) {
    e.preventDefault();
  
    const seleccionados = Array.from(
      document.querySelectorAll('input[name="elementos_seleccionados[]"]:checked')
    ).map(input => input.value);
    const form = e.target;
    const formData = new FormData(form);
    
    // Añade los elementos seleccionados manualmente como string separados por coma
    formData.set('elementos_devolutivos_seleccionados', seleccionados.join(','));
    
    // Convertir a objeto plano
    const data = Object.fromEntries(formData.entries());
    
    // Añadir la acción como se hace en el registro de usuario
    data.action = "registrarPrestamo";
    // console.log(data);
    
    try {
      const response = await fetch(
        "modules/solicitudPrestamos/controller/solicitudPrestamosController.php",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data)
        }
      );
  
      const result = await response.json();
  
      if (result.status === "success") {
        M.toast({ html: result.message, classes: 'green' });
      
        form.reset();
      
        // Reiniciar selects y textareas con Materialize
        M.FormSelect.init(document.querySelectorAll('select'));
        M.textareaAutoResize(document.querySelector('#pres_observacion'));
      
        // Reiniciar contador observación
        const contador = document.getElementById("contador-observacion");
        if (contador) {
          contador.textContent = '0 / 50';
        }
      
        // Limpiar inputs de cantidad de consumibles
        document.querySelectorAll('.cantConsu').forEach(input => {
          input.value = '';
          input.disabled = true;
        });
      
        // Desmarcar todos los checkboxes
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
          checkbox.checked = false;
        });
      
        // Limpiar campos de fecha si existen
        const fechaEntrega = document.getElementById('pres_fch_entrega');
        const fechaReserva = document.getElementById('pres_fch_reserva');
        if (fechaEntrega) fechaEntrega.value = '';
        if (fechaReserva) fechaReserva.value = '';
      }
 else {
        M.toast({ html: result.message || "Error al registrar el préstamo", classes: 'red' });
      }
  
    } catch (error) {
      console.error(error);
      M.toast({ html: "Error inesperado al enviar el formulario", classes: 'red' });
    }
  });





  const textareaObs = document.getElementById('pres_observacion');
  const contador = document.getElementById('contadorObservacion');

  if (textareaObs && contador) {
    textareaObs.addEventListener('input', () => {
      const longitud = textareaObs.value.length;
      contador.textContent = `${longitud} / 50`;
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


//Validador paara no permitir letras o caracteres especiales en el input
document.querySelectorAll('.cantConsu').forEach(input => {
  input.addEventListener('input', function () {
    const max = parseInt(this.getAttribute('max'), 10);
    let valor = this.value;

    // vacia el campo si contiene letras
    if (!/^\d*$/.test(valor)) {
      this.value = '';
      return;
    }

    valor = parseInt(valor, 10);

    // Si el valor es menor que 1 o mayor al máximo, ajustar
    if (valor < 1) {
      this.value = 1;
    } else if (valor > max) {
      this.value = max;
    }
  });

  // Previene el ingreso de caracteres no numéricos
  input.addEventListener('keydown', function (e) {
    const teclasPermitidas = [
      'Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete'
    ];

    // Permitir solo números y teclas útiles
    if (
      !teclasPermitidas.includes(e.key) &&
      (e.key < '0' || e.key > '9')
    ) {
      e.preventDefault();
    }
  });
});



//Accion para seleccionar y posteriormente indicar las cantidades
document.querySelectorAll('#tabla-elementos-consumibles-modal tr').forEach(fila => {
  const checkbox = fila.querySelector('input[type="checkbox"]');
  const inputCantidad = fila.querySelector('.cantConsu');

  // Desactivar la input cantidad
  inputCantidad.disabled = true;

  // Listener al momendo de darle check al box
  checkbox.addEventListener('change', () => {
    inputCantidad.disabled = !checkbox.checked;

    // Si se desactiva, limpia el campo
    if (!checkbox.checked) {
      inputCantidad.value = '';
    }
  });
});

