/**
 * Archivo donde podemos importar y re utilizar cosas. como crear elementos html o re utilizar cosas como peticiones futuras.
 */
export const closeModal = (modal) => {
    modal.style.display = 'none';
    
};

export const openModal = (modal) =>{
    modal.style.display = 'flex';
    
}

export const createI = () =>{
    const i = document.createElement('i');
    //Iconos para materialize, cambiar la clase si es para otro.
    i.setAttribute('class','material-icons');
    return i;
}

export default {
    closeModal,
    openModal,
    createI
}