import {
  getData,
  sendData,
  initAlert,
  initTooltip,
  toastOptions,
  instanceModal,
  closeModal,
  options,
} from '../utils/index.js';
const url = 'dashboard.php?modulo=GeneralCrud&controlador=GeneralCrud&function=selectData';
const formGeneral = document.querySelector('#formGeneral');
const tblBody = document.querySelector('#tblBodyGeneralCrud');
const tblFooterGeneralCrud = document.querySelector('#tblFooterGeneralCrud');
const closeModalBtn = document.querySelector('.closeModalBtn');
const addElementModal = instanceModal('#modalGeneralCrud', options);
const btnPreview = document.createElement('button');
const btnNext = document.createElement('button');
const generalCrudUpdate = document.querySelector('#generalCrudUpdate');
const render = async (pagina = 1) => {
  const response = await getData(url, 'GET', { pagina: pagina }, false, {});
  let totalPaginas = response.data.cantidadPaginas;

  let data = response.data.items;
  let fragmentBody = document.createDocumentFragment();
  tblBody.innerHTML = '';
  data.forEach((dta) => {
    // Creo el tr y luego el td
    let tr = document.createElement('tr');
    let tdIndex = document.createElement('td');
    let tdName = document.createElement('td');
    let tdDescript = document.createElement('td');
    let tdStatus = document.createElement('td');
    let tdOptions = document.createElement('td');
    let btnEdit = document.createElement('button');
    let btnChangeStatus = document.createElement('button');
    btnChangeStatus.innerText = 'Inhabilitar';
    btnEdit.innerText = 'Editar';
    btnEdit.setAttribute('value', dta.gc_id);
    btnChangeStatus.setAttribute('value', dta.gc_id);

    tdIndex.innerText = dta.gc_id;
    tdName.innerText = dta.gc_nombre;
    tdDescript.innerText = dta.gc_descrip;

    tdOptions.append(btnEdit, btnChangeStatus);
    tr.append(tdIndex, tdName, tdDescript, tdStatus, tdOptions);

    fragmentBody.appendChild(tr);

    btnEdit.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      console.log(e.target);

      addElementModal.open();

      // buscar como capturar la data y visualizarla e implementarla en el formulario

      // campos para editar
      let nombreGeneralCrudEdit = document.querySelector('#nombreGeneralCrudUpdate');
      let descriptGeneralCrudEdit = document.querySelector('#descripcionGeneralCrudUpdate');
      let idGeneralCrudUpdate = document.querySelector('#idGeneralCrudUpdate');

      nombreGeneralCrudEdit.value = dta.gc_nombre;
      descriptGeneralCrudEdit.value = dta.gc_descrip;
      idGeneralCrudUpdate.value = dta.gc_id;

      console.log({ dta: dta });
    });

    btnChangeStatus.addEventListener('click', async (e) => {
      e.preventDefault();
      e.stopPropagation();

      console.log(e.target);
      const update = 'dashboard.php?modulo=GeneralCrud&controlador=GeneralCrud&function=delete';

      // enviar data al controlador.
      const responseChangeStatus = await sendData(update, 'DELETE', { gc_id: dta.gc_id });
    });
  });

  tblBody.appendChild(fragmentBody);
  renderPagination(totalPaginas);
};
let actualPage = 1;

// PAGINATION
const renderPagination = (totalPaginas = 0) => {
  tblFooterGeneralCrud.innerHTML = '';
  const btnPrev = document.createElement('button');
  const btnNext = document.createElement('button');
  const spanActualPages = document.createElement('span');
  const spanTotalPages = document.createElement('span');
  spanActualPages.innerText = actualPage;
  spanTotalPages.innerText = totalPaginas;
  let spanPagina = 'Pagina';
  let spanDe = 'de';
  btnPrev.setAttribute('data-Btn', 'data-btnPagination');
  btnNext.setAttribute('data-Btn', 'data-btnPagination');
  btnPrev.setAttribute('value', 'preview');
  btnNext.setAttribute('value', 'next');

  btnNext.innerText = 'NEXT';
  btnPrev.innerText = 'PREVIEW';

  tblFooterGeneralCrud.append(
    btnPrev,
    spanPagina,
    spanActualPages,
    spanDe,
    spanTotalPages,
    btnNext
  );
  const allBtn = document.querySelectorAll('[data-Btn]');
  // let actualPage = 1;

  allBtn.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();

      let valueBtn = e.target.getAttribute('value');

      // Ejecutar evento si el boton es next
      if (valueBtn === 'next') {
        actualPage++;
        if (actualPage > totalPaginas) {
          actualPage = totalPaginas;
          return;
        }
      }
      // Ejecutar evento si el boton es prev
      if (valueBtn === 'preview') {
        actualPage--;
        if (actualPage < 1) {
          actualPage = 1;
          return;
        }
      }
      // No re enviamos peticion nuevamente si estamos en la pagina 1 y si la pagina actual es mayor al total, si es mayor, no enviamos peticion
      if (actualPage < 1 || actualPage > totalPaginas) {
        return;
      }
      render(actualPage);
    });
  });
};

render();

// ---Eventos---

// Evento post crear elemento
formGeneral.addEventListener('submit', async (e) => {
  e.preventDefault();
  e.stopPropagation();

  const newFormData = new FormData(e.target);
  let dataForm = Object.fromEntries(newFormData);
  const urlForm = e.target.getAttribute('action');
  const response = await sendData(urlForm, 'POST', dataForm);
  initAlert('exito', 'info', toastOptions);

  // Reiniciar el formulario
  e.target.reset();
});

// Evento put actualizar elemento
generalCrudUpdate.addEventListener('submit', async (f) => {
  f.stopPropagation();
  f.preventDefault();

  console.log(f.currentTarget);
  const formUpdate = new FormData(f.currentTarget);
  // datos
  let data = Object.fromEntries(formUpdate);
  console.log(data);
  let actionUpdate = f.currentTarget.getAttribute('action');
  console.log(actionUpdate);
  const response = await sendData(actionUpdate, 'PUT', data);
});

// Evento eliminar (uso delete pero la idea es aplicar el inhabilitar)

closeModal(addElementModal, closeModalBtn);
