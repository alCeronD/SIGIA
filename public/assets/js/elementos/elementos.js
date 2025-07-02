import { tooltipOptions } from "../utils/cases.js";

document.addEventListener('DOMContentLoaded', () => {
    // Modal Ver Más
    const modal = document.getElementById('modalVerMas');
    const modalCerrar = document.getElementById('modalCerrar');

    const tbody = document.querySelector('tbody');
    tbody.addEventListener('click', (event) => {
        const btn = event.target.closest('.btnVerMas');
        if (!btn) return;

        const fila = btn.closest('tr');
        if (!fila) return;

        const celdas = fila.querySelectorAll('td');
        document.getElementById('modalCod').textContent = celdas[0].textContent.trim();
        document.getElementById('modalPlaca').textContent = celdas[1].textContent.trim();
        document.getElementById('modalNombre').textContent = celdas[2].textContent.trim();
        document.getElementById('modalExistencia').textContent = celdas[3].textContent.trim();
        document.getElementById('modalUniMedida').textContent = celdas[4].textContent.trim();
        document.getElementById('modalTipo').textContent = celdas[5].textContent.trim();
        document.getElementById('modalEstado').textContent = celdas[6].textContent.trim();
        document.getElementById('modalArea').textContent = celdas[7].textContent.trim();

        // Mostrar filas ocultas si las hubiera
        modal.querySelectorAll('tbody tr').forEach(tr => {
            tr.style.display = 'table-row';
        });

        modal.classList.add('show');
    });

    modalCerrar.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    modal.addEventListener('click', e => {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });

    // Filtro elementos y ocultar columna "Tipo de Elemento"
    document.getElementById('filtroTipo').addEventListener('change', function() {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('tbody tr');
        const tabla = document.querySelector('.table'); // la tabla completa
        const tipoElementoColIndex = 5;

        filas.forEach(fila => {
            const tipoFila = fila.getAttribute('data-tipo');
            if (filtro === 'todos' || tipoFila === filtro) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });

        // Mostrar/ocultar columna "Tipo de Elemento"
        const mostrarColumna = filtro === 'todos';
        const ths = tabla.querySelectorAll('thead th');
        if (ths[tipoElementoColIndex]) {
            ths[tipoElementoColIndex].style.display = mostrarColumna ? '' : 'none';
        }
        filas.forEach(fila => {
            const tds = fila.querySelectorAll('td');
            if (tds[tipoElementoColIndex]) {
                tds[tipoElementoColIndex].style.display = mostrarColumna ? '' : 'none';
            }
        });
    });

    //Agregar existencia al elemento consumible.
    const btnAddCantidad = document.querySelector('#btnAddCantidad');
    console.log(btnAddCantidad);

    btnAddCantidad.addEventListener('click', (e)=>{

      e.stopPropagation();
      e.preventDefault();

      if (e.target.tagName === 'BUTTON') {
        console.log(e.target);
      }

    });

    //cuerpo de la tabla.
    const tbodyElementos = document.querySelectorAll('#tbodyElementos tr');
    tbodyElementos.forEach((el)=>{
      console.log(el);

    });

    // Manejo del modal Registrar
    const abrirModalBtn = document.getElementById('abrirModalRegistrar');
    const modalRegistrar = document.getElementById('modalRegistrar');
    const cerrarModalBtn = document.getElementById('cerrarModalRegistrar');
    const tipoElementoSelect = document.getElementById('tipoElementoSelect');
    const formDevolutivo = document.getElementById('formDevolutivo');
    const formConsumible = document.getElementById('formConsumible');

    abrirModalBtn.addEventListener('click', (e) => {
        e.preventDefault();
        modalRegistrar.style.display = 'flex';
        tipoElementoSelect.value = '';
        formDevolutivo.style.display = 'none';
        formConsumible.style.display = 'none';
        formDevolutivo.reset();
        formConsumible.reset();
    });

    cerrarModalBtn.addEventListener('click', () => {
        modalRegistrar.style.display = 'none';
    });

    tipoElementoSelect.addEventListener('change', () => {
        if (tipoElementoSelect.value === 'devolutivo') {
            formDevolutivo.style.display = 'block';
            formConsumible.style.display = 'none';
            if (window.M) {
                M.FormSelect.init(formDevolutivo.querySelectorAll('select'));
            }
        } else if (tipoElementoSelect.value === 'consumible') {
            formConsumible.style.display = 'block';
            formDevolutivo.style.display = 'none';
            if (window.M) {
                M.FormSelect.init(formConsumible.querySelectorAll('select'));
            }
        } else {
            formDevolutivo.style.display = 'none';
            formConsumible.style.display = 'none';
        }
    });

    window.addEventListener('click', e => {
        if (e.target === modalRegistrar) {
            modalRegistrar.style.display = 'none';
        }
    });
});


document.addEventListener('DOMContentLoaded', () => {
  const cantidadInput = document.getElementById('elm_existencia');

  cantidadInput.addEventListener('blur', () => {
    if (parseInt(cantidadInput.value, 10) < 1) {
      alert('La cantidad mínima es 1.');
      cantidadInput.value = 1; // Opcional: fuerza que sea 1
      cantidadInput.focus();
    }
  });

  // Opcional: también valida mientras se escribe
  cantidadInput.addEventListener('input', () => {
    if (parseInt(cantidadInput.value, 10) < 1) {
      cantidadInput.setCustomValidity('La cantidad mínima es 1.');
    } else {
      cantidadInput.setCustomValidity('');
    }
  });
});


document.addEventListener('DOMContentLoaded', () => {
  // Validar placa no negativa en devolutivo
  const placaDevolutivo = document.getElementById('elm_placa');
  if (placaDevolutivo) {
    placaDevolutivo.addEventListener('blur', () => {
      if (parseInt(placaDevolutivo.value, 10) < 0) {
        alert('La placa no puede ser un número negativo.');
        placaDevolutivo.value = 0;
        placaDevolutivo.focus();
      }
    });
    placaDevolutivo.addEventListener('input', () => {
      if (parseInt(placaDevolutivo.value, 10) < 0) {
        placaDevolutivo.setCustomValidity('La placa no puede ser negativa.');
      } else {
        placaDevolutivo.setCustomValidity('');
      }
    });
  }

  // Validar placa no negativa en consumible
  const placaConsumible = document.getElementById('elm_placa_c');
  if (placaConsumible) {
    placaConsumible.addEventListener('blur', () => {
      if (parseInt(placaConsumible.value, 10) < 0) {
        alert('La placa no puede ser un número negativo.');
        placaConsumible.value = 0;
        placaConsumible.focus();
      }
    });
    placaConsumible.addEventListener('input', () => {
      if (parseInt(placaConsumible.value, 10) < 0) {
        placaConsumible.setCustomValidity('La placa no puede ser negativa.');
      } else {
        placaConsumible.setCustomValidity('');
      }
    });
  }
});



//MODAL EDITAR
document.querySelectorAll('.editar-btn').forEach(btn => {
  btn.addEventListener('click', e => {
    e.preventDefault();

    const codigo = btn.getAttribute('data-cod');
    const elemento = window.elementosData.find(el => String(el.codigoElemento) === codigo);

    if (!elemento) {
      alert('Elemento no encontrado');
      return;
    }

    // Inputs ocultos
    document.getElementById('elm_cod').value = elemento.codigoElemento;
    document.getElementById('elm_cod_tp_elemento').value = elemento.codTipoElemento || '';

    // Mostrar etiquetas (solo lectura)
    document.getElementById('label_placa').textContent = elemento.placa || '';
    document.getElementById('label_existencia').textContent = elemento.cantidad || '';
    document.getElementById('label_tipoElemento').textContent = elemento.tipoElemento || '';

    // Inputs editables
    document.getElementById('elm_nombre').value = elemento.nombreElemento || '';
    document.getElementById('elm_uni_medida').value = elemento.unidadMedida || '';

    // Actualizar labels para inputs con valor
    M.updateTextFields();

    // Área: seleccionar opción correcta
    const selectArea = document.getElementById('elm_area_cod');
    const areaCodigo = elemento.elm_area_cod || elemento.codigoArea || '';

    // Destruir instancia anterior si existe
    const instance = M.FormSelect.getInstance(selectArea);
    if (instance) instance.destroy();

    // Asignar seleccionado
    for (let option of selectArea.options) {
      option.selected = option.value == areaCodigo;
    }

    // Inicializar select para refrescar UI
    M.FormSelect.init(selectArea);

    // Mostrar modal
    document.getElementById('modalEditarElemento').style.display = 'flex';
  });
});

// Cerrar modal
document.getElementById('cerrarModalEditar').addEventListener('click', () => {
  document.getElementById('modalEditarElemento').style.display = 'none';
});
document.getElementById('cancelarEditar').addEventListener('click', () => {
  document.getElementById('modalEditarElemento').style.display = 'none';
});

//FILTRO DE BUSQUEDA

document.addEventListener('DOMContentLoaded', function () {
    const inputBusqueda = document.getElementById('inputBusqueda');
    const btnBuscar = document.getElementById('btnBuscar');
    const filas = document.querySelectorAll('tbody tr');

    btnBuscar.addEventListener('click', function (e) {
        e.preventDefault(); // Evita que el botón recargue la página
        const filtro = inputBusqueda.value.toLowerCase().trim();

        filas.forEach(fila => {
            const placa = fila.cells[1].textContent.toLowerCase();
            const nombre = fila.cells[2].textContent.toLowerCase();

            if (placa.includes(filtro) || nombre.includes(filtro)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    });

    // También puedes buscar al escribir directamente
    inputBusqueda.addEventListener('keyup', function () {
        const filtro = inputBusqueda.value.toLowerCase().trim();

        filas.forEach(fila => {
            const placa = fila.cells[1].textContent.toLowerCase();
            const nombre = fila.cells[2].textContent.toLowerCase();

            if (placa.includes(filtro) || nombre.includes(filtro)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    });
});



  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.tooltipped');
    var instances = M.Tooltip.init(elems, tooltipOptions);
  });
