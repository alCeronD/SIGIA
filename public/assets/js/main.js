// Inicializar selects de materialize.
document.addEventListener('DOMContentLoaded', function () {
  let elems = document.querySelectorAll('select:not(.browser-default)');
  M.FormSelect.init(elems);
  //Inicializar los inputs
  M.updateTextFields();
  //Definir el resize del textarea del campo descripción del modulo de roles.
  M.textareaAutoResize(document.getElementById('rol_descripcionInput'));
});

document.querySelectorAll('.horizontalMenu > li > a').forEach(link =>{
  link.addEventListener('click', (e)=>{
    e.preventDefault();
    parent.classList.toggle('menu-open');
  });

});