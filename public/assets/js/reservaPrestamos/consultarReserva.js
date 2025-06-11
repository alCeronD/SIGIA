import { Ajax } from "../libraries/ajax.js";
import { closeModal } from "../libraries/cases.js";

const objAjax = new Ajax();

//Cuerpo de la tabla para renderizar los datos.
const tbodyReservaConsult = document.querySelector('#tbodyReservaConsult');
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

        let data = response.data.data;

        tbodyReservaConsult.innerHTML = '';
        data.forEach((dta)=>{

            let tr = document.createElement('tr');
            let btnAdd = document.createElement('button');
            btnAdd.setAttribute('class','addElements');
            
            let btnDetail = document.createElement('button');
            btnDetail.setAttribute('class', 'btnDetail');
            btnDetail.innerText = 'Detalle';
            btnAdd.innerHTML= '+';
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




        })

    }

    objAjax.request.setRequestHeader("Accept", "application/json");
    objAjax.request.send();

}


document.addEventListener('DOMContentLoaded', ()=>{
    getReservas();

});