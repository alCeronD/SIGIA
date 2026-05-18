import { getData, sendData, initAlert, initTooltip, toastOptions } from '../utils/index.js';
const url = 'dashboard.php?modulo=GeneralCrud&controlador=GeneralCrud&function=selectData';
const formGeneral = document.querySelector('#formGeneral');
const tblBody = document.querySelector('#tblBodyGeneralCrud');
const tblFooterGeneralCrud = document.querySelector('#tblFooterGeneralCrud');
const btnPreview = document.createElement('button');
const btnNext = document.createElement('button');
const render = async () => {
  const response = await getData(url, 'GET', {}, false, {});

  let data = response.data.items;
  let fragmentBody = document.createDocumentFragment();
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

  pagination();
};
// PAGINATION
const pagination = () => {
  const btnPrev = document.createElement('button');
  const btnNext = document.createElement('button');

  btnNext.innerText = '>';
  btnPrev.innerText = '<';

  tblFooterGeneralCrud.append(btnPrev, btnNext);
};

render();

// Evento submit
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
