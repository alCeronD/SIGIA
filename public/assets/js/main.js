import { cancelProcess } from './utils/const.js';
import {
  mostrarConfirmacion,
  initAlert,
  initTooltip,
  sendData,
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

// const responseStatus = Storage.getValue('sessionStatus');
const responseStatus = StorageHelper.getValue('sessionStatus');

// Uso este evento para validar el estado de la sessión, si es falso, cierro la sesión de las ventanas.
window.addEventListener('storage', (f) => {
  const newValueStorage = f.newValue;
  const oldValueStorage = f.oldValue;

  if (newValueStorage === 'false') {
    localStorage.removeItem('sessionStatus');
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
      if (!r) {
        initAlert(cancelProcess, 'info');
        return;
      }
      try {
        const url = e.target.getAttribute('data-Url');
        let dta = e.target.getAttribute('data-logout');
        let data = {
          action: dta,
        };

        const response = await sendData(url, 'POST', data);
        console.log(response);

        if (response.status) {
          StorageHelper.addValue({ key: 'sessionStatus', item: 'false' });
          window.location.href = response.data.redirect;
        }
      } catch (error) {
        console.log(error);
      }
    });
  });
});
