// Inicializar selects de materialize.
document.addEventListener('DOMContentLoaded', function () {
  let elems = document.querySelectorAll('select:not(.browser-default)');
  M.FormSelect.init(elems);
  //Inicializar los inputs
  M.updateTextFields();
});

document.querySelectorAll('.horizontalMenu > li > a').forEach(link =>{
  link.addEventListener('click', (e)=>{
    e.preventDefault();
    parent.classList.toggle('menu-open');
  });

});