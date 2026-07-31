import {
  closeModal,
  initAlert,
  InitComponents,
  METHOD,
  mostrarConfirmacion,
  openModal,
  Render,
  StorageHelper,
} from '../../../../public/assets/js/utils/index.js';
import {
  buttons,
  formAddFunctions,
  modals,
  selectors,
  tableFunctions,
  vars,
} from './Selectors-FunctionsAssoc.js';
vars.dataModule = StorageHelper.getParsedValue({ key: 'dataModulo' }); //Obtenemos los datos del modulo de localstorage
const Funciones = new Render();
const renderData = async ({ pagina = 1 } = {}) => {
  let idModulo = vars.dataModule['id_m'];
  let nombreModulo = vars.dataModule['nombre_modulo'];
  vars.dataFunctions = await Funciones.getData(`${vars.url}getFunctionsAssoc`, METHOD.GET, {
    pagina,
    idModulo,
    nombreModulo,
  });
  let data = vars.dataFunctions.data.data;
  vars.files = vars.dataFunctions.data.files;
  console.log(vars.files);
  const paginaActual = vars.dataFunctions.data.paginaActual;
  vars.dataPaginate['totalRegistros'] = vars.dataFunctions.data.totalRegistros;
  vars.dataPaginate['paginaActual'] = paginaActual;
  vars.dataPaginate['cantidadPaginas'] = vars.dataFunctions.data.cantidadPaginas;
  Funciones.actualPage(pagina);
  Funciones.renderData({
    bodyTbl: tableFunctions.body,
    headerTable: tableFunctions.header,
    id: data['idFuncion'],
    data: data,
  });
  Funciones.renderPaginate(vars.dataPaginate, tableFunctions.footer);

  // renderizamos selects de manera dinamica al formulario de crear funcion
  Funciones.renderSelects({
    container: formAddFunctions.divs.files,
    data: vars.files,
    textLabel: 'Seleccione una opción',
    textOption: 'Archivo asociado',
    name: 'file',
  });
  // inicializamos los selects.
  InitComponents.initSelect();
};

buttons.btnAddFunction.addEventListener('click', (e) => {
  openModal(modals.modalAddFunction);
});

formAddFunctions.form.addEventListener('submit', (e) => {
  e.preventDefault();
  e.stopPropagation();
  const url = e.target.action;
  const formData = new FormData(e.target);
  let data = Object.fromEntries(formData);
  data['id_modulo'] = vars.dataModule['id_m'];
  data['tp_funcion'] = data['tp_funcion'] === 'render' ? 1 : 2;
  console.log(data);
  let message =
    '¿Está seguro de crear la funcionalidad \n asegurese que la funcionalidad ya este registrada en el controlador de la clase';
  mostrarConfirmacion('Crear funcion', message, async (response) => {
    try {
      if (!response) return;

      const responseAddFunction = await Funciones.sendData(`${url}`, METHOD.POST, data);

      // en caso de que el estado sea false.
      if (!responseAddFunction.status) throw new Error(responseAddFunction.message);

      renderData({ pagina: vars.actualPage });
      initAlert(responseAddFunction.message, 'success');
      return;
    } catch (error) {
      console.error(error.message);
      initAlert(error.message, 'error');
      return;
    }
  });
});

closeModal(modals.modalAddFunction, buttons.btnCloseModalFunction);

document.addEventListener('DOMContentLoaded', () => {
  selectors.textTitleFunctions.innerHTML = `Funciones asociadas módulo - ${vars.dataModule['nombre_modulo']}`;

  renderData();
});
