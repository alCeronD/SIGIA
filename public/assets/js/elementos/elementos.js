// TODO: Depurar, este bloque del proyecto puede ser transladado a un archivo barril.
import {
  addClassItem,
  closeModal,
  createBtn,
  createCheckbox,
  createI,
  createSpan,
  initAlert,
  initTooltip,
  instanceModal,
  options,
  replaceln,
  toastOptions,
  tooltipOptions,
  mostrarConfirmacion,
} from "../utils/cases.js";
import {
  soloNumeros,
  validarCantidad,
  validarSerie,
  validatePlaca,
  validationRules,
} from "../utils/regex.js";
import { getData, sendData } from "../utils/fetch.js";

const typeElements = {
  dev: "devolutivo",
  consu: "consumible",
  all: "all",
};
let pageElement = 1;
let pageGlobal;
const previewElements = document.querySelector("#previewElements");
const nextElements = document.querySelector("#nextElements");
const inputBusqueda = document.querySelector("#inputBusqueda");
const tblElements = document.querySelector("#tblElements");
const tbodyElements = document.querySelector("#tbodyElements");
const filtroTipo = document.querySelector("#filtroTipo");
const tipoElementoSelect = document.querySelector("#tipoElementoSelect");
const addElementModal = instanceModal("#addElementModal", options);
const btnAddModalElements = document.querySelector("#btnAddModalElements");
const cerrarModalBtn = document.querySelector("#cerrarModalRegistrar");
let iBtnAddElements = createI();
iBtnAddElements.innerText = "add";
btnAddModalElements.append(iBtnAddElements);
//El tipo de elementos, creamos esta variable para reemplazarla ya que le daremos utilidad en los filtros.
let currentType = typeElements.all;

// Contenedor de la placa e inputs
const placaInputs = document.querySelector(".placaInputs");
const inputPlaca = document.querySelector(".inputPlaca");
const selectPlaca = document.querySelector(".selectPlaca");
const inputSerie = document.querySelector(".inputSerie");
const contentPlaca = document.querySelector(".contentPlaca");
// Radio button
const nuevaPlaca = document.querySelectorAll('input[name="placaRadio"]');
const selectedPlaca = document.querySelector("#selectPlaca");
// Input de unidad de medida.
// const undMedida = document.querySelector('#undMedida');
const tpElemento = document.querySelectorAll(
  'input[name="elm_cod_tp_elemento"]'
);
const checkboxTpElemento = document.querySelector(".checkboxTpElemento");
const selectAreas = document.querySelector("#selectAreas");
const selectCategorias = document.querySelector("#categoriaSelect");
const selectMarcas = document.querySelector("#selectMarca");
const selectTpElemento = document.querySelector("#selectTpElemento");
// Input para buscar la placa.
const searchPlaca = document.querySelector("#searchPlaca");
// Tabla de las placas
const tablePlaca = document.querySelector(".tableResult");
// Aca voy a mostrar el resultado.
const tbodyPlacaResult = document.querySelector("#tbodyPlacaResult");
// Formulario de envio de elemento.
const addElementForm = document.querySelector("#addElementForm");
const placaAssocContent = document.querySelector(".placaAssocContent");
// Input del serial que se va a asociar con la placa
const serialPlacaAssoc = document.querySelector("#serialPlacaAssoc");
// variable para guardar la cantidad disponible del elemento.
let cantidadExistencia = null;
// Campos de sugerencia y observación en registrar elemento
const sugerenciaInput = document.querySelector("#sugerenciaInput");
const observacionInput = document.querySelector("#observacionInput");
// Input de cantidad del elemento
const inputCantidad = document.querySelector("#inputCantidad");
// Inicializar el select de la unidad de medida.
const undMedida = document.querySelector("#undMedida");
// Input placa
const elm_placa = document.querySelector("#elm_placa");
const elm_serie = document.querySelector("#elm_serie");

// FUNCIÓN PARA RENDERIZAR Y VISUALIZAR LAS PLACAS EN EL REGISTRAR ELEMENTO.
function viewPlacaInputs(status = false) {
  if (!status) {
    // Placa nueva
    contentPlaca.style.display = "flex";
    contentPlaca.style.flexDirection = "column";
    inputPlaca.style.display = "grid";
    inputSerie.style.display = "grid";
    tablePlaca.style.display = "none";
    selectPlaca.style.display = "none";
    placaAssocContent.style.display = "none";
    // Elimino el atributo de la placa y de la serie asociada para evitar enviar campos vacios adicionales.
    searchPlaca.removeAttribute("name");
    serialPlacaAssoc.removeAttribute("name");
    elm_placa.setAttribute("name", "elm_placa");
    elm_serie.setAttribute("name", "elm_serie");
    elm_placa.value = "";
    elm_serie.value = "";
  } else {
    // Asociar placa
    contentPlaca.style.display = "none";
    inputPlaca.style.display = "none";
    inputSerie.style.display = "none";
    selectPlaca.style.display = "grid";
    serialPlacaAssoc.readOnly = true;
    tablePlaca.style.display = "grid";
    placaAssocContent.style.display = "grid";
    // Agrego el name a los atributos para enviarlos en caso de que el usuario requiera Adicionar una nueva placa.
    searchPlaca.setAttribute("name", "elm_placa");
    serialPlacaAssoc.setAttribute("name", "elm_serie");
    elm_placa.removeAttribute("name");
    elm_serie.removeAttribute("name");
    elm_placa.value = "";
    elm_serie.value = "";
    searchPlaca.value = "";
    serialPlacaAssoc.value = "";
    tbodyPlacaResult.innerHTML = "";
  }
}

const contentPlacaEdit = document.querySelector(".contentPlacaEdit");
function viewTpElementoInputs(status = false) {
  // Inicializar select de unidad de medida
  if (status) {
    checkboxTpElemento.style.display = "grid";
    undMedida.value = "1";
    inputCantidad.readOnly = true;
    inputCantidad.value = 1;
  } else {
    checkboxTpElemento.style.display = "grid";
    undMedida.value = "1";
    inputCantidad.value = 1;
    inputCantidad.readOnly = true;
  }
  // Reinicializo el elmento
  M.FormSelect.init(undMedida);
}

function renderResultPlacas({ resultado = {}, status = false } = {}) {
  if (!status || !Array.isArray(resultado) || resultado.length === 0) {
    tbodyPlacaResult.innerHTML = "No hay coincidencias.";
    return;
  }

  // Accedo a las series de la placa.
  const seriales = !resultado ? {} : resultado[0].seriales;
  const placa = resultado[0].elm_placa;

  let serialesDisponibles = "";

  if (!Array.isArray(seriales) || seriales.length === 0) {
    serialesDisponibles = "No hay seriales disponibles. Crear nuevo.";
  } else {
    // Filtra seriales válidos
    const serialesValidos = seriales.filter(
      (srl) => srl.serie && srl.serie.trim().length > 0
    );

    if (serialesValidos.length === 0) {
      serialesDisponibles = "No hay seriales disponibles. Crear nuevo.";
    } else {
      serialesDisponibles = serialesValidos.map((srl) => srl.serie).join(", ");
    }
  }

  tbodyPlacaResult.innerHTML = "";
  let tr = document.createElement("tr");
  let tdCodigo = document.createElement("td");
  let tdAcciones = document.createElement("td");
  let tdSerial = document.createElement("td");
  let checkbox = createCheckbox(seriales, placa);

  tdAcciones.appendChild(checkbox);

  tr.appendChild(tdCodigo);
  tr.appendChild(tdSerial);
  tr.appendChild(tdAcciones);
  tdSerial.innerHTML = serialesDisponibles;
  tdCodigo.innerHTML = placa;
  tbodyPlacaResult.appendChild(tr);

  const checkboxPlacas = document.querySelectorAll(
    'input[name="serialCheckbox"]'
  );
  checkboxPlacas.forEach((checkPl) => {
    checkPl.addEventListener("change", (e) => {
      e.stopPropagation();
      if (e.target.checked) {
        let seriesCheckbox = JSON.parse(e.target.dataset.seriales);

        let placaCheckbox = JSON.parse(e.target.dataset.placa);

        if (seriesCheckbox.length === 0) {
          serialPlacaAssoc.value = placaCheckbox + "-1";
          return;
        }

        // Ordeno los objetos de menor a mayor, uso localCompare porque es un string, si fuese number, usaría Num
        seriesCheckbox.sort((a, b) => a.serie.localeCompare(b.serie));

        // Extraigo solo los valores que esten en la clave serie del objeto.
        let valSeries = seriesCheckbox.map((ser) => ser.serie);

        // Ordeno el resultado
        valSeries.sort();

        // Extraigo el último valor
        let ultimoValor = valSeries[valSeries.length - 1];
        let serie = ultimoValor.slice(0, 4);
        // let codBasico = ultimoValor.indexOf(`${ultimoValor}"-"`);
        let codBasico = ultimoValor.indexOf("-");
        let consecutivo = parseInt(ultimoValor.slice(codBasico + 1));

        consecutivo++;
        let newCod = serie + "-" + consecutivo;

        serialPlacaAssoc.value = newCod;
      } else {
        serialPlacaAssoc.value = "";
      }
    });
  });
}

// Capturo todos los inputs con el name placaRadio
nuevaPlaca.forEach((inputRadio) => {
  inputRadio.addEventListener("change", (e) => {
    if (e.target.id === "nuevaPlaca") {
      // Inputs para nueva placa
      viewPlacaInputs(false);
    } else if (e.target.id === "selectPlaca") {
      viewPlacaInputs(true);
    }
  });
});

// Capturo todos los inputs del tipo de elemento, siendo devolutivo o consumible
tpElemento.forEach((tpElement) => {
  tpElement.addEventListener("change", (e) => {
    if (e.target.id === "devolutivoCheckbox") {
      viewTpElementoInputs(true);
    }
    if (e.target.id === "consumibleCheckbox") {
      viewTpElementoInputs();
    }
  });
});

const titleModal = document.querySelector("#titleModal");

// Reiniciar formulario.
function resetForm(form) {
  const inputs = form.querySelectorAll("input, textarea, select");

  inputs.forEach((input) => {
    if (input.type === "checkbox" || input.type === "radio") {
      input.checked = false;
      input.disabled = false;
    } else if (input.tagName === "SELECT") {
      // oculto los elementos
      const option = input.querySelector("option");
      option.selected = "selected";
      option.disabled = "disabled";
      option.selectedIndex = 0;
    } else {
      input.value = "";
      input.readOnly = false;
    }
  });

  const tpElementoDiv = form.querySelector(".checkboxTpElemento");
  if (tpElementoDiv) {
    tpElementoDiv.style.display = "none";
  }
}
// modal ver detalle
const modalVerMas = instanceModal("#modalVerMas", options);
// Modal edit.
const modalEditarElemento = instanceModal("#modalEditarElemento", options);
// modal agregarExistencia

// modal de confirmación
const modalConfirmacion = document.querySelector("#modalConfirmacion");
const modalAddExistencia = instanceModal("#modalAddExistencia", options);
const titleModalExistencia = document.querySelector("#titleModalExistencia");
const categoriaSelect = document.querySelector("#categoriaSelect");
const totalPages = document.querySelector("#totalPages");
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
const renderElements = async ({
  type = "all",
  action = "elements",
  page = 1,
  isBusqueda = false,
  value = "",
} = {}) => {
  try {
    let parameters = {};
    if (!isBusqueda && value === "") {
      parameters = { action, pages: page, type };
    } else {
      parameters = { action, pages: page, type, isBusqueda, value };
    }
    const dataElements = await getData(
      "modules/elementos/controller/elementosController.php",
      "GET",
      parameters
    );
    let data = dataElements.data.data;
    pageGlobal = dataElements.data.cantidadPaginas;
    tbodyElements.innerHTML = "";
    if (pageGlobal === 0) {
      totalPages.innerText = "";
      tbodyElements.innerHTML = "Sin resultados";
      return;
    }

    if (page > pageGlobal) {
      return;
    }

    const fragmentElements = document.createDocumentFragment();
    data.forEach((dta) => {
      let tr = document.createElement("tr");
      let tdPlaca = document.createElement("td");
      let tdCantidad = document.createElement("td");
      let tdCodigoElemento = document.createElement("td");
      let tdEstadoElemento = document.createElement("td");
      let tdNombreElemento = document.createElement("td");
      let tdAreaElemento = document.createElement("td");
      let tdUnidadMedida = document.createElement("td");
      let tdTipoElemento = document.createElement("td");
      let tdAcciones = document.createElement("td");
      tdAcciones.setAttribute("class", "accionesElements");
      const btnInfo = createBtn("btn");
      const btnEdit = createBtn("btn");
      const btnDelete = createBtn("btn");
      const btnAdd = createBtn("btn");
      let iconInfo = createI();
      let iconUpdate = createI();
      let iconDelete = createI();
      let iconAdd = createI();
      iconUpdate.innerText = "border_color";
      iconInfo.innerText = "info";
      iconAdd.innerText = "exposure";
      btnInfo.appendChild(iconInfo);
      btnInfo.setAttribute("dataPlaca", dta.codEstadoElemento);
      btnEdit.appendChild(iconUpdate);
      btnAdd.appendChild(iconAdd);

      addClassItem(btnInfo, { infoColor: "infoColor" });
      addClassItem(btnDelete, { btnInactive: "btnInactive" });
      addClassItem(btnEdit, { cyan: "cyan", blueGrey: "blue-grey" });
      addClassItem(btnAdd, { btnAddExistencia: "btnAddExistencia" });
      // Valido si el estado del elemento es inhabilitado le implemento otro icono.
      if (dta.codEstadoElemento === 4) {
        iconDelete.innerText = "loop";
      }

      if (
        dta.codEstadoElemento === 1 ||
        dta.codEstadoElemento === 3 ||
        dta.codEstadoElemento === 5
      ) {
        iconDelete.innerText = "delete_sweep";
      }

      btnDelete.appendChild(iconDelete);
      btnDelete.setAttribute("data-Cod", dta.codigoElemento);
      btnDelete.setAttribute("data-Status", dta.codEstadoElemento);

      tdPlaca.innerText = dta.placa;
      tdCantidad.innerText = dta.cantidad;
      tdCodigoElemento.innerText = dta.codigoElemento;
      tdEstadoElemento.innerText = dta.estadoElemento;
      tdNombreElemento.innerText = dta.nombreElemento;
      tdAreaElemento.innerText = dta.nombreArea;
      tdUnidadMedida.innerText = dta.nombreUnidad;
      tdTipoElemento.innerText = dta.tipoElemento;

      const type = dta.nombreTipoElemento.toLowerCase();
      if (type === typeElements.consu) {
        tdTipoElemento.setAttribute("data-type", typeElements.consu);
      } else if (type === typeElements.dev) {
        tdTipoElemento.setAttribute("data-type", typeElements.dev);
      }

      tdEstadoElemento.innerText = dta.estadoElemento;
      tdAreaElemento.innerText = dta.nombreArea;
      if (dta.cantidad <= 10 && dta.tipoElemento === "Consumible") {
        tdPlaca.style.color = "white";
        tr.style.color = "#d50000";
        tdPlaca.style.backgroundColor = "#e57373";
        initTooltip(
          tdPlaca,
          { ...tooltipOptions, margin: -25 },
          "Elemento por agotar existencia",
          "buttom"
        );
      }

      if (dta.tipoElemento === "Consumible")
        tdAcciones.append(btnInfo, btnEdit, btnDelete, btnAdd);
      if (dta.tipoElemento === "Devolutivo")
        tdAcciones.append(btnInfo, btnEdit, btnDelete);

      tr.append(
        tdPlaca,
        tdNombreElemento,
        tdCantidad,
        tdUnidadMedida,
        tdTipoElemento,
        tdEstadoElemento,
        tdAreaElemento,
        tdAcciones
      );

      fragmentElements.appendChild(tr);

      // Boton de información.
      btnInfo.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        modalVerMas.open();

        const dataToTableMap = {
          codigoElemento: "modalPlaca",
          serie: "modalSerie",
          nombreElemento: "modalNombreElemento",
          cantidad: "modalCantidad",
          tipoElemento: "modalTipo",
          estadoElemento: "modalEstadoElemento",
          nombreArea: "modalArea",
          observacionElemento: "modalObservacion",
          sugerenciaIngresada: "modalSugerencia",
        };

        //Creo un objeto mapeado con cada key en donde la key es el alias del resultado de la consulta y su value es el id del elemento html, valido que existe y a cada uno le implemento su información.
        Object.entries(dataToTableMap).forEach(([dataKey, elementId]) => {
          const cell = document.getElementById(elementId);
          if (cell && dta[dataKey] !== undefined) {
            if (dataKey === "cantidad" && dta[dataKey] === 0) {
              cell.textContent = "Sin existencia";
            } else {
              cell.textContent = dta[dataKey];
            }
          }
        });
      });

      // Botón de edición.
      btnEdit.addEventListener("click", async (e) => {
        e.stopPropagation();
        e.preventDefault();

        modalEditarElemento.open();

        // TODO, puedo hacerlo de mejor forma creando un objeto y ciclando el formulario, no se hace x falta de tiempo.
        let elm_placa_editar = document.querySelector("#elm_placa_editar");
        let elm_serie_edatar = document.querySelector("#elm_serie_editar");
        let elm_nombre_editar = document.querySelector("#elm_nombre_editar");
        let tp_elemento = document.querySelector("#tp_elemento");
        let undMedida = document.querySelector("#undMedida");
        let elm_area_cod_editar = document.querySelector(
          "#elm_area_cod_editar"
        );
        let elm_marca_cod_editar = document.querySelector(
          "#elm_marca_cod_editar"
        );
        let sugerenciaInputEditar = document.querySelector(
          "#sugerenciaInputEditar"
        );
        let observacionInputEditar = document.querySelector(
          "#observacionInputEditar"
        );
        let elm_existencia_editar = document.querySelector(
          "#elm_existencia_editar"
        );
        let codElementoEditar = document.querySelector("#codElementoEditar");
        elm_serie_edatar.value = dta.serie;
        elm_placa_editar.value = dta.placa;
        elm_placa_editar.readOnly = true;
        elm_nombre_editar.value = dta.nombreElemento;
        tp_elemento.value = dta.codTipoElemento;
        elm_existencia_editar.value =
          dta.codTipoElemento === 1 ? 1 : dta.cantidad;
        undMedida.value = dta.codUnidadMedida;
        observacionInputEditar.value = dta.observacionElemento;
        sugerenciaInputEditar.value = dta.sugerenciaIngresada;
        codElementoEditar.value = dta.codigoElemento;

        await renderSelectAreas("areas", elm_area_cod_editar);
        await renderSelectMarcas("marcas", elm_marca_cod_editar);
        elm_area_cod_editar.value = dta.codArea;
        elm_marca_cod_editar.value = dta.codMarca;

        M.FormSelect.init(tp_elemento);
        M.FormSelect.init(undMedida);
        M.FormSelect.init(elm_area_cod_editar);
        M.FormSelect.init(elm_marca_cod_editar);
      });

      // boton de inhabilitar elemento
      btnDelete.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        const btn = e.currentTarget;

        // Validar que el estado del elemento sea prestado para evitar inhabilitar el elemento
        if (parseInt(btn.dataset.status) === 3) {
          initAlert(
            "Este elemento no puede inhabilitarse hasta que el elemento haya sido devuelto",
            "info",
            toastOptions
          );
          return;
        }

        if (parseInt(btn.dataset.status) === 5) {
          initAlert(
            "Este elemento no puede inhabilitarse hasta que su reserva haya sido procesada",
            "info",
            toastOptions
          );
          return;
        }

        let message = "";
        let title = "";
        if (dta.estadoElemento === "Inhabilitado") {
          message = "¿Desea habilitar este elemento?";
          title = `Habilitar elemento - Placa #${dta.placa}`;
        } else {
          message = "¿Desea Inhabilitar este elemento?";
          title = `Inhabilitar elemento - Placa #${dta.placa}`;
        }

        mostrarConfirmacion(title, message, async (response) => {
          if (!response) {
            initAlert("Proceso cancelado", "info", toastOptions);
            return;
          }

          try {
            const dataCod = parseInt(btn.dataset.cod) || null;
            const dataStatus = parseInt(btn.dataset.status) || null;

            if (parseInt(dataStatus) === 3) {
              initAlert(
                "El cambio de estado del elemento debe ser validado desde las reservas",
                "info",
                toastOptions
              );
              return;
            }

            const data = {
              elm_cod: dataCod,
              elm_cod_estado: dataStatus,
            };

            let responseInhabilitar = await sendData(
              "modules/elementos/controller/elementosController.php",
              "PUT",
              "statusElement",
              data
            );

            if (!responseInhabilitar.status) {
              initAlert(
                "Ha ocurrido un error al actualizar el estado del elemento",
                "error",
                toastOptions
              );
              return;
            }

            let messageData = responseInhabilitar.data.message;
            // if (status) {
            const icon = btn.querySelector("i");
            if (icon) {
              icon.innerText = "compare_arrows";
            }
            initAlert(messageData, "success", toastOptions);

            renderElements({ page: pageElement, type: currentType }).then(
              () => {
                renderWithFilter();
              }
            );
            // }
          } catch (error) {
            initAlert(`${error.message}`, "error", toastOptions);
            throw new Error("Error al ejecutar proceso" + error);
          }
        });
      });

      //Boton adicionar existencia
      btnAdd.addEventListener("click", (e) => {
        e.stopPropagation();
        e.preventDefault();
        titleModalExistencia.innerText = "";
        modalAddExistencia.open();
        titleModalExistencia.innerText = `Registrar movimiento \n Placa #${dta.placa} \n Elemento: ${dta.nombreElemento}`;
        const codAddExistencia = document.querySelector("#codAddExistencia");
        codAddExistencia.value = dta.codigoElemento;
        const row = e.target.closest("tr");
        let cell = row.children;
        cantidadExistencia = cell[2].textContent.trim();
      });
    });

    tbodyElements.appendChild(fragmentElements);
    totalPages.innerText = `Página ${pageElement} de ${pageGlobal}`;
  } catch (error) {
    throw new Error(`Error al consultar los elementos ${error}`);
  }
};

const renderSelectAreas = async (action = "", inputSelect) => {
  let response = await getData(
    "modules/elementos/controller/elementosController.php",
    "GET",
    { action: action }
  );
  let dataResponse = response.data;
  inputSelect.innerHTML = "";
  const option = document.createElement("option");
  option.value = "";
  option.textContent = "Seleccione un departamento";
  option.setAttribute("selected", "selected");
  option.setAttribute("disabled", "disabled");
  inputSelect.appendChild(option);

  dataResponse.forEach((data) => {
    const optionDataAreas = document.createElement("option");
    optionDataAreas.value = data.ar_cod;
    optionDataAreas.textContent = data.ar_nombre;
    inputSelect.appendChild(optionDataAreas);
  });
  //Reinicializo los select, accedo a ellos mediante el objeto window.
  if (window.M) {
    M.FormSelect.init(inputSelect);
  }
};
const renderSelectCategorias = async (action = "", inputSelect) => {
  let response = await getData(
    "modules/elementos/controller/elementosController.php",
    "GET",
    { action: "categoria" }
  );
  let categorias = response.data;
  inputSelect.innerHTML = "";
  const option = document.createElement("option");
  option.value = "";
  option.textContent = "Seleccione una categoria";
  option.setAttribute("selected", "selected");
  option.setAttribute("disabled", "disabled");
  inputSelect.appendChild(option);
  categorias.forEach((dataCat) => {
    const optionDataCategorias = document.createElement("option");
    optionDataCategorias.value = dataCat.ca_id;
    optionDataCategorias.textContent = dataCat.ca_nombre;
    inputSelect.appendChild(optionDataCategorias);
  });

  if (window.M) {
    M.FormSelect.init(inputSelect);
  }
};

const renderSelectMarcas = async (action = "", selectMarcas) => {
  let response = await getData(
    "modules/elementos/controller/elementosController.php",
    "GET",
    { action: action }
  );
  let marcaData = response.data;
  selectMarcas.innerHTML = "";
  const option = document.createElement("option");
  option.value = "";
  option.textContent = "Seleccione una marca";
  option.setAttribute("selected", "selected");
  option.setAttribute("disabled", "disabled");
  selectMarcas.appendChild(option);
  marcaData.forEach((marca) => {
    const option = document.createElement("option");
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
const renderSelectPlacas = async (action = "") => {
  let responsePlacas = await getData(
    "modules/elementos/controller/elementosController.php",
    "GET",
    { action: action }
  );
  return responsePlacas.data.data;
};
/**
 * Realiza el proceso de páginado según los criterios (todos, consumibles o devolutivos)
 * y si hay una búsqueda activa.
 *
 * Esta función determina qué tipo de renderizado aplicar:
 *  1. Si el tipo es "all" (todos), renderiza todos los elementos, con o sin búsqueda.
 *  2. Si el tipo es "consumible" o "devolutivo" y hay búsqueda, renderiza con filtro y activa `renderWithFilter`.
 *  3. Si el tipo es "consumible" o "devolutivo" sin búsqueda, renderiza normalmente y aplica `renderWithFilter`.
 *
 * Utiliza un spread condicional para incluir los parámetros de búsqueda solo cuando es necesario.
 *
 * @async
 * @function executePagination
 * @returns {Promise<void>} No retorna valor; ejecuta acciones de renderizado asíncronas.
 */
const executePagination = async () => {
  // Creo una condicional interna dentro de la variable, si el campo de busqueda tiene caracteres, este me guarda true, en caso que no sea así, es false.
  const isBusqueda = inputBusqueda.value.trim().length > 0;

  // Proceso de páginado según su tipo de renderizado.
  // 1. Páginar todos los elementos con el renderizado all (todos). o páginar los elementos con el renderizado all y búsqueda.
  if (
    (currentType === typeElements.all && inputBusqueda.value.length != "") ||
    currentType === typeElements.all
  ) {
    await renderElements({
      type: currentType,
      page: pageElement,
      ...(isBusqueda && { isBusqueda: true, value: inputBusqueda.value }),
    });
    return;
  }

  // 2 Páginar todos los elementos con campo de búsqueda y filtro.
  if (
    (currentType === typeElements.consu || currentType === typeElements.dev) &&
    inputBusqueda.value.length != ""
  ) {
    await renderElements({
      type: currentType,
      page: pageElement,
      isBusqueda: true,
      value: inputBusqueda.value,
    }).then(() => {
      renderWithFilter();
    });
    return;
  }

  //3 páginado de elementos con filtros, sin búsqueda.
  if (currentType === typeElements.consu || currentType === typeElements.dev) {
    await renderElements({ type: currentType, page: pageElement }).then(() => {
      renderWithFilter();
    });
    return;
  }
};

previewElements.addEventListener("click", async (e) => {
  e.stopPropagation();
  e.preventDefault();
  if (pageElement <= 1) return;
  pageElement--;

  executePagination();
});

nextElements.addEventListener("click", async (e) => {
  e.stopPropagation();
  e.preventDefault();
  let params = {};
  if (pageElement >= pageGlobal) return;
  pageElement++;

  executePagination();
});

/**
 * Filtro de elementos
 */
filtroTipo.addEventListener("change", (e) => {
  e.stopPropagation();
  e.preventDefault();

  // Reemplazo la página actual para que me visualize los elementos filtrados desde la página 1.
  pageElement = 1;
  tbodyElements.innerHTML = "";

  // Acá cambiamos el tipo de elemento que ha sido seleccionado para ser páginado.
  const selectedOption =
    e.target.options[e.target.selectedIndex].value.toLowerCase();
  if ([typeElements.dev, typeElements.consu].includes(selectedOption)) {
    currentType = selectedOption;
  } else {
    currentType = typeElements.all;
  }

  let valueInput = document.querySelector("#inputBusqueda");

  if (valueInput.value === "") {
    renderElements({ type: currentType, page: pageElement }).then(() => {
      renderWithFilter();
    });
  } else {
    renderElements({
      action: "elements",
      value: valueInput.value,
      type: currentType,
      page: pageElement,
      isBusqueda: true,
    }).then(() => {
      renderWithFilter();
    });
  }
});

function renderWithFilter() {
  const ths = tblElements.querySelectorAll("thead tr th");

  const filas = tbodyElements.querySelectorAll(`tbody tr`);

  // El numero 4 corresponde a la columna del tipo de elemento.
  if (currentType === typeElements.consu || currentType === typeElements.dev) {
    ths[4].style.display = "none";
  } else {
    ths[4].style.display = "table-cell";
  }

  filas.forEach((fila) => {
    const tdTipo = fila.querySelector("[data-type]");
    if (!tdTipo) return;

    const tipo = tdTipo.getAttribute("data-type");
    if (currentType === tipo) {
      tdTipo.style.display = "none";
    } else {
      tdTipo.style.display = "";
    }
  });
}

let timer;
// Campo de input busqueda para buscar elementos.
inputBusqueda.addEventListener("keyup", function (e) {
  e.stopPropagation();
  const filtro = e.target.value.toLowerCase().trim();
  // Reemplazamos el valor de time out para crear una pequeña tardanza a la búsqueda, esto con el fin de realizar multiples peticiones.
  clearTimeout(timer);

  if (filtro.length === 0) {
    renderElements({ type: typeElements.all, type: currentType }).then(() => {
      renderWithFilter();
    });
    return;
  }

  if (filtro.length >= 3) {
    timer = setTimeout(() => {
      renderElements({
        action: "elements",
        value: filtro,
        type: currentType,
        page: 1,
        isBusqueda: true,
      }).then(() => {
        renderWithFilter();
      });
    }, 300);
  }
});

// span en donde se visualizara la respuesta de la placa si es correcta o no.
const respuestaPlaca = document.querySelector("#respuestaPlaca");
// Busqueda de placas.
searchPlaca.addEventListener("keyup", async (e) => {
  e.stopPropagation();
  const filtro = e.target.value.trim();
  if (filtro.length > 2) {
    if (!validatePlaca(filtro)) {
      respuestaPlaca.style.display = "block";
      respuestaPlaca.innerText = validationRules.placa.message;
      serialPlacaAssoc.value = "";
      renderResultPlacas({ status: true });
      return;
    } else {
      respuestaPlaca.style.display = "none";
    }

    const resultado = placas.filter((pl) => String(pl.elm_placa) === filtro);

    if (resultado.length > 0) {
      renderResultPlacas({ resultado, status: true });
    } else {
      renderResultPlacas({ status: true });
      serialPlacaAssoc.value = "";
    }
  } else {
    // Limpia mensaje si no hay suficientes caracteres
    respuestaPlaca.style.display = "none";
    renderResultPlacas({ status: true });
    serialPlacaAssoc.value = "";
  }
});

// Validad cantidad sea digitada por numeros
inputCantidad.addEventListener("change", (e) => {
  e.stopPropagation();
  let cantidad = e.target.value;

  if (!validarCantidad(cantidad)) {
    initAlert("Cantidad digitada no permitida", "warning", toastOptions);
    e.target.value = "";
    return;
  }
});

elm_serie.addEventListener("change", (e) => {
  e.stopPropagation();
  let serie = e.target.value;

  if (!validarSerie(serie)) {
    initAlert(
      "Solo esta permitido el - en este campo",
      "warning",
      toastOptions
    );
    elm_serie.value = "";
    return;
  }
});

// Validad numero de placa
elm_placa.addEventListener("change", (e) => {
  e.stopPropagation();
  let placa = e.target.value;
  if (!validarCantidad(placa)) {
    initAlert("Número de placa digiado incorrecto", "warning", toastOptions);
    e.target.value = "";
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
  elm_area_cod: "Departamento",
  elm_ma_cod: "Marca",
  elm_categoria: "Categoria",
};

// Mapeo de elementos del formulario editar.
const fieldLabelsEditar = {
  elm_cod: "Código del elemento",
  elm_serie: "Serie",
  elm_nombre: "Nombre del elemento",
  elm_area_cod: "Departamento",
  elm_ma_cod: "Marca",
  elm_categoria: "Categoria",
  elm_cod_tp_elemento: "Tipo de elemento",
  elm_existencia: "Existencia",
  elm_observacion: "Observación",
  elm_sugerencia: "Sugerencia",
};

// Keys de los campos opcionales del formulario de editar.
const opcionalFielLabelsEditar = [
  "elm_observacion",
  "elm_sugerencia",
  "descripcion_movimiento",
  "elm_cod",
];

// Keys de los campos opcionales del formulario de registrar
const opcionalFielLabelsRegistrar = [
  "elm_observacion",
  "elm_sugerencia",
  "elm_categoria",
  "elm_ma_cod",
];

/**
 * Description - Función para validar los campos obligatorios y opcionales del formulario
 *
 * @param {{ dataForm?: {}; campos?: {}; optionalInputs?: [];}} - Parámetros de la función
 * @param {{}} [dataForm={}] - Objeto con la información del formulario
 * @param {{}} [campos ={}] - Objeto con todos los campos del formulario junto a su name y nombre visual
 * @param {{}} [optionalsInput = []] - Arreglo con las keys del formulario NO OBLIGATORIAS.
 * @returns {boolean}
 */
const checkObject = ({
  dataForm = {},
  campos = {},
  optionalsInput = [],
} = {}) => {
  for (const key in dataForm) {
    //Con call se asegura que aplique sobre el objeto dataForm y lo valide con la key, en donde key es un valor que está dentro del objeto, es una forma más robusta de ciclar el objeto y validar el key
    if (Object.prototype.hasOwnProperty.call(dataForm, key)) {
      const element = dataForm[key];
      // Valido si la key esta en el arreglo de campos opcionales, de ser así, omita el paso.
      if (optionalsInput.includes(key)) {
        continue;
      }
      if (element === "") {
        initAlert(
          `el campo ${campos[key]} debe ser obligatorio`,
          "info",
          toastOptions
        );
        return;
      }
    }
  }

  return true;
};

const checkboxTp = document.querySelectorAll(
  'input[name="elm_cod_tp_elemento"]'
);
function validateValueChecked(inputRadio) {
  return Array.from(inputRadio).some((radio) => radio.checked);
}

/**
 * Validar la serie o codigo que se este digitando por el usuario para evitar duplicados
 *
 * @param {{ action?: string; data?: {}; }} [param0={}]
 * @param {string} [action=""]
 * @param {{}} [data=""]
 */
const validateDisponibilidad = async ({
  action = "",
  serie = "",
  codigo = "",
  isRegistrar,
} = {}) => {
  let parameters = {};
  console.log(parameters);
  // Valido que el balor de registrar sea false
  parameters = { action, serie, isRegistrar };

  if (typeof isRegistrar !== "boolean") {
    initAlert("Flag (bandera) definida incorrectamente", "error", toastOptions);
    return;
  }

  if (!isRegistrar) {
    parameters = {
      ...parameters,
      codigo,
    };
  }

  if (!serie || !action) return;

  const responseValidate = await getData(
    "modules/elementos/controller/elementosController.php",
    "GET",
    parameters
  );
  // Serie No existe, es decir, disponible para su creación
  if (responseValidate.status === 204) return true;

  // Serie existe, es decir, no disponible para su creación.
  if (!responseValidate.status) {
    initAlert(responseValidate.message, "info", toastOptions);
    return false;
  }

  // Retornamos en esta sección false para esperar que siempre lo falle, es decir, no disponible para su creación.
  return false;
};

//Abrir modal Registrar elemento
btnAddModalElements.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();

  addElementModal.open();
});

// Enviar datos del formulario.
addElementForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  e.stopPropagation();

  const formElements = new FormData(e.target);
  const dataObj = Object.fromEntries(formElements.entries());
  delete dataObj.placaRadio;

  // Capturo los valores de los select marca, categoria y departamento.
  const selectCategoriasHidden = document.querySelector(
    "#selectCategoriasHidden"
  );
  const selectCategorias = document.querySelector("#selectCategorias");
  selectCategoriasHidden.value = selectCategorias.value;

  const selectMarcasHidden = document.querySelector("#selectMarcaHidden");
  const selectMarcas = document.querySelector("#selectMarca");
  selectMarcasHidden.value = selectMarcas.value;

  const selectAreaHidden = document.querySelector("#selectAreaHidden");
  const selectAreas = document.querySelector("#selectAreas");
  selectAreaHidden.value = selectAreas.value;

  // Agrego los valores seleccionados al objeto.
  dataObj["elm_ma_cod"] = selectMarcasHidden.value;
  dataObj["elm_categoria"] = selectCategoriasHidden.value;
  dataObj["elm_area_cod"] = selectAreaHidden.value;
  // Validar si la opción asociar placa o nueva placa ha sido seleccionado.
  if (!validateValueChecked(nuevaPlaca)) {
    initAlert(
      "Debe seleccionar una opción de asociar placa o elemento con placa.",
      "warning",
      toastOptions
    );
    return;
  }

  // Validar si la opción tipo de elemento este seleccionada.
  if (!validateValueChecked(checkboxTp)) {
    initAlert(
      "El tipo de elemento debe ser seleccionado",
      "warning",
      toastOptions
    );
    return;
  }

  if (
    !checkObject({
      dataForm: dataObj,
      campos: fieldLabels,
      optionalsInput: opcionalFielLabelsRegistrar,
    })
  ) {
    return;
  }

  // Valido la disponibilidad y niego en caso de que el elemento ya este en la bd.
  const isDisponibleSerie = await validateDisponibilidad({
    action: "validateSerie",
    serie: dataObj.elm_serie,
    isRegistrar: true,
  });
  console.log(isDisponibleSerie);
  if (!isDisponibleSerie) return;

  mostrarConfirmacion(
    "Registrar elemento",
    "¿Estás seguro de registrar este elemento?",
    async (respuesta) => {
      try {
        if (!respuesta) {
          addElementModal.close();
          addElementForm.reset();
          return;
        }
        //La respuesta puedo tranformarla en una función generica.
        const responseAddElement = await sendData(
          "modules/elementos/controller/elementosController.php",
          "POST",
          "registrar",
          dataObj
        );

        if (!responseAddElement.status) {
          initAlert(`${result.message}`, "error", toastOptions);
          return;
        }

        initAlert("Elemento agreado exitosamente", "success", {
          toastOptions,
        });
        addElementModal.close();
        addElementForm.reset();
        renderSelectPlacas("placas");
        renderElements({ type: currentType, page: 1 });
      } catch (error) {
        initAlert(`${error.message}`, "error", toastOptions);
      }
    }
  );
});

// Formulario del modal editarElemento
const editarElementForm = document.querySelector("#editarElementForm");
editarElementForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  e.stopPropagation();

  const formUpdate = new FormData(e.target);
  const dataObj = Object.fromEntries(formUpdate.entries());
  let data = dataObj;
  if (
    !checkObject({
      dataForm: dataObj,
      campos: fieldLabelsEditar,
      optionalsInput: opcionalFielLabelsEditar,
    })
  )
    return;
  // Estos 3 elementos los estoy por ahora, borrando pero les daremos utilidad.
  delete dataObj["serialPlaca"];
  delete dataObj["elm_uni_medida_select"];

  if (!validarSerie(dataObj.elm_serie)) {
    initAlert(
      "Solo esta permitido el - en este campo",
      "warning",
      toastOptions
    );
    return;
  }

  // Valido la disponibilidad y niego en caso de que el elemento ya este en la bd.
  const isDisponibleSerie = await validateDisponibilidad({
    action: "validateSerie",
    serie: dataObj.elm_serie,
    codigo: dataObj.elm_cod,
    isRegistrar: false,
  });
  if (!isDisponibleSerie) return;

  mostrarConfirmacion(
    "Guardar cambios",
    "¿Está seguro de continuar con el proceso?",
    async (respuesta) => {
      if (respuesta) {
        try {
          let response = await sendData(
            "modules/elementos/controller/elementosController.php",
            "PUT",
            "updateElement",
            data
          );

          if (!response.status) {
            initAlert(response.message, "error", toastOptions);
            return;
          }
          initAlert(response.message, "success", toastOptions);
          modalEditarElemento.close();
          // renderizo los elementos en base a la página en la que se encuentra.
          renderElements({ page: pageElement, type: currentType }).then(() => {
            renderWithFilter();
          });
        } catch (error) {
          initAlert(`${error.message}`, "error", toastOptions);
          throw new Error("Error al actualizar el recurso.");
        }
      } else {
        initAlert("Proceso cancelado", "info", toastOptions);
        modalEditarElemento.close();
      }
    }
  );
});

// mapeo de adicionar existencia.
const formAddExistenciaLabels = {
  tipo_movimiento: "Tipo de movimiento",
  co_cantidad: "Cantidad del elemento",
  descripcion_movimiento: "Descripción",
};

// Formulario agregar existencia.
const formAddExistencia = document.querySelector("#formAddExistencia");
// Cantidad del elemento del modal agregar existencia.
const co_cantidad = document.querySelector("#co_cantidad");
// Descripción del elemento modal agregar existencia.
const descripcion_movimiento = document.querySelector(
  "#descripcion_movimiento"
);
const tipo_movimiento = document.querySelector("#tipo_movimiento");
formAddExistencia.addEventListener("submit", (e) => {
  e.stopPropagation();
  e.preventDefault();
  const form = new FormData(e.target);
  const data = Object.fromEntries(form);
  let total = 0;
  let title = "";

  // Valido que los campos esten diligenciados.
  if (
    !checkObject({
      dataForm: data,
      campos: formAddExistenciaLabels,
      optionalsInput: ["descripcion_movimiento"],
    })
  )
    return;

  if (parseInt(data.co_cantidad) === 0) {
    initAlert("Cantidad del elemento no valida", "info", toastOptions);
    return;
  }

  if (!validarCantidad(data.co_cantidad)) {
    initAlert("Valor digitado incorrecto", "error", toastOptions);
    return;
  }

  if (data.tipo_movimiento === "1") {
    title = "Agregar existencia";
    total = parseInt(cantidadExistencia) + parseInt(data.co_cantidad);
  } else if (data.tipo_movimiento === "5") {
    title = "Reembolzar existencia";
    total = parseInt(cantidadExistencia) - parseInt(data.co_cantidad);
  }

  let message = replaceln(
    `Cantidad actual: ${cantidadExistencia}\nCantidad adicionada: ${data.co_cantidad}\nTotal: ${total}`
  );
  data["co_cantidad_disponible"] = cantidadExistencia;
  if (
    parseInt(data.co_cantidad) > parseInt(cantidadExistencia) &&
    tipo_movimiento.value === "5"
  ) {
    initAlert(
      "La cantidad no debe ser mayor a la cantidad disponible",
      "info",
      toastOptions
    );
    return;
  } else {
    mostrarConfirmacion(title, message, async (result) => {
      if (result) {
        try {
          const response = await sendData(
            "modules/elementos/controller/elementosController.php",
            "PUT",
            "ChangeExistencia",
            data
          );

          if (!response.status) {
            initAlert(response.message, "error", toastOptions);
            return;
          }

          if (response.status) {
            // Renderizo la página nuevamente dependiendo del tipo y la página actual.
            renderElements({ type: currentType, page: pageElement }).then(
              () => {
                renderWithFilter();
                inputBusqueda.value = "";
              }
            );

            initAlert(response.message, "success", toastOptions);
            modalAddExistencia.close();
            formAddExistencia.reset();
            return;
          } else {
            initAlert(response.message, "success", toastOptions);
            modalAddExistencia.close();
            return;
          }
        } catch (error) {
          initAlert(`${error.message}`, "error", toastOptions);
        }
      } else {
        co_cantidad.value = "";
        tipo_movimiento.value = "";
        // descripcion_movimiento.value = "";
        tipo_movimiento.selected = true;
        initAlert("Proceso cancelado", "info", toastOptions);
        M.FormSelect.init(tipo_movimiento);
        return;
      }
    });
  }
});

// Validar cantidad.
co_cantidad.addEventListener("change", (e) => {
  e.stopPropagation();
  const value = e.target.value;
  if (!validarCantidad(value)) {
    initAlert("Caracter Número digitado no valido", "error", toastOptions);
    e.target.value = "";
    return;
  }
  if (
    parseInt(cantidadExistencia, 10) < value &&
    tipo_movimiento.value === "5"
  ) {
    initAlert(
      "La cantidad no debe ser mayor a la cantidad disponible",
      "info",
      toastOptions
    );
    return;
  }
  // cantidadExistencia
});

// Validar la cantidad del limite de los input.
descripcion_movimiento.addEventListener("input", (e) => {
  e.stopPropagation();

  const value = e.target.value;
  const maxLegth = parseInt(e.target.dataset.length, 10);

  if (value.length > maxLegth) {
    initAlert("Cantidad máxima de caracteres alcanzada", "info", toastOptions);
    e.target.value = value.slice(0, maxLegth);
    return;
  }
});

// puedo ejecutar el callback que me permita reiniciar los campos del formulario.
closeModal(addElementModal, cerrarModalBtn, () => {
  // Si existe el modal, traiga el selector form que se encuentra de manera interna.
  if (addElementModal) {
    const modalForm = addElementModal.el.querySelector("form");
    resetForm(modalForm);
    modalForm.reset();
  }
});
const modalCerrarVerMas = document.querySelector("#modalCerrarVerMas");
closeModal(modalVerMas, modalCerrarVerMas);
// boton de cerrar modal addexistencia
const cerrarModalExistencia = document.querySelector("#cerrarModalExistencia");
closeModal(modalAddExistencia, cerrarModalExistencia);
const cerrarModalEditar = document.querySelector("#cerrarModalEditar");
closeModal(modalEditarElemento, cerrarModalEditar);

// Formulario del modal addElement
const modalForm = document.querySelector("#addElementForm");

document.addEventListener("DOMContentLoaded", () => {
  //Renderizado de los elementos
  renderElements({ type: currentType });

  //Inicializo los select.
  const selectAreas = document.querySelector("#selectAreas");
  const selectCategorias = document.querySelector("#selectCategorias");
  const selectMarcas = document.querySelector("#selectMarca");
  const elemsSelect = document.querySelector("#placaAssoc");

  // inicializo el tipo de movimiento para el modal de agregar compra
  const tipo_movimiento = document.querySelector("#tipo_movimiento");

  // Estas 3 funciones puedo transformarlas en 1.
  renderSelectAreas("areas", selectAreas);
  renderSelectCategorias("categoria", selectCategorias);
  renderSelectMarcas("marcas", selectMarcas);
  // Hago esto para evitar que mi función DOOM content loader sea asincrona.
  renderSelectPlacas("placas").then((dataResult) => {
    placas = dataResult;
  });
  M.FormSelect.init(elemsSelect);
  M.FormSelect.init(selectCategorias);
  M.FormSelect.init(selectMarcas);
  M.FormSelect.init(selectTpElemento);
  M.FormSelect.init(undMedida);
  M.FormSelect.init(tipo_movimiento);

  // Inicializo todos los modales.
  const modals = document.querySelectorAll(".modal");
  M.Modal.init(modals);
  const elems = document.querySelectorAll(".tooltipped");
  M.Tooltip.init(elems);
  const infoTpMovimiento = document.querySelector("#infoTpMvnto");
  initTooltip(
    infoTpMovimiento,
    tooltipOptions,
    "Compra: agregar cantidad al elemento \n Regresión: reducir cantidad al elemento"
  );
  initTooltip(btnAddModalElements, tooltipOptions, "Agregar elemento", "top");
});
