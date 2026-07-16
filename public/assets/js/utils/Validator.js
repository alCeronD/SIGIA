import { addClassItem, initAlert, messages } from './index.js';
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
    numeros: {
      regex: /^\d+$/,
      message: 'Solo se permiten datos de tipos numericos',
    },
    empty: {
      regex: /^$/,
      message: 'Seleccione el campo',
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
    if (!value) return; //si el valor esta vacio.
    let validateRule = this.#rules[rule];
    if (!validateRule) return;
    return validateRule.regex.test(value);
  }

  /**
   * Function para validar la longitud de un string y determinar si es correcto o no para su respectiva validacion.
   *
   * @static
   * @param {{ value: any; maxLenght: any; }} param0
   * @param {*} param0.value: value
   * @param {*} param0.maxLenght: lenght
   * @returns {boolean}
   */
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

  static validateInput({ input: input, rule: rule = '' }) {
    // usamos el metodo blur para validar el input.
    input.addEventListener('blur', (e) => {
      let valueInput = e.target.value;

      let spanElement = e.target.parentElement.querySelector('.helper-text');
      // validamos si esta vacio para enviar otro mensaje.
      if (valueInput === '') {
        if (e.target.classList.contains('valid')) {
          e.target.classList.remove('valid');
        }
        spanElement.dataset.error = 'El campo es obligatorio';
        addClassItem(e.target, { valid: 'invalid' });
        return;
      }

      let responseValidateInput = this.validateRule({ value: valueInput, rule: rule });
      // SI NOS DA FALSE, ENTONCES AL INPUT LE IMPLEMENTAMOS LA CLASE INVALID, EN CASO CONTRARIO LA CLASE VALID.
      if (!responseValidateInput) {
        if (e.target.classList.contains('valid')) {
          e.target.classList.remove('valid');
        }
        // obtenemos el mensaje establecido por la regla de la expresion regular y lo dibujamos en el input.
        spanElement.dataset.error = this.getMessage({ rule: rule });
        addClassItem(e.target, { valid: 'invalid' });
      } else {
        if (e.target.classList.contains('invalid')) {
          e.target.classList.remove('invalid');
        }
        addClassItem(e.target, { valid: 'valid' });
      }
    });
  }

  /**
   * Function para validar que selector haya seleccionado el elemento adecuado.
   *
   * @static
   * @param {{ input: any; }} param0
   * @param {*} param0.input: input
   */
  static validateSelect({ input: input }) {
    input.addEventListener('change', (e) => {
      e.stopPropagation();
      e.preventDefault();

      const select = e.target;
      const container = select.closest('.input-field');
      const visibleInput = container.querySelector('input.select-dropdown');
      const spanElement = container.querySelector('.helper-text');

      if (!visibleInput || !spanElement) return;

      if (select.value === '') {
        spanElement.dataset.error = 'El campo es obligatorio';
        visibleInput.classList.remove('valid');
        spanElement.classList.remove('valid');
        addClassItem(spanElement, { invalid: 'invalid' });
        addClassItem(visibleInput, { valid: 'invalid' });

        spanElement.textContent = 'El campo es obligatorio';
        return;
      } else {
        spanElement.classList.remove('invalid');
        visibleInput.classList.remove('invalid');
        addClassItem(visibleInput, { valid: 'valid' });
        addClassItem(spanElement, { valid: 'valid' });
      }
    });
  }

  /**
   * Function para obtener el mensaje dependiendo de la regla.
   *
   * @static
   * @param {{ rule: any; }} param0
   * @param {*} param0.rule: rule
   * @returns {*}
   */
  static getMessage({ rule: rule }) {
    return this.#rules[rule].message;
  }
}
