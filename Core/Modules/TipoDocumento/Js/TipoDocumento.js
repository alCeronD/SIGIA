import {
  closeModal,
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
let responseGetData = null;
let data = {};
let dataPaginate = {};
let allBtnPaginate;
let formUpdate = document.querySelector('#tpUpdateForm'); //Formulario para actualizar
let formTp = document.querySelector('#formTp'); //Formulario para crear.
let modalUpdate = document.querySelector('#modalTp'); //Modal para editar información
let btnCloseModal = document.querySelector('.closeModalBtn');

// Instancia de la clase render.
const render = new Render({
  btnChangeStatus: {
    value: (row) => (row.tp_status === 1 ? 'inhabilitar' : 'habilitar'),
    key: 'btnChangeStatus',
    action: (id, fullRow) => changeStatus(id, fullRow),
  },
  btnEdit: {
    value: 'Editar',
    key: 'btnEdit',
    action: (id, row) => editarDepartamento(id, row),
  },
  btnDelete: {
    value: 'eliminar',
    key: 'btnDelete',
    action: (id) => eliminarItem(id),
  },
});

// function para eliminar el registro
const eliminarItem = (id = 0) => {
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
        const responseDelete = await render.sendData(`${url}deleteItem`, 'DELETE', { tp_id: id });
        if (responseDelete.status) {
          initAlert(responseDelete.message, 'success');
          loadTable();
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
      loadTable();
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
const loadTable = async () => {
  responseGetData = await render.getData(`${url}getData`, 'GET', {
    pagina: actualPage,
    limit: 4,
  });

  data = responseGetData.data.data;
  dataPaginate = {};
  dataPaginate['totalRegistros'] = responseGetData.data.totalRegistros;
  dataPaginate['paginaActual'] = responseGetData.data.paginaActual;
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
        const responseUpdate = await render.sendData(`${url}updateItem`, 'PUT', dataUpdate);
        if (responseUpdate.status) {
          modalUpdate.style.display = 'none';
          formUpdate.reset();
          initAlert(responseUpdate.message, 'success');
          loadTable();
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
        let responseCreate = await render.sendData(`${url}createDepartment`, 'POST', dataForm);

        if (responseCreate.status) {
          initAlert(responseCreate.message, 'success');
          loadTable();
          return;
        }
        f.target.reset();
      }
    );
  } catch (error) {
    console.error(error);
  }
});

// ejecutar justo cuando cargue el archivo.
document.addEventListener('DOMContentLoaded', async () => {
  // Logica para solicitar la data y redenderizarla.
  responseGetData = await render.getData(`${url}getData`, 'GET', {
    pagina: actualPage,
    limit: 4,
  });
  data = responseGetData.data.data;
  dataPaginate = {};
  dataPaginate['totalRegistros'] = responseGetData.data.totalRegistros;
  dataPaginate['paginaActual'] = responseGetData.data.paginaActual;
  dataPaginate['cantidadPaginas'] = responseGetData.data.cantidadPaginas;
  render.renderData(bodyTbl, tableConfigTp, 'tp_id', data, { tp_status: 'tp_status' });
  render.renderPaginate(dataPaginate, footerTp);

  allBtnPaginate = footerTp.querySelectorAll('.btnPaginate');

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
    // guardo el valor de la pagina en la propiedad de la clase.
    render.actualPage(actualPage);
    responseGetData = await render.getData(`${url}getData`, 'GET', {
      pagina: actualPage,
      limit: 4,
    });
    render.renderData(bodyTbl, tableConfigTp, 'tp_id', responseGetData.data.data, {
      tp_status: 'tp_status',
    });
    render.renderPaginate(dataPaginate, footerTp);
  });
});
