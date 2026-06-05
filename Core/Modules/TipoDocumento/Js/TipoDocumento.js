import {
  closeModal,
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
let modalUpdate = document.querySelector('#modalTp'); //Modal para editar información
let btnCloseModal = document.querySelector('.closeModalBtn');
const render = new Render({
  btnChangeStatus: {
    value: '',
    key: 'btnChangeStatus',
    action: (id) => changeStatus(id),
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
          // vuelvo a hacer la peticion pero con la pagina especifica y envio nuevamente la dara a renderizar.
          responseGetData = await render.getData(`${url}getData`, 'GET', {
            pagina: actualPage,
            limit: 4,
          });
          data = responseGetData.data.data;
          render.renderData(bodyTbl, tableConfigTp, 'tp_id', data);
          render.renderPaginate(dataPaginate, footerTp);
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
  openModal(modalUpdate);
  let input = null;
  // recorro el objeto con los datos, extraigo en clave valor, busco el selector con el nombre sabiendo que el nombre del input debe ser igual al key e implemento su valor.
  for (const [key, value] of Object.entries(row)) {
    input =
      formUpdate.querySelector(`input[name="${key}"]`) ||
      formUpdate.querySelector(`textarea[name="${key}"]`);

    // validamos, para asi validar que encontro el selector e implementarle su valor.
    if (input != null) {
      input.value = value;
    }
  }
  // inicializar el input con materialize.
  M.updateTextFields();

  // evento de envio.
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
            // renderizar datos
            responseGetData = await render.getData(`${url}getData`, 'GET', {
              pagina: actualPage,
              limit: 4,
            });
            data = responseGetData.data.data;
            render.renderData(bodyTbl, tableConfigTp, 'tp_id', data);
            render.renderPaginate(dataPaginate, footerTp);
            return;
          }
        }
      );
    } catch (error) {
      console.error(error);
      return;
    }
  });
};
// function para cambiar el estado del registro
const changeStatus = (id) => {
  console.log(id);
};

closeModal(modalUpdate, btnCloseModal, () => {
  // limpiamos los valores de modales.
  formUpdate.reset();
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
  render.renderData(bodyTbl, tableConfigTp, 'tp_id', data);
  render.renderPaginate(dataPaginate, footerTp);

  allBtnPaginate = footerTp.querySelectorAll('.btnPaginate');

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
    render.renderData(bodyTbl, tableConfigTp, 'tp_id', responseGetData.data.data);
    render.renderPaginate(dataPaginate, footerTp);
  });
});
