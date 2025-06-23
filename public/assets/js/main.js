// Inicializar materialize.
document.addEventListener('DOMContentLoaded', function () {
  let elems = document.querySelectorAll('select:not(.browser-default)');
  M.FormSelect.init(elems);
});

console.log('hello world main.js');