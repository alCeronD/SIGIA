import { getData, sendData, initAlert, initTooltip, toastOptions } from '../utils/index.js';
const url = 'dashboard.php?modulo=GeneralCrud&controlador=GeneralCrud&function=selectData';
const formGeneral = document.querySelector('#formGeneral');
const tblBody = document.querySelector('#tblBodyGeneralCrud');
const tblFooterGeneralCrud = document.querySelector('#tblFooterGeneralCrud');
const btnPreview = document.createElement('button');
const btnNext = document.createElement('button');
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

    tdIndex.innerText = dta.gc_id;
    tdName.innerText = dta.gc_nombre;
    tdDescript.innerText = dta.gc_descrip;

    tdOptions.append(btnEdit, btnChangeStatus);
    tr.append(tdIndex, tdName, tdDescript, tdStatus, tdOptions);

    fragmentBody.appendChild(tr);
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

// Eventos

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
