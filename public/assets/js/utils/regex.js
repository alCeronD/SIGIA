let regex = new RegExp();

// Reglas
export const validationRules = {
  documento: {
    regex: /^\d{5,15}$/,
    message: "El documento debe contener solo números (5 a 15 caracteres).",
  },
  placa: {
    // Numeros permitidos del 0-9
    regex: /^\d+$/,
    message: "La placa no debe tener caracteres especiales ni letras.",
  },
  cantidad: {
    regex: /^\d+$/,
    message: "Cantidad no permitida",
  },
  serie: {
    regex : /^(?!.*-.*-)[0-9-]+$/,
    message : "Solo se permite el uso de guión."
  }
};


/**
 * Description - Función para validar que la placa cumpla con las reglas de solo Números.
 *
 * @param {*} placa - Valor de la placa.
 * @returns {boolean} - True or false dependiendo del valor enviado como parámetro. 
 */
export const validatePlaca = (placa) => {
  const regex = validationRules.placa.regex;
  return regex.test(placa);
};

export const validarCantidad = (valor) => {
  const regex = validationRules.cantidad.regex;
  return regex.test(valor);
};

export const validarSerie = (valor)=>{
  const regex = validationRules.serie.regex;
  return regex.test(valor);
}

// Validar que solo se puedan escribir No#.
export const soloNumeros = (input) => {
  input.addEventListener("input", () => {
    input.value = input.value.replace(/\D/g, "");
  });
};

// Solo se puedan escribir letras
export const soloLetras = (input) => {
  input.addEventListener("input", () => {
    input.value = input.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúñÑ\s]/g, "");
  });
};

// formato para el correo
export const validarCorreo = (input) => {
  input.addEventListener("blur", () => {
    const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (input.value && !correoValido.test(input.value)) {
      M.toast({
        html: "Correo electrónico no válido",
      });
      input.classList.add("invalid");
    } else {
      input.classList.remove("invalid");
    }
  });
};
