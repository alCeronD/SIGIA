import { closeModal, instanceModal, openModal, options } from "../utils/cases.js";

// function abrirModal(id, nombre, descripcion) {
//     document.getElementById('modal_rol_id').value = id;
//     document.getElementById('modal_rol_nombre').value = nombre;
//     document.getElementById('modal_rol_descripcion').value = descripcion;
//     document.getElementById('modalEditar').classList.remove('hidden');
// }

// function cerrarModal() {
//     document.getElementById('modalEditar').classList.add('hidden');
// }

// Opcional: cerrar modal si se hace clic fuera del contenido
// document.getElementById('modalEditar').addEventListener('click', function(e) {
//     if (e.target === this) {
//         // cerrarModal();
// closeModal(instanModal,closeModalBtn);

//     }
// });

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
        
        instanModal.open();
    });

});

closeModal(instanModal,closeModalBtn);