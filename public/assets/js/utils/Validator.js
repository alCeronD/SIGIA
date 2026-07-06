export class Validator {
  static #rules = {
    documento: {
      regex: /^\d{5,15}$/,
      message: 'El documento debe contener solo números (5 a 15 caracteres).',
    },
    placa: {
      // Modificado para poner un límite sano (ej. 1 a 10 dígitos) según tu TODO
      regex: /^\d{1,10}$/,
      message: 'La placa debe contener solo números y máximo 10 caracteres.',
    },
    cantidad: {
      regex: /^\d{1,6}$/, // Ej: Máximo 999,999
      message: 'Cantidad no permitida (máximo 6 dígitos).',
    },
    serie: {
      // Permite números y guiones, controlando longitud total (ej. 3 a 20)
      regex: /^(?!.*-.*-)[0-9-]{3,20}$/,
      message: 'Solo se permite el uso de un guión y entre 3 y 20 caracteres.',
    },
    correo: {
      regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
      message: 'Correo electrónico no válido.',
    },
    letras: {
      regex: /^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/,
      message: 'Solo se permiten letras en el campo',
    },
  };

  /**
   * Funcion para validar la regla establecida en la clase.
   *
   * @static
   * @param {{ value: any; rule: any; }} param0
   * @param {*} param0.value: valor a validar
   * @param {*} param0.rule: tipo de regla a validar, sea de correo electronico, numeros, caracteres especiales, entre otros.
   */
  static validateRule({ value: value, rule: rule }) {
    let validateRule = this.#rules[rule];
    if (!validateRule) return;
    return validateRule.regex.test(value);
  }

  static validateLeght({ value: value, maxLenght: lenght }) {
    let minLenght = 0;

    // eliminar espacios del elemento y capturar la dimension del string
    let valueTest = String(value.trim()).length;
    if (valueTest > minLenght && minLenght < valueTest) {
      return false;
    } else {
      return true;
    }

    return true;
  }

  static getMessage({ rule: rule }) {
    return this.#rules[rule].message;
  }
}
