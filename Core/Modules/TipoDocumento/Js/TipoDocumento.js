import {
  addClassItem,
  closeModal,
  createI,
  fillDataForm,
  initAlert,
  mostrarConfirmacion,
  openModal,
} from '../../../../public/assets/js/utils/index.js';
import { Render } from '../../../../public/assets/js/utils/Render.js';
const url = 'dashboard.php?modulo=TipoDocumento&controlador=TipoDocumento&function=';
const bodyTbl = document.querySelector('#tableBodyTp');
const tableConfigTp = document.querySelector('#tHeadTP');
const footerTp = document.querySelector('#footerTp');
let actualPage = 1;
let data = {};
let dataPaginate = {};
let allBtnPaginate;
let formUpdate = document.querySelector('#tpUpdateForm'); //Formulario para actualizar
let formTp = document.querySelector('#formTp'); //Formulario para crear.
let modalUpdate = document.querySelector('#modalTp'); //Modal para editar información
let btnCloseModal = document.querySelector('.closeModalBtn');

// Instancia de la clase render.
const render = new Render({
  btnEdit: {
    value: (row, button) => {
      button.setAttribute('data-id', `${row.tp_id}`);
      button.setAttribute('data-nombre', `${row.tp_nombre}`);
      button.setAttribute('data-sigla', `${row.tp_sigla}`);
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
    action: (id, row) => editarDepartamento(id, row),
  },
  btnChangeStatus: {
    value: (row, button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('data-id', `${row.tp_id}`);
      button.setAttribute('data-status', `${row.tp_status}`);
      button.setAttribute('class', 'btnStatus');

      let iconStatus = null;
      let propertiesButton = null;
      if (row.tp_status === 1) {
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
  btnDelete: {
    value: (row, button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('data-id', `${row.tp_id}`);
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

// function para eliminar el registro
const eliminarItem = (id = 0) => {
  // function para eliminar el item
  let valueLenght = bodyTbl.querySelectorAll('tr').length;

  mostrarConfirmacion(
    'Eliminar Elemento',
    '¿Esta seguro de eliminar este registro?',
    async (response) => {
      try {
        if (!response) {
          initAlert('Proceso cancelado', 'info');
          return;
        }
        const responseDelete = await render.sendData(`${url}delete`, 'DELETE', { tp_id: id });
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
// function para editar el registro
const editarDepartamento = (id = 0, row = {}) => {
  fillDataForm(row, formUpdate);
  // inicializar el input con materialize.
  M.updateTextFields();
  openModal(modalUpdate);
};
// function para cambiar el estado del registro
const changeStatus = (id, fullRow) => {
  let newStatus = fullRow.tp_status === 1 ? 2 : 1;

  let dataStatus = {
    tp_status: newStatus,
    tp_id: id,
  };
  let title = fullRow.tp_status === 1 ? 'Inhabilitar departamento' : 'Habilitar departamento';
  let message =
    newStatus === 1
      ? '¿Esta seguro de inhabilitar este registro?'
      : '¿Esta seguro de habilitar este registro?';

  mostrarConfirmacion(title, message, async (response) => {
    if (!response) {
      initAlert('proceso cancelado', 'info');
      return;
    }
    const responseChangeStatus = await render.sendData(`${url}changeStatus`, 'PUT', dataStatus);
    if (responseChangeStatus.status) {
      console.log(responseChangeStatus.message);
      initAlert(responseChangeStatus.message, 'success');
      loadTable({ pagina: actualPage });
      return;
    }
  });
};

/**
 * Funcion en donde ejecutamos el renderizado de la tabla, y los botones.
 *
 * @async
 * @returns {*}
 */
const loadTable = async ({ pagina: actualPage }) => {
  let responseGetData = null;
  responseGetData = await render.getData(`${url}getData`, 'GET', {
    pagina: actualPage,
    limit: 4,
  });
  const realPage = responseGetData.data.paginaActual;

  data = responseGetData.data.data;
  dataPaginate = {};
  render.actualPage(realPage);
  dataPaginate['totalRegistros'] = responseGetData.data.totalRegistros;
  dataPaginate['paginaActual'] = realPage;
  dataPaginate['cantidadPaginas'] = responseGetData.data.cantidadPaginas;
  render.renderData(bodyTbl, tableConfigTp, 'tp_id', data, { tp_status: 'tp_status' });
  render.renderPaginate(dataPaginate, footerTp);
};

closeModal(modalUpdate, btnCloseModal, () => {
  // limpiamos los valores de modales.
  formUpdate.reset();
});

// evento submitUpdate
formUpdate.addEventListener('submit', (g) => {
  g.preventDefault();
  g.stopPropagation();

  let formData = new FormData(g.target);
  let dataUpdate = Object.fromEntries(formData);

  // mostrar modal de confirmacion.
  try {
    mostrarConfirmacion(
      'Actualizar registro',
      '¿Esta seguro de actualizar este registro?',
      async (response) => {
        if (!response) {
          initAlert('proceso cancelado', 'info');
          closeModal(modalUpdate, btnCloseModal, () => {
            formUpdate.reset();
          });
        }
        const responseUpdate = await render.sendData(`${url}save`, 'PUT', dataUpdate);
        if (responseUpdate.status) {
          modalUpdate.style.display = 'none';
          formUpdate.reset();
          initAlert(responseUpdate.message, 'success');
          loadTable({ pagina: actualPage });
          return;
        }
      }
    );
  } catch (error) {
    console.error(error);
    return;
  }
});

// evento crear elemento.
formTp.addEventListener('submit', (f) => {
  f.preventDefault();
  f.stopPropagation();

  try {
    mostrarConfirmacion(
      'Crear tipo documento',
      '¿Esta seguro de crear el siguiente registro?',
      async (response) => {
        if (!response) {
          initAlert('proceso cancelado', 'info');
          return;
        }

        let formData = new FormData(f.target);
        let dataForm = Object.fromEntries(formData);
        let responseCreate = await render.sendData(`${url}store`, 'POST', dataForm);

        if (responseCreate.status) {
          initAlert(responseCreate.message, 'success');
          formTp.reset();
          loadTable({ pagina: actualPage });
          return;
        }
      }
    );
  } catch (error) {
    console.error(error);
  }
});

// ejecutar justo cuando cargue el archivo.
document.addEventListener('DOMContentLoaded', async () => {
  loadTable({ pagina: 1 });

  // renderizado del footerPaginado
  footerTp.addEventListener('click', async (f) => {
    const button = f.target.closest('.btnPaginate');

    if (!button) return;
    f.stopPropagation();
    f.preventDefault();

    let value = button.value;

    if (value === 'next') {
      actualPage++;
      if (actualPage > dataPaginate.cantidadPaginas) {
        actualPage = dataPaginate.cantidadPaginas;
        return;
      }
    }

    if (value === 'preview') {
      actualPage--;

      if (actualPage < 1) {
        actualPage = 1;
        return;
      }
    }
    loadTable({ pagina: actualPage });
  });
});
