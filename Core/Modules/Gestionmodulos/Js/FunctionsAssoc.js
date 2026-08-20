import {
  addClassItem,
  closeModal,
  createI,
  fillDataForm,
  initAlert,
  InitComponents,
  METHOD,
  mostrarConfirmacion,
  openModal,
  Render,
  StorageHelper,
  validateFormData,
} from '../../../../public/assets/js/utils/index.js';
import {
  buttons,
  footer,
  formAddFunctions,
  formUpdateFunctions,
  mapConfigFunctions,
  modals,
  optionals,
  selectors,
  tableFunctions,
  vars,
} from './Selectors-FunctionsAssoc.js';

const Funciones = new Render({
  btnEdit: {
    value: (row, button) => {
      let iconEditar = createI('border_color');
      button.appendChild(iconEditar);
      addClassItem(button, {
        btn: 'btn',
        waves: 'waves-effect',
        hoover: 'waves-yellow',
        cyan: 'cyan', //button color.
      });
    },
    key: 'btnEdit',
    action: (id, row) => editFunction(id, row),
  },
  btnDelete: {
    value: (fullRow, button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('data-id', `${fullRow.id_m}`);
      button.setAttribute('data-status', `${fullRow.status_modulo}`);
      button.setAttribute('class', 'btnStatus');
      let iconStatus = null;
      let propertiesButton = null;
      propertiesButton = { btn: 'btn', waves: 'waves-red', red: 'red' };
      iconStatus = createI('delete');

      addClassItem(button, propertiesButton);
      button.appendChild(iconStatus);
    },
    key: 'btnChangeStatus',
    action: (id, fullRow) => deleteFunction(id, fullRow),
  },
});
const renderData = async ({ pagina = 1 } = {}) => {
  let idModulo = vars.dataModule['id_m'];
  let nombreModulo = vars.dataModule['nombre_modulo'];
  vars.dataFunctions = await Funciones.getData(`${vars.url}getFunctionsAssoc`, METHOD.GET, {
    pagina,
    idModulo,
    nombreModulo,
  });
  let data = vars.dataFunctions.data.data ?? null; //devolver null en caso de que no exista
  vars.files = vars.dataFunctions.data.files ?? null; //Devolver null en caso de que no exista
  const paginaActual = vars.dataFunctions.data.paginaActual;
  vars.dataPaginate['totalRegistros'] = vars.dataFunctions.data.totalRegistros;
  vars.dataPaginate['paginaActual'] = paginaActual;
  vars.dataPaginate['cantidadPaginas'] = vars.dataFunctions.data.cantidadPaginas;
  Funciones.actualPage = pagina;

  // renderizamos selects de manera dinamica al formulario de crear funcion
  if (vars.files !== null) {
    Funciones.renderSelects({
      container: formAddFunctions.divs.files,
      data: vars.files,
      textLabel: 'Seleccione una opción',
      textOption: 'Archivo asociado',
      name: 'file',
    });
  }

  // inicializamos los selects.
  InitComponents.initSelect();
  console.log(data);
  if (data !== null) {
    if (Object.keys(data).length > 0) {
      Funciones.renderData({
        bodyTbl: tableFunctions.body,
        headerTable: tableFunctions.header,
        id: data['idFuncion'],
        data: data,
        footer: footer,
      });
      Funciones.renderPaginate(vars.dataPaginate, tableFunctions.footer);
      return;
    } else {
      tableFunctions.body.innerHTML = 'No hay registros';
      footer.innerHTML = '';
      return;
    }
  }
};

const editFunction = (id, row) => {
  let rowObj = {};
  rowObj['id_funcion'] = row.idFuncion;
  rowObj['nombre_funcion'] = row.nombreFuncion;
  rowObj['nombre_funcion_user'] = row.nombreFuncionLabel;
  rowObj['tp_funcion'] = String(row.tipoDeFuncion).toLocaleLowerCase();

  // container files del formularioUpdateFunctions
  const filesUpdateFunction = formUpdateFunctions.form.querySelector('.files');
  Funciones.renderSelects({
    container: filesUpdateFunction,
    data: vars.files,
    textLabel: 'Seleccione una opción',
    textOption: 'Archivo asociado',
    name: 'file',
    isRequired: true,
  });
  fillDataForm(rowObj, formUpdateFunctions.form);
  // inicializamos los selects.
  InitComponents.initInputs();
  InitComponents.initSelect();

  openModal(modals.modalEditFunction);
};

const deleteFunction = (id, fullRow) => {
  const objDelete = {};
  objDelete['id_funcion'] = fullRow.idFuncion;

  try {
    mostrarConfirmacion(
      'Eliminar función',
      '¿Está seguro de eliminar esta funcionalidad? esta funcionalidad no estará disponible para la asignación de permisos al rol del usuario.',
      async (response) => {
        if (!response) return;
        const responseDelete = await Funciones.sendData(
          `${vars.urlsFunciones}delete`,
          METHOD.DELETE,
          objDelete
        );

        if (!responseDelete.status) throw new Error(responseDelete.message);

        // exito.
        initAlert(responseDelete.message, 'success');
        Funciones.actualPage = vars.actualPage;
        renderData({ pagina: vars.actualPage });
      }
    );
  } catch (error) {
    console.error(error.message);
  }
};

buttons.btnAddFunction.addEventListener('click', (e) => {
  if (vars.files === null) {
    initAlert('no hay archivos fisicos para implementar la funcionalidad');
    return;
  }
  openModal(modals.modalAddFunction);
});

formAddFunctions.form.addEventListener('submit', (e) => {
  e.preventDefault();
  e.stopPropagation();
  const url = e.target.action;
  const formData = new FormData(e.target);

  let data = Object.fromEntries(formData);

  if (
    !validateFormData({
      formData: formData,
      campos: optionals,
      mapForm: mapConfigFunctions.mapObjAdd,
    })
  ) {
    return;
  }

  data['id_modulo'] = vars.dataModule['id_m'];
  data['tp_funcion'] = data['tp_funcion'] === 'render' ? 1 : 2;
  let message = `¿Está seguro de crear la funcionalidad? \n asegurese que la funcionalidad ya este registrada en el controlador de la clase`;
  mostrarConfirmacion('Crear funcion', message, async (response) => {
    try {
      if (!response) return;

      const responseAddFunction = await Funciones.sendData(`${url}`, METHOD.POST, data);

      // en caso de que el estado sea false.
      if (!responseAddFunction.status) throw new Error(responseAddFunction.message);
      e.target.reset();
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

formUpdateFunctions.form.addEventListener('submit', (f) => {
  f.preventDefault();
  f.stopPropagation();
  const formData = new FormData(f.target);
  const url = f.target.action;

  let data = Object.fromEntries(formData);
  data['id_modulo'] = vars.dataModule['id_m'];
  if (
    !validateFormData({
      formData: formData,
      campos: optionals,
      mapForm: mapConfigFunctions.mapObjEdit,
    })
  ) {
    return;
  }

  mostrarConfirmacion(
    'Editar función',
    '¿Está seguro de editar la funcionalidad?',
    async (response) => {
      try {
        if (!response) return;

        const responseUpdateFunction = await Funciones.sendData(url, METHOD.PUT, data);

        if (!responseUpdateFunction.status) throw new Error(responseUpdateFunction.message);

        initAlert(responseUpdateFunction.message, 'success');
        Funciones.actualPage = vars.actualPage;
        modals.modalEditFunction.style.display = 'none';
        renderData({ pagina: vars.actualPage });
      } catch (error) {
        console.error(error.message);
      }
    }
  );
});

closeModal(modals.modalAddFunction, buttons.btnCloseModalFunctionInsert, () => {
  formAddFunctions.form.reset();
});
closeModal(modals.modalEditFunction, buttons.btnCloseModalFunctionEdit, () => {
  formUpdateFunctions.form.reset();
});

footer.addEventListener('click', (e) => {
  e.stopPropagation();
  e.preventDefault();

  let btnValue = e.target.closest('.btnPaginate') ? e.target.dataset.action : null;

  // ejecutamos el evento para una pagina en especifico.
  if (e.target.closest('.liPaginate')) {
    let actualPageData = e.target.closest('.liPaginate') ? e.target.dataset.actualpage : 1;
    // valido si en la pagina en la que nos encontramos es igual a la pagina del valor seleccionado, en caso de ser asi, retornamos y no renderizamos datos.
    if (actualPageData === vars.actualPage) return;
    vars.actualPage = actualPageData;
    renderData({ pagina: actualPageData });
    return;
  }

  // EJECUTAMOS LOS EVENTOS PARA LOS BOTONES BTNPAGINATE
  if (e.target.closest('.btnPaginate')) {
    if (!btnValue) return;
    if (btnValue === 'preview') {
      // re asignamos la pagina recibida por la peticion para asi reducir el valor y re enviar la peticion con la pagina anterior.
      vars.actualPage = vars.dataPaginate.paginaActual;
      vars.actualPage--;
      if (vars.actualPage < 1) {
        vars.actualPage = 1;
        return;
      }
    }
    if (btnValue === 'next') {
      vars.actualPage++;
      if (vars.actualPage > vars.dataPaginate.cantidadPaginas) {
        vars.actualPage = vars.dataPaginate.cantidadPaginas;
        return;
      }
    }

    renderData({ pagina: vars.actualPage });
    return;
  }
});

document.addEventListener('DOMContentLoaded', () => {
  vars.dataModule = StorageHelper.getParsedValue({ key: 'dataModulo' }); //Obtenemos los datos del modulo de localstorage
  selectors.textTitleFunctions.innerHTML = `Funciones asociadas módulo - ${vars.dataModule['nombre_modulo']}`;
  renderData();
});
