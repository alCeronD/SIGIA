export const closeModal = (modal, btn = false) => {
    btn.addEventListener('click', () => {
        modal.style.display = 'none';

    });

    
};

export default {
    closeModal
}