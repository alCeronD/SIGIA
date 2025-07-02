import { getData } from "../utils/fetch.js";
import { createBtn } from "../utils/cases.js";

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
export const renderElements = async ({type = 'all', action = 'elements', page = 1, pageGlobal = 1} = {}) => {
const tbodyElements = document.querySelector('#tbodyElementos');

    try {
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