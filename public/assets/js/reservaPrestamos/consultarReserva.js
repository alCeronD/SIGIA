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
  typeLoans,
} from "../utils/cases.js";
import { getData, sendData } from "../utils/fetch.js";

const objAjax = new Ajax();
//Cuerpo de la tabla para renderizar los datos.
const tbodyReservaConsult = document.querySelector("#tbodyReservaConsult");
const modalDetail = instanceModal("#modalDetail", options);
const modalValidate = instanceModal("#modalValidate", options);
const bodyDetailValidate = document.querySelector("#bodyDetailValidate");
const btnCloseValidte = document.querySelector("#modalValidate .close-modal");
const btnCloseElements = document.querySelector("#modalDetail .close-modal");
const formDetail = document.querySelector("#formDetail");
// El contenido de la tabla.
const tableContainerDetail = document.querySelector('.tableContainerDetail table');
// Contenedor del formulario
const formValidateContainer = document.querySelector('.formValidateContainer');
//TODO: mejorarlo.
let data;
//variable para guardar los elementos
let elementos = {};
const BodydetailReserva = document.querySelector("#BodydetailReserva");
//Codigo del prestamo para hacer el fech
let cases = "reservas";
let codigo;
let pages;
//Página actual.
let pagesReserva = 1;

//Variable para mostrar la información en el modal.
let elementosDetalle = [];

const renderReservas = async (page = 1) => {
  pagesReserva = page;

  //Traigo la data por medio de fetch.
  const result = await getData(
    "modules/reservaPrestamos/controller/reservaController.php",
    "GET",
    { action: "reservas", pages: page }
  );
  // let registros = result;
  let status = result.status;
  data = result.data.data;
  pages = result.data.pages;

  if (pagesReserva > pages) return;

  if (!status) {
    //Implementar mensaje de que no hay registros.
    tbodyReservaConsult.innerHTML = "";
    return;
  }

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

    // btnDetail.innerText = "Detalle";
    // btnValidateLoan.innerText = 'validar';
    btnDetail.setAttribute("class", "btnDetail btnClick");
    btnDetail.setAttribute("data-id", `${dta.codigo}`);
    //TODO: Esto lo debo si o si cambiar, puedo crear una funcion para implementar las clases.

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

    //TODO: la cantidad la saco haciendo una consulta basada en el count del prestamo al cual pertenecen los prestamos, por ahora, estalbecer por 1.
    let tdCantidad = document.createElement("td");
    let tdEstado = document.createElement("td");
    let tdAcciones = document.createElement("td");
    let tdTipo = document.createElement("td");
    tdCodigo.textContent = dta.codigo;
    tdNombreCompleto.textContent = dta.nombre + " " + dta.apellido;
    tdEstado.textContent = dta.estadoPrestamo;
    tdTipo.textContent = dta.tipoPrestamo;

    tbodyReservaConsult.appendChild(tr);
    tr.appendChild(tdCodigo);
    tr.appendChild(tdNombreCompleto);
    tr.appendChild(tdEstado);
    tr.appendChild(tdTipo);
    tdAcciones.innerHTML = "";
    tr.append(tdAcciones);

    if (tdEstado.textContent === "Finalizado") {
      btnEnd.style.display = "none";
      tdEstado.style.color = "gray";
      // tdEstado.style.fontWeight = "bold";
    }

    if (tdEstado.textContent === "Rechazado") {
      tdEstado.style.color = "red";
      // tdEstado.style.fontWeight = "bold";
    }

    if (tdEstado.textContent === "Validado") {
      tdEstado.style.color = "green";
      // tdEstado.style.fontWeight = "bold";
    }

    tdAcciones.appendChild(btnDetail);

    if (dta.estadoPrestamo === "Finalizado") {
      // Solo mostrar botón Detalle
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
      let getReservaElementos = getData(
        "modules/reservaPrestamos/controller/reservaController.php",
        "GET",
        { action: "reservaDetailElements" }
      );

      const detalleAjax = new Ajax();
      let action = "reservaDetailElements";
      detalleAjax.request.open(
        "GET",
        `modules/reservaPrestamos/controller/reservaController.php?codigo=${encodeURIComponent(
          codigo
        )}&action=${encodeURIComponent(action)}`,
        true
      );
      detalleAjax.request.setRequestHeader(
        "X-Requested-With",
        "XMLHttpRequest"
      );

      detalleAjax.request.onload = () => {
        let response = JSON.parse(detalleAjax.request.responseText);

        //Guardo el código de la reserva con los elementos que están asociados a ese prestamo.
        //Response.data tiene los elementos.
        elementos[dta.codigo] = {
          reserva: dta,
          elementos: response.data,
        };
      };

      detalleAjax.request.setRequestHeader("Accept", "application/json");
      detalleAjax.request.send();
    }
  });
};

document.addEventListener("DOMContentLoaded", () => {
  renderReservas();
});

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

    codigo = parseInt(btnDetail.getAttribute("data-id"));
    //TODO: refactorizarlo y transformarlo en una sola función.
    const reserva = data.find((item) => item.codigo === codigo);
    let action = "reservaDetailElements";

    //Petición para dibujar los elementos en la tabla del detail.
    //TODO: Mejorar, en la variable elementos encuentro toda la información, no necesito hacer otra petición.
    objAjax.request.open(
      "GET",
      `modules/reservaPrestamos/controller/reservaController.php?codigo=${encodeURIComponent(
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
  }

  //Para visualizar el detalle de elementos en caso de que sea requerido.
  // if (
  //   event.target.tagName === "BUTTON" &&
  //   event.target.getAttribute(["data-add"])
  // ) {
  //   console.log(event.target);
  // }

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
        "modules/reservaPrestamos/controller/reservaController.php"
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

          if (response.status) {
            initAlert(
              `Prestamo # ${endReserva.codigoReserva} finalizada`,
              "success",
              toastOptions
            );

            let codigoAdd = endReserva.codigoReserva;
            let tr = [...document.querySelectorAll("#tbodyReservaConsult tr")];

            tr.forEach((infoTr) => {
              if (
                infoTr
                  .querySelector("td")
                  .textContent.includes(String(codigoAdd))
              ) {
                let tdEstado = infoTr.children[2];
                let tdAcciones = infoTr.children[4];
                let btnEnd = tdAcciones.querySelector(
                  'button[data-end="' + codigoAdd + '"]'
                );

                btnEnd.style.display = "none";
                tdEstado.textContent = "Finalizado";

                if (tdEstado.textContent === "Finalizado") {
                  btnEnd.style.display = "none";
                }
              }
            });
          } else {
            console.warn("Respuesta negativa del servidor:", response.message);
          }
        } catch (error) {
          console.error(
            "Error al procesar la respuesta o actualizar la vista:",
            error
          );
        }
      };
      objEndReserva.request.setRequestHeader("accept", "application/json");
      objEndReserva.request.send(reservaJson);
    } else {
      console.warn(
        "No se encontraron elementos asociados aún. Espera a que cargue la información."
      );
    }
    // }
  }

  const btnSalida = event.target.closest("button[data-validate]");
  //Dar salida a los prestamos, cambiar el estado de la solicitudes a validada e implementar su salida.
  if (btnSalida) {
    modalValidate.open();
    //Capturo los datos para transformarlo en json.
    let validateReserva = setReserva(
      "data-validate",
      data,
      elementos,
      btnSalida
    );


    let action = "validateLoan";
    validateReserva["action"] = action;
    let consumibles = [];
    let devolutivos = [];

    let previewElements = validateReserva.elementos;
    // previewElements.forEach(element => {
    //       if (element.codTipoElemento === 1) {
    //     devolutivos.push(element);
    //   } else if (element.codTipoElemento === 2) {
    //     consumibles.push(element);
    //   }

    // });

    bodyDetailValidate.innerHTML = "";
    //Los elementos pertenecientes al prestamo, los agrego en la tabla para validar su salida.
    previewElements.forEach((el) => {
      let tr = document.createElement("tr");
      let tdCodigo = document.createElement("td");
      let tdNombre = document.createElement("td");
      let tdCantidad = document.createElement("td");
      let tdTipoElemento = document.createElement("td");
      let tdAcciones = document.createElement("td");

      // Input checkbox
      let label = document.createElement("label");
      let span = document.createElement("span");
      let input = document.createElement("input");

      input.type = "checkbox";
      input.classList.add("filled-in");
      input.classList.add("inputValidate");
      // input.value = el.codigo;
      input.dataset.codigo = el.codigo;
      input.dataset.tipoElemento = el.nombreTipoElemento;

      label.appendChild(input);
      label.appendChild(span);
      tdAcciones.appendChild(label);

      tdCodigo.innerText = el.codigo;
      tdNombre.innerText = el.nombre;
      tdTipoElemento.innerText = el.nombreTipoElemento;
      tdCantidad.innerText = el.cantidadSolicitada;
      bodyDetailValidate.appendChild(tr);
      tr.appendChild(tdCodigo);
      tr.appendChild(tdNombre);
      tr.appendChild(tdCantidad);
      tr.appendChild(tdTipoElemento);
      tr.appendChild(tdAcciones);
    });

    const checkBoxValidate = document.querySelector("#allValidateItems");
    // capturo el input de la tabla para seleccionarlos todos.
    const inputValidate = document.querySelectorAll(".inputValidate");
    const nextBtnValidate = document.querySelector(
      ".nextBtnValidate #btnNextValidate"
    );

    //Me valida que el checkbox este checked para así poder mostrar el botón.
    function validateCheckboxChecked() {
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
      }else{
        nextBtnValidate.style.display = "none";

      }
    }

    function addElementsToArray(input) {
      const tipo = input.dataset.tipoElemento;
      const cod = input.dataset.codigo;

      if (input.checked) {
        if (tipo === "Devolutivo" && !devolutivos.includes(cod)) {
          devolutivos.push(cod);
        }

        if (tipo === "Consumible" && !consumibles.includes(cod)) {
          consumibles.push(cod);
        }
      } else {
        if (tipo === "Consumible") {
          consumibles = consumibles.filter((consu) => consu !== cod);
        }

        if (tipo === "Devolutivo") {
          devolutivos = devolutivos.filter((dev) => dev !== cod);
        }
      }
    }

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

      validateCheckboxChecked();
    });

    inputValidate.forEach((input) => {
      input.addEventListener("change", (e) => {
        e.stopPropagation();
        e.preventDefault();
        if (e.target.checked) {
          addElementsToArray(input);
          validateCheckboxChecked();
        } else {
          addElementsToArray(input);
          validateCheckboxChecked();
        }
      });
    });

    //Variable para visualizar los elementos antes de validar el prestamo
    let elementosPreviewConsu = validateReserva.elementos.elmConsumibles;
    let elementosPreviewDev = validateReserva.elementos.elmDevolutivos;
    let dataTr = event.target.closest("tr");
    //Estado por validar
    let estadoNew = dataTr.children[2];
    let tdAcciones = dataTr.children[4];

    const previewBtnValidate = document.querySelector('#previewBtnValidate');
    //Cuando el usuario pase al siguiente paso, este valida todo
    nextBtnValidate.addEventListener('click', (e)=>{

      
      validateReserva.elementos = {
          elmConsumibles: consumibles,
          elmDevolutivos: devolutivos,
      };

      tableContainerDetail.style.display = "none";
      formValidateContainer.style.display = "flex";

      previewBtnValidate.style.display = 'inline-flex';
      nextBtnValidate.style.display = 'none';

      console.log({'validatereserva nextBtnValidate':validateReserva});
      
      
    });
    
    previewBtnValidate.addEventListener('click', (e)=>{
      e.stopPropagation();
      e.preventDefault();
      console.log({'validatereserva preview':validateReserva});
      
      tableContainerDetail.style.display = "flex";
      tableContainerDetail.style.flexDirection  = "column";
      formValidateContainer.style.display = "none";
      
      previewBtnValidate.style.display = 'none';
      nextBtnValidate.style.display = 'inline-flex';
      
    });




    // confirm(`¿Deseas dar salida a estos elementos? \n
    //   Consumibles:\n${
    //     elementosPreviewConsu.map((el) =>
    //       `Código: ${el.codigo} Nombre: ${el.nombre} Cantidad: ${el.cantidadSolicitada}`
    //     ).join("\n")
    //   }
    //   \nDevolutivos:\n${
    //     elementosPreviewDev.map((elDev) =>
    //       `Código: ${elDev.codigo} Nombre: ${elDev.nombre}`
    //     ).join("\n")
    //   }`)

    //Todo: implementar el if con el envio de data.
    // if () {

    // try {

    //   sendData('modules/reservaPrestamos/controller/reservaController.php','POST','validateLoan',validateReserva).then((response)=>{

    //     // tdAcciones.innerHTML = "";
    //     estadoNew.textContent = 'Validado';
    //     estadoNew.style.color = "green";

    //     let btnValidate = tdAcciones.querySelector(`button[data-validate='${validateReserva.codigoReserva}']`);
    //     if (btnValidate) {
    //       initAlert(`Prestamo validado ${validateReserva.codigoReserva}`,'success',toastOptions);
    //       btnValidate.style.display = "none";
    //     }

    //     let btnEnd = document.createElement("button");
    //     let iFinalizar = createI();
    //     iFinalizar.innerText = 'swap_horiz';
    //     //Este bloque de codigo se repite Más arriba, puedo buscar una forma para refactorizar.
    //     addClassItem(btnEnd,{btn: "btn",color: "red lighten-1",wavesEffect: "waves-effect",wavesLight: "waves-light",btnSmall: "btn-small"});
    //     btnEnd.append(iFinalizar);
    //     btnEnd.setAttribute("data-end", `${validateReserva.codigoReserva}`);
    //     tdAcciones.appendChild(btnEnd);

    //   });
    // } catch (error) {
    //   console.warn('error al realizar el proceso'+error);
    // }
    // }
  }
});

closeModal(modalValidate, btnCloseValidte);

/**
 * Paginación de los prestamos.
 *
 */
const previewReserva = document.querySelector("#previewReservas");
const nextReserva = document.querySelector("#nextReservas");
//TODO: necesito 2 funciones, 1 para mandar la solicitud y la otra para renderizar, usar fetch con async y await.

previewReserva.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();
  //Si es 1, el sale del evento y no ejecuta Más.
  if (pagesReserva <= 1) return;

  const prevPage = pagesReserva - 1;
  renderReservas(prevPage);
});

nextReserva.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();

  //Si el numero de Páginas enviado es mayor o igual al numero de paginas que tiene los registros, no haga petición.
  if (pagesReserva >= pages) return;
  const nextPage = pagesReserva + 1;
  renderReservas(nextPage);
});

closeModal(modalDetail, btnCloseElements);
//Limpiar la tabla apenas se cierre el modal.
BodydetailReserva.innerHTML = "";
