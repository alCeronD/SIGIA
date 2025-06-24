/**
 * Archivo donde podemos importar y re utilizar cosas. como crear elementos html o re utilizar cosas como peticiones futuras.
 */
export const closeModal = (modal) => {
  modal.style.display = "none";
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
