import {
  cancelProcess,
  deleteSuccess,
  successChangeStatusDisable,
  successChangeStatusEnable,
  titleEliminar,
} from '../../../../public/assets/js/utils/const.js';
import {
  createI,
  closeModal,
  openModal,
  createBtn,
  instanceModal,
  options,
  initAlert,
  validateFormData,
  sendData,
  mostrarConfirmacion,
  Render,
  fillDataForm,
  addClassItem,
} from '../../../../public/assets/js/utils/index.js';
import * as s from './Selectors.js';
let dataAreas = null;
let pageUser = 1;
let dataPaginate = {};
const Areas = new Render({
  btnChangeStatus: {
    value: (fullRow, button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('data-id', `${fullRow.ar_cod}`);
      button.setAttribute('data-status', `${fullRow.ar_status}`);
      button.setAttribute('class', 'btnStatus');

      let iconStatus = null;
      let propertiesButton = null;
      if (fullRow.ar_status === 1) {
        propertiesButton = { btn: 'btn', waves: 'waves-orange', orange: 'orange' };
        iconStatus = createI('clear');
      } else {
        propertiesButton = { btn: 'btn', waves: 'waves-green', green: 'green' };
        iconStatus = createI('check');
      }

      addClassItem(button, propertiesButton);
      button.appendChild(iconStatus);
    },
    key: 'btnChangeStatus',
    action: (id, fullRow) => changeStatus(id, fullRow),
  },
  btnEdit: {
    value: (row, button) => {
      console.log(row);
      button.setAttribute('data-id', `${row.ar_cod}`);
      button.setAttribute('data-nombre', `${row.ar_nombre}`);
      button.setAttribute('data-desc', `${row.ar_descripcion}`);
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
    action: (id, row) => editarArea(id, row),
  },
  btnDelete: {
    value: (row, button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('data-id', `${row.ar_cod}`);
      button.setAttribute('class', 'btnStatus');
      let iconStatus = null;
      let propertiesButton = null;
      propertiesButton = { btn: 'btn', waves: 'waves-red', red: 'red' };
      iconStatus = createI('delete_forever');
      addClassItem(button, propertiesButton);
      button.appendChild(iconStatus);
    },
    key: 'btnDelete',
    action: (id) => eliminarItem(id),
  },
});
const loadTable = async ({ pagina: actualPage }) => {
  let responseGetData = null;
  responseGetData = await Areas.getData(`${s.url}getData`, 'GET', { pagina: actualPage });
  const realPage = responseGetData.data.paginaActual;

  // guardamos la pagina actual en la propiedad de la clase para el correcto renderizado del usuario final.
  Areas.actualPage(realPage);
  dataAreas = responseGetData.data.data;
  dataPaginate = {};
  dataPaginate['totalRegistros'] = responseGetData.data.totalRegistros;
  dataPaginate['paginaActual'] = realPage;
  dataPaginate['cantidadPaginas'] = responseGetData.data.cantidadPaginas;

  Areas.renderData(s.tableBody, s.tableHeadArea, 'ar_cod', dataAreas);
  Areas.renderPaginate(dataPaginate, s.footerArea);
};

const eliminarItem = (id) => {
  let valueLenght = s.tableBody.querySelectorAll('tr').length;

  // function para eliminar el item
  mostrarConfirmacion(
    'Eliminar Elemento',
    '¿Esta seguro de eliminar este registro?',
    async (response) => {
      try {
        if (!response) {
          initAlert('Proceso cancelado', 'info');
          return;
        }
        const responseDelete = await Areas.sendData(`${s.url}delete`, 'DELETE', { ar_cod: id });
        // si la cantidad de tds es menor a 1 entonces recubidmos la pagina, significa que ya no hay mas registros por ende es la ultima pagina, mandamos a renderizar la informacion.
        if (valueLenght <= 1 && actualPage > 1) {
          actualPage--;
        }
        if (responseDelete.status) {
          initAlert(responseDelete.message, 'success');
          loadTable({ pagina: actualPage });
          return;
        }
      } catch (error) {
        console.log(error);
        return;
      }
    }
  );
};

const editarArea = (id, row) => {
  console.log(row);
  fillDataForm(row, s.areaUpdateForm);
  // inicializar el input con materialize.
  M.updateTextFields();
  openModal(s.modalAreaUpdate);
};

const changeStatus = (id, dataRow) => {
  // capturar codigo y status
  let message = dataRow.ar_status === 1 ? s.textEstaSeguroInhabilitar : s.textEstaSeguroHabilitar;
  let title = dataRow.ar_status === 1 ? s.titleInhabilitar : s.titleHabilitar;

  mostrarConfirmacion(title, message, async (response) => {
    try {
      if (!response) {
        initAlert(cancelProcess, 'info');
        return;
      }

      let data = {
        ar_cod: dataRow.ar_cod,
        ar_status: dataRow.ar_status,
      };

      const responseChangeStatus = await sendData(`${s.url}changeStatus`, 'PUT', data);
      if (responseChangeStatus.status) {
        let mesageStatus =
          dataRow.ar_status === 1 ? successChangeStatusDisable : successChangeStatusEnable;
        initAlert(mesageStatus, 'success');
        loadTable({ pagina: actualPage });
        return;
      } else {
        throw new Error(responseChangeStatus.message);
      }
    } catch (error) {
      initAlert(error, 'error');
      return;
    }
  });
};

let actualPage = 1;
s.footerArea.addEventListener('click', (e) => {
  e.stopPropagation();
  e.preventDefault();
  let btnValue = e.target.closest('.btnPaginate') ? e.target.value : null;
  if (!btnValue) return;
  if (btnValue === 'preview') {
    actualPage--;
    if (actualPage < 1) {
      actualPage = 1;
      return;
    }
  }

  if (btnValue === 'next') {
    actualPage++;
    if (actualPage > dataPaginate.cantidadPaginas) {
      actualPage = dataPaginate.cantidadPaginas;
      return;
    }
  }

  loadTable({ pagina: actualPage });
});

// Listener update
s.areaUpdateForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  e.stopPropagation();
  let formData = new FormData(e.target);
  let data = Object.fromEntries(formData);
  try {
    mostrarConfirmacion(s.titleActualizar, s.textEstaSeguro, async (response) => {
      if (!response) {
        s.modalAreaUpdate.style.display = 'none';
        initAlert(cancelProcess, 'info');
        return;
      }
      // campos opcionales
      let campos = ['ar_descripcion'];
      // validar campos obligatorios
      if (!validateFormData({ formData: formData, campos: campos, mapForm: s.mapCampos })) return;
      let update = `${s.url}save`;

      const responseUpdate = await sendData(update, 'PUT', data);
      // hay un contexto en donde no me devuelve un cuerpo porque la cantidad de columnas afectadas puede ser 0.
      if (responseUpdate.status === 204) {
        s.modalAreaUpdate.style.display = 'none';
        return;
      }
      if (responseUpdate.status) {
        loadTable({ pagina: actualPage });
        s.modalAreaUpdate.style.display = 'none';
        initAlert(responseUpdate.message, 'success');
        return;
      }
    });
  } catch (error) {
    initAlert(error, 'info');
    return;
  }
});

// Listener create
s.formCreate.addEventListener('submit', (g) => {
  g.preventDefault();
  g.stopPropagation();
  let formPost = new FormData(g.target);
  let data = Object.fromEntries(formPost);

  mostrarConfirmacion('Crear departamento', '', async (response) => {
    try {
      if (!response) {
        initAlert(cancelProcess, 'info');
        return;
      }

      let responsePost = await sendData(`${s.url}store`, 'POST', data);
      if (responsePost.status) {
        initAlert(responsePost.message, 'success');
        g.target.reset();
        loadTable({ pagina: actualPage });
        return;
      } else {
        throw new Error(responsePost.message);
      }
    } catch (error) {
      initAlert(error, 'error');
      return;
    }
  });
});

// Cerrar el boton
closeModal(s.modalAreaUpdate, s.btnCloseModalUpdate, () => {});

document.addEventListener('DOMContentLoaded', () => {
  let iPost = createI();
  iPost.innerText = 'send';
  s.btnAreaSend.append(iPost);
  loadTable({ pagina: actualPage });
});
