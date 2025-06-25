/**
 * Archivo donde podemos importar y re utilizar cosas. como crear elementos html o re utilizar cosas como peticiones futuras.
 */
export const closeModal = (modal,btn) => {

  //Valido primero que lo que llegue exista.
  if (!modal || !btn) {
    return;
  }

  if (!btn) {
    modal.close();
  }
  
  btn.addEventListener('click', (e)=>{
    e.preventDefault();
    e.stopPropagation();
    // modal.close();

    //Valido si el tipo de lo que voy a ejecutar es una función.
    if (typeof modal.close === 'function') {
      modal.close();
      
    }else{
      //En caso de que no sea una función, esta debe de ejecutar si o si cambiar el style del modal de flex a none, para que no sea visible.
      modal.style.display = 'none';
    }

  });
};

export const openModal = (modal) => {
  modal.style.display = "flex";
};

export const createI = () => {
  const i = document.createElement("i");
  //Iconos para materialize, cambiar la clase si es para otro.
  i.setAttribute("class", "material-icons");
  return i;
};

export const createBtn = ()=>{
  const button = document.createElement('button');
  return button;
}


//Configuración de las opciones del modal
export const options = {
  opacity: 0.7,
  inDuration: 300,
  outDuration: 200,
  dismissible: true,
  startingTop: "4%",
  endingTop: "10%",
  onOpenStart: () => console.log("Modal se está abriendo"),
  onCloseEnd: () => console.log("Modal se cerró completamente"),
};

//Iniciar modales
export const instanceModal = (selector,options = {}) => {
  let elements = document.querySelector(selector);
  //Si el selector no existe, devolver un null.
  if (!elements) {
    return null;
  }
  //Devuelve un nodo de todos los modales que contenga la clase .modal
  return M.Modal.init(elements, options);
};

export default {
  closeModal,
  openModal,
  createI,
  options,
};
