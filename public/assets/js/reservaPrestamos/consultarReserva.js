import { Ajax } from "../libraries/ajax.js";
import { closeModal } from "../libraries/cases.js";

const objAjax = new Ajax();

//Cuerpo de la tabla para renderizar los datos.
const tbodyReservaConsult = document.querySelector('#tbodyReservaConsult');
const modalDetail = document.querySelector('#modalDetail');
const btnCloseElements = document.querySelector(
  "#modalDetail .close-modal"
);
const formDetail = document.querySelector('#formDetail');
//TODO: mejorarlo.
let data = {};
const BodydetailReserva = document.querySelector('#BodydetailReserva');
const action = 'reservas';
function getReservas(){

    objAjax.request.open('GET',`modules/reservaPrestamos/controller/reservaController.php?action=${encodeURIComponent(action)}`,true);
    objAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

    objAjax.request.onload = ()=>{
        let response = JSON.parse(objAjax.request.responseText);
        console.log(response);

        if (!response.status) {
            //TODO: Agregar un span a la tabla para visualizar que no hay elementos.
            throw new Error("No hay elementos");
            
        }

        data = response.data.data;

        tbodyReservaConsult.innerHTML = '';
        data.forEach((dta)=>{

            let tr = document.createElement('tr');
            let btnAdd = document.createElement('button');
            let btnDetail = document.createElement('button');
            btnDetail.innerText = 'Detalle';
            btnAdd.innerHTML= '+';
            btnDetail.setAttribute('class', 'btnDetail');
            btnDetail.setAttribute('data-id',`${dta.codigo}`);
            btnAdd.setAttribute('class','addElements');
            btnAdd.setAttribute('data-add',`${dta.codigo}`);
            let tdCodigo = document.createElement('td');
            let tdNombreCompleto = document.createElement('td');
            
            //TODO: la cantidad la saco haciendo una consulta basada en el count del prestamo al cual pertenecen los prestamos, por ahora, estalbecer por 1.
            let tdCantidad = document.createElement('td');
            let tdEstado = document.createElement('td');
            let tdAcciones = document.createElement('td');
            let tdTipo = document.createElement('td');            
            tdCodigo.textContent = dta.codigo;
            tdNombreCompleto.textContent = dta.nombre+" "+dta.apellido;
            tdEstado.textContent = dta.estadoPrestamo;
            tdTipo.textContent = dta.tipoPrestamo;

            tbodyReservaConsult.appendChild(tr);
            tr.appendChild(tdCodigo);
            tr.appendChild(tdNombreCompleto);
            tr.appendChild(tdEstado);
            tr.appendChild(tdTipo);
            tr.append(btnDetail,btnAdd);

        });

    }

    objAjax.request.setRequestHeader("Accept", "application/json");
    objAjax.request.send();

}

document.addEventListener('DOMContentLoaded', ()=>{
    getReservas();

});

tbodyReservaConsult.addEventListener('click', (event) =>{
    event.preventDefault();
    event.stopPropagation();
    if (event.target.tagName === 'BUTTON' && event.target.getAttribute(['data-id'])) {
        let dataTr = event.target.closest('tr');
        //let codigo = dataTr.children[0].textContent;
        const nroIdentidad = document.querySelector('#formDetail #nroIdentidad');
        const nombre = document.querySelector('#formDetail #nombreCompleto');
        const fechaReserva = document.querySelector('#formDetail #fechaReserva');
        const fechaSolicitud = document.querySelector('#formDetail #fechaSolicitud');
        const fechaDevolucion = document.querySelector('#formDetail #fechaDevolucion');
        const observaciones = document.querySelector('#formDetail #observaciones');

        //Busco todos los input que tengan la clase inputFormDetail y los ciclo para aplicar un readOnly con el fin de que el usuario solo pueda leer la información, no manipularla.
        const readOnly = document.querySelectorAll('#formDetail .inputFormDetail').forEach((input)=>{
            input.readOnly=true;
        });
                
        let nombreCompleto = dataTr.children[1].textContent;
        let tipo = dataTr.children[2].textContent;
        let estado = dataTr.children[3].textContent;

        const codigo = parseInt(event.target.getAttribute('data-id'));
        const reserva = data.find(item => item.codigo === codigo);

        let action = 'reservaDetailElements';

        objAjax.request.open('GET',`modules/reservaPrestamos/controller/reservaController.php?codigo=${encodeURIComponent(codigo)}&action=${encodeURIComponent(action)}`,true);
        objAjax.request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        objAjax.request.onload = ()=>{
            
            let response = JSON.parse(objAjax.request.responseText);

            let elementos = response.data;
            BodydetailReserva.innerHTML = '';
            elementos.forEach((elm) =>{
                const trTable = document.createElement('tr');
                const tdCodigo = document.createElement('td');
                const tdNombre = document.createElement('td');
                const tdAccion = document.createElement('td');
                
                //Posiblemente lo haga para aca agregar elmeentos.
                let btnAdd = document.createElement('button');
                btnAdd.innerText = 'btnEjemplo'; 
                tdCodigo.innerText = elm.codigo;
                tdNombre.innerText = elm.nombre;

                BodydetailReserva.appendChild(trTable);
                tdAccion.append(btnAdd);
                trTable.appendChild(tdCodigo);
                trTable.appendChild(tdNombre);
                trTable.appendChild(tdAccion);



            });
        }

        objAjax.request.setRequestHeader("Accept", "application/json");
        objAjax.request.send();
        
        modalDetail.style.display = 'flex';
        nombre.value = nombreCompleto;
        fechaReserva.value = reserva.fechaReserva;
        fechaSolicitud.value = reserva.fechaSolicitud;
        fechaDevolucion.value = reserva.fechaDevolucion;
        nroIdentidad.value = reserva.nroIdentidad;
        observaciones.value = reserva.observacion;

    }

    //Para adicionar elementos en caso de que sea requerido.
    if (event.target.tagName === 'BUTTON' && event.target.getAttribute(['data-add'])) {
        console.log(event.target);
        
    }
});

closeModal(modalDetail,btnCloseElements);
//Limpiar la tabla apenas se cierre el modal.
BodydetailReserva.innerHTML = '';