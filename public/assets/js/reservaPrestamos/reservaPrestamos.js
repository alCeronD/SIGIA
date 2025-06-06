const btnSubmit = document.getElementById('btnSubmit');
btnSubmit.innerText = 'Reservar';

const inputElement = document.querySelectorAll('.inputForm input, .inputForm select');
const table = document.querySelector('#tableElements');
const modalAddElements = document.querySelector('#modalAddElements');
const btnAddElements = document.getElementById('btnAddElements');
const modalTitle = document.querySelector('#modalTitle');
const btnCloseButton = document.querySelector('.close');
btnAddElements.innerText = 'Seleccionar elementos';
modalTitle.innerText = 'Elementos disponibles';

btnAddElements.addEventListener('click',(btnTarget)=>{
    console.log('hello world');
    btnTarget.preventDefault();
    btnTarget.stopPropagation();

    //visualizar modal.
    modalAddElements.style.display = "flex";
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

//TODO: mejorar código y validar cuando vaya a cerrar la ventana.
btnCloseButton.addEventListener('click', (event)=>{
    event.stopPropagation();
    event.preventDefault();

    modalAddElements.style.display = 'none';
});