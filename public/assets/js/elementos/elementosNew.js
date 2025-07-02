import { getData } from "../utils/fetch.js";
// import {renderElements } from "./fetchElements.js";
import { createBtn } from "../utils/cases.js";

const typeElements = {
    dev: 'devolutivo',
    consu: 'consumible',
    all: 'all'
}
let pageElement = 1;
let pageGlobal;
const filtroTipo = document.querySelector('#filtroTipo');
const previewElements = document.querySelector('#previewElements');
const nextElements = document.querySelector('#nextElements');


/**
 * Obtiene elementos desde el backend mediante una petición GET con filtros de tipo y paginación.
 * /**
 * Renderiza elementos desde el backend usando filtros dinámicos.
 * 
 * @param {Object} options - Parámetros de filtro.
 * @param {string} [options.type='all'] - Tipo de elemento.
 * @param {string} [options.action='elements'] - Acción para el backend.
 * @param {number} [options.page=1] - Número de página.
 *
 * @example
 * // Obtener todos los elementos
 * getElements();
 *
 * @example
 * // Obtener elementos de tipo consumible en la página 2
 * getElements('consumible', 'elements', 2);
 */
const renderElements = async ({type = 'all', action = 'elements', page = 1} = {}) => {
    try {

    const tbodyElements = document.querySelector('#tbodyElementos');

        const dataElements = await getData(
        'modules/elementos/controller/elementosController.php',
        'GET',
        { action, pages: page, type }
    );

    let data = dataElements.data.data;
    pageGlobal = dataElements.data.cantidadPaginas;
    if (page > pageGlobal) {
        return;
    }
    
    //Puedo hacer que si el tipo del elemento es consumible o no, se renderice solo unas columnas, no otras.
    tbodyElements.innerHTML = "";
    data.forEach((dta)=>{

        let tr = document.createElement('tr');
        let tdPlaca = document.createElement('td');
        let tdCantidad = document.createElement('td');
        let tdCodigoElemento = document.createElement('td');
        let tdEstadoElemento = document.createElement('td');
        let tdNombreElemento = document.createElement('td');
        let tdAreaElemento = document.createElement('td');
        let tdUnidadMedida = document.createElement('td');
        let tdTipoElemento = document.createElement('td');
        let tdAcciones = document.createElement('td');
        const btnInfo = createBtn('btn');
        const btnEdit = createBtn('btn');
        const btnDelete = createBtn('btn');
        const btnAdd = createBtn('btn');
        btnInfo.innerText = '1';
        btnEdit.innerText = '2';
        btnDelete.innerText = '3';
        btnAdd.innerText = '4';

        tdPlaca.innerText = dta.placa;
        tdCantidad.innerText = dta.cantidad;
        tdCodigoElemento.innerText = dta.codigoElemento;
        tdEstadoElemento.innerText = dta.estadoElemento;
        tdNombreElemento.innerText = dta.nombreElemento;
        tdAreaElemento.innerText = dta.nombreArea;
        tdUnidadMedida.innerText = dta.nombreUnidad;
        tdTipoElemento.innerText = dta.tipoElemento;
        tdEstadoElemento.innerText = dta.estadoElemento;
        tdAreaElemento.innerText = dta.nombreArea;

        tbodyElements.appendChild(tr);
        tdAcciones.append(btnInfo,btnEdit,btnDelete,btnAdd);
        tr.append(tdPlaca,tdNombreElemento,tdCantidad,tdUnidadMedida,tdTipoElemento,tdEstadoElemento,tdAreaElemento,tdAcciones);


    });

    } catch (error) {
        throw new Error(`Error al consultar los elementos ${error}`);
                
    }
    
};

document.addEventListener('DOMContentLoaded', ()=>{

    renderElements();
});

//La forma en como filtro los elementos se puede cambiar.
filtroTipo.addEventListener('change', (e)=>{
    e.stopPropagation();
    e.preventDefault();

    //Hacer una validación de que el valor de optión exista.
    /**
     * por ejemplo, si el optin es devolutivo o cnsumible o todo, debe ejecutar, pero si hay OTRO, debe de mostrar x defecto el todo.
     */
    const selectedOption = e.target.options[e.target.selectedIndex];
    if (selectedOption.value === typeElements.dev) {
        renderElements(typeElements.dev);
    }else if (selectedOption.value === typeElements.consu){
        renderElements(typeElements.consu);
    }else{
        renderElements();
    }
    
});

previewElements.addEventListener('click', (e)=>{
    e.stopPropagation();
    e.preventDefault();
    console.log(pageElement);
    if (pageElement <= 1 )return;
    pageElement--;
    renderElements({page: pageElement});
});

nextElements.addEventListener('click', (e) => {
    e.stopPropagation();
    e.preventDefault();
    if (pageElement >= pageGlobal) return;
    pageElement++
    renderElements({page: pageElement});
});