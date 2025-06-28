import { Ajax } from "../utils/ajax.js";
import { closeModal,  createI,instanceDate, instanceModal, options, opcionesDatepicker, instanceDateTime, timePickerOptions, dateISOFormat, initTooltip, tooltipOptions, initAlert, toastOptions } from "../utils/cases.js";

const objAjax = new Ajax();
const btnSubmit = document.querySelector("#btnSubmit");
const tableDevolutivos = document.querySelector("#bodyDevolutions");
const tableUsers = document.querySelector("#tableBodyUsers");
const tablePreviewElements = document.querySelector(
  "#tableBodyPreviewElements"
);

const modalAddDevolutivos = instanceModal('#modalAddDevolutivos',{options});
const modalAddConsumibles = instanceModal('#modalAddConsumible',{options});
const modalUsers = instanceModal('#modalUsers',{options});
const btnAddElements = document.querySelector("#btnAddElements");
const ispanAddElements = createI();
ispanAddElements.innerText = 'add';

const btnAddConsumibles = document.querySelector('#btnAddConsumibles');
const modalTitle = document.querySelector("#modalTitle");
const areaDestino = document.querySelector("#areaDestino");
const horaInicio = document.querySelector(".horaInicio");
const horaFin = document.querySelector(".horaFin");
const horaInicioFin = document.querySelector(".horaInicioFin");
const btnCloseDevolutivos = document.querySelector(
  "#modalAddDevolutivos .close-modal"
);

const btnCloseConsumible = document.querySelector('#modalAddConsumible .close-modal');
const btnCloseUsers = document.querySelector("#modalUsers .close-modal");
const btnSearchUser = document.querySelector("#searchBtn");

//Aplico tooltip al boton de usuarios
initTooltip(
  btnSearchUser,
  tooltipOptions,
  'Seleccione el instructor\npara asignar al préstamo',
  'left'
);

const previewElements2 = document.querySelector('#previewElements2');
const iCreatePreview = createI();
iCreatePreview.innerText = 'info';
initTooltip(previewElements2,tooltipOptions,'Visualize los elementos\n que ha seleccionado \n para su respectivo prestamo','bottom');

previewElements2.append(iCreatePreview);
//Creo una instancia del modal
const instanPreview = instanceModal('#modalPreviewElements',{"opacity":options.opacity, "inDuration":options.inDuration, "outDuration":options.outDuration});
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

// Estas variables las uso para re utilizar la información
let objDataConsumibles = {};
let objDataDevolutivos = {};
let objDataUsers = {};
let button;
const valuePage = document.querySelector("#valuePage");
let iAddElement = createI();
iAddElement.innerText = 'add_a_photo';
btnAddElements.classList.add('btnClick');
btnAddElements.append(iAddElement);

let iConsumible = createI();
iConsumible.innerText = 'battery_std';
btnAddConsumibles.append(iConsumible);
initTooltip(btnAddElements,tooltipOptions,'Seleccione los elementos \n que requieren una \n devolución','bottom');
initTooltip(btnAddConsumibles,tooltipOptions,'Seleccione elementos \n a consumir sin \ndevolución obligatoria','bottom');

let iClass = createI();
modalTitle.innerText = "Elementos disponibles";
iClass.innerText = 'send';
btnSubmit.append(iClass);

// variables que corresponden a los números de páginas de las tablas elementosDevolutivos y usuarios.
let pagesUsers;
let pagesElements;
//Este arreglo lo voy a crear con el fin de guardar los ids de los elementos para saber cuales son los elementos seleccionados.
let ids = [];
let addElements;

/**
 * Función de renderizado y peticiones, TODO: Re factorizar y mover a otros archivos.
 */
  //TODO: Documentar la función usando JSDOC

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
      objDataDevolutivos = response.data.data;
      pagesElements = response.data.pages;

    }

    if (action === "users") {
      let response = JSON.parse(objAjax.request.responseText);
      objDataUsers = response.data.data;
      // pagesElementsConsumible = response.data.pages;

    }
  };
  //Específicamos que respuesta queremos recibir
  objAjax.request.setRequestHeader("Accept", "application/json");
  objAjax.request.send();
}

document.addEventListener('DOMContentLoaded', ()=>{
  fetchData("elements", 1);

  const selects = document.querySelectorAll('select');
  M.FormSelect.init(selects);

  //Hago la instancia de los input tipo date
  instanceDate('#fechaReserva',opcionesDatepicker);
  instanceDate('#fechaDevolucion',opcionesDatepicker);

  //Instancia de los input de tipo datetime.
  instanceDateTime('#fin',timePickerOptions);
  instanceDateTime('#inicio',timePickerOptions);
  
});

//Función para reestablecer los elementos a la página 1.
//TODO: Documentar la función usando JSDOC
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
    // valuePage.innerHTML = "";
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
        // valuePage.append(pagesOptions);
      }
      //por defecto, lo coloco en 1.
      // valuePage.value = String(pgUsers);

      tableUsers.innerHTML = "";
      data.forEach((us) => {
        let btnAdd = document.createElement("button");
        let iCreate = createI();
        // iCreate.setAttribute('class','material-icons');
        iCreate.innerText = 'add';
        button = btnAdd;
        btnAdd.setAttribute("type", "button");
        btnAdd.setAttribute('class','btn waves-effect waves-light');
        btnAdd.append(iCreate);
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

//TODO: Documentar la función usando JSDOC
function resetTableElements(action = "", pages = 1, resetFirstPage = false) {

  return new Promise((resolve,reject)=>{
    //Evitar que el numero de páginas sea mayor a las páginas de los elementos.
      
    //Si el valor es true pues devuelvo la página al principio.
      if (resetFirstPage) {
        pages = 1;
      }

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

    try {
      let response = JSON.parse(objAjax.request.responseText);
      pagesElements = response.data.pages;
      resolve(response.data.data);
    } catch (error) {
      reject(error);
    }
  };

  objAjax.request.setRequestHeader("accept", "application/json");
  objAjax.request.send();

  });
}

/**
 * Se valida que la cantidad de los elementos consumibles no sea ni negativa ni mayor a la cantidad disponible.
 * @constructor
 * @param {input} cantidadInput - El input number
 * @param {int} cantidad - cantidad Del elemento disponible.
 */
function definirCantidad(cantidadInput, cantidad) {
  cantidadInput.addEventListener("change", (event) => {
    event.stopPropagation();
    event.preventDefault();

    let valor = parseInt(event.target.value,10);

    if (valor < 0) {
      alert("Cantidad no disponible");
      event.target.value = valor;
    }

    if (event.target.value > cantidad) {
      alert(`Cantidad Máxima permitida ${cantidad}`);
      cantidadInput.value = "";
    }

    //El valor insertado en cantidad lo actualizo en el data del input. Si el usuario digita una cantidad menor a la cantidad disponible, el valor se actualiza.
    cantidadInput.dataset.cantidad = event.target.value;

  });
}

// Abrir modal de elementos disponibles devolutivos
btnAddElements.addEventListener("click", (btnTarget) => {
  btnTarget.preventDefault();
  btnTarget.stopPropagation();

  // Concepto aprendido nuevo: PROMISE
  /**
   * Esto es la respuesta de una promesa, hace que el aplicativo continue su flujo mientras que ejecuta este proceso.
   */


  resetTableElements("elements", 1).then((respuesta)=>{
    tableDevolutivos.innerHTML = "";

    //Implementar los datos en en la tabla.
    respuesta.forEach((dta) => {
      let codigo = dta.codigo;
      let elemento = dta.elemento;
      let area = dta.area;

      addElements = document.createElement("input");
      addElements.setAttribute("type", "checkbox");
      addElements.setAttribute("class", "checkboxInput");
      addElements.classList.add('filled-in');
      addElements.setAttribute("data-id", codigo);
      addElements.setAttribute('id',codigo);
      let label = document.createElement('label');
      let span = document.createElement('span');
      let trTable = document.createElement("tr");
      let tdCodigo = document.createElement("td");
      let tdElemento = document.createElement("td");
      let tdArea = document.createElement("td");
      let tdAccion = document.createElement("td");

      //A los elementos td les implemento su contenido, su contenido es la información de la tabla
      tdCodigo.textContent = codigo;
      tdElemento.textContent = elemento;
      tdArea.textContent = area;
      tdAccion.append(label);
      label.append(addElements,span);

      tableDevolutivos.appendChild(trTable);
      trTable.appendChild(tdCodigo);
      trTable.appendChild(tdElemento);
      trTable.appendChild(tdArea);
      trTable.append(tdAccion);

    });
  });

  //visualizar modal.
  modalAddDevolutivos.open();
  //Uso esta función para renderizar por defecto los elementos de tipo devolutivo, en la página 1.
});

//Abrir modal de elementos disponibles consumibles.
btnAddConsumibles.addEventListener("click", (event)=>{
  event.stopPropagation();
  event.preventDefault();
  //Respuesta de la promesa.
  resetTableElements("consumibles",1).then((result)=>{
    const tblBodyConsumibles = document.querySelector('#tblBodyConsumibles');
    tblBodyConsumibles.innerHTML = "";
    result.forEach((data)=>{
      let trConsumbile = document.createElement('tr');

      let tdCodigo = document.createElement('td');
      tdCodigo.setAttribute('class','codigoElemento');
      let tdNombre = document.createElement('td');
      let tdCantidad = document.createElement('td');
      let tdOpciones = document.createElement('td');
      let divElements = document.createElement('div');
      divElements.setAttribute('class','actionsElements');
      let cantidadInput = document.createElement('input');
      
      
      let divCheckbox = document.createElement('div');
      let labelCheck = document.createElement('label');
      let checkBoxSelect = document.createElement('input');
      let spanCheck = document.createElement('span');
      checkBoxSelect.classList.add('checkboxInput');
      checkBoxSelect.setAttribute('type','checkbox');
      checkBoxSelect.classList.add('filled-in');
      checkBoxSelect.setAttribute('data-id',data.codigo);
      divCheckbox.append(labelCheck);
      labelCheck.append(checkBoxSelect,spanCheck);      

      cantidadInput.classList.add('browser-default');
      // cantidadInput.classList.add('input-field');
      cantidadInput.setAttribute('type','number');
      cantidadInput.setAttribute('min',0);
      cantidadInput.setAttribute('data-cantidad',data.cantidad);
      divElements.append(cantidadInput,divCheckbox);
      tdCodigo.innerText = data.codigo;
      tdNombre.innerText = data.elemento;
      tdCantidad.innerText = data.cantidad;

      tblBodyConsumibles.appendChild(trConsumbile);
      trConsumbile.appendChild(tdCodigo);
      trConsumbile.appendChild(tdNombre);
      trConsumbile.appendChild(tdCantidad);
      trConsumbile.appendChild(tdOpciones);
      tdOpciones.append(divElements);

      let cantidad = data.cantidad;
      definirCantidad(cantidadInput,cantidad);

    });
  });

  modalAddConsumibles.open();

});

//Abrir modal usuarios
btnSearchUser.addEventListener("click", (event) => {
  event.stopPropagation();
  event.preventDefault();

  //Vuelvo a reiniciar la tabla para que inicie en la Página #1.
  resetTableUsers("users", true);

  modalUsers.open();
});

//Delegar evento sobre la tabla usuarios
tableUsers.addEventListener("click", (e) => {
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

    inputNroDocumento.textContent = nroDocumento;
    inputNombre.textContent = nombre;
    inputApellido.textContent = apellido;
    inputTelefono.textContent = telefono;
    inputEmail.textContent = email;

    initAlert(`Instructor ${nombre} ${apellido} asociado al prestamo`, 'info',toastOptions);
    closeModal(modalUsers);
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
      let info = inputChecked.closest("tr");
      
      //La idea es hacer un append de estos registros a la tabla. tblPreviewElements.
      let codigo = info.children[0].textContent;
      let nombre = info.children[1].textContent;
      let area = info.children[2].textContent;
      let trTablePreview = document.createElement("tr");
      let tdCodigo = document.createElement("td");
      tdCodigo.setAttribute("class", "codigoElemento");
      let tdNombre = document.createElement("td");
      let tdCantidad = document.createElement('td');
      let tdArea = document.createElement("td");


      //Capturo el valor del checkbox
      let valueInput = event.target.getAttribute("data-id");
      //Valido, si el arreglo no contiene el valueInput, entonces que implemente el valor ahí.
      if (!ids.includes(valueInput)) {
          ids.push(valueInput);

          tablePreviewElements.appendChild(trTablePreview);
          tdCodigo.textContent = codigo;
          tdNombre.textContent = nombre;
          tdCantidad.textContent = '1';
          tdArea.textContent = area;
          trTablePreview.appendChild(tdCodigo);
          trTablePreview.appendChild(tdNombre);
          trTablePreview.appendChild(tdArea);
          trTablePreview.appendChild(tdCantidad);

          initAlert(`${nombre} agregado a reserva`,'info',{"inDuration": toastOptions.inDuration});

      }else{
        alert('el elemento seleccionado ya está seleccionado.');
        //Reinicio el evento.
        event.preventDefault();
      }


    }
  }
});

//Delegar evento sobre la tabla de elementos consumibles
const tableConsumible = document.querySelector('#tableConsumible');
tableConsumible.addEventListener(('click'),(event)=>{
  event.stopPropagation();

  //Si se selecciona, debo de guardar el elemento en la tabla.
  if (event.target.tagName === 'INPUT' && event.target.type === 'checkbox' && event.target.checked) {

    let info = event.target.closest('tr');
    let inputCantidad = info.querySelector(`[type=number]`);
    let codigoConsu = info.children[0].textContent;
    let nombreConsu = info.children[1].textContent;
    //let cantidadConsu = info.children[3].inputCantidad.dataset.cantidad;
    let cantidadConsu = inputCantidad.value;
    // console.log({codigoConsu,nombreConsu,cantidadConsu});
    let checkboxChecked = event.target.checked;

    let trConsu = document.createElement('tr');

    let tdCodigoConsu = document.createElement('td');
    let tdNombreConsu = document.createElement('td');
    tdCodigoConsu.setAttribute("class", "codigoElemento");
    let tdAreaConsu = document.createElement('td');
    let tdCantidadConsu = document.createElement('td');
    tdCodigoConsu.textContent = codigoConsu;
    tdNombreConsu.textContent = nombreConsu;
    //Como el elemento es consumible defino su area como general.
    tdAreaConsu.textContent = 'General';
    tdCantidadConsu.textContent = cantidadConsu;
    
    if (checkboxChecked && (cantidadConsu === "")) {
      alert('digite la cantidad requerida en base a su disponibilidad');
      //Desmarcar el checkbox en caso de que no haya elegido cantidad al elemento.
      event.target.checked = false;      
    }else{

      //Valido que el elemento no este en la tabla, en caso de que este, evitar duplicidad.
      if (!ids.includes(codigoConsu)) {
        ids.push(codigoConsu);

        initAlert(`${cantidadConsu} unidades agregadas de ${nombreConsu}`, 'info',toastOptions);

        tablePreviewElements.appendChild(trConsu);
        trConsu.appendChild(tdCodigoConsu);
        trConsu.appendChild(tdNombreConsu);
        trConsu.appendChild(tdAreaConsu);
        trConsu.appendChild(tdCantidadConsu);
      }else{
        alert('Elemento consumible ya ha sido agregado');
        inputCantidad.value = "";
        event.preventDefault();
      }
    }
  }
});

/**
 * Paginación usuarios
 */

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
previewElement.addEventListener("click", () => {
  pgElementsDevolutivos = pgElementsDevolutivos === 1 ? 1 : pgElementsDevolutivos - 1;

    resetTableElements("elements", pgElementsDevolutivos).then((result)=>{

    tableDevolutivos.innerHTML = "";
    //Implementar los datos en en la tabla.
    result.forEach((dta) => {
      let codigo = dta.codigo;
      let elemento = dta.elemento;
      let area = dta.area;
      addElements = document.createElement("input");
      addElements.setAttribute("type", "checkbox");
      addElements.setAttribute("class", "checkboxInput");
      addElements.classList.add("checkboxInput", "filled-in");
      addElements.setAttribute("data-id", codigo);
      addElements.setAttribute('id',codigo);
      let label = document.createElement('label');
      let span = document.createElement('span');
      let trTable = document.createElement("tr");
      let tdCodigo = document.createElement("td");
      let tdElemento = document.createElement("td");
      let tdArea = document.createElement("td");
      let tdAccion = document.createElement("td");

      //A los elementos td les implemento su contenido, su contenido es la información de la tabla
      tdCodigo.textContent = codigo;
      tdElemento.textContent = elemento;
      tdArea.textContent = area;
      tdAccion.append(label);
      label.append(addElements,span);
      tableDevolutivos.appendChild(trTable);
      trTable.appendChild(tdCodigo);
      trTable.appendChild(tdElemento);
      trTable.appendChild(tdArea);
      trTable.append(tdAccion);

    });
  });
});

nextElement.addEventListener("click", () => {
  // Validación adicional para evitar que se desacople la información.
  if (pgElementsDevolutivos < pagesElements) {
    pgElementsDevolutivos++;
  }
  resetTableElements("elements", pgElementsDevolutivos).then((result)=>{
    
    tableDevolutivos.innerHTML = "";
    //Implementar los datos en en la tabla.
    result.forEach((dta) => {
      let codigo = dta.codigo;
      let elemento = dta.elemento;
      let area = dta.area;

      addElements = document.createElement("input");
      addElements.setAttribute("type", "checkbox");
      addElements.setAttribute("class", "checkboxInput");
      addElements.classList.add("checkboxInput", "filled-in");
      addElements.setAttribute("data-id", codigo);
      addElements.setAttribute('id',codigo);
      let label = document.createElement('label');
      let span = document.createElement('span');
      let trTable = document.createElement("tr");
      let tdCodigo = document.createElement("td");
      let tdElemento = document.createElement("td");
      let tdArea = document.createElement("td");
      let tdAccion = document.createElement("td");

      //A los elementos td les implemento su contenido, su contenido es la información de la tabla
      tdCodigo.textContent = codigo;
      tdElemento.textContent = elemento;
      tdArea.textContent = area;
      tdAccion.append(label);
      label.append(addElements,span);

      tableDevolutivos.appendChild(trTable);
      trTable.appendChild(tdCodigo);
      trTable.appendChild(tdElemento);
      trTable.appendChild(tdArea);
      trTable.append(tdAccion);


    });
  });
});

/**
 * Paginación elementos consumibles disponibles.
*/
let pagesConsumibles = 1;
document.querySelector('#previewElementConsumible').addEventListener('click', (event)=>{

  pagesConsumibles = pagesConsumibles === 1 ? 1 : pagesConsumibles - 1;

  //TODO: el renderizado pasarlo a una función así reutilizarlo en 3 lugares, boton preview, boton next y cuando abre el modal.
  resetTableElements("consumibles",pagesConsumibles).then((result)=>{
    const tblBodyConsumibles = document.querySelector('#tblBodyConsumibles');
    tblBodyConsumibles.innerHTML = "";
    result.forEach((data)=>{
      let trConsumbile = document.createElement('tr');

      let tdCodigo = document.createElement('td');
      tdCodigo.setAttribute('class','codigoElemento');
      let tdNombre = document.createElement('td');
      let tdCantidad = document.createElement('td');
      let tdOpciones = document.createElement('td');
      let divElements = document.createElement('div');
      divElements.setAttribute('class','actionsElements');
      let cantidadInput = document.createElement('input');
      
      
      let divCheckbox = document.createElement('div');
      let labelCheck = document.createElement('label');
      let checkBoxSelect = document.createElement('input');
      let spanCheck = document.createElement('span');
      checkBoxSelect.classList.add('checkboxInput');
      checkBoxSelect.setAttribute('type','checkbox');
      checkBoxSelect.classList.add('filled-in');
      checkBoxSelect.setAttribute('data-id',data.codigo);
      divCheckbox.append(labelCheck);
      labelCheck.append(checkBoxSelect,spanCheck);      

      cantidadInput.classList.add('browser-default');
      // cantidadInput.classList.add('input-field');
      cantidadInput.setAttribute('type','number');
      cantidadInput.setAttribute('min',0);
      cantidadInput.setAttribute('data-cantidad',data.cantidad);


      divElements.append(cantidadInput,divCheckbox);

      tdCodigo.innerText = data.codigo;
      tdNombre.innerText = data.elemento;
      tdCantidad.innerText = data.cantidad;

      tblBodyConsumibles.appendChild(trConsumbile);
      trConsumbile.appendChild(tdCodigo);
      trConsumbile.appendChild(tdNombre);
      trConsumbile.appendChild(tdCantidad);
      trConsumbile.appendChild(tdOpciones);
      tdOpciones.append(divElements);

      let cantidad = data.cantidad;
      definirCantidad(cantidadInput,cantidad);
    });
  });
  
});

document.querySelector('#nextElementConsumible').addEventListener('click',(event)=>{
  if (pagesConsumibles < pagesElements) {
    pagesConsumibles++;
  }
  
  resetTableElements("consumibles",pagesConsumibles).then((result)=>{
    const tblBodyConsumibles = document.querySelector('#tblBodyConsumibles');
    tblBodyConsumibles.innerHTML = "";
    result.forEach((data)=>{
      let trConsumbile = document.createElement('tr');

      let tdCodigo = document.createElement('td');
      tdCodigo.setAttribute('class','codigoElemento');
      let tdNombre = document.createElement('td');
      let tdCantidad = document.createElement('td');
      let tdOpciones = document.createElement('td');
      let divElements = document.createElement('div');
      divElements.setAttribute('class','actionsElements');
      let cantidadInput = document.createElement('input');
      
      
      let divCheckbox = document.createElement('div');
      let labelCheck = document.createElement('label');
      let checkBoxSelect = document.createElement('input');
      let spanCheck = document.createElement('span');
      checkBoxSelect.classList.add('checkboxInput');
      checkBoxSelect.setAttribute('type','checkbox');
      checkBoxSelect.classList.add('filled-in');
      checkBoxSelect.setAttribute('data-id',data.codigo);
      divCheckbox.append(labelCheck);
      labelCheck.append(checkBoxSelect,spanCheck);      

      cantidadInput.classList.add('browser-default');
      // cantidadInput.classList.add('input-field');
      cantidadInput.setAttribute('type','number');
      cantidadInput.setAttribute('min',0);
      cantidadInput.setAttribute('data-cantidad',data.cantidad);

      divElements.append(cantidadInput,divCheckbox);

      tdCodigo.innerText = data.codigo;
      tdNombre.innerText = data.elemento;
      tdCantidad.innerText = data.cantidad;

      tblBodyConsumibles.appendChild(trConsumbile);
      trConsumbile.appendChild(tdCodigo);
      trConsumbile.appendChild(tdNombre);
      trConsumbile.appendChild(tdCantidad);
      trConsumbile.appendChild(tdOpciones);
      tdOpciones.append(divElements);

      let cantidad = data.cantidad;
      definirCantidad(cantidadInput,cantidad);
    });
  });
});

//Cerrar el modal de elementos devolutivos
closeModal(modalAddDevolutivos, btnCloseDevolutivos);
resetTableElements("elements", pgElementsDevolutivos, true);

//Cerrar el modal de elementos consumibles
closeModal(modalAddConsumibles, btnCloseConsumible);
resetTableElements("consumibles",1,true);


//Preview de los elementos en forma de tabla.
previewElements2.addEventListener('click',(e)=>{
  e.stopPropagation();
  e.preventDefault();
  instanPreview.open();
});

/**
 * Submit al formulario.
 */
formSolicitudPrestamo.addEventListener("submit", (event) => {
  event.preventDefault();
  event.stopPropagation();

    let rows = {
    codigoElementos: {
      devolutivos:[],
      consumibles:[]
    },
  };

  let tdArea = [];

  let info = new FormData(formSolicitudPrestamo);
  //Data de formulario
  let data = Object.fromEntries(info);


  //Transformo la fecha en formato iso 8601
  let fechaReservaFormat = dateISOFormat(data.fechaReserva);
  let fechaDevolucionFormat = dateISOFormat(data.fechaDevolucion);
  data.fechaReserva = fechaReservaFormat;
  data.fechaDevolucion = fechaDevolucionFormat;

  //Agrego la cedula al objeto data.
  data.cedula = document.getElementById("cedula").textContent.trim();

  //Data de elementos.
  let filas = document.querySelectorAll(".tableElements .previewElements #tableBodyPreviewElements tr");
  //Capturo el codigo del elemento y lo guardo.
  //TODO: Validar que cuando el usuario presione el botón de enviar aplique un return cuando no se ha diligenciado ningún campo.
  filas.forEach((fl) => {

    let tds = fl.querySelectorAll('td');
    //4 Por la cantidad de columnas que hay
    if (tds.length >= 3) {
      let area = tds[2].textContent.trim();
      let codigoElemento = tds[0].textContent.trim();
      let cantidadElemento = tds[3] ? tds[3].textContent.trim() : '';
      cantidadElemento = cantidadElemento ? parseInt(cantidadElemento, 10) : 1;
      
      console.log('Área encontrada:', area);
      if (!tdArea.includes(area)) {
        tdArea.push(area);
      }

      const elements = {codigo: codigoElemento, cantidad: cantidadElemento};
      if (area === 'General') {
        rows.codigoElementos.consumibles.push(elements);
      }else{
        rows.codigoElementos.devolutivos.push(elements);
      }
    }
  });

  let codigosElementos = rows.codigoElementos;
  //Agrego los códigos de los elementos al data.
  data.codigosElementos = codigosElementos;

  if (!data["areaDestino"]) {
    // alert("El área de destino es obligatoria.");
    initAlert('El área de destino es obligatoria.','error',toastOptions);
    return;
  }

  objAjax.request.open(
    "POST",
    "modules/reservaPrestamos/controller/reservaController.php"
  );
  objAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

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


  let dataJson = JSON.stringify({
    data: data,
    action: 'registrar'
    });
  //TODO: transformar en sweet alert.
  if (confirm("¿Deseas realizar el siguiente prestamo?")) {
    objAjax.request.onload = () => {
      let response = JSON.parse(objAjax.request.responseText);

      if (response.status) {
        initAlert('Reserva realizada con exito','success',toastOptions);
        //Limpio el formulario, tabla y campos de span.
        formSolicitudPrestamo.reset();
        inputNroDocumento.textContent = "";
        inputNombre.textContent = "";
        inputApellido.textContent = "";
        inputEmail.textContent = "";
        inputTelefono.textContent = "";
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