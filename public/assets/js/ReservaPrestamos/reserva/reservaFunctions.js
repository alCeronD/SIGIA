/** @file Archivo que contiene todas las funciones que se van a dar uso en el archivo reservaPrestamos.js */

/**
 * Description - Con esta función valido que los campos del formulario sean diligenciados.
 *
 * @export
 * @param {*} formData 
 * @param {*} tipoPrestamo 
 * @returns {boolean} 
 */
export function validateFormData(formData, tipoPrestamo, {initAlert, toastOptions}) {
  for (const [key, value] of formData.entries()) {
    const isEmpty = !value || value.toString().trim() === "";

    // Evitamos validar dependiendo del tipo de prestamo, si es 1, omitir la fecha de reserva y si es 2, solo observaciones.
    const camposOpcionales =
      tipoPrestamo === "1"
        ? ["observaciones", "fechaReserva"]
        : ["observaciones"];
    if (isEmpty && !camposOpcionales.includes(key)) {
      initAlert(
        `El campo "${key}" debe ser diligenciado`,
        "info",
        toastOptions
      );
      return false;
    }
  }
  return true;
}

/**
 * Description - Función para validar que fecha es mayor
 *
 * @export
 * @param {*} date1 
 * @param {*} date2 
 * @returns {boolean} 
 */
export function validateDate(date1, date2) {
  let timeDate1 = date1.getTime();
  let timeDate2 = date2.getTime();

  if (timeDate1 > timeDate2) {
    return false;
  } else {
    return true;
  }
}

/**
 * Se valida que la cantidad de los elementos consumibles no sea ni negativa ni mayor a la cantidad disponible.
 * @constructor
 * @param {input} cantidadInput - El input number.
 * @param {int} cantidad - cantidad Del elemento disponible.
 * @param {input} checkBoxSelect - El checkbox deshabilitado.
 */
export function definirCantidad(cantidadInput, cantidad, checkBoxSelect, {initAlert,toastOptions }) {
  cantidadInput.addEventListener("change", (event) => {
    event.stopPropagation();
    event.preventDefault();

    let valor = parseInt(event.target.value, 10);
    if (valor < 0) {
      // alert("Cantidad no disponible");
      initAlert("Cantidad no disponible", "info", toastOptions);
      event.target.value = "";
      return;
    }
    if (event.target.value > cantidad) {
      initAlert(`Cantidad Máxima permitida ${cantidad}`, "info", toastOptions);
      cantidadInput.value = "";
      return;
    }

    //El valor insertado en cantidad lo actualizo en el data del input. Si el usuario digita una cantidad menor a la cantidad disponible, el valor se actualiza.
    cantidadInput.dataset.cantidad = event.target.value;
    // Habilito el checkbox
    checkBoxSelect.disabled = false;
  });
}

/**
 * Función para validar que aquellos elementos seleccionados esten disponibles en el rango de fecha seleccionado por el usuario.
 *
 * @async
 * @param {{ fechaReserva?: string; fechaDevolucion?: string; codigosElementos: any; isOnly?: boolean; method?: string; tpPrestamo?: any; }} param0 
 * @param {string} [fechaReserva=""] - Fecha de reserva seleccionada por el usuario, cuando el usuario espera reclarmar los insumos.
 * @param {string} [fechaDevolucion=""] - Fecha de devolución seleccionada por el usuario, cuando el usuario devuelve los elementos al almácen.
 * @param {*} codigosElementos - Código del elemento de manera unitaria
 * @param {boolean} [isOnly=false] - Flag para validar si el proceos se hace uno por uno o en su defecto todos los elementos
 * @param {string} [method="GET"] - Método de envió de la data, dependiendo de la cantidad de elementos se usa get o post
 * @param {null} [tpPrestamo=null] - Tipo de prestamo 1 si es prestamo, 2 si es reserva.
 * @param {{}} [param1={}] - Objecto por defecto
 * @param {*} sendData - Función para ejecutar la función de manera eficiente.
 * @param {*} getData - Función para ejecutar la función de manera eficiente.
 * @param {*} initAlert - Función para ejecutar la función de manera eficiente.
 * @param {*} toastOptions - Función para ejecutar la función de manera eficiente.
 * @returns {unknown} 
 */
export const validateDisponibilidad = async ({
  fechaReserva = "",
  fechaDevolucion = "",
  codigosElementos,
  isOnly = false,
  method = "GET",
  tpPrestamo = null,
},{ sendData, getData, initAlert, toastOptions } = {}) => {
  let param = {
    fechaReserva,
    fechaDevolucion,
    isOnly,
    tpPrestamo,
  };

  let responseDisponibilidadGet = null;
  let responseDisponibilidadPost = null;

  // Defino los parámetros, dependiendo de si es un solo elemento o varios.
  param = isOnly
    ? {
        ...param,
        elemento: codigosElementos,
        action: "validateElement",
      }
    : {
        ...param,
        elementos: codigosElementos,
        action: "validateElements",
      };

  try {
    if (method === "GET") {
      responseDisponibilidadGet = await getData(
        "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
        method,
        param
      );

      if (responseDisponibilidadGet.status === 204) {
        return true;
      }

      if (!responseDisponibilidadGet.status) {
        initAlert(
          "Este elemento ya está reservado para la fecha seleccionada",
          "info",
          toastOptions
        );

        return false;
      }
    } else if (method === "POST") {
      responseDisponibilidadPost = await sendData(
        "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
        method,
        "validateElements",
        param
      );

      if (responseDisponibilidadPost.status === 204) {
        return true;
      }

      // devuelvo la data en caso de que sea true.
      if (responseDisponibilidadPost.status) {
        return responseDisponibilidadPost;
      }
    }
  } catch (error) {
    console.log(error);
  }

  // return true;
};

/**
 * Función para unificar los elementos devolutivos y consumibles seleccionados para poder visualizar al usuario
 *
 * @param {{}} [rows={}] - Objeto que contiene todos los códigos de los elementos devolutivos y consumibles.
 * @returns {string}
 */
export const createMessageElementos = (rows = {}, replaceln) => {
  if (!rows) return "";

  let devolutivosRows = rows.codigoElementos.devolutivos;
  let consumiblesRows = rows.codigoElementos.consumibles;
  let textConfirmReservaConsumibles = "";
  let textConfirmReservaDev = "";

  if (consumiblesRows.length === 0) {
    textConfirmReservaConsumibles += `\n Consumibles:\n Sin elementos \n`;
  } else {
    textConfirmReservaConsumibles += `Elementos consumibles seleccionados por el usuario:\n${consumiblesRows
      .map(
        (el) =>
          `Código: ${el.codigo}  Nombre: ${el.nombreElemento} Cantidad: ${el.cantidad} \n`
      )
      .join("\n")}\n`;
  }

  if (devolutivosRows.length === 0) {
    textConfirmReservaDev += `Devolutivos:\n Sin elementos`;
  } else {
    // Devolutivos.
    textConfirmReservaDev += `Elementos devolutivos seleccionados por el usuario:\n${devolutivosRows
      .map(
        (el) =>
          `Serie: ${el.serie} Nombre: ${el.nombreElemento} Cantidad: ${el.cantidad}`
      )
      .join("\n")}\n`;
  }

  const textRegistrar =
    `\n Elementos seleccionados: \n ${replaceln(
      textConfirmReservaConsumibles
    )}\n` + `\n ${replaceln(textConfirmReservaDev)}`;

  return textRegistrar;
};

/**
 * Función para complementar la estructura del mensaje de pre confirmación al usuario.
 *
 * @param {{}} [dataValidate={}] - Objeto con los elementos validos para el respectivo prestamo
 * @param {*} tpPrestamo - tipo de prestamo para definir al usuario si es reserva o prestamo
 * @returns {{}} 
 */
export const createMessagReservados = (dataValidate = {}, tpPrestamo, replaceln) => {
  if (!dataValidate) return {};

  if (!tpPrestamo) return "";

  let textDataReservados = "";
  let textConfirmReserva = "";

  textDataReservados += `\n ${dataValidate
    .map(
      (el) =>
        `Serie elemento ${el.seriElemento} Nombre elemento: ${el.nombreElemento} Fecha Reservada: ${el.fechaReserva} Fecha Devolución : ${el.fechaDevolucion}`
    )
    .join("\n")}\n`;

  if (tpPrestamo === "2") {
    textConfirmReserva += `\n Estos elementos ya están reservados para la fecha seleccionada : ${replaceln(
      textDataReservados
    )}`;
  }

  if (tpPrestamo === "1") {
    textConfirmReserva += `\n Estos elementos ya están reservados para la fecha de devolución seleccionada o posterior a ella ${replaceln(
      textDataReservados
    )} \n`;
  }

  return textConfirmReserva;
};