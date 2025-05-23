// const title = document.getElementById('menuTitle');
// const menuTitleConsult = document.getElementById('menuTitleConsult');

// console.log({title,menuTitleConsult});
// menuTitleConsult.innerText = 'Consultar Prestamos';
// title.innerText = 'Registrar solicitud';
// table.classList.add('table');
// table.setAttribute('scope','row');


const btnSubmit = document.getElementById('btnSubmit');
btnSubmit.innerText = 'Reservar';

const inputElement = document.querySelectorAll('.inputForm input, .inputForm select');
const table = document.querySelector('#tableElements');
const modalAddElements = document.querySelector('#modalAddElements');
const btnAddElements = document.getElementById('btnAddElements');
const modalTitle = document.querySelector('#modalTitle');
btnAddElements.innerText = '+';
modalTitle.innerText = 'Elementos disponibles';

btnAddElements.addEventListener('click',(btnTarget)=>{
    btnTarget.preventDefault();
    btnTarget.stopPropagation();
    console.log(btnTarget.target);

    //visualizar modal.
    modalAddElements.style.display = "flex";
    modalAddElements.style.justifyContent = "center";
    modalAddElements.style.flexDirection = "column";


});



//Aca ada elemento input estoy agregando la clase form-control de boostrap, actualmente no se aplica porque no está el archivo bootstrap funcionando.
inputElement.forEach(element =>{

    /**
     * 
     * aclaracion hecha por mi = type sirve para saber que tipo de input es, si es email, number, entre otros.
     * tagName para saber que etiqueta es, si es un select, una tabla o un input.
     */

    if (element.tagName === 'SELECT') {
        element.classList.add('form-select');
    } else if (element.tagName === 'INPUT') {
        element.classList.add('form-control');
    }

});

