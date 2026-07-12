import { optionsSelect } from './index.js';

// Archivo para inicializar los componentes materialize.
export class InitComponents {
  /**
   * Function estatica para re inicializar el selector de materialize.
   *
   * @static
   */
  static initSelect() {
    const selectsMaterialize = document.querySelectorAll('select');
    let instances = M.FormSelect.init(selectsMaterialize, optionsSelect);
  }

  static initCheckbox() {}

  /**
   * Function estatica para re inicializar los modales creados por materialize.
   *
   * @static
   */
  static initModals() {
    const elemsModals = document.querySelectorAll('.modal');
    //inicializar los modales
    M.Modal.init(elemsModals);
  }

  static initInputs() {
    M.updateTextFields();
  }

  /**
   * Function para inicializar el selector del textArea
   *
   * @static
   * @param {{ selector: any; }} param0
   * @param {*} param0.selector: selector
   * @returns
   */
  static initTextArea({ selector: selector }) {
    M.textareaAutoResize(selector);
  }
}
