import { buttons, forms, modals, table, titlesModulo, vars } from './Selectors-Modulos.js';
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
  successChangeStatusDisable,
  successChangeStatusEnable,
  validateFormData,
} from '../../../../public/assets/js/utils/index.js';
const Modulos = new Render({
  btnChangeStatus: {
    value: (fullRow, button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('data-id', `${fullRow.id_m}`);
      button.setAttribute('data-status', `${fullRow.status_modulo}`);
      button.setAttribute('class', 'btnStatus');

      let iconStatus = null;
      let propertiesButton = null;
      if (fullRow.status_modulo === 'Activo') {
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
      button.setAttribute('data-id', `${row.id_m}`);
      button.setAttribute('data-nombre', `${row.nombre_modulo}`);
      button.setAttribute('data-desc', `${row.descripcion}`);
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
    action: (id, row) => editarModulo(id, row),
  },
});
let dataPaginate = {};
const renderData = async ({ pagina = 1 } = {}) => {
  try {
    if (!pagina) throw new Error('Dato incorrecto');
    vars.dataModulos = await Modulos.getData(`${vars.url}getData`, METHOD.GET, { pagina: pagina });
    let dataModulos = vars.dataModulos.data.data;
    const paginaActual = vars.dataModulos.data.paginaActual;
    dataPaginate = {};
    dataPaginate['totalRegistros'] = vars.dataModulos.data.totalRegistros;
    dataPaginate['paginaActual'] = paginaActual;
    dataPaginate['cantidadPaginas'] = vars.dataModulos.data.cantidadPaginas;

    // asignamos la pagina actual a la propiedad de la instancia.
    Modulos.actualPage(paginaActual);

    Modulos.renderData({
      bodyTbl: table.body,
      headerTable: table.header,
      id: vars.dataModulos.id_m,
      data: dataModulos,
    });
    Modulos.renderPaginate(dataPaginate, table.footer);
  } catch (error) {
    console.error(error.message);
  }
};

/** Logica de la paginación. */
const executePaginate = () => {
  // let actualPage = 1;
  table.footer.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    let btnValue = e.target.closest('.btnPaginate') ? e.target.dataset.action : null;

    // ejecutamos el evento para una pagina en especifico.
    if (e.target.closest('.liPaginate')) {
      let actualPageData = e.target.closest('.liPaginate') ? e.target.dataset.actualpage : 1;
      renderData({ pagina: actualPageData });
      return;
    }

    // EJECUTAMOS LOS EVENTOS PARA LOS BOTONES BTNPAGINATE
    if (e.target.closest('.btnPaginate')) {
      if (!btnValue) return;
      if (btnValue === 'preview') {
        // re asignamos la pagina recibida por la peticion para asi reducir el valor y re enviar la peticion con la pagina anterior.
        vars.actualPage = dataPaginate.paginaActual;
        vars.actualPage--;
        if (vars.actualPage < 1) {
          vars.actualPage = 1;
          return;
        }
      }
      if (btnValue === 'next') {
        vars.actualPage++;
        if (vars.actualPage > dataPaginate.cantidadPaginas) {
          vars.actualPage = dataPaginate.cantidadPaginas;
          return;
        }
      }

      renderData({ pagina: vars.actualPage });
    }
  });
};

const editarModulo = (id, row) => {
  fillDataForm(row, forms.formUpdate);
  // inicializar el input con materialize.
  InitComponents.initInputs();
  openModal(modals.modalEdit);
};

const changeStatus = (id, dataRow) => {
  // capturar codigo y status
  let message = dataRow.estatus_modulo === 1 ? titlesModulo.inactiveUser : titlesModulo.activeUser;
  let title = dataRow.estatus_modulo === 1 ? titlesModulo.inactiveUser : titlesModulo.activeUser;
  console.log(dataRow);
  mostrarConfirmacion(title, message, async (response) => {
    try {
      if (!response) return;

      let data = {
        id_m: dataRow.id_m,
        status_modulo: dataRow.status_modulo === 'Activo' ? 1 : 0,
      };

      console.log(data);

      const responseChangeStatus = await Modulos.sendData(
        `${vars.url}changeStatus`,
        METHOD.PUT,
        data
      );
      if (!responseChangeStatus.status) throw new Error(responseChangeStatus.message);
      let mesageStatus =
        dataRow.status_modulo === 'Activo' ? successChangeStatusEnable : successChangeStatusDisable;
      initAlert(mesageStatus, 'success');
      renderData({ pagina: vars.actualPage });
      return;
    } catch (error) {
      initAlert(error, 'error');
      return;
    }
  });
};

forms.formUpdate.addEventListener('submit', (e) => {
  e.stopPropagation();
  e.preventDefault();
  const formData = new FormData(e.target);
  const dataUpdate = Object.fromEntries(formData);

  mostrarConfirmacion(
    'Actualizar módulo',
    '¿Está seguro de actualizar la información de este módulo?',
    async (response) => {
      try {
        if (!response) return;
        const responseUpdate = await Modulos.sendData(`${vars.url}save`, METHOD.PUT, dataUpdate);

        if (responseUpdate.status) {
          initAlert(responseUpdate.message, 'success');
          Modulos.actualPage(parseInt(vars.actualPage));
          renderData({ pagina: parseInt(vars.actualPage) });
          return;
        }
        if (!responseUpdate.status) throw new Error(responseUpdate.message);
      } catch (error) {
        initAlert(error.message);
        return;
      }
    }
  );
});

document.addEventListener('DOMContentLoaded', () => {
  renderData();
  executePaginate();
  closeModal(modals.modalEdit, buttons.btnCloseModal);
});
