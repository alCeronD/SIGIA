import {Ajax} from '../libraries/ajax.js';
//La idea es capturar la información aca desde javascript y enviarla al controlador.

//Formulario
const formulario = document.querySelector('#formArea');
const objAjax = new Ajax();
const table = 'areas';

// Apenas cargue la información, ejecutar la función fetchData para traer la info usando ajax.
document.addEventListener('DOMContentLoaded',()=>{

    objAjax.request.open('GET',`modules/configModules/api/apiConfigModules.php?tableName=${encodeURIComponent(table)}`);

    //Aca va la respuesta y el renderizado de los datos en la tabla.
    objAjax.request.onload = ()=>{
        //Capturo la respuesta
        let response = JSON.parse(objAjax.request.responseText);
        let data = response.data

        if (objAjax.request.status) {
            console.log(data);
        }
    }

    objAjax.request.send();

});

formulario.addEventListener('submit',(event)=>{
    event.preventDefault();
    event.stopPropagation();

    let form = new FormData(formulario);
    let data = JSON.stringify(Object.fromEntries(form));
    console.log(data);



});