// Clase para renderizar datos y ejecutar procesos transaccionales.
import { HttpData } from '../../js/utils/HttpData.js';
import { createBtn } from './cases.js';
export class Render extends HttpData {
  #data = {};
  #actualPage = null;
  #objBotones = {};
  constructor(buttons = {}) {
    super();

    this.#objBotones = { ...buttons };
    this.#actualPage = 1;
  }

  /**
   * Function para renderizar elementos en la tabla, esta funcion se envian ciertos parametros y me renderiza en base a las cabeceras del header.
   *
   * @async
   * @param {string} [url=''] - Url para solicitar el recurso de los datos
   * @param {*} [bodyTbl=null] - El document.querySelector del body de la tabla
   * @param {*} [headerTable=null] - El header de la tabla
   * @param {string} [id=''] - el id primario del recurso que vamos a acceder, esto requererido para renderizar los botones de acciones.
   * @returns {*}
   */
  async renderData(bodyTbl = null, headerTable = null, id = '', data) {
    // NECESITO EL FETCH para renderizar la data.
    try {
      let fragmentBody = document.createDocumentFragment();

      this.#data = { ...data };
      bodyTbl.innerHTML = '';
      data.forEach((element) => {
        let itemElement = element;
        let tr = document.createElement('tr');
        let tdItem = null;
        let buttons = null;
        let idRow = itemElement[id];
        for (const [clave, valor] of Object.entries(itemElement)) {
          tdItem = document.createElement('td');
          tdItem.innerText = valor;

          // Aca tiene que ir la opcion de los botones.
          tr.append(tdItem);
        }
        buttons = this.createButtons(idRow, element);
        tr.append(buttons);
        fragmentBody.appendChild(tr);
      });
      bodyTbl.appendChild(fragmentBody);
      // buscar de la tabla extraer el header para conocer la cantidad de columnas y de ahi capturar la data.
    } catch (error) {
      console.error(error);
    }
  }

  /**
   * Function para renderizar los botones de acción y habilitar un callback para ejecutar algun proceso.
   *
   * @param {string} [idRow=''] - el id de cada fila para asi implementar el id en el boton en caso de ser requerido
   * @param {string} [fullRow={}] - toda la fila con la informacion del elemento, esto recibe un objeto
   * @returns {*}
   */
  createButtons(idRow = '', fullRow = {}) {
    let botones = this.objBotones;

    let tdOptions = document.createElement('td');
    for (const [key, value] of Object.entries(this.objBotones)) {
      let buttons = createBtn(key);
      buttons.innerText = value.value;
      buttons.dataset.id = idRow;
      // definimos si es un tipo de function y vamos a ejecutar.
      tdOptions.append(buttons);

      if (typeof value.action === 'function') {
        buttons.addEventListener('click', (f) => {
          f.preventDefault();
          f.stopPropagation();
          // me ejecuta la function que esta en el objeto del boton en la propiedad, action.
          value.action(idRow, fullRow);
        });
      }
    }
    return tdOptions;
  }

  /**
   * Function para renderizar los botones del paginado en caso de ser requerido.
   *
   * @param {{}} [dataPaginate={}] - Objeto que contiene la informacion requerida para la paginacion como la cantidad de paginas que hay y cantidad de registros
   * @param {*} [selector=null] - selector en donde debe de ir los botones.
   */
  renderPaginate(dataPaginate = {}, selector = null) {
    selector.innerHTML = '';
    let btnPreview = createBtn('btnPreview');
    let btnNext = createBtn('btnNext');
    btnPreview.setAttribute('class', 'btnPaginate');
    btnNext.setAttribute('class', 'btnPaginate');

    btnPreview.innerText = '<';
    btnNext.innerText = '>';
    btnNext.value = 'next';
    btnPreview.value = 'preview';
    let textInfo = `Página ${this.#actualPage} de ${dataPaginate.cantidadPaginas}`;
    selector.append(btnPreview, textInfo, btnNext);
  }

  get objBotones() {
    return this.#objBotones;
  }

  actualPage(page) {
    this.#actualPage = page;
  }

  // Los setters deben tener declarado la palabra set seguido del nombre de la function.
}
