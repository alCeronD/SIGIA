import { initAlert, mostrarConfirmacion, toastOptions, sendData, Storage } from './utils/index.js';
const btnCerrarSesion = document.querySelector('#btnCerrarSesion');

document.addEventListener('DOMContentLoaded', () => {
  const elemsModals = document.querySelectorAll('.modal');
  M.Modal.init(elemsModals);
});