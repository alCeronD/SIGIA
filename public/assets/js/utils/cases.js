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


const today = new Date();
//Valido que la fecha sea inicial como 00:00:00
today.setHours(0,0,0,0);
//Configuración de las opciones de los inputTipoDate.
export const opcionesDatepicker = {
  // Fecha mínima que se puede seleccionar
  minDate: today, // 
  //Hago que la fecha del día de hoy se seleccione por defecto.
  setDefaultDate: true,
  // Fecha máxima que se puede seleccionar
  maxDate: null, // new Date(2030, 11, 31)

  // Si permite cambiar el mes desde un selector desplegable
  showMonthAfterYear: false,

  // Mostrar selector de meses (dropdown)
  showMonthsShort: false,
  showMonthDropdown: true,

  // Mostrar selector de años (dropdown) y cuántos años mostrar
  yearRange: 10, // Puede ser número o array: [1900, 2025]

  // Primer día de la semana (0 = domingo, 1 = lunes, etc.)
  firstDay: 0,

  // Formato de la fecha para mostrar (ver más abajo)
  format: 'mmm dd, yyyy', // Ej: Jan 01, 2025

  // Habilitar/Deshabilitar selección de fechas
  disableWeekends: false,
  //Me permite validar que no se seleccionen fechas anteriores a la fecha actual.
  disableDayFn: function (date){
    return date < today;
  },


  // Idioma (traducciones)
  i18n: {
    cancel: 'Cancelar',
    clear: 'Limpiar',
    done: 'Ok',
    previousMonth: '‹',
    nextMonth: '›',
    months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
    weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S']
  },

  // Si el selector debe cerrarse automáticamente al seleccionar
  autoClose: false,

  // Si el input debe tener selección de fechas (sin abrir modal)
  defaultDate: null, // new Date()
  setDefaultDate: false,

  // Si se abre el calendario al hacer focus
  showClearBtn: false,

  // Comportamiento de animación
  container: null, // Por defecto es <body>
  onSelect: function(date) {},
  onClose: function() {},
  onOpen: function() {},
  onDraw: function() {}
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

export const instanceDate = (selector = '.datepicker', options ={})=>{
  let datePicker = document.querySelector(selector);
  //TODO: crear un objeto con las opciones personalizadas, usar object.assing para este proceso.
  return M.Datepicker.init(datePicker,options);
};

export default {
  closeModal,
  openModal,
  createI,
  options,
};
