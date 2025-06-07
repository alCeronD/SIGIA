export const closeModal = (modal, btn) => {
    btn.addEventListener('click', () => {
        modal.style.display = 'none';

    });

    
};

export default {
    closeModal
}