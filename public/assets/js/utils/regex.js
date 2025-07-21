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
};

export const validarGenero = (genero) => {
  let regex = /[a-zA-Z]+/g;
  let regexNumber = /[0-1]+/g;
  generoValue.innerHTML = "valor digitado: " + genero;
  return regex.test(genero);
};

export const validatePlaca = (placa) => {
  regex = validationRules.placa.regex;
  return regex.test(placa);
};

export const validarCantidad = (valor) => {
  regex = validationRules.cantidad.regex;
  return regex.test(valor);
};

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
        classes: "red darken-1",
      });
      input.classList.add("invalid");
    } else {
      input.classList.remove("invalid");
    }
  });
};
