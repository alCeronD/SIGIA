// TODO: Depurar, este bloque del proyecto puede ser transladado a un archivo barril.
import { addClassItem, closeModal, createBtn, createCheckbox, createI, initAlert, initTooltip, instanceModal, options, toastOptions, tooltipOptions } from "../utils/cases.js";
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
// Input de cantidad del elemento
const inputCantidad = document.querySelector('#inputCantidad');
// Inicializar el select de la unidad de medida.
const undMedida = document.querySelector('#undMedida');
// Input placa
const elm_placa = document.querySelector('#elm_placa');
const elm_serie = document.querySelector('#elm_serie');
// FUNCIÓN PARA RENDERIZAR Y VISUALIZAR LAS PLACAS EN EL REGISTRAR ELEMENTO.
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
        elm_placa.setAttribute('name', 'elm_placa');
        elm_serie.setAttribute('name','elm_serie');
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
        searchPlaca.setAttribute('name', 'elm_placa');
        serialPlacaAssoc.setAttribute('name', 'elm_serie');
        elm_placa.removeAttribute('name');
        elm_serie.removeAttribute('name');
    }
}


const contentPlacaEdit = document.querySelector('.contentPlacaEdit');
// FUNCIÓN PARA RENDERIZAR LA PLACA EN EL EDITAR ELEMENTO
function showPlacaAsociadaEditar() {
    contentPlacaEdit.style.display = 'none'; 
    inputPlaca.style.display = 'none';
    inputSerie.style.display = 'none';

    selectPlaca.style.display = 'grid';
    tablePlaca.style.display = 'grid';
    placaAssocContent.style.display = 'grid';

    serialPlacaAssoc.readOnly = true;

    // Asegúrate de tener los nombres correctos
    searchPlaca.setAttribute('name', 'searchPlaca');
    serialPlacaAssoc.setAttribute('name', 'serialPlaca');

    elm_placa.removeAttribute('name');
    elm_serie.removeAttribute('name');
}

function viewTpElementoInputs(status =false){

    // Inicializar select de unidad de medida
    if (status) {
        checkboxTpElemento.style.display = "grid";
        undMedida.value = '1';
        inputCantidad.readOnly = true;
        inputCantidad.value = 1;
        
    }else{
        checkboxTpElemento.style.display = "grid";
        undMedida.value = '1';
        inputCantidad.value = 1;
        inputCantidad.readOnly = true;
    }
    // Reinicializo el elmento
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
    console.log(createCheckbox());
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

// modal ver detalle
const modalVerMas = instanceModal('#modalVerMas', options);
// Modal edit.
const modalEditarElemento = instanceModal('#modalEditarElemento', options);
// modal agregarExistencia
// const modalAddExistencia = document.querySelector('#modalAddExistencia');

// modal de confirmación
const modalConfirmacion = document.querySelector('#modalConfirmacion');
const modalAddExistencia = instanceModal('#modalAddExistencia', options);

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
        tdAcciones.setAttribute('class', 'accionesElements');
        const btnInfo = createBtn('btn');
        const btnEdit = createBtn('btn');
        const btnDelete = createBtn('btn');
        const btnAdd = createBtn('btn');
        let iconInfo = createI();
        let iconUpdate = createI();
        let iconDelete = createI();
        iconUpdate.innerText = 'border_color'
        iconInfo.innerText = 'info';
        btnInfo.appendChild(iconInfo);
        btnInfo.setAttribute('dataPlaca',dta.codEstadoElemento);
        btnEdit.appendChild(iconUpdate);

        addClassItem(btnInfo,{"infoColor": "infoColor"});
        addClassItem(btnDelete, {deepOrangeDarken:"deep-orange darken-1"});
        addClassItem(btnEdit, {cyan: "cyan", blueGrey:"blue-grey"});
        btnAdd.innerText = '4';

        // Valido si el estado del elemento es inhabilitado le implemento otro icono.
        if (dta.codEstadoElemento === 4) {
            iconDelete.innerText = 'loop';
        }
        
        if (dta.codEstadoElemento === 1 || dta.codEstadoElemento === 3 || dta.codEstadoElemento === 5) {
            iconDelete.innerText = 'delete_sweep';
        }

        btnDelete.appendChild(iconDelete);
        btnDelete.setAttribute('data-Cod', dta.codigoElemento);
        btnDelete.setAttribute('data-Status', dta.codEstadoElemento);

        //TODO: esto se puede mejorar implementando la información de manera dinámica.
        /**
         * buscar como funciona document.createDocumentFragment
         */
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

        if (dta.tipoElemento === 'Consumible') tdAcciones.append(btnInfo,btnEdit,btnDelete,btnAdd);
        if (dta.tipoElemento === 'Devolutivo') tdAcciones.append(btnInfo,btnEdit,btnDelete);

        tbodyElements.appendChild(tr);
        tr.append(tdPlaca,tdNombreElemento,tdCantidad,tdUnidadMedida,tdTipoElemento,tdEstadoElemento,tdAreaElemento,tdAcciones);


        // Boton de información.
        btnInfo.addEventListener('click', (e)=>{
            e.preventDefault();
            e.stopPropagation();

            modalVerMas.open();

            const dataToTableMap = {
            codigoElemento: 'modalPlaca',
            serie: 'modalSerie',
            nombreElemento: 'modalNombreElemento',
            cantidad: 'modalCantidad',
            tipoElemento: 'modalTipo',
            estadoElemento: 'modalEstadoElemento',
            nombreArea: 'modalArea'
            };

            // Ciclo el mapa creado y valido la info del campo cantidad, si este existe y su valor es 0, el text content mostrar la palabra sin existencia.
            Object.entries(dataToTableMap).forEach(([dataKey, elementId]) => {
                const cell = document.getElementById(elementId);
                if (cell && dta[dataKey] !== undefined) {
                    if (dataKey === 'cantidad' && dta[dataKey] === 0) {
                        cell.textContent = 'Sin existencia';
                    } else {
                        cell.textContent = dta[dataKey];
                    }
                }
            });
        });

        // Botón de edición.
        btnEdit.addEventListener('click', async (e)=>{
            e.stopPropagation();
            e.preventDefault();

            modalEditarElemento.open();
            

            console.log(dta.codigoElemento);
        
            // TODO, puedo hacerlo de mejor forma creando un objeto y ciclando el formulario, no se hace x falta de tiempo.
            let elm_placa_editar = document.querySelector('#elm_placa_editar');
            let elm_nombre_editar = document.querySelector('#elm_nombre_editar');
            let tp_elemento = document.querySelector('#tp_elemento');
            let undMedida = document.querySelector('#undMedida');
            let elm_area_cod_editar = document.querySelector('#elm_area_cod_editar');
            let elm_marca_cod_editar = document.querySelector('#elm_marca_cod_editar');
            let sugerenciaInputEditar = document.querySelector('#sugerenciaInputEditar');
            let observacionInputEditar = document.querySelector('#observacionInputEditar');
            let elm_existencia_editar = document.querySelector('#elm_existencia_editar');
            let codElementoEditar = document.querySelector('#codElementoEditar');
            elm_placa_editar.value = dta.placa;
            elm_placa_editar.readOnly = true;
            elm_nombre_editar.value = dta.nombreElemento;
            tp_elemento.value = dta.codTipoElemento;
            elm_existencia_editar.value = dta.codTipoElemento === 1 ? 1 : dta.cantidad;
            undMedida.value = dta.codUnidadMedida;
            observacionInputEditar.value = dta.observacionElemento;
            sugerenciaInputEditar.value = dta.sugerenciaIngresada;
            codElementoEditar.value = dta.codigoElemento;
            
            await renderSelectAreas('areas', elm_area_cod_editar);
            await renderSelectMarcas('marcas', elm_marca_cod_editar);
            elm_area_cod_editar.value = dta.codArea;
            elm_marca_cod_editar.value = dta.codMarca;

            M.FormSelect.init(tp_elemento);
            M.FormSelect.init(undMedida);
            M.FormSelect.init(elm_area_cod_editar);
            M.FormSelect.init(elm_marca_cod_editar);
            

        });

        // boton de inhabilitar elemento
        btnDelete.addEventListener('click', (e)=>{
            e.preventDefault();
            e.stopPropagation();
            console.log(e.target);
            const btn = e.currentTarget;

            console.log(btn.dataset.status);
            // Validar que el estado del elemento sea prestado para evitar inhabilitar el elemento
            if (parseInt(btn.dataset.status) === 3) {
                initAlert("Este elemento no puede inhabilitarse hasta que elemento haya sido devuelto", "info", toastOptions);
                return;
            }

            if (parseInt(btn.dataset.status) === 5) {
                initAlert("Este elemento no puede inhabilitarse hasta que su reserva haya sido procesada", "info", toastOptions);
                return;
            }

            let message="";
            let title = "";
            if (dta.estadoElemento === 'Inhabilitado') {
                message = "¿Desea habilitar este elemento?";
                title = `Habilitar elemento - Placa #${dta.placa}`;
                
            }else{
                message = "¿Desea Inhabilitar este elemento?";
                title = `Inhabilitar elemento - Placa #${dta.placa}`;
            }

            mostrarConfirmacion(title,message, (response)=>{

                if (!response) {
                    initAlert('Proceso cancelado', 'info', toastOptions);
                    return;   
                }else{

                    try {
                
                        const dataCod = parseInt(btn.dataset.cod) || null;
                        const dataStatus = parseInt(btn.dataset.status) || null;

                        if (parseInt(dataStatus) === 3) {
                            initAlert('El cambio de estado del elemento debe ser validado desde las reservas', 'info',toastOptions);
                            return;
                        }

                        const data = {
                            elm_cod: dataCod,
                            elm_cod_estado: dataStatus
                        };


                    let responseStatus = sendData("modules/elementos/controller/elementosController.php", 'PUT','statusElement',data);

                    responseStatus.then((resultUpdate)=>{
                        let messageData = resultUpdate.data.message;
                        let status = resultUpdate.data.status;
                        // TODO: arreglar, este icono debe de cambiar cuando el elemento se inhabilite.
                        if (status) {
                            const icon = btn.querySelector('i');
                            if (icon) {
                                icon.innerText = 'compare_arrows';
                            }
                            initAlert(messageData,'success',toastOptions);
                        }
                        renderElements({page:pageElement});
                    });

                } catch (error) {
                    throw new Error("Error al ejecutar proceso"+error);
                }
                }

                
            });

        });

        //Boton adicionar existencia
        btnAdd.addEventListener('click', (e)=>{
            e.stopPropagation();
            e.preventDefault();

            console.log(e.target);
            modalAddExistencia.open();




        });

    });

     } catch (error) {
        throw new Error(`Error al consultar los elementos ${error}`);

    }
};


const categoriaSelect = document.querySelector('#categoriaSelect');
const renderSelectAreas = async (action = '', inputSelect)=>{
    let response = await getData('modules/elementos/controller/elementosController.php','GET',{action: action});
    let dataResponse = response.data;
    console.log('hello worldAreas');
    inputSelect.innerHTML = '';
    const option = document.createElement('option');
    option.value = 0;
    option.textContent = "Seleccione un departamento";
    option.setAttribute('selected', 'selected');
    option.setAttribute('disabled', 'disabled');
    inputSelect.appendChild(option);
    
    dataResponse.forEach((data)=>{
        const optionDataAreas = document.createElement('option');
        optionDataAreas.value = data.ar_cod;
        optionDataAreas.textContent = data.ar_nombre;
        inputSelect.appendChild(optionDataAreas);
    });
    //Reinicializo los select, accedo a ellos mediante el objeto window.  
    if (window.M) {
        M.FormSelect.init(inputSelect);
    }
}
const renderSelectCategorias = async (action = '',inputSelect)=>{
    let response = await getData('modules/elementos/controller/elementosController.php','GET',{action: 'categoria'});
    let categorias = response.data;
    inputSelect.innerHTML = '';
    const option = document.createElement('option');
    option.value = 0;
    option.textContent = "Seleccione una categoria";
    option.setAttribute('selected', 'selected');
    option.setAttribute('disabled', 'disabled');
    inputSelect.appendChild(option);
    categorias.forEach((dataCat) => {
        const optionDataCategorias = document.createElement('option');
        optionDataCategorias.value = dataCat.ca_id;
        optionDataCategorias.textContent = dataCat.ca_nombre;
        inputSelect.appendChild(optionDataCategorias);
    });

    if (window.M) {
        M.FormSelect.init(inputSelect);
    }
};

const renderSelectMarcas = async (action = '',selectMarcas) =>{
    let response = await getData('modules/elementos/controller/elementosController.php','GET',{action: action});
    let marcaData = response.data;
    selectMarcas.innerHTML = '';
    const option = document.createElement('option');
    option.value = "";
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

// Mapeo de elementos del formulario registrar.
const fieldLabels = {
    elm_placa: "Placa",
    elm_serie: "Serie",
    elm_nombre: "Nombre del elemento",
    elm_uni_medida: "Unidad de medida",
    elm_existencia: "Existencia",
    elm_observacion: "Observaciones",
    elm_sugerencia: "Sugerencias",
    elm_area_cod: "Area"
};

// Mapeo de elementos del formulario editar.
const fieldLabelsEditar = {
  elm_cod: "Código del elemento",
  elm_nombre: "Nombre del elemento",
  elm_area_cod: "Departamento",
  elm_ma_cod: "Marca",
  elm_cod_tp_elemento: "Tipo de elemento",
  elm_existencia: "Existencia",
  elm_observacion: "Observación",
  elm_sugerencia: "Sugerencia",
//   searchPlaca: "Búsqueda de placa",
//   serialPlaca: "Serial asociado",
//   elm_placa: "Número de placa",
//   elm_serie: "Código de serie"
};

function checkObject(object, campos){
    for (const key in object) {
        if (Object.prototype.hasOwnProperty.call(object, key)) {
            const element = object[key];
            if (key === "elm_serie" || key === "elm_observacion" || key === "elm_sugerencia") {
                    continue;
            }
            if (element === "") {
                console.log(key);
                initAlert(`el campo ${campos[key]} debe ser obligatorio`, 'info', toastOptions);
                return;
            }
        }
    }

    return true;


}

const checkboxTp = document.querySelectorAll('input[name="elm_cod_tp_elemento"]');
function validateValueChecked(inputRadio){
    return Array.from(inputRadio).some((radio)=> radio.checked);

}

// Enviar datos del formulario.
addElementForm.addEventListener('submit', (e)=>{
    e.preventDefault();
    e.stopPropagation();

    const formElements = new FormData(e.target);
    const dataObj = Object.fromEntries(formElements.entries());
    delete dataObj.placaRadio;
    let data = dataObj;

    if (!validateValueChecked(nuevaPlaca)) {
        initAlert("Debe seleccionar una opción para registrar la placa.", "warning",toastOptions);
        return;
    }

    // valido que los campos del formulario obligatorios esten validados.
    checkObject(dataObj,fieldLabels);

    if (!validateValueChecked(checkboxTp)) {
        initAlert("El tipo de elemento debe ser seleccionado", "warning",toastOptions);
        return;
    }

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
                renderSelectPlacas('placas');
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

    addElementModal.open();
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
    const selectMarcas = document.querySelector('#selectMarca');

    // Inicializar select de las placas ya registradas
    const elemsSelect = document.querySelector('#placaAssoc');

    // inicializo el tipo de movimiento para el modal de agregar compra
    const tipo_movimiento = document.querySelector('#tipo_movimiento');

    // Estas 3 funciones puedo transformarlas en 1.
    renderSelectAreas('areas', selectAreas);
    renderSelectCategorias('categoria',selectCategorias);
    renderSelectMarcas('marcas',selectMarcas);
    // Hago esto para evitar que mi función DOOM content loader sea asincrona.
    renderSelectPlacas('placas').then((dataResult)=>{
        placas = dataResult;
    });
    M.FormSelect.init(elemsSelect);
    M.FormSelect.init(selectCategorias);
    M.FormSelect.init(selectMarcas);
    M.FormSelect.init(selectTpElemento);
    M.FormSelect.init(undMedida);
    M.FormSelect.init(tipo_movimiento);

    // Inicializo todos los modales.
    const modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);

});

// Formulario del modal editarElemento
const editarElementForm = document.querySelector('#editarElementForm');
editarElementForm.addEventListener('submit', (e)=>{
    e.preventDefault();
    e.stopPropagation();

    //TODO Validar campos obligatorios.
    const formUpdate = new FormData(e.target);
    const dataObj = Object.fromEntries(formUpdate.entries());
    let data = dataObj;
    if(!checkObject(dataObj,fieldLabelsEditar)) return;
    // Estos 3 elementos los estoy por ahora, borrando pero les daremos utilidad.
    delete dataObj['elm_serie'];
    delete dataObj['serialPlaca'];
    delete dataObj['elm_uni_medida_select'];

    mostrarConfirmacion('Guardar cambios',"¿Está seguro de continuar con el proceso?", (respuesta)=>{
        if (respuesta) {
            try {
                let response = sendData("modules/elementos/controller/elementosController.php",'PUT','updateElement',data);

                response.then((result)=>{
                    if (!result) {
                        initAlert('error al actualizar el recuros','warning', toastOptions);
                    }
                    initAlert('recurso actualizado con exito', 'success',toastOptions);
                    modalEditarElemento.close();
                    // renderizo los elementos en base a la página en la que se encuentra.
                    renderElements({page:pageElement});
                    
                });
            } catch (error) {
                throw new Error("Error al actualizar el recurso.");
            }
        }else{
            initAlert('Proceso cancelado','info', toastOptions);
            modalEditarElemento.close();
        }

    });

});



// Formulario agregar existencia.
const formAddExistencia = document.querySelector('#formAddExistencia');
formAddExistencia.addEventListener('submit', (e)=>{
    e.stopPropagation();
    e.preventDefault();
    const form = new FormData(e.target);
    const data = Object.fromEntries(form);
    console.log(data);

    checkObject(data);

});

// puedo ejecutar el callback que me permita reiniciar los campos del formulario.
closeModal(addElementModal,cerrarModalBtn, ()=>{
  // Si existe el modal, traiga el selector form que se encuentra de manera interna.
  if (addElementModal) {
    const modalForm = addElementModal.el.querySelector("form");
    console.log(modalForm);
    resetForm(modalForm);
    modalForm.reset();
  }
});

const modalCerrarVerMas = document.querySelector('#modalCerrarVerMas');
closeModal(modalVerMas,modalCerrarVerMas);