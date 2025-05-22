import { Ajax } from "./../libraries/ajax";

//La idea es capturar la información aca desde javascript y enviarla al controlador.
document.addEventListener('DOMContentLoaded',()=>{
    const formulario = document.querySelector('#areaForm');
    console.log('hello world');

    const objAjax = new Ajax();

    objAjax.request.open('GET','');


});