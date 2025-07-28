import { closeModal, instanceModal, openModal, options } from "../utils/cases.js";

const btnEditar = document.querySelectorAll('.btnEditar');
const modalEditar = document.querySelector('#modalEditar');
const closeModalBtn = document.querySelector('.closeModalBtn');

options.outDuration
options.opacity
const instanModal = instanceModal('#modalEditar',{"inDuration":options.inDuration,"outDuration":options.outDuration,"opactity":options.opacity});

console.log(btnEditar);


btnEditar.forEach((btnEdit)=>{
    btnEdit.addEventListener('click',(e)=>{
        e.preventDefault();
        e.stopPropagation();

        let id = e.target.getAttribute('data-id');
        let descripcion = e.target.getAttribute('data-desc');
        let nombre = e.target.getAttribute('data-nombre');

        let inputId = document.querySelector('#modalEditar #modal_rol_id');
        let inputNombre = document.querySelector('#modalEditar #modal_rol_nombre');
        let inputDescript = document.querySelector('#modalEditar #modal_rol_descripcion');
        console.log({inputId,inputNombre,inputDescript});

        inputId.value = id;
        inputNombre.value = nombre;
        inputDescript.value = descripcion;

        //Como cambiamos el valor del formulario, necesitamos volver a reiniciar los input del formulario
        M.updateTextFields();
        
        instanModal.open();
    });

});

closeModal(instanModal,closeModalBtn);