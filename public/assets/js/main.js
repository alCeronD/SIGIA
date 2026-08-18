import { cancelProcess, METHOD } from './utils/const.js';
import {
  mostrarConfirmacion,
  initAlert,
  initTooltip,
  HttpData,
  InitComponents,
  StorageHelper,
} from './utils/index.js';

// Inicializar selects de materialize.
document.addEventListener('DOMContentLoaded', function () {
  // inicializar selects
  InitComponents.initSelect();
  //inicializar los modales
  InitComponents.initModals();
});

const Http = new HttpData();

// const responseStatus = Storage.getValue('sessionStatus');
const responseStatus = StorageHelper.getValue('sessionStatus');

// Uso este evento para validar el estado de la sessión, si es falso, cierro la sesión de las ventanas.
window.addEventListener('storage', (f) => {
  const newValueStorage = f.newValue;
  const oldValueStorage = f.oldValue;

  if (newValueStorage === 'false') {
    localStorage.clear();
    window.location.href = '/index.php';
  }
});

// metodo para cerrar la sesion desde el header.
const btnClose = document.querySelectorAll('[data-btnClose]');
btnClose.forEach((btnCerrarSesion) => {
  btnCerrarSesion.addEventListener('click', (e) => {
    e.stopPropagation();
    e.preventDefault();

    mostrarConfirmacion('Cerrar sesión', '¿Deseas salir de la aplicación?', async (r) => {
      try {
        if (!r) return;

        const url = e.target.getAttribute('data-Url');
        const response = await Http.sendData(url, METHOD.POST);
        if (!response.status)
          throw new Error('Error al cerrar la sesion, seras re dirigido al inicio', response.url);
        localStorage.clear(); //Eliminamos todos los datos de localStorage
        window.location.replace(response.data.redirect);
      } catch (error) {
        initAlert(error.message, 'info');
        window.location.replace(error.url);
      }
    });
  });
});
