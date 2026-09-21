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
} from '../../../../public/assets/js/utils/index.js';
import { urls, tables, forms, modals, btnClose } from './SelectorsCategorias.js';
const Categorias = new Render({
  btnChangeStatus: {
    value: (fullRow, button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('data-id', `${fullRow.ca_cod}`);
      button.setAttribute('data-status', `${fullRow.ca_status}`);
      button.setAttribute('class', 'btnStatus');
      addClassItem(button, { btnArea: 'btnCategoria' });

      let iconStatus = null;
      let propertiesButton = null;
      if (fullRow.ca_status === 'Activo') {
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
      button.setAttribute('data-id', `${row.ca_cod}`);
      button.setAttribute('data-nombre', `${row.ca_nombre}`);
      button.setAttribute('data-desc', `${row.ca_descripcion}`);
      addClassItem(button, { btnCategoria: 'btnCategoria' });

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
    action: (id, row) => edit(id, row),
  },
  btnDelete: {
    value: (row, button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('data-id', `${row.ca_cod}`);
      button.setAttribute('class', 'btnStatus');
      addClassItem(button, { btnCategoria: 'btnCategoria' });
      let iconStatus = null;
      let propertiesButton = null;
      propertiesButton = { btn: 'btn', waves: 'waves-red', red: 'red' };
      iconStatus = createI('delete_forever');
      addClassItem(button, propertiesButton);
      button.appendChild(iconStatus);
    },
    key: 'btnDelete',
    action: (id, fullRow) => eliminarItem(id, fullRow),
  },
});

const loadTable = async ({ pagina = 1 }) => {
  const getData = await Categorias.getData(`${urls.urlCategoria}getData`, METHOD.GET, {
    pagina: pagina,
    limit: 4,
  });

  const data = getData.data.data;
  const dataPaginate = {
    cantidadPaginas: getData.data.cantidadPaginas,
    paginaActual: getData.data.paginaActual,
    totalRegistros: getData.data.totalRegistros,
  };
  Categorias.actualPage = getData.data.paginaActual;
  Categorias.dataPaginate = dataPaginate;

  Categorias.renderData({
    bodyTbl: tables.tblCategoria.tbody,
    headerTable: tables.tblCategoria.theader,
    data: data,
  });
  Categorias.renderPaginate(dataPaginate, tables.tblCategoria.tfoot);
};

const edit = (id, fullRow) => {
  fillDataForm(fullRow, forms.update);
  openModal(modals.update);
  InitComponents.initInputs();
};

const eliminarItem = (id, fullRow) => {
  mostrarConfirmacion(
    'Eliminar registro',
    '¿Está seguro de eliminar este registro?',
    async (response) => {
      try {
        if (!response) return;
        const dataDelete = {
          ca_id: fullRow.ca_id,
        };

        const responseDelete = await Categorias.sendData(
          `${urls.urlCategoria}delete`,
          METHOD.DELETE,
          dataDelete
        );

        if (!responseDelete.status) {
          initAlert(responseDelete.message, 'error');
          throw new Error(responseDelete.message);
        }

        initAlert(responseDelete.message, 'success');
        loadTable({ pagina: Categorias.actualPage });
        return;
      } catch (error) {
        console.error(error.message);
        return;
      }
    }
  );
};

const changeStatus = (id, fullRow) => {
  try {
    const ca_id = fullRow.ca_id;
    const ca_status = fullRow.ca_status === 'Activo' ? 2 : 1;
    const title = ca_status === 2 ? 'Habilitar Categoria' : 'Inhabilitar Categoria';
    const message =
      ca_status === 1
        ? '¿Está seguro de inhabilitar este recurso? No estára disponible para los módulos de uso'
        : '¿Está seguro de inhabilitar este recurso?';
    mostrarConfirmacion(title, message, async (response) => {
      if (!response) return;
      const dataSend = {
        ca_id: ca_id,
        ca_status: ca_status,
      };

      const responseChangeStatus = await Categorias.sendData(
        `${urls.urlCategoria}changeStatus`,
        METHOD.PUT,
        dataSend
      );

      if (!responseChangeStatus.status) {
        initAlert(responseChangeStatus.message, 'error');
        throw new Error(responseChangeStatus.message);
      }

      initAlert(responseChangeStatus.message, 'success');
      loadTable({ pagina: Categorias.actualPage });
      return;
    });
  } catch (error) {
    console.error(error.message);
    return;
  }
};

// CREATE
forms.create.addEventListener('submit', (f) => {
  f.preventDefault();
  f.stopPropagation();

  const formData = new FormData(f.target);
  const dataForm = Object.fromEntries(formData);
  mostrarConfirmacion(
    'Crear categoria',
    '¿Está seguro de registrar el siguiente item?',
    async (response) => {
      if (!response) return;

      try {
        const responseCreate = await Categorias.sendData(
          `${urls.urlCategoria}store`,
          METHOD.POST,
          dataForm
        );

        if (!responseCreate.status) {
          initAlert(responseCreate.message, 'info');
          return;
        }

        initAlert(responseCreate.message, 'success');
        loadTable({ pagina: Categorias.actualPage });
      } catch (error) {
        initAlert(error.message, 'error');
        return;
      }
    }
  );
});

// UPDATE
forms.update.addEventListener('submit', (e) => {
  e.stopPropagation();
  e.preventDefault();

  const formData = new FormData(e.target);
  const dataForm = Object.fromEntries(formData);

  mostrarConfirmacion(
    'Modificar categoria',
    '¿Está seguro de modificar el siguiente item?',
    async (response) => {
      if (!response) return;

      try {
        const responseStore = await Categorias.sendData(
          `${urls.urlCategoria}save`,
          METHOD.PUT,
          dataForm
        );

        if (!responseStore.status) {
          initAlert(responseStore.message, 'info');
          return;
        }

        initAlert(responseStore.message, 'success');
        loadTable({ pagina: Categorias.actualPage });
      } catch (error) {
        initAlert(error.message, 'error');
        return;
      }
    }
  );
});

document.addEventListener('DOMContentLoaded', (f) => {
  loadTable({ pagina: 1 });
  Categorias.executePaginate(tables.tblCategoria.tfoot, loadTable);

  closeModal(modals.update, btnClose.closeModalUpdate);
});
