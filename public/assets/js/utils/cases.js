/**
 * Archivo donde podemos importar y re utilizar cosas. como crear elementos html
 */
export const closeModal = (modal, btn, onCloseCallback) => {
  //Valido que el modal si haya sido enviado
  if (!modal) {
    return;
  }

  if (!btn) {
    modal.close();
    return;
  }

  const executeClose =()=>{
         //Valido si el tipo de lo que voy a ejecutar es una función.
      if (typeof modal.close === 'function') {
        modal.close();
        
      }else{
        //En caso de que no sea una función, esta debe de ejecutar si o si cambiar el style del modal de flex a none, para que no sea visible.
        modal.style.display = 'none';
      }
    //Si el tipo de la función closeCallback y se paso por parámetro, ejecutarla.
    if (typeof onCloseCallback === 'function') {
      onCloseCallback();
    }
  };


  btn.addEventListener('click', (e)=>{
      e.preventDefault();
      e.stopPropagation();

      executeClose();
  });
};

export const openModal = (modal) => {
  modal.style.display = "flex";
};

export const createI = (text) => {
  const i = document.createElement("i");
  i.setAttribute("class", "material-icons");
  i.style.pointerEvents = "none";
  i.innerText = text;
  return i;
};

export const createSpan = () => {
  const span = document.createElement("span");
  // span.setAttribute("class", "material-symbols-outlined");
  return span;
};

export const createBtn = (valueClass = "") => {
  const button = document.createElement("button");
  button.setAttribute("class", valueClass);
  return button;
};

export const addClassItem = (item, valuesClass = {})=>{

  if (!valuesClass) {
    return;
  }

  //Como objeto, puedo buscar una forma de hacerlo con arreglo.
  Object.values(valuesClass).forEach((val)=>{
    // item.classList.add(val);
    val.split(" ").forEach(cl => item.classList.add(cl));
  });
}

//Crear el horario de la reserva.
export const instanceDateTime = (
  selector = ".timepicker",
  timePickerOptions = {}
) => {
  if (!selector) return null;

  let elems = document.querySelector(selector);
  return M.Timepicker.init(elems, options);
};

//Iniciar modales
export const instanceModal = (selector, options = {}) => {
  let elements = document.querySelector(selector);
  const existInstance = M.Modal.getInstance(elements);
  if (existInstance) {
    existInstance.destroy();
  }

  //Si el selector no existe, devolver un null.
  if (!elements) {
    return null;
  }
  //Devuelve un nodo de todos los modales que contenga la clase .modal
  return M.Modal.init(elements, options);
};

//Crear una fecha.
export const instanceDate = (selector = ".datepicker", options = {}) => {
  let datePicker = document.querySelector(selector);
  //TODO: crear un objeto con las opciones personalizadas, usar object.assing para este proceso.
  return M.Datepicker.init(datePicker, options);
};

//Crear el tooltip al boton TODO: crear el tooltip
export const initTooltip = (
  btn,
  options = {},
  message = "",
  position = "left"
) => {
  if (!btn) return;
  //Destruimos el tooltip que existe asignado al boton, esto para evitar que se vuelva a sobre escribir.
  const instanceToltip = M.Tooltip.getInstance(btn);
  //Destruyo la instancia
  if (instanceToltip) {
    instanceToltip.destroy();
  }

  let newMessage = message.replace(/\n/g, "<br>");
  const mergedOptions = {
    ...options,
    html: newMessage,
    position: position,
  };

  if (!btn.classList.contains("tooltipped")) {
    btn.classList.add("tooltipped");
  }

  //Si trabajamos html, debemos eliminar el data-tooltip.
  btn.removeAttribute("data-tooltip");
  //btn.setAttribute('data-tooltip', message);

  btn.setAttribute("data-position", position);

  //Lo re inicializo.
  M.Tooltip.init(btn, mergedOptions);
};

// Reemplazar los saltos de línea de un texto por etiquetas br html.
export const replaceln = (message) => {
  // la letra g significa "global", es decir, se debe aplicar a todos.
  return message.replace(/\n/g, "<br>");
};

//Configuración de las opciones del modal
export const options = {
  opacity: 0.7,
  inDuration: 300,
  outDuration: 200,
  dismissible: false,
  startingTop: "4%",
  endingTop: "10%",
  onOpenStart: () => {},
  onCloseEnd: () => {},
};

export const tooltipOptions = {
  exitDelay: 0, // Tiempo (ms) que tarda en desaparecer el tooltip al salir del elemento
  enterDelay: 200, // Tiempo (ms) antes de que aparezca el tooltip al pasar el cursor
  // html: null,             // Contenido HTML opcional (string o nodo HTML)
  margin: 5, // Espacio entre el tooltip y el elemento objetivo
  inDuration: 300, // Duración de la animación de entrada (ms)
  outDuration: 250, // Duración de la animación de salida (ms)
  position: "bottom", // Posición del tooltip: 'top', 'right', 'bottom', 'left'
  transitionMovement: 10, // Movimiento vertical durante la transición (solo para 'top' y 'bottom')
};

const today = new Date();
//Valido que la fecha sea inicial como 00:00:00
today.setHours(0, 0, 0, 0);
export const opcionesDatepicker = {
  // Fecha mínima que se puede seleccionar
  minDate: today, //
  //Hago que la fecha del día de hoy se seleccione por defecto.
  setDefaultDate: true,
  // Fecha máxima que se puede seleccionar
  maxDate: null,

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
  format: "yyyy dd mmm",

  // Habilitar/Deshabilitar selección de fechas
  disableWeekends: false,
  //Me permite validar que no se seleccionen fechas anteriores a la fecha actual.
  disableDayFn: function (date) {
    return date < today;
  },

  // Idioma Español.
  i18n: {
    cancel: "Cancelar",
    clear: "Limpiar",
    done: "Ok",
    previousMonth: "‹",
    nextMonth: "›",
    months: [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre",
    ],
    monthsShort: [
      "Ene",
      "Feb",
      "Mar",
      "Abr",
      "May",
      "Jun",
      "Jul",
      "Ago",
      "Sep",
      "Oct",
      "Nov",
      "Dic",
    ],
    weekdays: [
      "Domingo",
      "Lunes",
      "Martes",
      "Miércoles",
      "Jueves",
      "Viernes",
      "Sábado",
    ],
    weekdaysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
    weekdaysAbbrev: ["D", "L", "M", "M", "J", "V", "S"],
  },

  // Si el selector debe cerrarse automáticamente al seleccionar
  autoClose: false,

  // Si el input debe tener selección de fechas (sin abrir modal)
  defaultDate: null, // new Date()

  // Si se abre el calendario al hacer focus
  showClearBtn: false,

  // Comportamiento de animación
  container: null, // Por defecto es <body>
  onSelect: function (date) {},
  onClose: function () {},
  onOpen: function () {},
  onDraw: function () {},
};


/**
 * Description - Función para formatear la fecha del input del formulario, solo sirven para materialize.
 *
 * @param {*} fecha 
 * @param {boolean} [isNewDate=false] 
 * @returns {*} 
 */
export const dateISOFormat = (fecha, isNewDate = false) => {
  if (!fecha) {
    return null;
  }
  const [year, day, month] = fecha.split(" ");
  const months = {
  Ene: "01",
  Feb: "02",
  Mar: "03",
  Abr: "04",
  May: "05",
  Jun: "06",
  Jul: "07",
  Ago: "08",
  Sep: "09",
  Oct: "10",
  Nov: "11",
  Dic: "12",
};

  const monthFormate = months[month];
  const newDate = `${year}-${monthFormate}-${day.padStart(2, "0")}`;
  //Con padStart se rellena el valor en caso de que no hayan dos digitos, si hay 1 solo el le coloca un 0, si hay un valor de 10 en adelante, no hace nada y devuelve el nro 10.
  return isNewDate ? new Date(newDate) : newDate;
};


/**
 * Objeto con las opciones de las alertas.
 *
 * @type {{ displayLength: number; classes: string; inDuration: number; outDuration: number; activationPercent: number; }}
 */
export const toastOptions = {
  displayLength: 4000,
  classes: "",
  inDuration: 300,
  outDuration: 500,
  activationPercent: 0.8,
};

/**
 * Description - Función para visualizar alertas al usuario.
 *
 * @param {string} [message=""] - Mensaje de la alerta
 * @param {string} [type="info"] - Tipo de mensaje para una mejor experiencia de usuario
 * @param {{}} [options={}] - Objeto para enviar las opciones requeridas
 */
export const initAlert = (message = "", type = "info", options = {}) => {
  const toastElements = document.querySelectorAll(".toast");
  // Alertas Máximas, 3 en pantalla.
  const maxToast = 3;

  // Muestra la alerta.
  const alertInstance = M.toast({
    html: message,
    ...options,
  });

  // Coloco el valor de el, porque materialize devuelve una propiedad el HTML DEL DOOM, esta propiedad se llama el. que tecnicamente es la alerta en si.
  if (!alertInstance.el.classList.contains(`toast-${type}`)) {
    alertInstance.el.classList.add(`toast-${type}`);
  }

  if (toastElements.length >= maxToast) {
    toastElements[0].remove();
    return;
  }


};

/**
 * Estados de los prestamos
 * @property string - el nombre del estado
 * @value int - el valor del prestamo
 */
export const statusLoans = {
  validado: 1,
  rechazado: 2,
  porValidar: 3,
  finalizad: 4,
  cancelado: 5,
};

export const typeLoans = {
  inmediata: 1,
  solicitud: 2,
};

//Con esta función defino la estructura para FINALIZAR EL PRESTAMO y VALIDAR LA SOLICITUD., es cuando el prestamo me ya va a ser devuelto.
export const setReserva = (attribute = "", data = {}, elementos = {},target,action = "finalizar") => {
  const codigoReserva = Number(target.getAttribute([`${attribute}`]));
  const dataResult = data.find((dta) => Number(dta.codigo) === codigoReserva);

  if (dataResult && codigoReserva && elementos[codigoReserva]) {
    const reservaConElementos = elementos[codigoReserva];
    const elementosDeReserva = reservaConElementos.elementos;

    return {
      codigoReserva: reservaConElementos.reserva.codigo,
      elementos: elementosDeReserva,
      dataUsuario: dataResult
    }

  }
};

/**
 * Description Función para crear los checkbox y renderizarlos dinámicamente, se envía como parámetro el serial y placa y se adjunta como data en el checkbox, esta función solo funciona si se está usando checkboxes de materialize...
 *
 * @param {*} seriales 
 * @param {*} placa 
 * @returns {*} 
 */
export const createCheckbox = (seriales, placa) => {
  let p = document.createElement('p');
  let label = document.createElement('label');
  let input = document.createElement('input');
  let span = document.createElement('span');

  input.setAttribute('type', 'checkbox');
  input.setAttribute('name', 'serialCheckbox');
  input.classList.add('filled-in'); 
  
  if (Array.isArray(seriales)) {
    input.setAttribute('data-seriales', JSON.stringify(seriales));
    input.setAttribute('data-placa',JSON.stringify(placa));

     const ultimaSerie = seriales[seriales.length - 1]?.serie || '';
    span.innerText = `Asociar desde ${ultimaSerie}`;
  } else {
    input.setAttribute('data-seriales', seriales || '');
    span.innerText = 'Crear serial';
  }

  label.appendChild(input);
  label.appendChild(span);
  p.appendChild(label);

  return p;
};

/**
 * Description Función para capturar el selector y devolver su selector.
 *
 * @param {*} selector - Nombre del selector que se requiere capturar
 * @returns {*} 
 */
export const getSelector = (selector) =>{
  const el = document.querySelector(selector);

  if (!el) {
    console.warn("elemento no idenfificado");
  }

  return el;
}

// Función para crear un checkbox generico con materialize, esta función debo implementar un ciclo en caso de que se requiera adicionar más clases o más data-*
export const createCheckboxGeneric = ({ text = '', id = '', name = '', value = '', checkedValue = false, classItem, data = ''} = {}) => {
  const div = document.createElement('div');
  div.classList.add('checkbox-container');

  const labelText = document.createElement('span');
  labelText.innerText = text;

  const labelCheckbox = document.createElement('label');
  const inputCheckbox = document.createElement('input');
  inputCheckbox.setAttribute('type', 'checkbox');
  if (id) inputCheckbox.id = id;
  if (name) inputCheckbox.name = name;
  if (value) inputCheckbox.value = value;
  if (checkedValue) inputCheckbox.checked = checkedValue;
  if (classItem) inputCheckbox.classList.add(classItem);
  if(data) inputCheckbox.setAttribute('data-idModulo', data);

  const span = document.createElement('span'); // Materialize uses this

  labelCheckbox.appendChild(inputCheckbox);
  labelCheckbox.appendChild(span);

  div.appendChild(labelText);     // Texto a la izquierda
  div.appendChild(labelCheckbox); // Checkbox a la derecha

  return div;
};

/**
 * Description placeholder - Función para validar los campos obligatorios del formulario.
 *
 * @param {*} formData - new Form Data del formulario
 * @param {*} campos  - Campos opcionales en caso de que no sean obligatorios para el usuario
 * @param {*} mapForm - Objeto del formulario para validar que la información del Form Data tenga información.
 * @returns {boolean} 
 */
export const validateFormData = ({formData, campos, mapForm}={}) =>{
  for (const [key, value] of formData.entries()) {
    const isEmpty = !value || value.toString().trim() === "";
    // pasamos por referencia los valores opcionales.
    const camposOpcionales = campos;
    if (isEmpty && !camposOpcionales.includes(key)) {
      initAlert(
        `El campo "${mapForm[key]}" debe ser diligenciado`,
        "info",
        toastOptions
      );
      return false;
    }
  }
  return true;
}

/**
 * Visualiza un modal de confirmación antes de una ejecución, contiene 2 botones, aceptar o cancelar.
 * Ejecuta una función callback dependiendo de la respuesta del usuario.
 *
 * @param {string} titulo - Título que se mostrará en el modal.
 * @param {string} mensaje - Mensaje HTML que se mostrará en el cuerpo del modal.
 * @param {function(boolean): void} callback - Función que se ejecuta al hacer clic en Aceptar o Cancelar.
 *     Recibe `true` si el usuario acepta, `false` si cancela.
 */
export const mostrarConfirmacion = (titulo, mensaje, callback)=>{
// Rellenar el contenido
  document.getElementById("modalConfirmacionTitulo").textContent = titulo;
  document.getElementById("modalConfirmacionMensaje").innerHTML = mensaje;

  // Obtener instancia y abrir el modal
  const modalElem = document.getElementById("modalConfirmacion");
  const instance = M.Modal.getInstance(modalElem);
  instance.open();

  // Manejo de botones
  const btnAceptar = document.getElementById("btnAceptar");
  const btnCancelar = document.getElementById("btnCancelar");

  // Limpiar cualquier listener anterior
  btnAceptar.onclick = () => callback(true);
  btnCancelar.onclick = () => callback(false);
}



/**
 * Description Función para invocar una fecha en formato (Y-M-D)
 *
 * @returns {string} 
 */
export const setDate = () => {
  const dateNow = new Date();
  let day = dateNow.getDate().toString();
  let month = (dateNow.getMonth() + 1).toString();
  let year = dateNow.getFullYear();
  let dateActual = `${year}-${month.padStart(2,"0")}-${day.padStart(2, "0")}`;
  return dateActual;
};


/**
 * Description - Función para implementar el caracter obligatorio(*) en caso de que el usuario seleccione la opción SI/NO
 *
 * @param {{ baseText?: string; selector?: any; isRequired?: boolean; }} [param0={}] 
 * @param {string} [param1.baseText=""] - Texto que se desea unificar
 * @param {null} [param2.selector=null]  - Selector del html
 * @param {boolean} [param3.isRequired=false] - Flag boolean para realizar el cambio.
 */
export const setObservacion = ({baseText = "", selector = null, isRequired = false}={})=>{
  selector.innerText = "";
  selector.innerText = isRequired ? `${baseText} *` : baseText;
}