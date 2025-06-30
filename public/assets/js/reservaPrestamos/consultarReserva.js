import { Ajax } from "../utils/ajax.js";
import { closeModal, createBtn, instanceDate, instanceDateTime, instanceModal, opcionesDatepicker, openModal, options, setReserva, statusLoans, typeLoans } from "../utils/cases.js";
import { sendData } from "../utils/fetch.js";

const objAjax = new Ajax();


//Cuerpo de la tabla para renderizar los datos.
const tbodyReservaConsult = document.querySelector("#tbodyReservaConsult");
// const modalDetail = document.querySelector("#modalDetail");
const modalDetail = instanceModal('#modalDetail',options);
const btnCloseElements = document.querySelector("#modalDetail .close-modal");
const formDetail = document.querySelector("#formDetail");
//TODO: mejorarlo.
let data = {};
//variable para guardar los elementos
let elementos = {};
const BodydetailReserva = document.querySelector("#BodydetailReserva");
//Codigo del prestamo para hacer el fech
let cases = "reservas";
let codigo;

//Variable para mostrar la información en el modal.
let elementosDetalle = [];
function getReservas() {
  //console.log(cases);
  //Fetch para traer la información del prestamo
  objAjax.request.open(
    "GET",
    `modules/reservaPrestamos/controller/reservaController.php?action=${encodeURIComponent(
      cases
    )}`,
    true
  );
  objAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  objAjax.request.onload = () => {
    let response = JSON.parse(objAjax.request.responseText);

    if (!response.status) {
      //TODO: Agregar un span a la tabla para visualizar que no hay elementos.
      throw new Error("No hay elementos");
    }
    tbodyReservaConsult.innerHTML = "";
    data = response.data.data;
    //Codigo que servira para renderizar las elementos a corde a su código
    data.forEach((dta) => {
      codigo = dta.codigo;

      let tr = document.createElement("tr");
      let btnAdd = document.createElement("button");
      let btnEnd = document.createElement("button");
      let btnDetail = document.createElement("button");
      let btnValidateLoan = createBtn('btnClick');

      btnDetail.innerText = "Detalle";
      btnValidateLoan.innerText = 'validar';
      btnDetail.setAttribute("class", "btnDetail btnClick");
      btnDetail.setAttribute("data-id", `${dta.codigo}`);
      btnAdd.setAttribute("class", "addElements");
      btnAdd.setAttribute("data-add", `${dta.codigo}`);
      btnEnd.setAttribute("data-end", `${dta.codigo}`);
      btnValidateLoan.setAttribute("data-validate", `${dta.codigo}`);
      btnEnd.innerText = 'finalizar';
      btnAdd.setAttribute("class", "btnEnd");
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
      tr.append(tdAcciones);

      //Si el estado del prestamo es finalizado, no debe de visualizar el boton de finalizar.
      if (tdEstado.textContent === 'Finalizado') {
        btnEnd.style.display = 'none';
      }


      if (dta.codigoTipoPrestamo === typeLoans.solicitud) {
        tdAcciones.append(btnDetail,btnValidateLoan);
      }

      if (dta.codigoTipoPrestamo == typeLoans.inmediata) {
        tdAcciones.append(btnDetail,btnEnd);
      }


      const reserva = data.find((item) => item.codigo === codigo);

      if (reserva) {
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

  objAjax.request.setRequestHeader("Accept", "application/json");
  objAjax.request.send();
}

document.addEventListener("DOMContentLoaded", () => {
  getReservas();
});

tbodyReservaConsult.addEventListener("click", (event) => {
  event.preventDefault();
  event.stopPropagation();
  if (event.target.tagName === "BUTTON" && event.target.getAttribute(["data-id"])
  ) {
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

    codigo = parseInt(event.target.getAttribute("data-id"));
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
        const tdAccion = document.createElement("td");

        let btnAdd = document.createElement("button");
        btnAdd.innerText = "btnEjemplo";

        tdCodigo.innerText = elm.codigo;
        tdNombre.innerText = elm.nombre;

        tdAccion.append(btnAdd);
        trTable.appendChild(tdCodigo);
        trTable.appendChild(tdNombre);
        trTable.appendChild(tdAccion);
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
  if (
    event.target.tagName === "BUTTON" &&
    event.target.getAttribute(["data-add"])
  ) {
    console.log(event.target);
  }

  //Finalizar el prestamo de los elementos.
  if (event.target.tagName === "BUTTON" && event.target.getAttribute(["data-end"])) {
    //se compara con doble igual porque el json que recibe de data su codigo esta en string pero el getAttribute esta como entero.

    //Crear función para finalizar los prestamos de los elementos, se debe de finalizar a ambos prestamos, los que se hace como reserva Previa y reserva inmediata.
    function endLoan(atributeData){}

  
    //Hago la captura de la data necesaria para validar el prestamo o para reservalo inmedaitamente.
    let endReserva = setReserva('data-end',data,elementos,event.target,"finalizar");
    console.log(endReserva);
    const objEndReserva = new Ajax();
      if (
        confirm(
          `¿Está seguro de finalizar el préstamo?\nEstos son los elementos que cambiarán a disponible:\n${
            endReserva.elementos
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
              alert(
                `Prestamo # ${endReserva.codigoReserva} finalizada`
              );

              let codigoAdd = endReserva.codigoReserva;
              console.log(codigoAdd);
              let tr = [
                ...document.querySelectorAll("#tbodyReservaConsult tr"),
              ];

              tr.forEach((infoTr) => {

                if (infoTr.querySelector("td").textContent.includes(String(codigoAdd))) {
                  console.log(infoTr);
                  
                  let tdEstado = infoTr.children[2];
                  let tdAcciones = infoTr.children[4];
                  let btnEnd = tdAcciones.querySelector('button[data-end="' + codigoAdd + '"]');

                  btnEnd.style.display = "none";
                  tdEstado.textContent = "Finalizado";

                  if (tdEstado.textContent === "Finalizado") {
                    btnEnd.style.display = "none";
                  }
                }
              });
            } else {
              console.warn(
                "Respuesta negativa del servidor:",
                response.message
              );
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

  //Dar salida a los prestamos, cambiar el estado de la solicitudes a validada e implementar su salida.
  if (event.target.tagName === "BUTTON" && event.target.getAttribute(["data-validate"])) {
    // console.log(data);
    //Capturo los datos para transformarlo en json.
    let validateReserva = setReserva('data-validate',data,elementos,event.target);
    let action = 'validateLoan';
    validateReserva['action'] = action;
    let consumibles = [];
    let devolutivos = [];

    let previewElements = validateReserva.elementos;
    previewElements.forEach(element => {
          if (element.codTipoElemento === 1) {
        devolutivos.push(element);
      } else if (element.codTipoElemento === 2) {
        consumibles.push(element);
      }

    });

    //Borro todos los elementos que esten en el objeto y los re asigno
    delete validateReserva.elementos;

    validateReserva.elementos = {    
      elmConsumibles: consumibles,
      elmDevolutivos: devolutivos
    }

    //Variable para visualizar los elementos antes de validar el prestamo
    let elementosPreviewConsu = validateReserva.elementos.elmConsumibles;
    let elementosPreviewDev = validateReserva.elementos.elmDevolutivos;

    console.log(validateReserva);
    //Todo: implementar esto en sweetAlert
    if (confirm(`¿Deseas dar salida a estos elementos? \n
      Consumibles:\n${
        elementosPreviewConsu.map((el) => 
          `Código: ${el.codigo} Nombre: ${el.nombre} Cantidad: ${el.cantidadSolicitada}`
        ).join("\n")
      }
      \nDevolutivos:\n${
        elementosPreviewDev.map((elDev) => 
          `Código: ${elDev.codigo} Nombre: ${elDev.nombre}`
        ).join("\n")
      }`)) {

        console.log('funciona mi papayo');
        let response = sendData('modules/reservaPrestamos/controller/reservaController.php','POST','validateLoan',validateReserva);
      }
      
  }

});

closeModal(modalDetail, btnCloseElements);
//Limpiar la tabla apenas se cierre el modal.
BodydetailReserva.innerHTML = "";
