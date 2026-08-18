import {
  initAlert,
  validationRules,
  StorageHelper,
  HttpData,
  METHOD,
} from '../../../../public/assets/js/utils/index.js';
import { loginForm } from './Selectors-Login.js';
const fetchData = new HttpData();

// Proceso Storage para re direccionar inmediatamente al usuario en caso de que este tenga su inicio de sesión.
window.addEventListener('storage', (g) => {
  const newValue = g.newValue;
  const key = g.key;
  if (key === 'sessionStatus' && newValue === 'true') {
    window.location.href =
      '/Core/dashboard.php?modulo=Dashboard&controlador=Dashboard&function=dashboard';
  }
});

document.addEventListener('DOMContentLoaded', function () {
  // const loginForm = document.getElementById('loginForm');
  const documInput = document.getElementById('docum');
  const passInput = document.getElementById('pass');

  documInput.addEventListener('change', (e) => {
    const docum = e.target.value.trim();
    e.stopPropagation();
    if (!validationRules.documento.regex.test(docum)) {
      initAlert(validationRules.documento.message, 'info');
      loginForm.reset();
      documInput.focus();
      return;
    }
  });

  loginForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    e.stopPropagation();

    const url = loginForm.getAttribute('action');
    const formData = new FormData(loginForm);
    const dataObj = Object.fromEntries(formData);

    const docum = dataObj['docum'].trim();
    const pass = dataObj['pass'].trim();

    try {
      if (pass.length === 0 && docum.length === 0) {
        initAlert('Por favor llene todos los campos', 'info');
        return;
      }

      if (pass.length === 0) {
        initAlert('La contraseña es obligatoria', 'info');
        return;
      }

      if (docum.length === 0) {
        initAlert('El No de documento es obligatorio', 'info');
        return;
      }
      const responseLogin = await fetchData.sendData(url, METHOD.POST, dataObj);
      if (!responseLogin.status) {
        throw new Error(responseLogin.message);
      }

      StorageHelper.addValue({ key: 'sessionStatus', item: 'true' });
      window.location.replace(responseLogin.data.url); //usamos replace para evitar el uso del boton de atras del navegador.
    } catch (error) {
      initAlert(error.message, 'error');
      return;
    }
  });
});
