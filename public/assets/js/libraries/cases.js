// Podemos crear Más funciones para exportar

export const closeModal = (modal, btn) => {
    btn.addEventListener('click', () => {
        modal.style.display = 'none';
    });
};

// export const openModal = (modal,btn) =>{
//     btn.addEventListener('click', () => {
//         modal.style.display = 'flex';
//     });
// }
export const openModal = (modal) =>{
    modal.style.display = 'flex';

}

export default {
    closeModal,
    openModal
}