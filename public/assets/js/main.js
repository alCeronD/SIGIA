import { Storage } from "./utils/Storage.js";
// Inicializar selects de materialize.
document.addEventListener('DOMContentLoaded', function () {
  M.updateTextFields();
  // //Definir el resize del textarea del campo descripción del modulo de roles.
  M.textareaAutoResize(document.getElementById('rol_descripcionInput'));

  //buscar los modales
  const elemsModals = document.querySelectorAll('.modal');
  //inicializar los modales
  M.Modal.init(elemsModals);

});

const responseStatus = Storage.getValue("sessionStatus");

// Uso este evento para validar el estado de la sessión, si es falso, cierro la sesión de las ventanas.
window.addEventListener('storage', (f) => {
  const newValueStorage = f.newValue;
  const oldValueStorage = f.oldValue;

  if (newValueStorage === 'false') {
    localStorage.removeItem('sessionStatus');
    window.location.href = '/index.php';
  }
});