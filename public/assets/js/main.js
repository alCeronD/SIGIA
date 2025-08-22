import { Storage } from "./utils/Storage.js";
// Inicializar selects de materialize.
document.addEventListener('DOMContentLoaded', function () {
  //Tooltips
  let tooltip = document.querySelectorAll('.tooltipped');
  M.Tooltip.init(tooltip);

  let elems = document.querySelectorAll('select:not(.browser-default)');
  M.FormSelect.init(elems);
  //Inicializar los inputs
  M.updateTextFields();
  //Definir el resize del textarea del campo descripción del modulo de roles.
  M.textareaAutoResize(document.getElementById('rol_descripcionInput'));

  //buscar los modales
  const elemsModals = document.querySelectorAll('.modal');
  //inicializar los modales
  M.Modal.init(elemsModals); 
  M.FormSelect.init(document.querySelectorAll('select'));

});

const responseStatus = Storage.getValue("sessionStatus");

// Uso este evento para validar el estado de la sessión, si es falso, cierro la sesión de las ventanas.
window.addEventListener('storage', (f)=>{
  const newValueStorage = f.newValue;
  const oldValueStorage = f.oldValue;

  if (newValueStorage === 'false') {
    localStorage.removeItem('sessionStatus');
    window.location.href = '/proyecto_sigia/index.php';
  }
});