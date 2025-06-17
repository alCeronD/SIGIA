import { Ajax } from "../libraries/ajax.js";
import { closeModal } from "../libraries/cases.js";

const objAjax = new Ajax();

//Cuerpo de la tabla para renderizar los datos.
const tbodyReservaConsult = document.querySelector("#tbodyReservaConsult");
const modalDetail = document.querySelector("#modalDetail");
const btnCloseElements = document.querySelector("#modalDetail .close-modal");
const formDetail = document.querySelector("#formDetail");
//TODO: mejorarlo.
let data = {};
//variable para guardar los elementos
let elementos ={};
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
      btnDetail.innerText = "Detalle";
      btnEnd.innerHTML = "Finalizar";
      btnDetail.setAttribute("class", "btnDetail btnClick");
      btnDetail.setAttribute("data-id", `${dta.codigo}`);
      btnAdd.setAttribute("class", "addElements");
      btnAdd.setAttribute("data-add", `${dta.codigo}`);
      btnEnd.setAttribute("data-end", `${dta.codigo}`);
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
      tr.append(btnDetail, btnEnd);

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
        detalleAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

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
  //console.log(getReservas());
  getReservas();
});

tbodyReservaConsult.addEventListener("click", (event) => {
  event.preventDefault();
  event.stopPropagation();
  if (
    event.target.tagName === "BUTTON" &&
    event.target.getAttribute(["data-id"])
  ) {
    let dataTr = event.target.closest("tr");
    //let codigo = dataTr.children[0].textContent;
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

    modalDetail.style.display = "flex";
    //TODO: transformar a texto span.
    nombre.value = nombreCompleto;
    fechaReserva.value = reserva.fechaReserva;
    fechaSolicitud.value = reserva.fechaSolicitud;
    fechaDevolucion.value = reserva.fechaDevolucion;
    nroIdentidad.value = reserva.nroIdentidad;
    observaciones.value = reserva.observacion;
  }

  //Para visualizar el detalle de elementos en caso de que sea requerido.
  if (event.target.tagName === "BUTTON" &&
    event.target.getAttribute(["data-add"])
  ) {
    console.log(event.target);
  }

  //Finalizar el prestamo de los elementos.
  if (
    event.target.tagName === "BUTTON" &&
    event.target.getAttribute(["data-end"])
  ) {

    if (confirm("¿Esta seguro de finalizar el prestamo?")) {
        //se compara con doble igual porque el json que recibe de data su codigo esta en string pero el getAttribute esta como entero.
        const codigoReserva = Number(event.target.getAttribute(["data-end"]));
      const dataResult = data.find(
        (dta) => (Number(dta.codigo) === codigoReserva)
      );
      //Valida que sea true la respuesta de dataResult y que el codigo de la reserva este en el objeto elementos.
        if (dataResult && codigoReserva && elementos[codigoReserva]) {
        const reservaConElementos = elementos[codigoReserva];
        const listaElementos = reservaConElementos.elementos;

        console.log("Reserva:", reservaConElementos.reserva);
        console.log("Elementos asociados:", listaElementos);

        //Debo enviar la lista de los elementos, el código del prestamo y el número de identificación.
        let endReserva = {
            "elementos":listaElementos,
            "codigoReserva":reservaConElementos.reserva.codigo
        }

        const objEndReserva = new Ajax();

        objEndReserva.request.open('POST','modules/reservaPrestamos/controller/reservaController.php');
        objEndReserva.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        objEndReserva.request.setRequestHeader("Content-Type", "application/json");

        let reservaJson = JSON.stringify({
            data: endReserva,
            action: 'finalizar'
        });

        objEndReserva.request.onload = ()=>{
            let response = objEndReserva.request.responseText;
            console.log(response);
        }
        objEndReserva.request.setRequestHeader("accept", "application/json");
        objEndReserva.request.send(reservaJson);


        } else {
        console.warn("No se encontraron elementos asociados aún. Espera a que cargue la información.");
        }
    }
  }
});

closeModal(modalDetail, btnCloseElements);
//Limpiar la tabla apenas se cierre el modal.
BodydetailReserva.innerHTML = "";
