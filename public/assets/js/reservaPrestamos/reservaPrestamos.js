import { addClassItem } from "../utils/cases.js";
import {
  Ajax,
  closeModal,
  createI,
  instanceDate,
  opcionesDatepicker,
  dateISOFormat,
  initTooltip,
  tooltipOptions,
  initAlert,
  toastOptions,
  tablesDoom,
  modalDoom,
  btnDoom,
  getData,
  inputsForm,
  iDom,
  objDataConsumibles,
  divContainers,
  mostrarConfirmacion,
  sendData,
  replaceln,
} from "./index.js";

const objAjax = new Ajax();
btnDoom.btnModalPreviewElements.append(iDom.iCreatePreview);
btnDoom.btnAddElements.classList.add("btnClick");
btnDoom.btnAddElements.append(iDom.iAddElement);
btnDoom.btnAddConsumibles.append(iDom.iAddConsumible);
btnDoom.btnSubmit.append(iDom.iSendReserva);
// Estas variables las uso para re utilizar la información en otros bloques.
let objDataDevolutivos = {};
const valuePage = document.querySelector("#valuePage");
const formSolicitudPrestamo = document.querySelector("#formSolicitudPrestamo");

tablesDoom.tblBodyUsers.innerHTML =
  '<tr><td colspan="7">Cargando usuarios...</td></tr>';

// variables que corresponden a los números de páginas de las tablas elementosDevolutivos y usuarios.
let pagesUsers;
let pagesElements;
let pagesElementsConsumibles;
let pagesElementsDevolutivos;
//Este arreglo lo voy a crear con el fin de guardar los ids de los elementos para saber cuales son los elementos seleccionados.
let ids = [];
let addElements;

/**
 * Renderiza los usuarios en una tabla HTML.
 *
 * @param {Object} options - Opciones de configuración.
 * @param {string} options.action - Acción para la consulta (por defecto: "users").
 * @param {number} options.pages - Página actual (por defecto: 1).
 * @param {boolean} options.resetToFirstPage - Si debe reiniciar a la primera página.
 */
const renderUsers = async ({
  action = "users",
  pages = 1,
  resetToFirstPage = false,
} = {}) => {
  try {
    const response = await getData(
      "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
      "GET",
      { action: action, pages }
    );

    let data = response.data.data;
    let allPages = response.data.pages;

    //Evitar que no se aumente la página más allá del número de páginas.
    if (pgUsers > allPages) {
      pgUsers = allPages;
      return;
    }

    if (resetToFirstPage) {
      pgUsers = 1;
    }
    //Limpio los selects, evito que hayan duplicados.
    for (let index = 1; index <= allPages; index++) {
      let pagesOptions = document.createElement("option");
      pagesOptions.value = index;
      pagesOptions.innerHTML = index;
      // valuePage.append(pagesOptions);
    }
    //por defecto, lo coloco en 1.
    // valuePage.value = String(pgUsers);

    tablesDoom.tblBodyUsers.innerHTML = "";
    data.forEach((us) => {
      let btnAdd = document.createElement("button");
      btnAdd.setAttribute("type", "button");
      addClassItem(btnAdd, {
        btn: "btn",
        effect: "waves-effect",
        light: "waves-light",
      });
      // Envio la función a crear, no puedo retornar el nodo, porque sino se reemplaza.
      btnAdd.appendChild(createI("add"));
      let trTableUsers = document.createElement("tr");
      let tdNroDocumento = document.createElement("td");
      let tdNombre = document.createElement("td");
      let tdApellido = document.createElement("td");
      let tdTelefono = document.createElement("td");
      let tdEmail = document.createElement("td");
      let tdRol = document.createElement("td");
      let tdAcciones = document.createElement("td");

      tdNroDocumento.textContent = us.nroDocumento;
      tdNombre.textContent = us.nombres;
      tdApellido.textContent = us.apellidos;
      tdTelefono.textContent = us.telefono;
      tdEmail.textContent = us.email;
      tdRol.textContent = us.rol;

      tablesDoom.tblBodyUsers.appendChild(trTableUsers);
      trTableUsers.appendChild(tdNroDocumento);
      trTableUsers.appendChild(tdNombre);
      trTableUsers.appendChild(tdApellido);
      trTableUsers.appendChild(tdTelefono);
      trTableUsers.appendChild(tdEmail);
      trTableUsers.appendChild(tdRol);
      trTableUsers.appendChild(tdAcciones);
      tdAcciones.appendChild(btnAdd);
    });
  } catch (error) {
    console.log(error);
    initAlert(`${error.message}`, "warning", toastOptions);
    // throw new Error(`Error de procesado ${error}`);
  }
};

const getElements = async ({ action = "", pages = 1 } = {}) => {
  try {
    const response = await getData(
      "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
      "GET",
      { action, pages }
    );
    const data = response.data.data;
    const pagesResult = response.data.pages;

    if (action === "elementsConsumibles") {
      // Limpio el arreglo.
      objDataConsumibles.length = 0;
      // Si voy a pasar la data con su misma referencia uso el operado spread (...) para así evitar que la data se desacople.
      objDataConsumibles.push(...data);
      return {
        objDataConsumibles,
        pagesElementsConsumibles: pagesResult,
      };
    }
    if (action === "elementsDevolutivos") {
      return {
        objDataDevolutivos: data,
        pagesElementsDevolutivos: pagesResult,
      };
    }

    throw new Error("Acción no reconocida.");
  } catch (error) {
    initAlert(`${error.message}`, "warning", toastOptions);
    throw new Error(`Error al procesar la solicitud: ${error}`);
  }
};

const renderConsumibles = async ({
  objDataConsumibles = {},
  pagesElementsConsumibles = 1,
} = {}) => {
  //Si el valor es true pues devuelvo la página al principio.
  tablesDoom.tblBodyConsumibles.innerHTML = "";
  objDataConsumibles.forEach((data) => {
    let trConsumbile = document.createElement("tr");
    let tdCodigo = document.createElement("td");
    tdCodigo.setAttribute("class", "codigoElemento");
    let tdNombre = document.createElement("td");
    let tdCantidad = document.createElement("td");
    let tdOpciones = document.createElement("td");
    let divElements = document.createElement("div");
    divElements.setAttribute("class", "actionsElements");
    let cantidadInput = document.createElement("input");
    let labelCheck = document.createElement("label");
    let checkBoxSelect = document.createElement("input");
    let spanCheck = document.createElement("span");
    checkBoxSelect.classList.add("filled-in", "checkboxInput");
    checkBoxSelect.setAttribute("type", "checkbox");
    checkBoxSelect.setAttribute("data-id", data.codigo);
    checkBoxSelect.disabled = true;
    labelCheck.appendChild(checkBoxSelect);
    labelCheck.appendChild(spanCheck);
    cantidadInput.classList.add("browser-default");
    cantidadInput.setAttribute("type", "number");
    cantidadInput.setAttribute("min", 0);
    cantidadInput.setAttribute("data-cantidad", data.cantidad);
    divElements.appendChild(cantidadInput);
    divElements.appendChild(labelCheck);
    tdCodigo.innerText = data.codigo;
    tdNombre.innerText = data.elemento;
    tdCantidad.innerText = data.cantidad;
    trConsumbile.setAttribute("data-id", data.codigo);
    tablesDoom.tblBodyConsumibles.appendChild(trConsumbile);
    trConsumbile.appendChild(tdCodigo);
    trConsumbile.appendChild(tdNombre);
    trConsumbile.appendChild(tdCantidad);
    trConsumbile.appendChild(tdOpciones);
    tdOpciones.appendChild(divElements);

    let cantidad = data.cantidad;
    definirCantidad(cantidadInput, cantidad, checkBoxSelect);
    let codigoString = data.codigo.toString();
    if (ids.includes(codigoString)) {
      checkBoxSelect.disabled = false;
      checkBoxSelect.checked = true;
      cantidadInput.disabled = true;
    }
  });

  modalDoom.modalAddConsumibles.open();
};

const renderDevolutivos = async ({
  objDataDevolutivos = {},
  pagesElementsDevolutivos = 1,
  resetPage = false,
}) => {
  tablesDoom.tblBodyDevolutivos.innerHTML = "";
  //Implementar los datos en en la tabla.
  objDataDevolutivos.forEach((dta) => {
    let codigo = dta.codigo;
    let elemento = dta.elemento;
    let area = dta.area;
    let serie = dta.serie;

    addElements = document.createElement("input");
    addElements.setAttribute("type", "checkbox");
    addElements.setAttribute("class", "checkboxInput");
    addElements.classList.add("filled-in");
    addElements.setAttribute("data-id", codigo);
    addElements.setAttribute("id", codigo);
    let label = document.createElement("label");
    let span = document.createElement("span");
    let trTable = document.createElement("tr");
    let tdCodigo = document.createElement("td");
    let tdSerie = document.createElement("td");
    let tdElemento = document.createElement("td");
    let tdArea = document.createElement("td");
    let tdAccion = document.createElement("td");

    tdCodigo.textContent = codigo;
    tdSerie.textContent = serie;
    tdElemento.textContent = elemento;
    tdArea.textContent = area;
    tdAccion.append(label);
    label.append(addElements, span);
    // Valido si el elemento está en el arreglo para así marcar como checked.
    let codigoString = codigo.toString();
    if (ids.includes(codigoString)) {
      addElements.checked = true;
    }

    tablesDoom.tblBodyDevolutivos.appendChild(trTable);
    trTable.appendChild(tdCodigo);
    trTable.appendChild(tdSerie);
    trTable.appendChild(tdElemento);
    trTable.appendChild(tdArea);
    trTable.append(tdAccion);
  });
};

/**
 * Se valida que la cantidad de los elementos consumibles no sea ni negativa ni mayor a la cantidad disponible.
 * @constructor
 * @param {input} cantidadInput - El input number.
 * @param {int} cantidad - cantidad Del elemento disponible.
 * @param {input} checkBoxSelect - El checkbox deshabilitado.
 */
function definirCantidad(cantidadInput, cantidad, checkBoxSelect) {
  cantidadInput.addEventListener("change", (event) => {
    event.stopPropagation();
    event.preventDefault();

    let valor = parseInt(event.target.value, 10);

    if (valor < 0) {
      // alert("Cantidad no disponible");
      initAlert("Cantidad no disponible", "info", toastOptions);
      event.target.value = "";
      return;
    }

    if (event.target.value > cantidad) {
      initAlert(`Cantidad Máxima permitida ${cantidad}`, "info", toastOptions);
      cantidadInput.value = "";
      return;
    }

    //El valor insertado en cantidad lo actualizo en el data del input. Si el usuario digita una cantidad menor a la cantidad disponible, el valor se actualiza.
    cantidadInput.dataset.cantidad = event.target.value;
    // Habilito el checkbox
    checkBoxSelect.disabled = false;
  });
}

// Abrir modal de elementos disponibles devolutivos
btnDoom.btnAddElements.addEventListener("click", async (btnTarget) => {
  btnTarget.preventDefault();
  btnTarget.stopPropagation();

  let { objDataDevolutivos, pagesElementsDevolutivos } = await getElements({
    action: "elementsDevolutivos",
    pages: 1,
  });

  await renderDevolutivos({
    objDataDevolutivos: objDataDevolutivos,
    pagesElementsDevolutivos: 1,
  });

  modalDoom.modalAddDevolutivos.open();
  //Uso esta función para renderizar por defecto los elementos de tipo devolutivo, en la página 1.
});

//Abrir modal de elementos disponibles consumibles.
btnDoom.btnAddConsumibles.addEventListener("click", async (event) => {
  event.stopPropagation();
  event.preventDefault();
  const { objDataConsumibles, pagesElementsConsumibles } = await getElements({
    action: "elementsConsumibles",
    pages: 1,
    resetToFirstPage: true,
  });
  await renderConsumibles({ objDataConsumibles, pagesElementsConsumibles });
  modalDoom.modalAddConsumibles.open();
});

//Abrir modal usuarios
btnAddUser.addEventListener("click", (event) => {
  event.stopPropagation();
  event.preventDefault();

  //Vuelvo a reiniciar la tabla para que inicie en la Página #1.
  renderUsers({ action: "users", pages: 1 });
  modalDoom.modalUsers.open();
});

//Delegar evento sobre la tabla usuarios
tablesDoom.tblBodyUsers.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();

  //Capturo el tipo de boton y le doy utilidad a ella solamente cuando presiono el evento es de tipo BUTTON.
  let button = e.target.closest("button");
  if (button) {
    //Me devuelve la fila en base al botón que se ha presionado.
    let elements = e.target.closest("tr");
    let nroDocumento = elements.children[0].textContent;
    let nombre = elements.children[1].textContent;
    let apellido = elements.children[2].textContent;
    let telefono = elements.children[3].textContent;
    let email = elements.children[4].textContent;

    inputsForm.inputNroDocumento.textContent = nroDocumento;
    inputsForm.inputNombre.textContent = nombre;
    inputsForm.inputApellido.textContent = apellido;
    inputsForm.inputTelefono.textContent = telefono;
    inputsForm.inputEmail.textContent = email;

    initAlert(
      `Usuario ${nombre} ${apellido} asociado al prestamo`,
      "info",
      toastOptions
    );
    closeModal(modalDoom.modalUsers);
  }
});

const validateDisponibilidad = async ({
  fecha = "",
  codigosElementos,
  isOnly = false,
  method = "GET",
  tpPrestamo = null
} = {}) => {
  let param = {};

  let responseDisponibilidadGet = null;
  let responseDisponibilidadPost = null;

  if (isOnly)
    param = { fechaReserva: fecha, elementos: codigosElementos, isOnly };

  try {
    if (method === "GET") {
      
      param = {
        ...param,
        action: "validateElement",
      };
      responseDisponibilidadGet = await getData(
        "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
        method,
        param
      );


      if (responseDisponibilidadGet.status === 204) {
        return true;
      }

      if (!responseDisponibilidadGet.status) {
        initAlert(
          "Este elemento ya está reservado para la fecha seleccionada",
          "info",
          toastOptions
        );
        return false;
      }
    } else {
      param = {
        ...param,
        action: "validateElements",
        elementos: codigosElementos,
        isOnly: false,
        fechaReserva: fecha,
        tpPrestamo: tpPrestamo
      };

      responseDisponibilidadPost = await sendData(
        "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
        method,
        "validateElements",
        param
      );

      // console.log(responseDisponibilidadPost);

      if (responseDisponibilidadPost.status === 204) {
        return true;
      }

      // devuelvo la data en caso de que sea true.
      if (responseDisponibilidadPost.status) {
        return responseDisponibilidadPost;
      }
    }
  } catch (error) {
    console.log(error);
  }

  return true;
};

tablesDoom.tblBodyDevolutivos.addEventListener("click", async (event) => {
  event.stopPropagation();

  //Valido si el evento ejecutado corresponde a un input con la clase checkboxInput.
  if (event.target.matches(".checkboxInput")) {
    let isChecked = event.target.checked;
    let inputChecked = event.target;
    let info = inputChecked.closest("tr");
    let codigo = info.children[0].textContent;
    let nombre = info.children[1].textContent;
    let area = info.children[2].textContent;
    let valueInput = event.target.getAttribute("data-id");
    if (isChecked) {
      const fechaReserva = document.querySelector("#fechaReserva").value;
      if (fechaReserva !== "") {
        // event.preventDefault();
        let fechaParse = dateISOFormat(fechaReserva, false);
        const responseValide = await validateDisponibilidad({
          fecha: fechaParse,
          codigosElementos: valueInput,
          method: "GET",
          isOnly: true,
        });
        if (!responseValide) {
          event.target.checked = false;
          return;
        }
      }

      //Valido, si el arreglo no contiene el valueInput, entonces que implemente el valor ahí.
      if (!ids.includes(valueInput)) {
        ids.push(valueInput);
        //La idea es hacer un append de estos registros a la tabla. tblPreviewElements.

        let trTablePreview = document.createElement("tr");
        let tdCodigo = document.createElement("td");
        tdCodigo.setAttribute("class", "codigoElemento");
        let tdNombre = document.createElement("td");
        let tdCantidad = document.createElement("td");
        let tdArea = document.createElement("td");
        tablesDoom.tblBodyPreviewElements.appendChild(trTablePreview);
        tdCodigo.textContent = codigo;
        tdNombre.textContent = nombre;
        tdCantidad.textContent = "1";
        tdArea.textContent = area;
        trTablePreview.appendChild(tdCodigo);
        trTablePreview.appendChild(tdNombre);
        trTablePreview.appendChild(tdArea);
        trTablePreview.appendChild(tdCantidad);
        //Capturo el valor del checkbox
        trTablePreview.setAttribute("data-id", valueInput);

        initAlert(`${nombre} agregado a reserva`, "info", toastOptions);
      } else {
        initAlert(
          "El elemento seleccionado ya esta seleccionado",
          "info",
          toastOptions
        );
        //Reinicio el evento.
        event.preventDefault();
      }
    } else {
      // fila del elemento a eliminar
      let trTablePreview = document.querySelector(`[data-id="${valueInput}"]`);
      trTablePreview.remove();

      if (ids.includes(valueInput) && !isChecked) {
        ids = ids.filter((id) => id !== valueInput);
      }

      initAlert(
        `Elemento ${nombre} Eliminado del prestamo`,
        "info",
        toastOptions
      );
    }
  }
});

//Delegar evento sobre la tabla de elementos consumibles
const tableConsumible = document.querySelector("#tableConsumible");
tableConsumible.addEventListener("click", (event) => {
  event.stopPropagation();
  let info = event.target.closest("tr");
  let inputCantidad = info.querySelector(`[type=number]`);
  //Proceso para seleccionar el elemento consumible.
  if (
    event.target.tagName === "INPUT" &&
    event.target.type === "checkbox" &&
    event.target.checked
  ) {
    let codigoConsu = info.children[0].textContent;
    let nombreConsu = info.children[1].textContent;
    let cantidadConsu = inputCantidad.value;
    let checkboxChecked = event.target.checked;
    let trConsu = document.createElement("tr");
    let tdCodigoConsu = document.createElement("td");
    let tdNombreConsu = document.createElement("td");
    tdCodigoConsu.setAttribute("class", "codigoElemento");
    trConsu.setAttribute("data-id", codigoConsu);
    let tdAreaConsu = document.createElement("td");
    let tdCantidadConsu = document.createElement("td");
    tdCodigoConsu.textContent = codigoConsu;
    tdNombreConsu.textContent = nombreConsu;
    //Como el elemento es consumible defino su area como general.
    tdAreaConsu.textContent = "General";
    tdCantidadConsu.textContent = cantidadConsu;
    if (checkboxChecked && cantidadConsu === "") {
      //Desmarcar el checkbox en caso de que no haya elegido cantidad al elemento.
      event.target.checked = false;
      return;
    } else {
      //Valido que el elemento no este en la tabla, en caso de que este, evitar duplicidad.
      if (!ids.includes(codigoConsu)) {
        ids.push(codigoConsu);

        initAlert(
          `${cantidadConsu} unidades agregadas de ${nombreConsu}`,
          "info",
          toastOptions
        );

        tablesDoom.tblBodyPreviewElements.appendChild(trConsu);
        trConsu.appendChild(tdCodigoConsu);
        trConsu.appendChild(tdNombreConsu);
        trConsu.appendChild(tdAreaConsu);
        trConsu.appendChild(tdCantidadConsu);
      } else {
        initAlert(
          "Elemento consumible ya ha sido agregado",
          "info",
          toastOptions
        );
        inputCantidad.value = "";
        event.preventDefault();
      }
    }
  }
  // Proceso para eliminar el elemento consumible del prestamo.
  if (event.target.type === "checkbox" && !event.target.checked) {
    let checkboxId = event.target.getAttribute("data-id");
    ids = ids.filter((id) => id != checkboxId);
    inputCantidad.disabled = false;
    event.target.disabled = true;
    inputCantidad.value = "";
    const tblBodyPreviewElements = document.querySelector(
      "#tblBodyPreviewElements"
    );
    let datatype = tblBodyPreviewElements.querySelector(
      `[data-id="${checkboxId}"]`
    );
    datatype.remove();
    initAlert("Elemento eliminado del prestamo", "info", toastOptions);
  }
});

/**
 * Paginación usuarios
 */
let pgUsers = 1;
//Botón de evento para marcar el preview de la página.
btnDoom.btnPreviewUsers.addEventListener("click", (event) => {
  event.stopPropagation();
  event.preventDefault();

  pgUsers = pgUsers === 1 ? 1 : pgUsers - 1;

  //Decrementa la página por el valor del pages.
  renderUsers({ action: "users", pages: pgUsers });
});

//Botón para marcar el next de la página.
btnDoom.btnNextUsers.addEventListener("click", (event) => {
  event.stopPropagation();
  event.preventDefault();
  pgUsers++;
  renderUsers({ action: "users", pages: pgUsers });
});

/**
 * Paginación elementos devolutivos disponibles
 *
 */
let pgElementsDevolutivos = 1;
const previewElement = document.querySelector("#previewElement");
const nextElement = document.querySelector("#nextElement");
// Selector = Puede que no lo use. Este selector es para colocar la cantidad de páginas que tengo basado en la cantidad de elementos de la tabla.
previewElement.addEventListener("click", async (e) => {
  e.stopPropagation();
  pgElementsDevolutivos =
    pgElementsDevolutivos === 1 ? 1 : pgElementsDevolutivos - 1;
  let { objDataDevolutivos, pagesElementsDevolutivos } = await getElements({
    action: "elementsDevolutivos",
    pages: pgElementsDevolutivos,
  });

  renderDevolutivos({
    objDataDevolutivos: objDataDevolutivos,
    pagesElementsDevolutivos: pgElementsDevolutivos,
  });
});

nextElement.addEventListener("click", async () => {
  pgElementsDevolutivos++;

  let { objDataDevolutivos, pagesElementsDevolutivos } = await getElements({
    action: "elementsDevolutivos",
    pages: pgElementsDevolutivos,
  });

  if (pgElementsDevolutivos > pagesElementsDevolutivos) {
    pgElementsDevolutivos = pagesElementsDevolutivos;
    return;
  }
  renderDevolutivos({
    objDataDevolutivos: objDataDevolutivos,
    pagesElementsDevolutivos: pgElementsDevolutivos,
  });
});

btnDoom.btnClosePreviewElements.addEventListener("click", (e) => {
  e.stopPropagation();
  modalDoom.modalPreviewElements.close();
});
/**
 * Paginación elementos consumibles disponibles.
 */
let pagesConsumibles = 1;
document
  .querySelector("#previewElementConsumible")
  .addEventListener("click", async (event) => {
    event.stopPropagation();

    const prevPage = pagesConsumibles - 1;

    if (prevPage < 1) return;

    const { objDataConsumibles, pagesElementsConsumibles: totalPages } =
      await getElements({ action: "elementsConsumibles", pages: prevPage });

    if (objDataConsumibles.length > 0) {
      pagesConsumibles = prevPage;
      pagesElementsConsumibles = totalPages;

      await renderConsumibles({
        objDataConsumibles,
        pagesElementsConsumibles: totalPages,
      });
    }
  });

document
  .querySelector("#nextElementConsumible")
  .addEventListener("click", async (event) => {
    event.stopPropagation();

    // Obtener los datos de la siguiente página
    const nextPage = pagesConsumibles + 1;

    const { objDataConsumibles, pagesElementsConsumibles: totalPages } =
      await getElements({ action: "elementsConsumibles", pages: nextPage });

    // valido que en el objeto hayan resultados para poder renderizar la siguiente página.
    if (objDataConsumibles.length > 0 && nextPage <= totalPages) {
      pagesConsumibles = nextPage;
      pagesElementsConsumibles = totalPages;

      await renderConsumibles({
        objDataConsumibles,
        pagesElementsConsumibles: totalPages,
      });
    }
  });

// Cerrar el modal de los usuarios.
closeModal(modalDoom.modalUsers, btnDoom.btnCloseUsers, () => {
  renderUsers({ action: "users", pages: 1, resetToFirstPage: true });
});

closeModal(modalDoom.modalAddDevolutivos, btnDoom.btnCloseDevolutivos, () => {
  pgElementsDevolutivos = 1;
});

//Cerrar el modal de elementos consumibles
closeModal(modalDoom.modalAddConsumibles, btnDoom.btnCloseConsumible, () => {
  // Reinicio la página para que cuando vuelva a ingresar este en la página inicial.
  pagesConsumibles = 1;
});

//Preview de los elementos en forma de tabla.
const tableMessage = document.querySelector("#tableMessage");
btnDoom.btnModalPreviewElements.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();

  if (ids.length === 0) {
    tableMessage.innerHTML = "No hay elementos seleccionados en la reserva";
  }

  modalDoom.modalPreviewElements.open();
});

//Con esta función valido que los campos del formulario sean diligenciados.
function validateFormData(formData, tipoPrestamo) {
  for (const [key, value] of formData.entries()) {
    const isEmpty = !value || value.toString().trim() === "";

    // Evitamos validar dependiendo del tipo de prestamo, si es 1, omitir la fecha de reserva y si es 2, solo observaciones.
    const camposOpcionales =
      tipoPrestamo === "1"
        ? ["observaciones", "fechaReserva"]
        : ["observaciones"];
    if (isEmpty && !camposOpcionales.includes(key)) {
      initAlert(
        `El campo "${key}" debe ser diligenciado`,
        "info",
        toastOptions
      );
      return false;
    }
  }
  return true;
}

function validateDate(date1, date2) {
  let timeDate1 = date1.getTime();
  let timeDate2 = date2.getTime();

  if (timeDate1 > timeDate2) {
    return false;
  } else {
    return true;
  }
}

// Seleccionar los radiobuttons
const radioButonTp = document.querySelectorAll('[name="tipoPr"]');
radioButonTp.forEach((rd) => {
  rd.addEventListener("change", (event) => {
    event.stopPropagation();

    if (event.target.checked && event.target.value === "1") {
      formSolicitudPrestamo.style.display = "grid";
      divContainers.divFechaReserva.style.display = "none";
    } else {
      formSolicitudPrestamo.style.display = "grid";
      divContainers.divFechaReserva.style.display = "flex";
    }
  });
});


/**
 * Función para unificar los elementos devolutivos y consumibles seleccionados para poder visualizar al usuario
 *
 * @param {{}} [rows={}] 
 * @returns {string} 
 */
const createMessageElementos = (rows = {}) => {
  if (!rows) return "";

  let devolutivosRows = rows.codigoElementos.devolutivos;
  let consumiblesRows = rows.codigoElementos.consumibles;
  let textConfirmReservaConsumibles = "";
  let textConfirmReservaDev = "";

  if (consumiblesRows.length === 0) {
    textConfirmReservaConsumibles += `\n Consumibles:\n Sin elementos \n`;
  } else {
    textConfirmReservaConsumibles += `Elementos consumibles seleccionados por el usuario:\n${consumiblesRows
      .map(
        (el) =>
          `Código: ${el.codigo}  Nombre: ${el.nombreElemento} Cantidad: ${el.cantidad} \n`
      )
      .join("\n")}\n`;
  }

  if (devolutivosRows.length === 0) {
    textConfirmReservaDev += `Devolutivos:\n Sin elementos`;
  } else {
    // Devolutivos.
    textConfirmReservaDev += `Elementos devolutivos seleccionados por el usuario:\n${devolutivosRows
      .map(
        (el) =>
          `Serie: ${el.serie} Nombre: ${el.nombreElemento} Cantidad: ${el.cantidad}`
      )
      .join("\n")}\n`;
  }

  const textRegistrar = `\n Elementos seleccionados: \n ${replaceln(textConfirmReservaConsumibles)}\n` +
    `\n ${replaceln(textConfirmReservaDev)}`;

  return textRegistrar;
};

const createMessagReservados = (dataValidate = {}, tpPrestamo) => {
  if (!dataValidate) return {};

  if(!tpPrestamo) return "";

  let textDataReservados = "";
  let textConfirmReserva = "";

  textDataReservados += `\n ${dataValidate
      .map(
        (el) =>
          `Serie elemento ${el.seriElemento} Nombre elemento: ${el.nombreElemento} Fecha Reservada: ${el.fechaReserva} Fecha Devolución : ${el.fechaDevolucion}`
      )
      .join("\n")}\n`;

  if (tpPrestamo === "2") {
    textConfirmReserva += `\n Estos elementos ya están reservados para la fecha seleccionada : ${replaceln(
      textDataReservados
    )}`;
  }

  if (tpPrestamo === "1") {
    textConfirmReserva += `\n Estos elementos ya están reservados para la fecha de devolución seleccionada o posterior a ella ${replaceln(
      textDataReservados
    )} \n` ;
  }
  


  return textConfirmReserva;
};

/**
 * Submit al formulario.
 */
formSolicitudPrestamo.addEventListener("submit", async (event) => {
  event.preventDefault();
  event.stopPropagation();

  // Seleccionar el radiobutton seleccionado
  const radioButtonTp = document.querySelector('[name="tipoPr"]:checked');
  let tpPrestamo = null;

  tpPrestamo = radioButtonTp ? radioButtonTp.value : null;

  if (!tpPrestamo) {
    initAlert(
      "Debes Seleccionar la opción Prestamo o Reserva",
      "warning",
      toastOptions
    );
    return;
  }

  if (tpPrestamo === "1" && inputsForm.inputFechaReserva) {
    inputsForm.inputFechaReserva.value = "";
  }

  let rows = {
    codigoElementos: {
      devolutivos: [],
      consumibles: [],
    },
  };

  let tdArea = [];
  let info = new FormData(formSolicitudPrestamo);
  //Data de formulario
  let data = Object.fromEntries(info);
  if (!validateFormData(info, tpPrestamo)) return;
  let fechaReservaParse = null;
  let fechaDevolucionParse = null;
  let fechaReservaFormat = null;
  let fechaDevolucionFormat = null;
  if (tpPrestamo === "2") {
    fechaReservaParse = dateISOFormat(data.fechaReserva, true);
    fechaDevolucionParse = dateISOFormat(data.fechaDevolucion, true);

    if (!validateDate(fechaReservaParse, fechaDevolucionParse)) {
      initAlert(
        "La fecha de reserva no debe ser mayor a la fecha de devolución.",
        "info",
        toastOptions
      );
      return;
    }

    //Transformo la fecha en formato iso 8601
    fechaReservaFormat = dateISOFormat(data.fechaReserva);
    fechaDevolucionFormat = dateISOFormat(data.fechaDevolucion);

    data.fechaReserva = fechaReservaFormat;
    data.fechaDevolucion = fechaDevolucionFormat;
  }

  if (tpPrestamo === "1") {
    fechaDevolucionFormat = dateISOFormat(data.fechaDevolucion);
    data.fechaDevolucion = fechaDevolucionFormat;
    delete data.fechaReserva;
  }

  //Agrego la cedula al objeto data.
  data.cedula = document.getElementById("cedula").textContent.trim();
  if (!data.cedula) {
    initAlert("Los datos del usuario son obligatorios", "info", toastOptions);
    return;
  }
  //Data de elementos.
  let filas = document.querySelectorAll(
    ".tableElements .previewElements #tblBodyPreviewElements tr"
  );
  // //Capturo el codigo del elemento y lo guardo.
  filas.forEach((fl) => {
    let tds = fl.querySelectorAll("td");
    //4 Por la cantidad de columnas que hay
    if (tds.length >= 3) {
      let area = tds[2].textContent.trim();
      let codigoElemento = tds[0].textContent.trim();
      let cantidadElemento = tds[3] ? tds[3].textContent.trim() : "";
      let serieElemento = tds[1].textContent.trim();
      cantidadElemento = cantidadElemento ? parseInt(cantidadElemento, 10) : 1;
      let nombreElemento = tds[2].textContent.trim();
      if (!tdArea.includes(area)) {
        tdArea.push(area);
      }
      const elements = {
        codigo: codigoElemento,
        cantidad: cantidadElemento,
        serie: serieElemento,
        nombreElemento: nombreElemento,
      };
      if (area === "General") {
        rows.codigoElementos.consumibles.push(elements);
      } else {
        rows.codigoElementos.devolutivos.push(elements);
      }
    }
  });
  let codigosElementos = rows.codigoElementos;
  // Agrego el tipo de prestamo al objeto data para envíar a registrar.
  data.tpPrestamo = tpPrestamo;
  data.codigosElementos = codigosElementos;

  //Validar si hay elementos seleccionados para así continuar con el proceso.
  if (rows.codigoElementos.devolutivos.length === 0 && rows.codigoElementos.consumibles.length === 0
  ) {
    initAlert(
      "Debes agregar al menos un elemento para la solicitud.",
      "error",
      toastOptions
    );

    btnDoom.btnAddElements.classList.remove("shake");
    //Obligo al dom a que vuelva a re ejecutar este elemento.
    void btnDoom.btnAddElements.offsetWidth;
    btnDoom.btnAddElements.classList.add("shake");
    return;
  }

  let messageElements = createMessageElementos(rows);
  //Valido si hay elemento seleccionados.

  let title = tpPrestamo === "1" ? "Prestamo" : "Reserva";
  // let responseValidate = null;
  let messageValidate = "";
  let dataValidate = {};
  let textDataReservados = "";
  let paramModal = {};
  let textConfirmReserva = "";
  paramModal = { titulo: `Registrar ${title}`, mensaje: messageElements };

  try {
    let paramValidateDisponibilidad = {};
    let devolutivosCheck = rows.codigoElementos.devolutivos;
    let modalMessage = "";

    if (tpPrestamo === "1") {
      let fechaDevolucion = data.fechaDevolucion;
      paramValidateDisponibilidad = {
        fecha: fechaDevolucion,
        codigosElementos: devolutivosCheck,
        method: "POST",
        isOnly: false,
        tpPrestamo: tpPrestamo,
      };
    } else {
      let fechaReserva = data.fechaReserva;
      paramValidateDisponibilidad = {
        fecha: fechaReserva,
        codigosElementos: devolutivosCheck,
        method: "POST",
        isOnly: false,
        tpPrestamo: tpPrestamo,
      };
    }

    const responseValidate = await validateDisponibilidad(paramValidateDisponibilidad);

    //Mostrar mensaje de modal para informar que hay elementos ya reservados para esa fecha y por ende, no se podrán reservar.
    if (responseValidate.status) {
      messageValidate = responseValidate.message;
      dataValidate = responseValidate.data;
      const messageReservado = createMessagReservados(dataValidate, tpPrestamo);
      // Unificamos ambos mensajes, el de los elementos que ya están reservados con los elementos que el usuario ha seleccionado.
      modalMessage += `\n ${messageReservado} \n Si presiona aceptar, los elementos ya reservados no se asociarán a la reserva, ¿Desea continuar? \n ${replaceln(
        messageElements
      )} \n `;
      paramModal = {
        titulo: `Registrar ${title}`,
        mensaje: modalMessage,
      };
    }
  } catch (error) {
    console.error(`${error}`);
  }

  mostrarConfirmacion(
    paramModal.titulo,
    paramModal.mensaje,
    async (response) => {
      if (!response) {
        initAlert("Proceso cancelado", "info", toastOptions);
        return;
      }

      // if (JSON.stringify(dataValidate) != "{}") {
      if (Array.from(dataValidate) && dataValidate.length  > 0 ) {
        
        // Transformo el arreglo dataValidate en uno nuevo solamente trayendo LOS CÓDIGOS de los elementos para comparar con los que el usuario ha seleccionado.
        const codigosElementosReservados = dataValidate.map(
          (item) => item.codigoElemento
        );
        // Uso la función filter para mutar los nuevos elementos que voy a enviar a reservar Y SOLO RETORNO los elementos que NO ESTEN INCLUIDOS EN MI OBJETO ELEMENTO.
        const newDev = codigosElementos.devolutivos.filter((elemento) => {
          return !codigosElementosReservados.includes(parseInt(elemento.codigo));
        });

        codigosElementos.devolutivos = newDev;
        //Agrego los códigos de los elementos al data.
        data.codigosElementos = codigosElementos;

      }

      // Extraer los elementos para validar si hay o no elementos.
      let validateDevolutivos = data.codigosElementos.devolutivos;
      // Si el usuario selecciona x elementos y todos ellos están reservados para la fecha en concreto, muestro una alerta indicando que seleccione elementos que estén disponibles para esa fecha.
      if (validateDevolutivos.length === 0) {
        let fecha = tpPrestamo === "2" ? data.fechaReserva : data.fechaDevolucion;
        initAlert(`Seleccione elementos que no estén reservados para la fecha ${fecha}`, "info", toastOptions);
        return;
      }

      try {
        const responseReserva = await sendData(
          "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
          "POST",
          "registrar",
          data
        );

        let status = responseReserva.status;

        if (!status) {
          initAlert("Error al realizar el proceso", "info", toastOptions);
          return;
        }

        initAlert("Reserva realizada con exito", "success", toastOptions);
        // Elimino los ids de los prestamos.
        ids.length = 0;
        //Limpio el formulario, tabla y campos de span.
        formSolicitudPrestamo.reset();
        const radioButtonTp = document.querySelectorAll('[name="tipoPr"]');
        radioButtonTp.forEach((rd) => {
          rd.checked = false;
        });
        formSolicitudPrestamo.style.display = "none";
        inputsForm.inputNroDocumento.textContent = "";
        inputsForm.inputNombre.textContent = "";
        inputsForm.inputApellido.textContent = "";
        inputsForm.inputEmail.textContent = "";
        inputsForm.inputTelefono.textContent = "";
        tablesDoom.tblBodyPreviewElements.innerHTML = "";
      } catch (error) {
        initAlert(error.message, "error", toastOptions);
      }
    }
  );
});

document.addEventListener("DOMContentLoaded", () => {
  initTooltip(
    btnDoom.btnAddUser,
    tooltipOptions,
    "Seleccione el instructor\npara asignar al préstamo",
    "left"
  );
  initTooltip(
    btnDoom.btnModalPreviewElements,
    tooltipOptions,
    "Visualize los elementos\n que ha seleccionado \n para su respectivo prestamo",
    "bottom"
  );
  initTooltip(
    btnDoom.btnAddElements,
    tooltipOptions,
    "Seleccione los elementos \n que requieren una \n devolución",
    "bottom"
  );
  initTooltip(
    btnDoom.btnAddConsumibles,
    tooltipOptions,
    "Seleccione elementos \n a consumir sin \ndevolución obligatoria",
    "bottom"
  );

  const tipoPrestamo = document.querySelector("#helpPrestamo");

  initTooltip(
    tipoPrestamo,
    tooltipOptions,
    "Prestamo: Entrega inmediata de elementos. \n Reserva: Entrega de elementos en una fecha específica.",
    "top"
  );

  // Inicializar los select
  const selects = document.querySelectorAll("select");
  M.FormSelect.init(selects);

  //Inicializar los modales
  const modals = document.querySelectorAll(".modal");
  M.Modal.init(modals);

  //Hago la instancia de los input tipo date
  instanceDate("#fechaReserva", opcionesDatepicker);
  instanceDate("#fechaDevolucion", opcionesDatepicker);

  closeModal(modalDoom.modalUsers, btnDoom.btnCloseUsers);
  closeModal(modalDoom.modalPreviewElements, btnDoom.btnClosePreviewElements);
});
