// import {renderElement, renderElements } from "./fetchElements.js";
import { getData } from "../utils/fetch.js";
import { closeModal, createBtn, createI, initTooltip, instanceModal, options, tooltipOptions } from "../utils/cases.js";

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
const inputBusqueda = document.querySelector('#inputBusqueda');
const tbodyElements = document.querySelector('#tbodyElements');
const tipoElementoSelect = document.querySelector('#tipoElementoSelect');
const addElementModal = instanceModal('#addElementModal', options);
const btnAddModalElements = document.querySelector('#btnAddModalElements');
const cerrarModalBtn = document.querySelector('#cerrarModalRegistrar');
// Select de las areas, es para registrar el elemento.
const selectAreaDev = document.querySelector('#select_area_dev');
const selectAreaConsu = document.querySelector('#select_area_consu');
let iBtnAddElements = createI();
iBtnAddElements.innerText = 'add'
btnAddModalElements.append(iBtnAddElements);
//El tipo de elementos, creamos esta variable para reemplazarla ya que le daremos utilidad en los filtros.
let currentType = typeElements.all;
/**
 * Renderiza elementos desde el backend utilizando filtros de tipo, acción y paginación.
 * Realiza una petición GET al servidor y construye dinámicamente el contenido de una tabla HTML.
 *
 * @async
 * @function renderElements
 * @param {Object} [options={}] - Objeto con parámetros de configuración.
 * @param {string} [options.type='all'] - Tipo de elemento a consultar. Valores posibles: `'all'`, `'consumible'`, `'devolutivo'`.
 * @param {string} [options.action='elements'] - Acción a enviar al backend, normalmente usada para definir el contexto de la consulta.
 * @param {number} [options.page=1] - Página actual de la consulta para el sistema de paginación.
 * @param {number} [options.pageGlobal=1] - Total de páginas disponibles (se actualiza con la respuesta del backend).
 *
 * @returns {Promise<void>} - No retorna ningún valor explícito, pero actualiza dinámicamente la tabla en el DOM.
 *
 * @example
 * // Renderizar todos los elementos en la primera página
 * renderElements();
 *
 * @example
 * // Renderizar elementos consumibles en la página 2
 * renderElements({ type: 'consumible', page: 2 });
 *
 * @example
 * // Renderizar elementos devolutivos con acción personalizada
 * renderElements({ type: 'devolutivo', action: 'buscarElementos', page: 1 });
 */
const renderElements = async ({type = 'all', action = 'elements', page = 1} = {}) => {

    try {
        const dataElements = await getData(
        'modules/elementos/controller/elementosController.php',
        'GET',
        { action, pages: page, type }
    );

    let data = dataElements.data.data;
    console.log(data);
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
        if (dta.cantidad <= 10 && dta.tipoElemento === 'Consumible') {
            tr.style.backgroundColor = '#e57373';
            tr.style.color = 'white';
        }


        tbodyElements.appendChild(tr);
        tdAcciones.append(btnInfo,btnEdit,btnDelete,btnAdd);
        tr.append(tdPlaca,tdNombreElemento,tdCantidad,tdUnidadMedida,tdTipoElemento,tdEstadoElemento,tdAreaElemento,tdAcciones);


    });

    } catch (error) {
        throw new Error(`Error al consultar los elementos ${error}`);
                
    }
    
};

const getAreas = async (type = 'devolutivo')=>{
    let response = await getData('modules/elementos/controller/elementosController.php','GET',{action: 'areas'});
    let data = response.data;
    selectAreaDev.innerHTML = '';
    selectAreaConsu.innerHTML = '';
    const defaultOption = document.createElement('option');
    defaultOption.setAttribute('selected', 'selected');
    defaultOption.innerText = 'Seleccione un área';


    selectAreaDev.appendChild(defaultOption);
    selectAreaConsu.appendChild(defaultOption);

        if (type === 'devolutivo') {
            data.forEach((dta)=>{
            
                if (dta.nombre != 'General') {
                    const option = document.createElement('option');
                    option.innerText = dta.nombre;
                    option.value = dta.codigo;
                    selectAreaDev.appendChild(option);
                } 
            });           
        }else if (type === 'consumible'){
            const areaGeneral = data.find((dta) => dta.nombre === 'General');
            if (areaGeneral) {
                const option = document.createElement('option');
                option.innerText = areaGeneral.nombre;
                option.value = areaGeneral.codigo;
                console.log(option);
                selectAreaConsu.appendChild(option);
            }
            
        }

    //Reinicializo los select, accedo a ellos mediante el objeto window.  
    if (window.M) {
        M.FormSelect.init(selectAreaDev);
        M.FormSelect.init(selectAreaConsu);
    }

}

document.addEventListener('DOMContentLoaded', ()=>{
    //Inicializo los select.
    M.FormSelect.init(formDevolutivo.querySelectorAll('select'));
    initTooltip(btnAddModalElements,tooltipOptions,'Agregar elemento','top');
    //Renderizado de los elementos
    renderElements({type: currentType});

});

/**
 * Filtro de elementos
 */
filtroTipo.addEventListener('change', (e)=>{
    e.stopPropagation();
    e.preventDefault();
    // reinicio a la primera Página para que siempre dependiendo del filtro, visualice la primera página como inicio.
    pageElement = 1;
    //Hacer una validación de que el valor de optión exista.
    /**
     * por ejemplo, si el optin es devolutivo o cnsumible o todo, debe ejecutar, pero si hay OTRO, debe de mostrar x defecto el todo.
     */
    const selectedOption = e.target.options[e.target.selectedIndex];
    if (selectedOption.value === typeElements.dev) {
        currentType = typeElements.dev;
    }else if (selectedOption.value === typeElements.consu){
        currentType = typeElements.consu;
    }else{
        currentType = typeElements.all;
    }    
    renderElements({type : currentType, page: pageElement});
});

/**
 * Paginación de elementos
 */
previewElements.addEventListener('click', (e)=>{
    e.stopPropagation();
    e.preventDefault();
    if (pageElement <= 1 )return;
    pageElement--;
    renderElements({type: currentType ,page: pageElement});
});

nextElements.addEventListener('click', (e) => {
    e.stopPropagation();
    e.preventDefault();
    if (pageElement >= pageGlobal) return;
    pageElement++;
    renderElements({type: currentType, page:pageElement});
});

/**
 * Búsqueda de elementos TODO: por implementar, la consulta ya esta hecha.
 */
let timer;
// También puedes buscar al escribir directamente
    inputBusqueda.addEventListener('keyup', function (e) {
        e.stopPropagation();
        const filtro = e.target.value.toLowerCase().trim();
        // console.log({"valor": filtro});
        // console.log({"cantidadCaracteres": filtro.length});
        // console.log(filtro);

        // if (filtro.length === 0) {
        //     renderElements({type : typeElements.all, type: currentType});
        //     return 
        // }

        // timer = setTimeout(()=>{
        //     renderElement({action: 'onlyElement', value:filtro});

        // }, 400);
        
    });


/**
 * Registrar elemento.
 */

//Abrir modal
btnAddModalElements.addEventListener('click', (e)=>{
    e.stopPropagation();
    e.preventDefault();

    addElementModal.open();
    

});

//Elegir tipo de elemento
tipoElementoSelect.addEventListener('change', (e) => {
    const tipo = e.target.value;

    if (tipo === 'devolutivo') {
        getAreas('devolutivo');
        formDevolutivo.style.display = 'block';
        formConsumible.style.display = 'none';
    } else if (tipo === 'consumible') {
        getAreas('consumible');
        formConsumible.style.display = 'block';
        formDevolutivo.style.display = 'none';
    } else {
        formDevolutivo.style.display = 'none';
        formConsumible.style.display = 'none';
    }
});




closeModal(addElementModal,cerrarModalBtn);
