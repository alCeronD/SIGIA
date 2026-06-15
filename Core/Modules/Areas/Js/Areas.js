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
} from '../../../../public/assets/js/utils/index.js';
import renderData, { renderPaginate } from './Functions.js';
import * as s from './Selectors.js';
let iPost = createI();
iPost.innerText = 'send';
s.btnAreaSend.append(iPost);

s.tableBody.addEventListener('click', (e) => {
  // evento editar
  if (e.target.closest('.btnEdit')) {
    // Abrir el modal.
    let tr = e.target.closest('tr');
    let td = tr.querySelectorAll('td');
    let id = td[0].textContent;
    let idUpdate = td[0].textContent;
    let nombre = td[1].textContent;
    let descripcion = td[2].textContent;

    // adicionar items a formulario de area.
    let inputNombre = s.areaUpdateForm.querySelector('#nombreAreaUpdate');
    let inputDescript = s.areaUpdateForm.querySelector('#descripcionAreaUpdate');
    let inputId = document.querySelector('#idCodigo');
    inputNombre.value = nombre;
    inputDescript.value = descripcion;
    inputId.value = idUpdate;
    // habilitamos el modal
    openModal(s.modalAreaUpdate);
  }
  // evento change status
  if (e.target.closest('.btnChangeStatus')) {
    e.stopPropagation();
    e.preventDefault();

    // capturar codigo y status
    let actualStatus = e.target.value;
    let cod = e.target.getAttribute('datacod');

    let message = actualStatus === 1 ? s.textEstaSeguroInhabilitar : s.textEstaSeguroHabilitar;
    let title = actualStatus === 1 ? s.titleInhabilitar : s.titleHabilitar;

    mostrarConfirmacion(title, message, async (response) => {
      try {
        if (!response) {
          initAlert(cancelProcess, 'info');
          return;
        }

        let data = {
          ar_cod: cod,
          ar_status: actualStatus,
        };

        const responseChangeStatus = await sendData(`${s.url}changeStatus`, 'PUT', data);
        if (responseChangeStatus.status) {
          let mesageStatus =
            actualStatus === '1' ? successChangeStatusDisable : successChangeStatusEnable;
          initAlert(mesageStatus, 'success');
          renderData(s.actualPage);
          return;
        } else {
          throw new Error(responseChangeStatus.message);
        }
      } catch (error) {
        initAlert(error, 'error');
        return;
      }
    });
  }

  // evento eliminar
  if (e.target.closest('.btnDelete')) {
    e.stopPropagation();
    e.preventDefault();

    // capturar el codigo y enviarlo al backend.
    let ar_cod = e.target.value;
    let data = {
      ar_cod: ar_cod,
    };
    try {
      mostrarConfirmacion(titleEliminar, s.textEstaSeguroEliminar, async (response) => {
        if (!response) {
          initAlert(cancelProcess, 'info');
          return;
        }
        const responseDelete = await sendData(`${s.url}deleteDepartment`, 'DELETE', data);
        if (responseDelete.status === 204) {
          initAlert(deleteSuccess, 'success');
          renderData(s.actualPage);
          return;
        }
      });
    } catch (error) {
      console.log(error);
      return;
    }
  }
});

// Cerrar el boton
closeModal(s.modalAreaUpdate, s.btnCloseModalUpdate, () => {});

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
        initAlert(cancelProcess, 'info', toastOptions);
        return;
      }
      // campos opcionales
      let campos = ['ar_descripcion'];
      // validar campos obligatorios
      if (!validateFormData({ formData: formData, campos: campos, mapForm: s.mapCampos })) return;
      let update = `${s.url}editDepartment`;

      const responseUpdate = await sendData(update, 'PUT', data);
      // hay un contexto en donde no me devuelve un cuerpo porque la cantidad de columnas afectadas puede ser 0.
      if (responseUpdate.status === 204) {
        s.modalAreaUpdate.style.display = 'none';
        return;
      }
      if (responseUpdate.status) {
        renderData();
        s.modalAreaUpdate.style.display = 'none';
        initAlert(responseUpdate.message, 'success', toastOptions);
        return;
      }
    });
  } catch (error) {
    initAlert(error, 'info', toastOptions);
    return;
  }
});

// Listener create
s.formCreate.addEventListener('submit', (g) => {
  g.preventDefault();
  let formPost = new FormData(g.target);
  let data = Object.fromEntries(formPost);

  mostrarConfirmacion('Crear departamento', '', async (response) => {
    try {
      if (!response) {
        initAlert(cancelProcess, 'info');
        return;
      }

      let responsePost = await sendData(`${s.url}createDepartment`, 'POST', data);
      if (responsePost.status) {
        initAlert(responsePost.message, 'success');
        renderData(s.actualPage);
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

document.addEventListener('DOMContentLoaded', () => {
  renderData(s.actualPage);
});
