import { Ajax } from "../libraries/ajax.js";
import { closeModal } from "../libraries/cases.js";

const objAjax = new Ajax();
const btnSubmit = document.getElementById("btnSubmit");
const tableDevolutivos = document.querySelector("#bodyDevolutions");
const tableUsers = document.querySelector("#tableBodyUsers");
const tablePreviewElements = document.querySelector(
  "#tableBodyPreviewElements"
);
const modalAddElements = document.querySelector("#modalAddElements");
const modalUsers = document.querySelector("#modalUsers");
const btnAddElements = document.getElementById("btnAddElements");
const modalTitle = document.querySelector("#modalTitle");
const areaDestino = document.querySelector("#areaDestino");
const horaInicio = document.querySelector(".horaInicio");
const horaFin = document.querySelector(".horaFin");
const horaInicioFin = document.querySelector(".horaInicioFin");
const btnCloseElements = document.querySelector(
  "#modalAddElements .close-modal"
);
const btnCloseUsers = document.querySelector("#modalUsers .close-modal");
const btnSearchUser = document.querySelector("#searchBtn");
const formSolicitudPrestamo = document.querySelector("#formSolicitudPrestamo");
horaInicio.style.visibility = "hidden";
horaInicio.style.opacity = "0";
horaFin.style.visibility = "hidden";
horaFin.style.opacity = "0";
horaInicioFin.style.visibility = "hidden";
horaInicioFin.style.opacity = "0";
//Inputs del formulario
let inputNombre = document.querySelector("#nombre");
let inputNroDocumento = document.querySelector("#cedula");
let inputApellido = document.querySelector("#apellido");
let inputTelefono = document.querySelector("#telefono");
let inputEmail = document.querySelector("#email");
//Inhabilito los inputs para evitar que el usuario digite los campos.
inputNombre.readOnly = true;
inputNroDocumento.readOnly = true;
inputApellido.readOnly = true;
inputTelefono.readOnly = true;
inputEmail.readOnly = true;
//En caso de que sean muchos registros.
tableUsers.innerHTML = '<tr><td colspan="7">Cargando usuarios...</td></tr>';
const btnPreview = document.querySelector("#preview");
const btnNext = document.querySelector("#next");

areaDestino.addEventListener("change", () => {
  let value = areaDestino.options[areaDestino.selectedIndex];
  //console.log(value);
  if (value.value === "centro") {
    //TODO: Mejorar a función para mostrar los elementos
    //TODO: Cambiar los input de tipo type, en vez de que esten ocultos, implementarlos en el html cuando su valor sea centro.
    horaInicio.style.visibility = "visible";
    horaInicio.style.opacity = "1";
    horaFin.style.visibility = "visible";
    horaFin.style.opacity = "1";
    horaInicioFin.style.visibility = "visible";
    horaInicioFin.style.opacity = "1";
  }
  if (value.value === "externo" || value.value === "---") {
    //TODO: mejorar a función para ocultar los elementos.
    horaInicio.style.visibility = "hidden";
    horaInicio.style.opacity = "0";
    horaFin.style.visibility = "hidden";
    horaFin.style.opacity = "0";
    horaInicioFin.style.visibility = "hidden";
    horaInicioFin.style.opacity = "0";
  }
});

// Selecciono el elemento específico.
let objDataElements = {};
let objDataUsers = {};
let button;
const valuePage = document.querySelector("#valuePage");
btnAddElements.innerText = "Devolutivos";
btnAddElements.setAttribute('class','btnClick');
modalTitle.innerText = "Elementos disponibles";
btnSubmit.innerText = "Reservar";
btnSubmit.setAttribute('class', 'btnSubmit');
btnSearchUser.innerText = "Consultar";
// variables que corresponden a los números de páginas de las tablas elementosDevolutivos y usuarios.
let pagesUsers;
let pagesElements;

/**
 * Función de renderizado de los instructores o elementos.
 */
function fetchData(action = "", page = 1) {
  objAjax.request.open(
    "GET",
    `modules/reservaPrestamos/controller/reservaController.php?pages=${encodeURIComponent(
      page
    )}&action=${encodeURIComponent(action)}`,
    true
  );
  objAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  objAjax.request.onload = () => {
    if (action === "elements") {
      //Transformo la respuesta
      let response = JSON.parse(objAjax.request.responseText);
      objDataElements = response.data.data;
      pagesElements = response.data.pages;

    }

    if (action === "users") {
      let response = JSON.parse(objAjax.request.responseText);
      objDataUsers = response.data.data;

    }
  };
  //Específicamos que respuesta queremos recibir
  objAjax.request.setRequestHeader("Accept", "application/json");
  objAjax.request.send();
}

//Función para reestablecer los elementos a la página 1.
function resetTableUsers(action = "", resetToFirstPage = false) {
  if (resetToFirstPage) {
    pgUsers = 1;
  }
  //TODO: Si se le envía más parámetros, buscar como funciona URLSearchParams para poder enviar muchos parámetros en forma de objeto.
  objAjax.request.open(
    "GET",
    `modules/reservaPrestamos/controller/reservaController.php?pages=${encodeURIComponent(
      pgUsers
    )}&action=${encodeURIComponent(action)}`,
    true
  );
  objAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  objAjax.request.onload = () => {
    valuePage.innerHTML = "";
    if (action === "users") {
      let response = JSON.parse(objAjax.request.responseText);
      let data = response.data.data;
      let allPages = response.data.pages;

      //Evitar que no se aumente la página más allá del número de páginas.
      if (pgUsers > allPages) {
        pgUsers = allPages;
      }

      //Limpio los selects, evito que hayan duplicados.
      for (let index = 1; index <= allPages; index++) {
        let pagesOptions = document.createElement("option");
        pagesOptions.value = index;
        pagesOptions.innerHTML = index;
        valuePage.append(pagesOptions);
      }
      //por defecto, lo coloco en 1.
      valuePage.value = String(pgUsers);

      tableUsers.innerHTML = "";
      data.forEach((us) => {
        let btnAdd = document.createElement("button");
        button = btnAdd;
        btnAdd.innerText = "Seleccionar";
        btnAdd.setAttribute("type", "button");
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

        tableUsers.appendChild(trTableUsers);
        trTableUsers.appendChild(tdNroDocumento);
        trTableUsers.appendChild(tdNombre);
        trTableUsers.appendChild(tdApellido);
        trTableUsers.appendChild(tdTelefono);
        trTableUsers.appendChild(tdEmail);
        trTableUsers.appendChild(tdRol);
        trTableUsers.appendChild(tdAcciones);
        tdAcciones.appendChild(btnAdd);
      });
    }
  };

  objAjax.request.setRequestHeader("accept", "application/json");
  objAjax.request.send();
}

//Este arreglo lo voy a crear con el fin de guardar los ids de los elementos para saber cuales son los elementos seleccionados.
let ids = [];
//Aca se van a guardar los input de tipo Checkbox.
let addElements;
function resetTableElements(action = "", pages = 1, resetFirstPage = false) {
  //Si el valor es true pues devuelvo la página al principio.
  if (resetFirstPage) {
    pages = 1;
  }

  //Evitar que el numero de páginas sea mayor a las páginas de los elementos.
  if (pages > pagesElements) {
    pages = pagesElements;
  }

  objAjax.request.open(
    "GET",
    `modules/reservaPrestamos/controller/reservaController.php?pages=${encodeURIComponent(
      pages
    )}&action=${encodeURIComponent(action)}`,
    true
  );
  objAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  objAjax.request.onload = () => {
    let response = JSON.parse(objAjax.request.responseText);
    objDataElements = response.data.data;
    tableDevolutivos.innerHTML = "";
    //Implementar los datos en en la tabla.
    objDataElements.forEach((dta) => {
      let codigo = dta.codigo;
      let elemento = dta.elemento;
      let area = dta.area;

      addElements = document.createElement("input");
      addElements.setAttribute("type", "checkbox");
      addElements.setAttribute("class", "checkboxInput");
      addElements.setAttribute("data-id", codigo);
      let trTable = document.createElement("tr");
      let tdCodigo = document.createElement("td");
      let tdElemento = document.createElement("td");
      let tdArea = document.createElement("td");
      let tdAccion = document.createElement("td");

      //A los elementos td les implemento su contenido, su contenido es la información de la tabla
      tdCodigo.textContent = codigo;
      tdElemento.textContent = elemento;
      tdArea.textContent = area;
      tdAccion.appendChild(addElements);

      tableDevolutivos.appendChild(trTable);
      trTable.appendChild(tdCodigo);
      trTable.appendChild(tdElemento);
      trTable.appendChild(tdArea);
      trTable.append(tdAccion);
    });
  };

  objAjax.request.setRequestHeader("accept", "application/json");
  objAjax.request.send();
}

document.addEventListener("DOMContentLoaded", () => {
  fetchData("elements", 1);
});
// Abrir modal de elementos disponibles devolutivos y consumibles.
btnAddElements.addEventListener("click", (btnTarget) => {
  btnTarget.preventDefault();
  btnTarget.stopPropagation();

  //visualizar modal.
  modalAddElements.style.display = "flex";
  //Uso esta función para renderizar por defecto los elementos de tipo devolutivo, en la página 1.
  resetTableElements("elements", 1);
});

//Abrir modal usuarios
btnSearchUser.addEventListener("click", (event) => {
  event.stopPropagation();
  event.preventDefault();

  //Vuelvo a reiniciar la tabla para que inicie en la Página #1.
  resetTableUsers("users", true);

  modalUsers.style.display = "flex";
});

//Delegar evento sobre la tabla usuarios
tableUsers.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();

  //Capturo la información y le doy utilidad a ella solamente cuando presiono el evento es de tipo BUTTON.
  if (e.target.tagName === "BUTTON") {
    //Me devuelve la fila en base al botón que se ha presionado.
    let elements = e.target.closest("tr");
    let nroDocumento = elements.children[0].textContent;
    let nombre = elements.children[1].textContent;
    let apellido = elements.children[2].textContent;
    let telefono = elements.children[3].textContent;
    let email = elements.children[4].textContent;

    inputNombre.value = "";
    inputNroDocumento.value = "";
    inputApellido.value = "";
    inputTelefono.value = "";
    inputEmail.value = "";

    inputNroDocumento.value = nroDocumento;
    inputNombre.value = nombre;
    inputApellido.value = apellido;
    inputTelefono.value = telefono;
    inputEmail.value = email;

    //Cerrar el modal justo que el administrador elija al usuario.
    modalUsers.style.display = "none";
  }
});

//Delegar evento sobre la tabla de elementos devolutivos.
tableDevolutivos.addEventListener("click", (event) => {
  event.stopPropagation();


  //Valido si el evento ejecutado corresponde a un input con la clase checkboxInput.
  if (event.target.matches(".checkboxInput")) {

    let isChecked = event.target.checked;
    if (isChecked) {
      let inputChecked = event.target;
      //Uso parentNode para traer todos los elementos de la fila, Excepto, el elemento que activo el evento, es decir, el input checkbox.
      let info = inputChecked.closest("tr");

      //La idea es hacer un append de estos registros a la tabla. tblPreviewElements.
      let codigo = info.children[0].textContent;
      let nombre = info.children[1].textContent;
      let area = info.children[2].textContent;
      let trTablePreview = document.createElement("tr");

      let tdCodigo = document.createElement("td");
      tdCodigo.setAttribute("class", "codigoElemento");
      let tdNombre = document.createElement("td");
      let tdArea = document.createElement("td");

      tablePreviewElements.appendChild(trTablePreview);

      tdCodigo.textContent = codigo;
      tdNombre.textContent = nombre;
      tdArea.textContent = area;

      trTablePreview.appendChild(tdCodigo);
      trTablePreview.appendChild(tdNombre);
      trTablePreview.appendChild(tdArea);
    }
  }
});

//Delegar evento sobre la tabla elementos
/**
 * Esta es solo la interacción con la tabla de elementos preview, que sirven para poder eliminar un registro si no lo quiero antes de enviar el prestamo.
 */
tablePreviewElements.addEventListener("click", (event) => {
  event.stopPropagation();
  event.preventDefault();
});

/**
 * Paginación usuarios
 */
valuePage.addEventListener("change", (event) => {
  event.stopPropagation();
  event.preventDefault();

  pgUsers = Number(event.target.value);
  resetTableUsers("users");
});

let pgUsers = 1;
//Botón de evento para marcar el preview de la página.
btnPreview.addEventListener("click", (event) => {
  event.stopPropagation();
  event.preventDefault();

  pgUsers = pgUsers === 1 ? 1 : pgUsers - 1;

  //Decrementa la página por el valor del pages.
  resetTableUsers("users");
});

//Botón para marcar el next de la página.
btnNext.addEventListener("click", (event) => {
  event.stopPropagation();
  event.preventDefault();
  pgUsers++;

  //Aumenta la Página hasta que llega al final.
  resetTableUsers("users");
});

closeModal(modalUsers, btnCloseUsers);
//Con el true se devuelve a la primera página.
resetTableUsers("", true);

/**
 * Paginación elementos devolutivos disponibles
 *
 */
let pgElementsDevolutivos = 1;
const previewElement = document.querySelector("#previewElement");
const nextElement = document.querySelector("#nextElement");
// Selector = Puede que no lo use. Este selector es para colocar la cantidad de páginas que tengo basado en la cantidad de elementos de la tabla.
const valuePageElement = document.querySelector("#valuePageElement");
previewElement.addEventListener("click", () => {
  pgElementsDevolutivos =
    pgElementsDevolutivos === 1 ? 1 : pgElementsDevolutivos - 1;
  resetTableElements("elements", pgElementsDevolutivos);
});

nextElement.addEventListener("click", () => {
  // Validación adicional para evitar que se desacople la información.
  if (pgElementsDevolutivos < pagesElements) {
    pgElementsDevolutivos++;
    // pgElementsDevolutivos++;
  }
  resetTableElements("elements", pgElementsDevolutivos);
});

//Cerrar el modal de elementos
closeModal(modalAddElements, btnCloseElements);
resetTableElements("elements", pgElementsDevolutivos, true);

/**
 * Submit al formulario.
 */
formSolicitudPrestamo.addEventListener("submit", (event) => {
  event.preventDefault();
  event.stopPropagation();

  let rows = {
    codigo: [],
  };
  let td = [];

  let info = new FormData(formSolicitudPrestamo);
  //Data de formulario
  let data = Object.fromEntries(info);

  //Data de elementos.
  const filas = document.querySelectorAll(
    ".tableElements .previewElements #tableBodyPreviewElements tr"
  );
  //Capturo el codigo del elemento y lo guardo.
  //TODO: Validar que cuando el usuario presione el botón de enviar aplique un return cuando no se ha diligenciado ningún campo.
  filas.forEach((fl) => {
    td = document.querySelectorAll(
      ".tableElements .previewElements #tableBodyPreviewElements .codigoElemento"
    );
  });

  td.forEach((tds) => {
    //Guardo el elemento.
    rows.codigo.push(tds.textContent);
  });

  //Evita duplicidad pero para enviar el formulario, debo de arreglarlo para que no se agregue a la vista.
  rows.codigo = rows.codigo.filter(
    (value, index, self) => self.indexOf(value) === index
  );

  let codigosElementos = rows.codigo;
  //Agrego los códigos de los elementos al data.
  data.codigosElementos = codigosElementos;

  objAjax.request.open(
    "POST",
    "modules/reservaPrestamos/controller/reservaController.php"
  );
  objAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  //Elimino las propiedades que no necesito.
  delete data["nombre"];
  delete data["apellido"];
  delete data["telefono"];
  delete data["email"];

  if (!data["areaDestino"]) {
    alert("El área de destino es obligatoria.");
    return;
  }

  //Si el area destino es mayor, es decir, si su valor es igual a centro, valida si contiene valores en las horas.
  //TODO: Mejorar logica antes de validar todo.
  if (data["areaDestino"] === "centro") {
    if (!data["inicio"] || !data["fin"]) {
      alert("La hora de inicio y fin son obligatorias para el centro.");
      return;
    }
  } else if (data["areaDestino"] === "externo") {
    // Eliminar las horas si no aplican
    data["inicio"] = null;
    data["fin"] = null;
  }
  let dataJson = JSON.stringify(data);

  //TODO: transformar en sweet alert.
  if (confirm("¿Deseas registrar los siguientes elementos?")) {
    objAjax.request.onload = () => {
      let response = JSON.parse(objAjax.request.responseText);
      console.log(response);

      if (response.status) {
        alert("Reserva realizada con exito");
        //Limpio el formulario y la tabla.
        formSolicitudPrestamo.reset();
        tablePreviewElements.innerHTML = "";

        //Oculto inputs de tipo time.
        horaInicio.style.visibility = "hidden";
        horaInicio.style.opacity = "0";
        horaFin.style.visibility = "hidden";
        horaFin.style.opacity = "0";
        horaInicioFin.style.visibility = "hidden";
        horaInicioFin.style.opacity = "0";
      }
    };

    objAjax.request.setRequestHeader("accept", "application/json");
    objAjax.request.send(dataJson);
  }
});