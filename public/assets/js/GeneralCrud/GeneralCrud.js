import { getData, sendData, initAlert, initTooltip, toastOptions } from '../utils/index.js';

const formGeneral = document.querySelector('#formGeneral');

const render = async () => {
  const dataR = await getData(url, 'GET', {}, false, {});
};

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
