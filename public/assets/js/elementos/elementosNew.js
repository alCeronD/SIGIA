// import {renderElement, renderElements } from "./fetchElements.js";
// TODO: Depurar, este bloque del proyecto puede ser transladado a un archivo barril.
import { closeModal, createBtn, createCheckbox, createI, initAlert, initTooltip, instanceModal, options, toastOptions, tooltipOptions } from "../utils/cases.js";
import { validarCantidad, validatePlaca, validationRules } from "../utils/regex.js";
import { getData, sendData } from "../utils/fetch.js";

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
let iBtnAddElements = createI();
iBtnAddElements.innerText = 'add'
btnAddModalElements.append(iBtnAddElements);
//El tipo de elementos, creamos esta variable para reemplazarla ya que le daremos utilidad en los filtros.
let currentType = typeElements.all;

// Contenedor de la placa e inputs
const placaInputs = document.querySelector('.placaInputs');
const inputPlaca = document.querySelector('.inputPlaca');
const selectPlaca = document.querySelector('.selectPlaca');
const inputSerie = document.querySelector('.inputSerie');
const contentPlaca = document.querySelector('.contentPlaca');
// Radio button
const nuevaPlaca = document.querySelectorAll('input[name="placaRadio"]');
const selectedPlaca = document.querySelector('#selectPlaca');
// Input de unidad de medida.
// const undMedida = document.querySelector('#undMedida');
const tpElemento = document.querySelectorAll('input[name="elm_cod_tp_elemento"]');
const checkboxTpElemento = document.querySelector('.checkboxTpElemento');
const selectAreas = document.querySelector('#selectAreas');
const selectCategorias = document.querySelector('#categoriaSelect');
const selectMarcas = document.querySelector('#selectMarca');
const selectTpElemento = document.querySelector('#selectTpElemento');
// Input para buscar la placa.
const searchPlaca = document.querySelector('#searchPlaca');
// Tabla de las placas
const tablePlaca = document.querySelector('.tableResult');
// Aca voy a mostrar el resultado.
const tbodyPlacaResult = document.querySelector('#tbodyPlacaResult');
// Formulario de envio de elemento.
const addElementForm = document.querySelector('#addElementForm');
const placaAssocContent = document.querySelector('.placaAssocContent');
// Input del serial que se va a asociar con la placa
const serialPlacaAssoc = document.querySelector('#serialPlacaAssoc');

// Campos de sugerencia y observación en registrar elemento
const sugerenciaInput = document.querySelector('#sugerenciaInput');
const observacionInput = document.querySelector('#observacionInput');

function viewPlacaInputs(status = false) {
    if (!status) {
        // Placa nueva
        contentPlaca.style.display = 'flex';
        contentPlaca.style.flexDirection = 'column';
        inputPlaca.style.display = 'grid';
        inputSerie.style.display = 'grid';
        tablePlaca.style.display = "none";
        selectPlaca.style.display = 'none';
        placaAssocContent.style.display = "none"; 
        // Elimino el atributo de la placa y de la serie asociada para evitar enviar campos vacios adicionales.
        searchPlaca.removeAttribute('name');
        serialPlacaAssoc.removeAttribute('name');

    } else {
        // Asociar placa
        contentPlaca.style.display = 'none'; 
        inputPlaca.style.display = 'none';
        inputSerie.style.display = 'none';
        selectPlaca.style.display = 'grid';
        serialPlacaAssoc.readOnly = true;
        tablePlaca.style.display = "grid";
        placaAssocContent.style.display = "grid";
        
        
        // Agrego el name a los atributos para enviarlos en caso de que el usuario requiera Adicionar una nueva placa.
        searchPlaca.setAttribute('name', 'searchPlaca');
        serialPlacaAssoc.setAttribute('name', 'serialPlaca');

        elm_placa.removeAttribute('name');
        elm_serie.removeAttribute('name');
    }
}

// Input de cantidad del elemento
const inputCantidad = document.querySelector('#inputCantidad');
// Inicializar el select de la unidad de medida.
const undMedida = document.querySelector('#undMedida');
// Input placa
const elm_placa = document.querySelector('#elm_placa');
function viewTpElementoInputs(status =false){


    // Inicializar select de unidad de medida

    if (status) {
        checkboxTpElemento.style.display = "grid";
        undMedida.value = '1';
        inputCantidad.readOnly = true;
        inputCantidad.value = 1;
        // Reinicializo el elmento
        
    }else{
        checkboxTpElemento.style.display = "grid";
        undMedida.value = '0';
        inputCantidad.value = '';
        inputCantidad.readOnly = false;
    }
    M.FormSelect.init(undMedida);
}

function renderResultPlacas({ resultado = {}, status = false } = {}){

    if (!status || !Array.isArray(resultado) || resultado.length === 0) {
        tbodyPlacaResult.innerHTML = 'No hay coincidencias.';
        return;
    }
    
    // Accedo a las series de la placa.
    const seriales = (!resultado) ?{} : resultado[0].seriales;
    const placa = resultado[0].elm_placa;

    let serialesDisponibles = '';

    if (!Array.isArray(seriales) || seriales.length === 0) {
        serialesDisponibles = 'No hay seriales disponibles. Crear nuevo.';
    }else{
        // Filtra seriales válidos
        const serialesValidos = seriales.filter(srl => srl.serie && srl.serie.trim().length > 0);

        if (serialesValidos.length === 0) {
            serialesDisponibles = 'No hay seriales disponibles. Crear nuevo.';
        } else {
            serialesDisponibles = serialesValidos.map(srl => srl.serie).join(', ');
        }
    }

    // const placas = 
    tbodyPlacaResult.innerHTML = '';
    let tr = document.createElement('tr');
    let tdCodigo = document.createElement('td');
    let tdAcciones = document.createElement('td');
    let tdSerial = document.createElement('td');
    let checkbox = createCheckbox(seriales,placa);
    tdAcciones.appendChild(checkbox);

    tr.appendChild(tdCodigo);
    tr.appendChild(tdSerial);
    tr.appendChild(tdAcciones);
    tdSerial.innerHTML = serialesDisponibles;
    tdCodigo.innerHTML = placa;
    tbodyPlacaResult.appendChild(tr);

    const checkboxPlacas = document.querySelectorAll('input[name="serialCheckbox"]');
    console.log(checkboxPlacas);
    checkboxPlacas.forEach((checkPl)=>{
        checkPl.addEventListener('change', (e)=>{
            e.stopPropagation();
            if (e.target.checked) {
                let seriesCheckbox = JSON.parse(e.target.dataset.seriales);

                let placaCheckbox = JSON.parse(e.target.dataset.placa);

                if (seriesCheckbox.length === 0) {
                    serialPlacaAssoc.value = placaCheckbox + '-1';
                    return;
                }   

                    // Ordeno los objetos de menor a mayor, uso localCompare porque es un string, si fuese number, usaría Num
                    seriesCheckbox.sort((a, b) => a.serie.localeCompare(b.serie));

                    // Extraigo solo los valores que esten en la clave serie del objeto.
                    let valSeries = seriesCheckbox.map(ser => ser.serie);

                    // Ordeno el resultado
                    valSeries.sort();

                    // Extraigo el último valor
                    let ultimoValor = valSeries[valSeries.length - 1];
                    let serie = ultimoValor.slice(0,4);
                    // let codBasico = ultimoValor.indexOf(`${ultimoValor}"-"`);
                    let codBasico = ultimoValor.indexOf('-');
                    let consecutivo = parseInt(ultimoValor.slice(codBasico + 1));

                    consecutivo++;
                    let newCod = serie+"-"+consecutivo;

                    serialPlacaAssoc.value = newCod;

                
            }else{
                serialPlacaAssoc.value = '';
            }
        });
    });
}

// Capturo todos los inputs con el name placaRadio
nuevaPlaca.forEach((inputRadio)=>{
    inputRadio.addEventListener('change', (e)=>{
        if (e.target.id === 'nuevaPlaca') {
            // Inputs para nueva placa
            viewPlacaInputs(false);
        }else if (e.target.id === 'selectPlaca'){
            viewPlacaInputs(true);
        }
    });
});

// Capturo todos los inputs del tipo de elemento, siendo devolutivo o consumible
tpElemento.forEach((tpElement)=>{
    tpElement.addEventListener('change', (e)=>{
        console.log(e.target);

        if (e.target.id === 'devolutivoCheckbox') {
            viewTpElementoInputs(true);
        }
        if (e.target.id === 'consumibleCheckbox') {
            viewTpElementoInputs();
        }

    });
});

const titleModal = document.querySelector('#titleModal');

// Reiniciar formulario.
function resetForm(form) {
    const inputs = form.querySelectorAll('input, textarea, select');

    inputs.forEach((input) => {
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
            input.disabled = false;

        } else if (input.tagName === 'SELECT') {
            // Esto no funciona como se espera, investigar
            input.disabled = false;
            input.selectedIndex = 0;

        } else {
            input.value = '';
            input.readOnly = false;
        }
    });
    

}
// Mostrar modal = TODO: Crear una función de un modal dinámico.
function showModal({modal = '', type = 'registro', titulo = '', onAceptar = true, data = {}} = {}){
    // modal.el = accedemos al elemento direcemtne del doom porque en materialize creamos una instancia del modal, no del doom directamente.
    if (!modal || !modal.el) return;

    console.log(data);

    // El modal que le estamos pasando por parametro, le capturamos su formulario
    const form = modal.el.querySelector('form');
    // Capturo todos los inputs del formulario
    const inputs = form.querySelectorAll('input, textarea, select');
    if(!form) return;

    const footerBtn = document.querySelector('.footerBtn');

    // Llamo la función de reinicio antes de ejecutar el modal
    resetForm(form);
    // Reinicio por segunda ves el elemento.
    form.reset();

    switch (type) {
        case 'registrar':
            modal.open();
            
            if (onAceptar) footerBtn.style.display = "flex";
            titleModal.innerText = titulo;

        break;
        
            case 'info':
                modal.open();
                if (!onAceptar) footerBtn.style.display = "none";
                titleModal.innerText = titulo;
                
            inputs.forEach((input)=>{
                console.log(`${input}: ${input.type}`)

                // Deshabilito los input tipo checkbox
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.disabled = true;
                } else if (input.tagName === 'SELECT') {
                      input.disabled = true;
                } else {
                    input.readOnly = true;
                }
            });

        break;

        case 'actualizar':
            modal.open();
        break;

        default:
            console.log('opcion no valida');
            break;
    }
}

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
        let iconInfo = createI();
        iconInfo.innerText = 'info';
        btnInfo.appendChild(iconInfo);
        btnInfo.setAttribute('dataPlaca',dta.codigoElemento);
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
            tdPlaca.style.color = 'white';
            tr.style.color = '#d50000';
            tdPlaca.style.backgroundColor = '#e57373';
            initTooltip(tdPlaca,{...tooltipOptions,margin:-25},'Elemento por agotar existencia','buttom');     
        }

        if (dta.tipoElemento === 'Consumible') {
            tdAcciones.append(btnInfo,btnEdit,btnDelete,btnAdd);
        }
    
        if (dta.tipoElemento === 'Devolutivo') {
            tdAcciones.append(btnInfo,btnEdit,btnDelete);
            
        }


        tbodyElements.appendChild(tr);
        tr.append(tdPlaca,tdNombreElemento,tdCantidad,tdUnidadMedida,tdTipoElemento,tdEstadoElemento,tdAreaElemento,tdAcciones);


        // Boton de información.
        btnInfo.addEventListener('click', (e)=>{
            e.preventDefault();
            e.stopPropagation();
            
            showModal({modal: addElementModal, type: 'info',titulo:`Detalle elemento placa Nro ${dta.placa}`, onAceptar: false, data: dta});

        });


    });

        } catch (error) {
        throw new Error(`Error al consultar los elementos ${error}`);
                
    }

    
    
};

const renderSelectAreas = async (action = '')=>{
    let response = await getData('modules/elementos/controller/elementosController.php','GET',{action: action});
    let dataResponse = response.data;

    selectAreas.innerHTML = '';
    const option = document.createElement('option');
    option.value = 0;
    option.textContent = "Seleccione un departamento";
    option.setAttribute('selected', 'selected');
    option.setAttribute('disabled', 'disabled');
    selectAreas.appendChild(option);
    
    dataResponse.forEach((data)=>{
        const optionDataAreas = document.createElement('option');
        optionDataAreas.value = data.ar_cod;
        optionDataAreas.textContent = data.ar_nombre;
        selectAreas.appendChild(optionDataAreas);
    });
    //Reinicializo los select, accedo a ellos mediante el objeto window.  
    if (window.M) {
        M.FormSelect.init(selectAreas);
    }
}

const renderSelectCategorias = async (action = '')=>{
    let response = await getData('modules/elementos/controller/elementosController.php','GET',{action: 'categoria'});
    let categorias = response.data;
    selectCategorias.innerHTML = '';
    const option = document.createElement('option');
    option.value = 0;
    option.textContent = "Seleccione una categoria";
    option.setAttribute('selected', 'selected');
    option.setAttribute('disabled', 'disabled');
    selectCategorias.appendChild(option);
    categorias.forEach((dataCat) => {
        const optionDataCategorias = document.createElement('option');
        optionDataCategorias.value = dataCat.ca_id;
        optionDataCategorias.textContent = dataCat.ca_nombre;
        selectCategorias.appendChild(optionDataCategorias);
    });

    if (window.M) {
        M.FormSelect.init(selectCategorias);
    }
};

const renderSelectMarcas = async (action = '') =>{
    let response = await getData('modules/elementos/controller/elementosController.php','GET',{action: action});
    let marcaData = response.data;
    selectMarcas.innerHTML = '';
    selectMarcas.innerHTML = '';
    const option = document.createElement('option');
    option.value = 0;
    option.textContent = "Seleccione una marca";
    option.setAttribute('selected', 'selected');
    option.setAttribute('disabled', 'disabled');
    selectMarcas.appendChild(option);
    marcaData.forEach((marca)=>{
        const option = document.createElement('option');
        option.value = marca.ma_id;
        option.innerText = marca.ma_nombre;

        selectMarcas.appendChild(option);
    });

    // Reinicializo el select
        if (window.M) {
        M.FormSelect.init(selectMarcas);
    }


};

let placas = [];
const renderSelectPlacas = async (action = '') =>{
    let responsePlacas = await getData('modules/elementos/controller/elementosController.php', 'GET',{action: action});
    return responsePlacas.data.data;
};

// Mensaje de confirmación.
function mostrarConfirmacion(titulo, mensaje, callback) {
  // Rellenar el contenido
  document.getElementById('modalConfirmacionTitulo').textContent = titulo;
  document.getElementById('modalConfirmacionMensaje').textContent = mensaje;

  // Obtener instancia y abrir el modal
  const modalElem = document.getElementById('modalConfirmacion');
  const instance = M.Modal.getInstance(modalElem);
  instance.open();

  // Manejo de botones
  const btnAceptar = document.getElementById('btnAceptar');
  const btnCancelar = document.getElementById('btnCancelar');

  // Limpiar cualquier listener anterior
  btnAceptar.onclick = () => callback(true);
  btnCancelar.onclick = () => callback(false);
}

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
// let timer;
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


// span en donde se visualizara la respuesta de la placa si es correcta o no.
const respuestaPlaca = document.querySelector('#respuestaPlaca');
// Busqueda de placas.
searchPlaca.addEventListener('keyup', async (e) => {
    e.stopPropagation();
    const filtro = e.target.value.trim();

    if (filtro.length > 2) {
        if (!validatePlaca(filtro)) {
            respuestaPlaca.style.display = 'block';
            respuestaPlaca.innerText = validationRules.placa.message;
            serialPlacaAssoc.value = '';
            renderResultPlacas({ status: true }); 
            return;
        } else {
            respuestaPlaca.style.display = 'none';
        }

        const resultado = placas.filter(pl => String(pl.elm_placa) === filtro);

        if (resultado.length > 0) {
            renderResultPlacas({ resultado, status: true });
        } else {
            renderResultPlacas({ status: true });
            serialPlacaAssoc.value = '';
        }
    } else {
        // Limpia mensaje si no hay suficientes caracteres
        respuestaPlaca.style.display = 'none';
        renderResultPlacas({ status: true });
        serialPlacaAssoc.value = '';
    }
});

// Validad cantidad sea digitada por numeros 
inputCantidad.addEventListener('change', (e)=>{
    e.stopPropagation();
    let cantidad = e.target.value;

    if (!validarCantidad(cantidad)) {
        initAlert('Cantidad digitada no permitida','warning',toastOptions);
        e.target.value = '';
        return;
    }
});

// Validad numero de placa
elm_placa.addEventListener('change', (e)=>{
    e.stopPropagation();
    let placa = e.target.value;
    if (!validarCantidad(placa)) {
        initAlert('Número de placa digiado incorrecto','warning',toastOptions);
        e.target.value = '';
        return;
    }
});

// Enviar datos del formulario.
addElementForm.addEventListener('submit', (e)=>{
    e.preventDefault();
    e.stopPropagation();

    // Captura el valor del checkbox
    const checkboxTp = document.querySelector('input[name="tpElementoRadio"]:checked')?.value;
    const formElements = new FormData(e.target);
    const dataObj = Object.fromEntries(formElements.entries());
    delete dataObj.placaRadio;
    let data = dataObj;

    mostrarConfirmacion("Registrar elemento", "¿Estás seguro de eliminar este elemento?", function (respuesta){
        
    try {
        
        if (!respuesta) {
        addElementModal.close();
        addElementForm.reset();
        // Ejecutar acción
        } 
        //La respuesta puedo tranformarla en una función generica.
        sendData("modules/elementos/controller/elementosController.php","POST","registrar",data).then((result)=>{

            if (result) {
                // initAlert('Elemento agreado exitosamente','succes',{toastOptions});
                initAlert('Elemento agreado exitosamente','succes',{toastOptions});
                addElementModal.close();
                addElementForm.reset();
                renderSelectPlacas('placas')
                renderElements({type: currentType, page: 1});
            }

        });
    } catch (error) {
        
    }
    
});

    // Hacer una validación antes del envio, si el tipo de elemento seleccionado es consumible y en las opciones del select sea diferente de la primera y su cantidad sea mayor a 1.

});

//Abrir modal Registrar elemento
btnAddModalElements.addEventListener('click', (e)=>{
    e.stopPropagation();
    e.preventDefault();

    // addElementModal.open();
    showModal({modal:addElementModal,titulo:'registrar elemento',type:'registrar'})
    

});

// Formulario del modal addElement
const modalForm = document.querySelector('#addElementForm');
document.addEventListener('DOMContentLoaded',  ()=>{
    //Inicializo los select.
    // M.FormSelect.init(formDevolutivo.querySelectorAll('select'));
    initTooltip(btnAddModalElements,tooltipOptions,'Agregar elemento','top');
    //Renderizado de los elementos
    renderElements({type: currentType});

    const selectAreas = document.querySelector('#selectAreas');
    const selectCategorias = document.querySelector('#selectCategorias');

    // Inicializar select de las placas ya registradas
    const elemsSelect = document.querySelector('#placaAssoc');

    M.FormSelect.init(elemsSelect);
    M.FormSelect.init(selectCategorias);
    M.FormSelect.init(selectMarcas);
    M.FormSelect.init(selectTpElemento);
    M.FormSelect.init(undMedida);

    // Estas 3 funciones puedo transformarlas en 1.
    renderSelectAreas('areas');
    renderSelectCategorias('categoria');
    renderSelectMarcas('marcas');
    // Hago esto para evitar que mi función DOOM content loader sea asincrona.
    renderSelectPlacas('placas').then((dataResult)=>{
        placas = dataResult;
    });

    // puedo ejecutar el callback que me permita reiniciar los campos del formulario.
    closeModal(addElementModal,cerrarModalBtn, ()=>{
        // Si existe el modal, traiga el selector form que se encuentra de manera interna.
        if (addElementModal) {
            const modalForm = addElementModal.el.document.querySelector('form');
            resetForm(modalForm);
            titleModal.innerText = '';
        }
    });

    // Inicializo todos los modales.
    const modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);

});