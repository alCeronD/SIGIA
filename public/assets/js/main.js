import { cancelProcess } from './utils/const.js';
import { Storage, mostrarConfirmacion, initAlert, initTooltip, sendData } from './utils/index.js';
// Inicializar selects de materialize.
document.addEventListener('DOMContentLoaded', function () {
  M.updateTextFields();
  //Definir el resize del textarea del campo descripción del modulo de roles.
  M.textareaAutoResize(document.getElementById('rol_descripcionInput'));

  //buscar los modales
  const elemsModals = document.querySelectorAll('.modal');
  //inicializar los modales
  M.Modal.init(elemsModals);
});

const responseStatus = Storage.getValue('sessionStatus');
// Uso este evento para validar el estado de la sessión, si es falso, cierro la sesión de las ventanas.
window.addEventListener('storage', (f) => {
  const newValueStorage = f.newValue;
  const oldValueStorage = f.oldValue;

  if (newValueStorage === 'false') {
    localStorage.removeItem('sessionStatus');
    window.location.href = '/index.php';
  }
});

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
        let dta = e.target.getAttribute('data-logOut');
        let data = {
          action: dta,
        };

        const response = await sendData(url, 'POST', data);

        if (response.status) {
          Storage.addValue({ key: 'sessionStatus', item: 'false' });
          window.location.href = response.data.redirect;
        }
      } catch (error) {
        console.log(error);
      }
    });
  });
});
