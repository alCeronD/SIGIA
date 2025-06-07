import { Ajax } from "../libraries/ajax.js";

const objAjax = new Ajax();
const btnSubmit = document.getElementById('btnSubmit');
const tableDevolutivos = document.querySelector('#bodyDevolutions');
const modalAddElements = document.querySelector('#modalAddElements');
const btnAddElements = document.getElementById('btnAddElements');
const modalTitle = document.querySelector('#modalTitle');
const btnCloseButton = document.querySelector('.close');
let dataDevolutivos = {};
let dataConsumibles = {};
btnAddElements.innerText = 'Seleccionar elementos';
modalTitle.innerText = 'Elementos disponibles';
btnSubmit.innerText = 'Reservar';

// AJAX GET ELEMENTS
/**
 * TODO: Cambiar a función.
 */

document.addEventListener('DOMContentLoaded', ()=>{
    objAjax.request.open('GET','modules/reservaPrestamos/controller/reservaController.php',true);
     objAjax.request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    objAjax.request.onload = ()=>{
        //Transformo la respuesta
        let response = JSON.parse(objAjax.request.responseText);
        dataDevolutivos = response.data;
        //console.log(data);
    }
    //Específicamos que respuesta queremos recibir
    objAjax.request.setRequestHeader('Accept', 'application/json');
    objAjax.request.send();

});

btnAddElements.addEventListener('click',(btnTarget)=>{
    btnTarget.preventDefault();
    btnTarget.stopPropagation();

    //visualizar modal.
    modalAddElements.style.display = "flex";

    //Limpiar la tabla antes de renderizarla, esto se hace para evitar duplicados.
    tableDevolutivos.innerHTML = "";
    //Implementar los datos en en la tabla.
    dataDevolutivos.forEach(dta => {

        let codigo = dta.codigo;
        let elemento = dta.elemento;
        let area = dta.area;

        let trTable = document.createElement('tr');
        let tdCodigo = document.createElement('td');
        let tdElemento = document.createElement('td');
        let tdArea = document.createElement('td');

        //A los elementos td les implemento su contenido, su contenido es la información de la tabla
        tdCodigo.textContent = codigo;
        tdElemento.textContent = elemento;
        tdArea.textContent = area;

        tableDevolutivos.appendChild(trTable);
        trTable.appendChild(tdCodigo);
        trTable.appendChild(tdElemento);
        trTable.appendChild(tdArea);


        
    });
});


// Cerrar el modal.
btnCloseButton.addEventListener('click', (event)=>{
    event.stopPropagation();
    event.preventDefault();

    modalAddElements.style.display = 'none';

});

