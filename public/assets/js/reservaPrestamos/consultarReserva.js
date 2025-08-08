import { Ajax } from "../utils/ajax.js";
import {
  addClassItem,
  closeModal,
  createBtn,
  createI,
  initAlert,
  instanceModal,
  options,
  setReserva,
  statusLoans,
  toastOptions,
  tooltipOptions,
  typeLoans,
} from "../utils/cases.js";
import { getData, sendData } from "../utils/fetch.js";

// tipos de prestamos
const typesPrestamosLoan  ={
  all: 'all',
  validate: 'validate',
  done: 'done',
  toValidate: 'toValidate'
};

// Selector del filtro.
const filtroTipoReserva = document.querySelector('#filtroTipoReserva');

// Página actual de los prestamos
let currentPage =1;
const objAjax = new Ajax();
//Cuerpo de la tabla para renderizar los datos.
const tbodyReservaConsult = document.querySelector("#tbodyReservaConsult");
const modalDetail = instanceModal("#modalDetail", options);
const modalValidate = instanceModal("#modalValidate", options);
const bodyDetailValidate = document.querySelector("#bodyDetailValidate");
const btnCloseValidte = document.querySelector("#modalValidate .close-modal");
const btnCloseElements = document.querySelector("#modalDetail .close-modal");
const formDetail = document.querySelector("#formDetail");
// El contenido de la tabla del modal validatePrestamo
const tableContainerDetail = document.querySelector(
  ".tableContainerDetail table"
);
// Contenedor principal de modalValidate
const containerDetail = document.querySelector('.tableContainerDetail');
let consumibles = [];
let devolutivos = [];
// Contenedor del formulario
// const formValidateContainer = document.querySelector(".formValidateContainer");
//TODO: mejorarlo.
let data;
//variable para guardar los elementos
let elementos = {};
const BodydetailReserva = document.querySelector("#BodydetailReserva");
//Codigo del prestamo para hacer el fech
let cases = "reservas";
let codigo;
let pages;
// En esta variable guardo toda la información que voy a enviar cuando doy salida a los elementos.
let validateReserva;
//Página actual.
let pagesReserva = 1;
// Checkbox para validar TODOS LOS ELEMENTOS
const checkBoxValidate = document.querySelector("#allValidateItems");
// capturo el input de la tabla para seleccionarlos todos.
const inputValidate = document.querySelectorAll(".inputValidate");
const nextBtnValidate = document.querySelector("#btnNextValidate");
//Variable para mostrar la información en el modal.
let elementosDetalle = [];

const renderReservas = async ({page = 1, type = 'all'} = {}) => {
  pagesReserva = page;

  try {
    //Traigo la data por medio de fetch.
  const result = await getData(
    "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
    "GET",
    { action: "reservas", pages: page, type },false
  );

  let status = result.status;
  data = result.data.data;
  pages = result.data.pages;
  if (status && data.length === 0) {
    tbodyReservaConsult.innerHTML = "";
    tbodyReservaConsult.innerHTML = "No hay prestamos para el estado del prestamo seleccionado.";
    return;
  }
  if (pagesReserva > pages) return;
  tbodyReservaConsult.innerHTML = "";
  data.forEach((dta) => {
    codigo = dta.codigo;
    let tr = document.createElement("tr");
    let btnAdd = document.createElement("button");
    let btnEnd = document.createElement("button");
    let btnDetail = document.createElement("button");
    btnAdd.setAttribute("class", "btn waves-effect waves-light");
    let btnValidateLoan = createBtn("btnClick");
    let iDetalle = createI();
    btnDetail.append(iDetalle);
    iDetalle.innerText = "info";
    let iValidate = createI();
    iValidate.innerText = "done";

    btnDetail.setAttribute("class", "btnDetail btnClick");
    btnDetail.setAttribute("data-id", `${dta.codigo}`);
    btnAdd.setAttribute("class", "addElements");
    btnAdd.setAttribute("data-add", `${dta.codigo}`);
    btnEnd.setAttribute("data-end", `${dta.codigo}`);
    btnValidateLoan.setAttribute("data-validate", `${dta.codigo}`);
    let iFinalizar = createI();
    iFinalizar.innerText = "swap_horiz";
    btnAdd.setAttribute("class", "btnEnd");
    addClassItem(btnDetail, {
      btn: "btn",
      wavesEffect: "waves-effect",
      wavesLight: "wares-light",
      btnSmall: "btn-small",
    });
    addClassItem(btnValidateLoan, {
      btn: "btn",
      color: "yellow darken-2",
      wavesEffect: "waves-effect",
      wavesLight: "waves-light",
      btnSmall: "btn-small",
    });
    addClassItem(btnEnd, {
      btn: "btn",
      color: "red lighten-1",
      wavesEffect: "waves-effect",
      wavesLight: "waves-light",
      btnSmall: "btn-small",
    });
    btnValidateLoan.append(iValidate);
    btnEnd.append(iFinalizar);
    let tdCodigo = document.createElement("td");
    let tdNombreCompleto = document.createElement("td");
    let tdFechaRegistro = document.createElement("td");
    let tdCantidad = document.createElement("td");
    let tdEstado = document.createElement("td");
    let tdAcciones = document.createElement("td");
    let tdTipo = document.createElement("td");
    tdCodigo.textContent = dta.codigo;
    tdNombreCompleto.textContent = dta.nombre + " " + dta.apellido;
    tdEstado.textContent = dta.estadoPrestamo;
    tdTipo.textContent = dta.tipoPrestamo;
    tdFechaRegistro.textContent = dta.fechaSolicitud;

    tbodyReservaConsult.appendChild(tr);
    tr.appendChild(tdCodigo);
    tr.appendChild(tdFechaRegistro);
    tr.appendChild(tdNombreCompleto);
    tr.appendChild(tdEstado);
    tr.appendChild(tdTipo);
    
    tdAcciones.innerHTML = "";
    tr.append(tdAcciones);

    if (tdEstado.textContent === "Finalizado") {
      btnEnd.style.display = "none";
      tdEstado.style.color = "gray";
    }

    if (tdEstado.textContent === "Rechazado") {
      tdEstado.style.color = "red";
    }

    if (tdEstado.textContent === "Validado") {
      tdEstado.style.color = "green";
    }

    tdAcciones.appendChild(btnDetail);

    if (dta.estadoPrestamo === "Finalizado") {
      return;
    }

    if (dta.codigoTipoPrestamo === typeLoans.solicitud) {
      if (dta.estadoPrestamo === "Por validar") {
        tdAcciones.appendChild(btnValidateLoan);
      } else if (dta.estadoPrestamo === "Validado") {
        tdAcciones.appendChild(btnEnd);
      }
    }

    if (dta.codigoTipoPrestamo === typeLoans.inmediata) {
      // En préstamos inmediatos, ya están validados desde el backend
      if (dta.estadoPrestamo === "Validado") {
        tdAcciones.appendChild(btnEnd);
      }
    }

    //Re factorizarlo y transformarlo en fetch, en una sola función.
    const reserva = data.find((item) => item.codigo === codigo);
    if (reserva) {
      let getReservaElementos =  getData(
        "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
        "GET",
        { action: "reservaDetailElements" , codigo: dta.codigo}
      ).then((result)=>{

        //Guardo el código de la reserva con los elementos que están asociados a ese prestamo.
        //Response.data tiene los elementos.
        elementos[dta.codigo] = {
          reserva: dta,
          elementos: result.data,
        };

      });

    }
  });
  } catch (error) {
    console.warn(`Error al procesar la solicitud, intente más tarde ${error}`);
    tbodyReservaConsult.innerHTML = "Error al realizar la solicitud, intente nuevamente";
  }
};

//Estas variables las uso para guardar los elementos que no han sido validados.
let noselectedDevolutivos = [];
let noselectedConsumibles = [];


function validateCheckboxChecked(inputValidate, checkBoxValidate) {
  /**
   * Con la propiedad array from me extrae el elemento en concreo que se ha chequeado, luego de ello, me valida quue alguno de esos elementos este chequeados para así determinar que el btnNextValidate se visualice.
   */
  const elementChecked = Array.from(inputValidate).some(
    (input) => input.checked
  );
  checkBoxValidate.checked = elementChecked;
  // nextBtnValidate.style.display = checkBoxValidate.checked ? "flex" : "none";
  if (elementChecked) {
    nextBtnValidate.style.display = "flex";
  } else {
    nextBtnValidate.style.display = "none";
  }
}

function addElementsToArray(input, cantidadPersonalizada = null) {
  const tipo = input.dataset.tipoElemento;
  const cod = input.dataset.codigo;
  const nombre = input.dataset.nombreElemento;
  const cantidad = cantidadPersonalizada ?? input.dataset.cantidadSalida;

  if (input.checked) {
    if (tipo === "Devolutivo" && !devolutivos.find(dev => dev.cod === cod)) {
      devolutivos.push({ tipo, cod, nombre, cantidadSalida: cantidad });
    }

    if (tipo === "Consumible") {
      // Reemplazo el elemento si ya existe.
      consumibles = consumibles.filter(consu => consu.cod !== cod); 
      consumibles.push({ tipo, cod, nombre, cantidadSalida: cantidad });
    }
  } else {
    if (tipo === "Consumible") {
      consumibles = consumibles.filter(consu => consu.cod !== cod);
    }

    if (tipo === "Devolutivo") {
      devolutivos = devolutivos.filter(dev => dev.cod !== cod);
    }
  }

}

/**
 * Reinicia la visualización del modal de validación según el estado proporcionado.
 *
 * @function resetModalValidate
 * @param {boolean} [status=false] - Define el modo del modal:
 *   - `true`: Muestra la vista previa de los elementos seleccionados (tabla).
 *   - `false`: Muestra el formulario para validar elementos.
 * Esta función restaura a su estado por defecto los inputs requeridos de modalValidate
 */
function resetModalValidate(showTable = false) {
  // Estas variables se estan re definiendo pese a que estan definidas de manera global, es para reiniciar el formulario ANTES de prestionare el botón de salida del elemento, no después.
  const table = document.querySelector("#modalValidate table");
  const formContainer = document.querySelector("#modalValidate .formValidateContainer");
  const radioInputs = document.querySelectorAll('#modalValidate input[name="radioValidate"]');
  const textareaObservacion = document.querySelector('#inputObservacion');
  const checkboxSelectAll = document.querySelector('#checkBoxValidate');
  const allValidateItems = document.querySelector('#allValidateItems');
  const btnNextValidate = document.querySelector('#btnNextValidate');

  // 1. Mostrar tabla o formulario
  if (showTable) {
    table.style.display = "table";
    formContainer.style.display = "none";
    previewBtnValidate.style.display = "none";
    nextBtnValidate.style.display = "inline-flex";
  } else {
    table.style.display = "none";
    formContainer.style.display = "flex";
    previewBtnValidate.style.display = "inline-flex";
    nextBtnValidate.style.display = "none";
  }

  // Desmarcar todos los radio buttons del formulario
  radioInputs.forEach(radio => {
    radio.checked = false;
  });

  // 3. eliminar texto del textArea
  textareaObservacion.value = '';
  textareaObservacion.disabled = true;

  // Paso 4 cambiar estado del checked de los elementos a false
  if (checkboxSelectAll) {
    checkboxSelectAll.checked = false;
  }

  // Paso 5 cambiar estado del checked general de los elementos a false
  allValidateItems.checked = false;

  // Paso 6 ocultar el botón de next
  btnNextValidate.style.display = "none";

  // Paso 6 Limpiar tabla de detalles
  BodydetailReserva.innerHTML = '';
}

/**
 * Reinicia los datos utilizados en el proceso de validación del modal.
 *
 * @function resetDataModal
 *
 * Esta función limpia los arreglos `consumibles` y `devolutivos`, así como el objeto
 * `validateReserva`, dejándolos en su estado inicial. Se utiliza normalmente cuando
 * se cancela una operación o se desea reiniciar el estado del formulario/modal.
 */
function resetDataModal() {
  consumibles = [];
  devolutivos = [];
  validateReserva = {};
}

// Responsabilidades de las consultas
tbodyReservaConsult.addEventListener("click", (event) => {
  event.preventDefault();
  event.stopPropagation();
  const btnDetail = event.target.closest("button[data-id]");
  //Apunto ahora al boton porque cuando se agrega un elemento hijo, el evento click de javascript detecta el evento hijo, no el padre, lo ideal es capturarlo mejor por el data.
  if (btnDetail) {
    let dataTr = event.target.closest("tr");
    const nroIdentidad = document.querySelector("#formDetail #nroIdentidad");
    const nombre = document.querySelector("#formDetail #nombreCompleto");
    const fechaReserva = document.querySelector("#formDetail #fechaReserva");
    const fechaSolicitud = document.querySelector(
      "#formDetail #fechaSolicitud"
    );
    const fechaDevolucion = document.querySelector(
      "#formDetail #fechaDevolucion"
    );
    const observaciones = document.querySelector("#formDetail #observaciones");

    //Busco todos los input que tengan la clase inputFormDetail y los ciclo para aplicar un readOnly con el fin de que el usuario solo pueda leer la información, no manipularla.
    const readOnly = document
      .querySelectorAll("#formDetail .inputFormDetail")
      .forEach((input) => {
        input.readOnly = true;
      });

    let nombreCompleto = dataTr.children[1].textContent;
    let tipo = dataTr.children[2].textContent;
    let estado = dataTr.children[3].textContent;
    
    const modalTitle = document.querySelector('#modalTitle');
    codigo = parseInt(btnDetail.getAttribute("data-id"));
    //TODO: refactorizarlo y transformarlo en una sola función.
    const reserva = data.find((item) => item.codigo === codigo);
    let action = "reservaDetailElements";
    //Petición para dibujar los elementos en la tabla del detail.
    //TODO: Mejorar, en la variable elementos encuentro toda la información, no necesito hacer otra petición.
    objAjax.request.open(
      "GET",
      `Modules/reservaPrestamos/controller/reservaPrestamosController.php?codigo=${encodeURIComponent(
        codigo
      )}&action=${encodeURIComponent(action)}`,
      true
    );
    objAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

    //TODO, Todo esto arreglarlo, no hay necesidad de hacer nuevamente la peticion porque en la función getReserva se encuentra toda esta información.
    objAjax.request.onload = () => {
      let response = JSON.parse(objAjax.request.responseText);
      elementosDetalle = response.data;

      BodydetailReserva.innerHTML = "";
      elementosDetalle.forEach((elm) => {
        const trTable = document.createElement("tr");
        const tdCodigo = document.createElement("td");
        const tdNombre = document.createElement("td");
        const tdCantidad = document.createElement("td");

        tdCodigo.innerText = elm.codigo;
        tdNombre.innerText = elm.nombre;
        tdCantidad.innerText = elm.cantidadSolicitada;
        trTable.appendChild(tdCodigo);
        trTable.appendChild(tdNombre);
        trTable.appendChild(tdCantidad);
        BodydetailReserva.appendChild(trTable);
      });
    };
    objAjax.request.setRequestHeader("Accept", "application/json");
    objAjax.request.send();

    modalDetail.open();
    //TODO: transformar a texto span.
    nroIdentidad.innerText = reserva.nroIdentidad;
    nombre.innerText = `${reserva.nombre} ${reserva.apellido}`;
    fechaReserva.innerText = reserva.fechaReserva;
    fechaSolicitud.innerText = reserva.fechaSolicitud;
    fechaDevolucion.innerText = reserva.fechaDevolucion;
    observaciones.innerText = reserva.observacion;
    modalTitle.innerText = `Reserva # ${reserva.codigo}`;
  }

  //Finalizar el prestamo de los elementos.
  if (
    event.target.tagName === "BUTTON" &&
    event.target.getAttribute(["data-end"])
  ) {
    //Crear función para finalizar los prestamos de los elementos, se debe de finalizar a ambos prestamos, los que se hace como reserva Previa y reserva inmediata.
    function endLoan(atributeData) {}

    //Hago la captura de la data necesaria para validar el prestamo o para reservalo inmedaitamente.
    let endReserva = setReserva(
      "data-end",
      data,
      elementos,
      event.target,
      "finalizar"
    );

    const objEndReserva = new Ajax();
    if (
      confirm(
        `¿Está seguro de finalizar el préstamo?\nEstos son los elementos que cambiarán a disponible:\n${endReserva.elementos
          .map((el) => `- ${el.nombre}`)
          .join("\n")}`
      )
    ) {
      objEndReserva.request.open(
        "POST",
        "Modules/reservaPrestamos/controller/reservaPrestamosController.php"
      );
      objEndReserva.request.setRequestHeader(
        "X-Requested-With",
        "XMLHttpRequest"
      );
      objEndReserva.request.setRequestHeader(
        "Content-Type",
        "application/json"
      );

      let reservaJson = JSON.stringify({
        data: endReserva,
        action: "finalizar",
      });

      objEndReserva.request.onload = () => {
        try {
          let response = JSON.parse(objEndReserva.request.responseText);
          console.log(response);
          if (response.status) {
            initAlert(
              `Prestamo # ${endReserva.codigoReserva} finalizada`,
              "success",
              toastOptions
            );

            // Lo ideal es renderizar esto en la página actual a la cual se encuentran los prestamos, no re direccionar a la página 1.
            renderReservas({page:1, type:valueSelect});

            let codigoAdd = endReserva.codigoReserva;
            let tr = [...document.querySelectorAll("#tbodyReservaConsult tr")];

          } else {
            initAlert(`Respuesta del servidor: \n ${response.message}`, "warning", toastOptions);
            console.warn("Respuesta negativa del servidor:", response.message);
          }
        } catch (error) {
          initAlert(`${error.message}`, "warning", toastOptions);
        }
      };
      objEndReserva.request.setRequestHeader("accept", "application/json");
      objEndReserva.request.send(reservaJson);
    } else {
      console.warn(
        "No se encontraron elementos asociados aún. Espera a que cargue la información."
      );
    }
  }

  const btnSalida = event.target.closest("button[data-validate]");
  //Dar salida a los prestamos, cambiar el estado de la solicitudes a validada e implementar su salida.
  if (btnSalida) {
    resetModalValidate(true);
    modalValidate.open();
    //Capturo los datos para transformarlo en json.
    validateReserva = setReserva("data-validate", data, elementos, btnSalida);
    let action = "validateLoan";
    validateReserva["action"] = action;
    let previewElements = validateReserva.elementos;
    bodyDetailValidate.innerHTML = "";
    //Los elementos pertenecientes al prestamo, los agrego en la tabla para validar su salida.
    previewElements.forEach((el) => {
      let tr = document.createElement("tr");
      let tdCodigo = document.createElement("td");
      let tdNombre = document.createElement("td");
      let tdCantidad = document.createElement("td");
      let tdTipoElemento = document.createElement("td");
      let tdAcciones = document.createElement("td");
      tdAcciones.setAttribute("class", "idAccionesPreviewElements");
      // Input checkbox
      let label = document.createElement("label");
      let span = document.createElement("span");
      let input = document.createElement("input");

      //Input agregar cantidad.
      let inputCantidad = document.createElement("input");
      inputCantidad.type = "number";
      inputCantidad.setAttribute("min", 0);
      inputCantidad.dataset.codigo = el.codigo;
      inputCantidad.dataset.tipoElemento = el.nombreTipoElemento;
      inputCantidad.dataset.nombreElemento = el.nombre;
      inputCantidad.dataset.cantidadSalida = el.cantidadSolicitada;
      inputCantidad.disabled = true;
      inputCantidad.classList.add("input-cantidad");
      input.type = "checkbox";
      input.classList.add("filled-in");
      input.classList.add("inputValidate");

      input.dataset.codigo = el.codigo;
      input.dataset.tipoElemento = el.nombreTipoElemento;
      input.dataset.nombreElemento = el.nombre;
      input.dataset.cantidadSalida = el.cantidadSolicitada;

      tdCodigo.innerText = el.codigo;
      tdNombre.innerText = el.nombre;
      tdTipoElemento.innerText = el.nombreTipoElemento;
      tdCantidad.innerText = el.cantidadSolicitada;
      bodyDetailValidate.appendChild(tr);
      tr.appendChild(tdCodigo);
      tr.appendChild(tdNombre);
      tr.appendChild(tdCantidad);
      tr.appendChild(tdTipoElemento);

      label.appendChild(input);
      label.appendChild(span);
      tdAcciones.appendChild(label);
      if (el.nombreTipoElemento === "Consumible") {
        tdAcciones.appendChild(inputCantidad);
      }

      tr.appendChild(tdAcciones);

    });

    const inputValidate = document.querySelectorAll(".inputValidate");
    checkBoxValidate.addEventListener("change", (e) => {
      e.stopPropagation();
      e.preventDefault();

      //Limpio los arreglos cuando el usuario deselecciona los items.
      devolutivos = [];
      consumibles = [];

      inputValidate.forEach((inV) => {


        inV.checked = e.target.checked;
        if (inV.checked) {
          addElementsToArray(inV); 
        }
      });

      validateCheckboxChecked(inputValidate, checkBoxValidate);
    });

    function addElementsNoSelected(input) {
      // Limpio los arreglos antes de agregar los elementos no seleccionados.
      noselectedConsumibles.length = 0;
      noselectedDevolutivos.length = 0;

      input.forEach((intp) => {
        if (!intp.checked) {
          const tipo = intp.dataset.tipoElemento;
          const cod = intp.dataset.codigo;
          const cantidad = intp.dataset.cantidadSalida;
          const nombreElemento = intp.dataset.nombreElemento;
          if (tipo === "Consumible" && !noselectedConsumibles.includes(cod)) {
            noselectedConsumibles.push({
              cod: cod,
              nombreElemento: nombreElemento,
              cantidad: cantidad,
            });
          }
          if (tipo === "Devolutivo" && !noselectedDevolutivos.includes(cod)) {
            // la cantidad en devolutivos no creo que lo necesitemos
            noselectedDevolutivos.push({
              cod: cod,
              nombreElemento: nombreElemento,
              cantidad: cantidad,
            });
          }
        }
      });
    }

    inputValidate.forEach((input) => {
      const row = input.closest("tr");
      const inputCantidad = row.querySelector(".input-cantidad");
      const maxCantidad = parseInt(input.dataset.cantidadSalida);
      const tipo = input.dataset.tipoElemento;

      if (inputCantidad) {
        inputCantidad.addEventListener("input", (e) => {
          const val = parseInt(e.target.value);
          if (isNaN(val) || val <= 0 || val > maxCantidad) {
            e.target.value = "";
            input.checked = false;
            inputCantidad.disabled = true;
            initAlert(
              `Ingrese una cantidad entre 1 y ${maxCantidad}`,
              "info",
              toastOptions
            );
            return;
          }
          addElementsToArray(input, val); 
        });
      }

      input.addEventListener("change", (e) => {
        const checked = e.target.checked;

        if (tipo === "Consumible") {
          if (inputCantidad) inputCantidad.disabled = !checked;
          if (!checked) inputCantidad.value = "";
          if (!checked) addElementsToArray(input); 
        }

        if (tipo === "Devolutivo") {
          addElementsToArray(input);
        }

        validateCheckboxChecked(inputValidate, checkBoxValidate);
      });
    });

    // //Variable para visualizar los elementos cuando se ha validado el prestamo
    let elementosPreviewConsu = [];
    let elementosPreviewDev = [];
    let dataTr = event.target.closest("tr");
    //Estado por validar
    let estadoNew = dataTr.children[2];
    let tdAcciones = dataTr.children[4];

    const previewBtnValidate = document.querySelector("#previewBtnValidate");
    const radioYes = document.querySelector("#radioYes");
    const radioNo = document.querySelector("#radioNo");
    //Cuando el usuario pase al siguiente paso, este valida todo
    nextBtnValidate.addEventListener("click", (e) => {
      resetModalValidate(false);
      addElementsNoSelected(inputValidate);

      validateReserva.elementosSalida = {
        elmConsumibles: consumibles,
        elmDevolutivos: devolutivos,
      };

      validateReserva.elementosRechazados = {
        elmConsumibles: noselectedConsumibles,
        elmDevolutivos: noselectedDevolutivos,
      };

      elementosPreviewConsu = validateReserva.elementosSalida.elmConsumibles;
      elementosPreviewDev = validateReserva.elementosSalida.elmDevolutivos;

    });

    previewBtnValidate.addEventListener("click", (e) => {
      e.stopPropagation();
      e.preventDefault();
      resetModalValidate(true);
    });

    // No tiene ni punto ni asterisco porque lo llamo x el nombre
    const textAreaObsInput = document.querySelector(
      'textarea[name="textarea1"]'
    );

    radioYes.addEventListener("change", (e) => {
      let radioCheck = e.target.checked;
      let radioNoChecked = radioCheck ? false : true;
      radioNo.checked = radioNoChecked;

      if (radioCheck) {
        textAreaObsInput.disabled = false;
      }
    });
    radioNo.addEventListener("change", (e) => {
      if (e.target.checked) {
        textAreaObsInput.value = "";
        textAreaObsInput.disabled = true;
      }
    });

    const formValidate = document.querySelector("#formValidate");
    formValidate.addEventListener("submit", async (e) => {
      e.stopPropagation();
      e.preventDefault();

      let form = new FormData(e.target);

      let valuesForm = Object.fromEntries(form.entries());
      let validate = Object.values(valuesForm);

      if (!validate[0]) {
        alert("Selección de observación requerida");
        return;
      }
      let observacion = !valuesForm.textarea1
        ? ""
        : valuesForm.textarea1.trim();

      // Aplico spreead para traer las propiedades previas del objeto y adiciono la observación.
      validateReserva = {
        ...validateReserva,
        observacionSalida: observacion,
      };

      let textConfirm = "";
      textConfirm += `Consumibles:\n${elementosPreviewConsu
        .map(
          (el) =>
            `Código: ${el.cod} Nombre: ${el.nombre} Cantidad: ${el.cantidadSalida}`
        )
        .join("\n")}\n`;

      textConfirm += `Devolutivos:\n${elementosPreviewDev
        .map((el) => `Código: ${el.cod} Nombre: ${el.nombre}`)
        .join("\n")}`;

      if (confirm(`¿Deseas dar salida a estos elementos? \n${textConfirm}`)) {
        try {
          const responseValidate = await sendData(
            "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
            "POST",
            "validateLoan",
            validateReserva
          );
          if (responseValidate.status) {
            estadoNew.textContent = "Validado";
            estadoNew.style.color = "green";

            let btnValidate = document.querySelector(`#tbodyReservaConsult tr td [data-validate='${validateReserva.codigoReserva}']`);
            if (btnValidate) {
              console.log(btnValidate);
              // Renderizo nuevamente basada en la pagína y el tipo.
              renderReservas({page:currentPage, type: valueSelect});
              initAlert(
                `Prestamo validado ${validateReserva.codigoReserva}`,
                "success",
                toastOptions
              );
              btnValidate.style.display = "none";
              modalValidate.close();
            }

            let btnEnd = document.createElement("button");
            let iFinalizar = createI();
            iFinalizar.innerText = "swap_horiz";
            //Este bloque de codigo se repite Más arriba, puedo buscar una forma para refactorizar.
            addClassItem(btnEnd, {
              btn: "btn",
              color: "red lighten-1",
              wavesEffect: "waves-effect",
              wavesLight: "waves-light",
              btnSmall: "btn-small",
            });
            btnEnd.append(iFinalizar);
            btnEnd.setAttribute("data-end", `${validateReserva.codigoReserva}`);
            tdAcciones.appendChild(btnEnd);
          }

        } catch (error) {
          initAlert(`${error.message}`, "error", toastOptions);
        }
      } else {
        initAlert("Proceso cancelado", "warning", tooltipOptions);
        modalValidate.close();

        // Esto se repite, lo puedo modificar haciendo no una función sino cerrando el modal usando la función close modal, para ello debo de cambiar la forma de enviar los parámetros, lo ideal, enviarlos mediante objeto.
        BodydetailReserva.innerHTML = "";
        // let falseChecked = checkBoxValidate.checked ? false : true;
        let falseChecked = false;
        checkBoxValidate.checked = falseChecked;

        previewBtnValidate.style.display = "none";
        nextBtnValidate.style.display = "none";
        resetModalValidate(true);
        resetDataModal();
      }
    });
  }
});

/**
 * aplico la función callback porque requiero de ocultar ambos botones dependiendo del contexto de cerrar el modal desde la ventana.
 */
closeModal(modalValidate, btnCloseValidte, () => {
  instanceModal('#modalValidate', {
    ...options,
    onCloseEnd: () => {
      resetDataModal();
      resetModalValidate(true); 

      // Limpiar radios
      document.querySelectorAll('#modalValidate input[name="radioValidate"]').forEach(radio => {
        radio.checked = false;
      });

      // Limpiar textarea y deshabilitar
      const observacion = document.querySelector('#inputObservacion');
      observacion.value = '';
      observacion.disabled = true;

      // Desmarcar checkbox de "seleccionar todo"
      checkBoxValidate.checked = false;

      // Limpiar la tabla
      BodydetailReserva.innerHTML = "";
    }
  });
});
/**
 * Paginación de los prestamos.
 *
 */
const previewReserva = document.querySelector("#previewReservas");
const nextReserva = document.querySelector("#nextReservas");

previewReserva.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();
  //Si es 1, el sale del evento y no ejecuta Más.
  if (pagesReserva <= 1) return;
  currentPage = currentPage - 1;
  renderReservas({page:currentPage, type:valueSelect});
});

nextReserva.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();

  //Si el numero de Páginas enviado es mayor o igual al numero de paginas que tiene los registros, no haga petición.
  if (pagesReserva >= pages) return;
  currentPage = currentPage + 1;
  renderReservas({page:currentPage, type:valueSelect});
});
let valueSelect;
filtroTipoReserva.addEventListener('change', (e)=>{
  e.preventDefault();
  e.stopPropagation();

  valueSelect = e.target.value;

  let mapTypeLoan = typesPrestamosLoan[valueSelect] ?? 'all';
  if (mapTypeLoan) {
    renderReservas({page: currentPage, type:mapTypeLoan});
  }else{
    renderReservas({page:1});
  }
});

closeModal(modalDetail, btnCloseElements);
document.addEventListener("DOMContentLoaded", () => {
  renderReservas();
  // Inicializar select 
  M.FormSelect.init(filtroTipoReserva);
});