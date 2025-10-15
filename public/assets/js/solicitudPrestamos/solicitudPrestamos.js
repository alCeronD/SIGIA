/////////////////////////////////
// IMPORTACIONES Y CONSTANTES
/////////////////////////////////

import { initAlert, toastOptions } from "../utils/cases.js";

const itemsPorPagina = 5;

/////////////////////////////////
// INICIALIZACIÓN GENERAL
/////////////////////////////////

document.addEventListener("DOMContentLoaded", function () {
  M.Modal.init(document.querySelectorAll(".modal"));
  M.FormSelect.init(document.querySelectorAll("select"));

  initDatepickers();
  initFiltroDevolutivos();
  initFiltroConsumibles();
  initTextareaObservacion();
  initFormularioSolicitud();
});

/////////////////////////////////
// DATEPICKERS
/////////////////////////////////

function initDatepickers() {
  const presFchReserva = document.getElementById("pres_fch_reserva");
  const presFchEntrega = document.getElementById("pres_fch_entrega");

  if (!presFchReserva || !presFchEntrega) return;

  // Inicializa datepicker de entrega por defecto
  M.Datepicker.init(presFchEntrega, {
    format: "yyyy-mm-dd",
    minDate: new Date(),
    autoClose: true,
    i18n: { cancel: "Cancelar", clear: "Limpiar", done: "Aceptar" },
  });

  // Inicializa datepicker de reserva con onSelect
  M.Datepicker.init(presFchReserva, {
    format: "yyyy-mm-dd",
    minDate: new Date(),
    autoClose: true,
    i18n: { cancel: "Cancelar", clear: "Limpiar", done: "Aceptar" },
    onSelect: (fechaReserva) => {
      const pickerEntrega = M.Datepicker.getInstance(presFchEntrega);
      if (pickerEntrega) pickerEntrega.destroy();
      M.Datepicker.init(presFchEntrega, {
        format: "yyyy-mm-dd",
        minDate: fechaReserva,
        autoClose: true,
        i18n: { cancel: "Cancelar", clear: "Limpiar", done: "Aceptar" },
      });
      presFchEntrega.value = "";
    },
  });
}

/////////////////////////////////
// PAGINACIÓN Y FILTRADO - DEVOLUTIVOS
/////////////////////////////////

// function initFiltroDevolutivos() {
//   const filtroArea = document.getElementById("filtro_area_modal");
//   const paginacion = document.getElementById("paginacion");
//   let filasOriginales = [];
//   let filasFiltradas = [];

//   function inicializarFilas() {
//     filasOriginales = Array.from(
//       document.querySelectorAll("#tabla-elementos-devolutivos-modal tr")
//     );
//     filasFiltradas = [...filasOriginales];
//     generarPaginacion();
//   }

//   function actualizarTabla(pagina) {
//     filasOriginales.forEach((fila) => (fila.style.display = "none"));
//     const inicio = (pagina - 1) * itemsPorPagina;
//     const fin = inicio + itemsPorPagina;
//     filasFiltradas.slice(inicio, fin).forEach((fila) => (fila.style.display = "table-row"));
//   }

//   // function generarPaginacion() {
//   //   paginacion.innerHTML = "";
//   //   const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);
//   //   for (let i = 1; i <= totalPaginas; i++) {
//   //     const li = document.createElement("li");
//   //     li.classList.add("waves-effect");
//   //     li.innerHTML = `<a href="#!">${i}</a>`;
//   //     li.addEventListener("click", (e) => {
//   //       e.preventDefault();
//   //       actualizarTabla(i);
//   //       document.querySelectorAll("#paginacion li").forEach((el) => el.classList.remove("active"));
//   //       li.classList.add("active");
//   //     });
//   //     paginacion.appendChild(li);
//   //   }

//   //   if (totalPaginas > 0) {
//   //     paginacion.firstChild.classList.add("active");
//   //     actualizarTabla(1);
//   //   }
//   // }
//   function generarPaginacion() {
//     paginacion.innerHTML = "";
//     const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);

//     // máximo de páginas visibles en la barra
//     const maxVisible = 5;
//     let currentPage = 1;

//     function renderPagination() {
//       paginacion.innerHTML = "";

//       // calcular inicio y fin de ventana
//       let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
//       let endPage = Math.min(totalPaginas, startPage + maxVisible - 1);

//       // corregir cuando estamos cerca del final
//       if (endPage - startPage < maxVisible - 1) {
//         startPage = Math.max(1, endPage - maxVisible + 1);
//       }

//       // botón Anterior
//       if (currentPage > 1) {
//         const liPrev = document.createElement("li");
//         liPrev.classList.add("waves-effect");
//         liPrev.innerHTML = `<a href="#!"><i class="material-icons">chevron_left</i></a>`;
//         liPrev.addEventListener("click", () => {
//           currentPage--;
//           actualizarTabla(currentPage);
//           renderPagination();
//         });
//         paginacion.appendChild(liPrev);
//       }

//       // números de página
//       for (let i = startPage; i <= endPage; i++) {
//         const li = document.createElement("li");
//         li.classList.add("waves-effect");
//         if (i === currentPage) li.classList.add("active");
//         li.innerHTML = `<a href="#!">${i}</a>`;
//         li.addEventListener("click", () => {
//           currentPage = i;
//           actualizarTabla(currentPage);
//           renderPagination();
//         });
//         paginacion.appendChild(li);
//       }

//       // botón Siguiente
//       if (currentPage < totalPaginas) {
//         const liNext = document.createElement("li");
//         liNext.classList.add("waves-effect");
//         liNext.innerHTML = `<a href="#!"><i class="material-icons">chevron_right</i></a>`;
//         liNext.addEventListener("click", () => {
//           currentPage++;
//           actualizarTabla(currentPage);
//           renderPagination();
//         });
//         paginacion.appendChild(liNext);
//       }
//     }

//     if (totalPaginas > 0) {
//       actualizarTabla(currentPage);
//       renderPagination();
//     }
//   }


//   if (filtroArea) {
//     filtroArea.addEventListener("change", () => {
//       const selectedArea = filtroArea.value;
//       filasFiltradas = filasOriginales.filter((fila) => {
//         const area = fila.getAttribute("data-area");
//         return selectedArea === "" || area === selectedArea;
//       });
//       generarPaginacion();
//     });
//   }

//   const modalTrigger = document.querySelector(".modal-trigger");
//   if (modalTrigger) {
//     modalTrigger.addEventListener("click", () => {
//       setTimeout(() => inicializarFilas(), 100);
//     });
//   }
// }

function initFiltroDevolutivos() {
  const filtroArea = document.getElementById("filtro_area_modal");
  const paginacion = document.getElementById("paginacion");
  let filasOriginales = [];
  let filasFiltradas = [];
  let currentPage = 1; // 👈 se mantiene global para este init

  function inicializarFilas() {
    filasOriginales = Array.from(
      document.querySelectorAll("#tabla-elementos-devolutivos-modal tr")
    );
    filasFiltradas = [...filasOriginales];
    generarPaginacion();
  }

  function actualizarTabla(pagina) {
    filasOriginales.forEach((fila) => (fila.style.display = "none"));
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;
    filasFiltradas.slice(inicio, fin).forEach((fila) => (fila.style.display = "table-row"));
  }

  function generarPaginacion() {
    paginacion.innerHTML = "";
    const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);

    const maxVisible = 5;

    function renderPagination() {
      paginacion.innerHTML = "";

      let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
      let endPage = Math.min(totalPaginas, startPage + maxVisible - 1);

      if (endPage - startPage < maxVisible - 1) {
        startPage = Math.max(1, endPage - maxVisible + 1);
      }

      // botón Anterior
      if (currentPage > 1) {
        const liPrev = document.createElement("li");
        liPrev.classList.add("waves-effect");
        liPrev.innerHTML = `<a href="#!"><i class="material-icons">chevron_left</i></a>`;
        liPrev.addEventListener("click", () => {
          currentPage--;
          actualizarTabla(currentPage);
          renderPagination();
        });
        paginacion.appendChild(liPrev);
      }

      // números de página
      for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement("li");
        li.classList.add("waves-effect");
        if (i === currentPage) li.classList.add("active");
        li.innerHTML = `<a href="#!">${i}</a>`;
        li.addEventListener("click", () => {
          currentPage = i;
          actualizarTabla(currentPage);
          renderPagination();
        });
        paginacion.appendChild(li);
      }

      // botón Siguiente
      if (currentPage < totalPaginas) {
        const liNext = document.createElement("li");
        liNext.classList.add("waves-effect");
        liNext.innerHTML = `<a href="#!"><i class="material-icons">chevron_right</i></a>`;
        liNext.addEventListener("click", () => {
          currentPage++;
          actualizarTabla(currentPage);
          renderPagination();
        });
        paginacion.appendChild(liNext);
      }
    }

    if (totalPaginas > 0) {
      if (currentPage > totalPaginas) currentPage = totalPaginas; // 👈 evitar error si filtras y quedan menos páginas
      actualizarTabla(currentPage);
      renderPagination();
    }
  }

  if (filtroArea) {
    filtroArea.addEventListener("change", () => {
      const selectedArea = filtroArea.value;
      filasFiltradas = filasOriginales.filter((fila) => {
        const area = fila.getAttribute("data-area");
        return selectedArea === "" || area === selectedArea;
      });
      currentPage = 1; // 👈 reset solo cuando cambias filtro
      generarPaginacion();
    });
  }

  const modalTrigger = document.querySelector(".modal-trigger");
  if (modalTrigger) {
    modalTrigger.addEventListener("click", () => {
      setTimeout(() => inicializarFilas(), 100);
    });
  }
}


/////////////////////////////////
// ARREGLOS DE ELEMENTOS
/////////////////////////////////

let devolutivos = [];
let consumibles = [];

function addElementos({ typeElement = null, cantidad = 0, codigoElemento = null, input = null } = {}) {
  if (input.checked && typeElement === "1") {
    devolutivos.push({ codigo: codigoElemento, cantidad });
  } else if (!input.checked && typeElement === "1") {
    devolutivos = devolutivos.filter((d) => d.codigo !== codigoElemento);
  }

  if (input.checked && typeElement === "2") {
    consumibles.push({ codigo: codigoElemento, cantidad });
  } else if (!input.checked && typeElement === "2") {
    consumibles = consumibles.filter((c) => c.codigo !== codigoElemento);
  }
}

document.querySelectorAll("input[name='elementosDevolutivos']").forEach((elm) => {
  elm.addEventListener("change", (e) => {
    const input = e.target;
    addElementos({
      typeElement: input.dataset.tpelementodev,
      cantidad: input.dataset.cantidaddev,
      codigoElemento: input.value,
      input: input,
    });
  });
});

document.querySelectorAll("#tabla-elementos-consumibles-modal tr").forEach((fila) => {
  const checkbox = fila.querySelector('input[type="checkbox"]');
  const inputCantidad = fila.querySelector(".cantConsu");

  inputCantidad.disabled = true;

  checkbox.addEventListener("change", () => {
    inputCantidad.disabled = !checkbox.checked;
    if (!checkbox.checked) {
      inputCantidad.value = "";
      consumibles = consumibles.filter((item) => item.codigo !== checkbox.value);
    }
  });

  inputCantidad.addEventListener("input", () => {
    if (checkbox.checked) {
      addElementos({
        typeElement: checkbox.dataset.tpelementocons,
        cantidad: inputCantidad.value,
        codigoElemento: checkbox.value,
        input: checkbox,
      });
    }
  });
});

/////////////////////////////////
// CONTADOR OBSERVACIÓN
/////////////////////////////////

function initTextareaObservacion() {
  const textareaObs = document.getElementById("pres_observacion");
  const contador = document.getElementById("contadorObservacion");

  if (textareaObs && contador) {
    textareaObs.addEventListener("input", () => {
      contador.textContent = `${textareaObs.value.length} / 50`;
    });
  }
}

/////////////////////////////////
// FORMULARIO DE ENVÍO
/////////////////////////////////

function initFormularioSolicitud() {
  document.getElementById("formSolicitudPrestamo").addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    formData.delete("elementosDevolutivos");

    const getDataformulario = Object.fromEntries(formData.entries());
    getDataformulario.action = "registrarPrestamo";

    const data = {
      ...getDataformulario,
      elementos_consumibles: consumibles,
      elementos_devolutivos: devolutivos,
    };

    try {
      const response = await fetch(
        "modules/solicitudPrestamos/controller/solicitudPrestamosController.php",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
        }
      );
      const result = await response.json();

      if (result.status === "success") {
        initAlert(result.message, "succes", toastOptions);
        form.reset();
        M.FormSelect.init(document.querySelectorAll("select"));
        M.textareaAutoResize(document.querySelector("#pres_observacion"));
        // document.getElementById("contador-observacion").textContent = "0 / 50";
        const contador = document.getElementById("contadorObservacion");
        if (contador) {
          contador.textContent = "0 / 50";
        }

        document.querySelectorAll(".cantConsu").forEach((input) => {
          input.value = "";
          input.disabled = true;
        });
        document.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
          checkbox.checked = false;
        });
        const fechaEntrega = document.getElementById("pres_fch_entrega");
        const fechaReserva = document.getElementById("pres_fch_reserva");
        if (fechaEntrega) fechaEntrega.value = "";
        if (fechaReserva) fechaReserva.value = "";
        setTimeout(() => location.reload(), 15000);
      } else {
        M.toast({ html: result.message || "Error al registrar el préstamo", classes: "red" });
      }
    } catch (error) {
      // console.error(error);
      M.toast({ html: "Error inesperado al enviar el formulario", classes: "red" });
    }
  });
}

/////////////////////////////////
// PAGINACIÓN CONSUMIBLES
/////////////////////////////////

// function initFiltroConsumibles() {
//   const filtroArea = document.getElementById("filtro_area_modal_consumibles");
//   const paginacion = document.getElementById("paginacion_consumibles");
//   const filasOriginales = Array.from(
//     document.querySelectorAll("#tabla-elementos-consumibles-modal tr")
//   );
//   let filasFiltradas = [...filasOriginales];

//   function actualizarTabla(pagina) {
//     filasOriginales.forEach((fila) => (fila.style.display = "none"));
//     const inicio = (pagina - 1) * itemsPorPagina;
//     const fin = inicio + itemsPorPagina;
//     filasFiltradas.slice(inicio, fin).forEach((fila) => (fila.style.display = "table-row"));
//   }

//   // function generarPaginacion() {
//   //   paginacion.innerHTML = "";
//   //   const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);
//   //   for (let i = 1; i <= totalPaginas; i++) {
//   //     const li = document.createElement("li");
//   //     li.classList.add("waves-effect");
//   //     li.innerHTML = `<a href="#!">${i}</a>`;
//   //     li.addEventListener("click", (e) => {
//   //       e.preventDefault();
//   //       actualizarTabla(i);
//   //       document
//   //         .querySelectorAll("#paginacion_consumibles li")
//   //         .forEach((el) => el.classList.remove("active"));
//   //       li.classList.add("active");
//   //     });
//   //     paginacion.appendChild(li);
//   //   }

//   //   if (totalPaginas > 0) {
//   //     paginacion.firstChild.classList.add("active");
//   //     actualizarTabla(1);
//   //   }
//   // }

//   if (filtroArea) {
//     filtroArea.addEventListener("change", () => {
//       const selectedArea = filtroArea.value;
//       filasFiltradas = filasOriginales.filter((fila) => {
//         const area = fila.getAttribute("data-area");
//         return selectedArea === "" || area === selectedArea;
//       });
//       generarPaginacion();
//     });
//   }

//   const modalTrigger = document.querySelector('a[href="#modalSeleccionConsumibles"]');
//   if (modalTrigger) {
//     modalTrigger.addEventListener("click", () => {
//       setTimeout(() => {
//         filasFiltradas = [...filasOriginales];
//         generarPaginacion();
//       }, 100);
//     });
//   }
// }

function initFiltroConsumibles() {
  const filtroArea = document.getElementById("filtro_area_modal_consumibles");
  const paginacion = document.getElementById("paginacion_consumibles");
  const filasOriginales = Array.from(
    document.querySelectorAll("#tabla-elementos-consumibles-modal tr")
  );
  let filasFiltradas = [...filasOriginales];
  let currentPage = 1; // 👈 variable propia para consumibles

  function actualizarTabla(pagina) {
    filasOriginales.forEach((fila) => (fila.style.display = "none"));
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;
    filasFiltradas.slice(inicio, fin).forEach(
      (fila) => (fila.style.display = "table-row")
    );
  }

  function generarPaginacion() {
    paginacion.innerHTML = "";
    const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);
    const maxVisible = 5;

    function renderPagination() {
      paginacion.innerHTML = "";

      let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
      let endPage = Math.min(totalPaginas, startPage + maxVisible - 1);

      if (endPage - startPage < maxVisible - 1) {
        startPage = Math.max(1, endPage - maxVisible + 1);
      }

      // Botón Anterior
      if (currentPage > 1) {
        const liPrev = document.createElement("li");
        liPrev.classList.add("waves-effect");
        liPrev.innerHTML = `<a href="#!"><i class="material-icons">chevron_left</i></a>`;
        liPrev.addEventListener("click", () => {
          currentPage--;
          actualizarTabla(currentPage);
          renderPagination();
        });
        paginacion.appendChild(liPrev);
      }

      // Botones numéricos
      for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement("li");
        li.classList.add("waves-effect");
        if (i === currentPage) li.classList.add("active");
        li.innerHTML = `<a href="#!">${i}</a>`;
        li.addEventListener("click", () => {
          currentPage = i;
          actualizarTabla(currentPage);
          renderPagination();
        });
        paginacion.appendChild(li);
      }

      // Botón Siguiente
      if (currentPage < totalPaginas) {
        const liNext = document.createElement("li");
        liNext.classList.add("waves-effect");
        liNext.innerHTML = `<a href="#!"><i class="material-icons">chevron_right</i></a>`;
        liNext.addEventListener("click", () => {
          currentPage++;
          actualizarTabla(currentPage);
          renderPagination();
        });
        paginacion.appendChild(liNext);
      }
    }

    if (totalPaginas > 0) {
      if (currentPage > totalPaginas) currentPage = totalPaginas;
      actualizarTabla(currentPage);
      renderPagination();
    }
  }

  if (filtroArea) {
    filtroArea.addEventListener("change", () => {
      const selectedArea = filtroArea.value;
      filasFiltradas = filasOriginales.filter((fila) => {
        const area = fila.getAttribute("data-area");
        return selectedArea === "" || area === selectedArea;
      });
      currentPage = 1; // 👈 reset al cambiar filtro
      generarPaginacion();
    });
  }

  const modalTrigger = document.querySelector(
    'a[href="#modalSeleccionConsumibles"]'
  );
  if (modalTrigger) {
    modalTrigger.addEventListener("click", () => {
      setTimeout(() => {
        filasFiltradas = [...filasOriginales];
        currentPage = 1; // 👈 reset cuando se abre modal
        generarPaginacion();
      }, 100);
    });
  }
}




/////////////////////////////////
// VALIDACIÓN INPUT NUMÉRICO
/////////////////////////////////

document.querySelectorAll(".cantConsu").forEach((input) => {
  input.addEventListener("input", function () {
    const max = parseInt(this.getAttribute("max"), 10);
    let valor = this.value;

    if (!/^\d*$/.test(valor)) {
      this.value = "";
      return;
    }

    valor = parseInt(valor, 10);
    if (valor < 1) this.value = 1;
    else if (valor > max) this.value = max;
  });

  input.addEventListener("keydown", function (e) {
    const teclasPermitidas = ["Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete"];
    if (!teclasPermitidas.includes(e.key) && (e.key < "0" || e.key > "9")) {
      e.preventDefault();
    }
  });
});
