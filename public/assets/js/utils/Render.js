import { HttpData } from '../../js/utils/HttpData.js';
import { addClassItem, createBtn, createI } from './index.js';

/**
 * Clase para renderizar datos y ejecutar procesos transaccionales.
 * @constructor - el constructor recibe un objeto, este objeto tiene los botones especificos para la acción, tiene un value, una key y un action, value contiene toda la logica de personalizacion del boton, ya sea su color, asignacion de atributos, ids, datas, entre otros, key es la clase inicial y action es una function especifica que ejecuta el boton en el evento click.
 * @export - la clase se exporta para darle uso en otros modulos y/o tablas.
 * @class Render
 * @typedef {Render}
 * @extends {HttpData} - Clase que extiende de httpData, esta clase tiene la forma de hacer envios de peticiones al backend.
 */
export class Render extends HttpData {
  #data = {};
  #actualPage = null;
  #objBotones = {};
  constructor(buttons = {}) {
    super();
    // aplicamos spread (copiamos) el objeto recibido y lo guardamos en la propiedad.
    this.#objBotones = { ...buttons };
    this.#actualPage = 1;
  }

  /**
   * Function para renderizar elementos en la tabla, esta funcion se envian ciertos parametros y me renderiza en base a las cabeceras del header.
   *
   * @async
   * @param {string} [url=''] - Url para solicitar el recurso de los datos
   * @param {HTMLElement|HTMLTableSectionElement} [bodyTbl=null] - Elemento dom del body de la tabla
   * @param {HTMLElement|HTMLTableSectionElement} [headerTable=null] - Elemento dom del header de la tabla
   * @param {string} [id=''] - el id primario del recurso que vamos a acceder, esto requererido para adjuntar el id como dataset en el boton.
   * @param {Object.<string, string>} [customText={}] - objeto clave valor que contiene el campo que queremos personalizar, ejemplo = tp_status, usamos ese campo para validar su existencia y colocar en texto si esta habilitado o inhabilitado.
   * @returns {Promise<void>} No retorna nada
   */
  async renderData(bodyTbl = null, headerTable = null, id = '', data = {}, customText = {}) {
    // NECESITO EL FETCH para renderizar la data.
    try {
      let fragmentBody = document.createDocumentFragment();
      this.#data = { ...data };
      bodyTbl.innerHTML = '';
      data.forEach((element) => {
        let tr = document.createElement('tr');
        let tdItem = null;
        let buttons = null;
        const itemElement = element;
        const idRow = itemElement[id];
        for (const [clave, valor] of Object.entries(itemElement)) {
          // uso el encadenamiento opcional, si no encuentra nada, devuelve undefined.
          const thElement = headerTable?.querySelector(`#${clave}`)?.getAttribute('id');

          // vamos a renderizar solo lo que esta en la tabla con sus respectivos ids validanto que el id de los encabezados sean iguales a las claves de la data a renderizar
          if (thElement === clave) {
            tdItem = document.createElement('td');

            // validamos si el objeto tiene una clave igual a al objeto data para asi personalizar el texto
            let newValor = null;
            if (Object.hasOwn(customText, clave)) {
              newValor = valor === 1 ? 'Habilitado' : 'Inhabilitado';
            }
            tdItem.innerText = newValor != null ? newValor : valor;

            // Aca tiene que ir la opcion de los botones.
            tr.append(tdItem);
          }
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
   * Function INTERNA para renderizar los botones de acción y habilitar un callback para ejecutar algun proceso.
   *
   * @param {string} [idRow=''] - el id de cada fila para asi implementar el id en el boton en caso de ser requerido
   * @param {string} [fullRow={}] - toda la fila con la informacion del elemento, esto recibe un objeto
   * @returns {*}
   */
  createButtons(idRow = '', fullRow = {}) {
    let tdOptions = document.createElement('td');
    for (const [key, value] of Object.entries(this.objBotones)) {
      let buttons = createBtn(key);

      // validamos si el value es una function o en su defecto solo texto, si es una function, ejecutar.
      if (typeof value.value === 'function') {
        /**
         * Ejecuta el renderizado de la fila y maneja los elementos del DOM.
         * * @param {object} fullRow - Los datos completos de la fila actual.
         * @param {HTMLElement} buttons - El contenedor de botones del DOM.
         * @returns {string|HTMLElement} El resultado del renderizado.
         */
        const textResult = value.value(fullRow, buttons);
        if (textResult) buttons.innerText = textResult;
      } else {
        buttons.innerText = value.value;
      }
      buttons.dataset.id = idRow;
      tdOptions.append(buttons);

      // definimos si es un tipo de function y ejecutamos.
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
   * @param {HTMLElement|HTMLTableSectionElement} [selector=null] - Selector en donde se renderizara la informacion
   * @param {{}} [dataPaginate={}] - Objeto que contiene la informacion requerida para la paginacion como la cantidad de paginas que hay y cantidad de registros
   */
  renderPaginate(dataPaginate = {}, selector = null) {
    selector.innerHTML = '';
    const fragmentContainer = document.createDocumentFragment();
    const tr = document.createElement('tr');
    const td = document.createElement('td');
    td.setAttribute('colspan', '6');
    tr.append(td);

    const containerPaginate = document.createElement('div');
    addClassItem(containerPaginate, { container: 'containerPaginate' });
    const ul = document.createElement('ul');

    let iBtnPreview = createI('chevron_left');
    let iBtnNext = createI('chevron_right');
    addClassItem(iBtnNext, { materialIcon: 'material-icons' });
    addClassItem(iBtnPreview, { materialIcon: 'material-icons' });

    iBtnNext.style.pointerEvents = 'none';
    iBtnPreview.style.pointerEvents = 'none';
    // INICIAMOS EN 1 y lo extraemos porque lo usaremos en el if y else.
    let actualPage = 1;

    // validamos mostrar los numeros de paginas entre 1 a 5, en caso contrario, hacer un calculo y mostrar las paginas de 2 o de 3 en 3.
    if (dataPaginate.cantidadPaginas >= 1 && dataPaginate.cantidadPaginas <= 5) {
      const liPreview = document.createElement('li');
      let li = null; // lo creamos por fuera porque una vez renderizemos. vamos a validar aplicar el estilo active cuando el usuario haga click.
      liPreview.style.cursor = 'pointer'; //hacemos que el li tenga pointerevent para dar click
      liPreview.style.pointerEvents = 'auto'; //hacemos que el li tenga pointerevent para dar click
      addClassItem(liPreview, { btnPaginate: 'btnPaginate' });
      liPreview.dataset.action = 'preview';
      if (this.#actualPage === 1) liPreview.setAttribute('disabled', 'disabled'); //si no funciona asi al hacer la pagina 2, eliminar la propiedad.
      liPreview.append(iBtnPreview);

      ul.append(liPreview);
      while (actualPage <= dataPaginate.cantidadPaginas) {
        li = document.createElement('li');
        li.innerText = actualPage;
        addClassItem(li, { wavesEffect: 'waves-effect', liPaginate: 'liPaginate' });
        li.dataset.actualpage = actualPage;
        // si estas en la pagina actual entonces agregarle la clase para determinar que esta activa.
        if (actualPage === this.#actualPage) {
          addClassItem(li, { active: 'active' });
        } else {
          li.classList.remove('active');
        }

        ul.append(li);
        if (actualPage > dataPaginate.cantidadPaginas) break;
        actualPage++;
      }
      const liNext = document.createElement('li');
      addClassItem(liNext, { btnPaginate: 'btnPaginate' });
      liNext.dataset.action = 'next';
      if (actualPage === dataPaginate.cantidadPaginas) liNext.setAttribute('disabled', 'disabled');
      liNext.append(iBtnNext);
      liNext.style.cursor = 'pointer'; //hacemos que el li tenga pointerevent para dar click
      liNext.style.pointerEvents = 'auto'; //hacemos que el li tenga pointerevent para dar click
      ul.append(liNext);
      containerPaginate.append(ul);
    } else {
      // renderizar si son mas de 5 paginas de 2 en 2.
      const fragmentCustomPage = document.createDocumentFragment();
      // BOTON PREVIEW
      const liPreview = document.createElement('li');
      liPreview.style.cursor = 'pointer'; //hacemos que el li tenga pointerevent para dar click
      liPreview.style.pointerEvents = 'auto'; //hacemos que el li tenga pointerevent para dar click
      addClassItem(liPreview, { btnPaginate: 'btnPaginate' });
      liPreview.dataset.action = 'preview';
      if (this.#actualPage === 1) liPreview.setAttribute('disabled', 'disabled');
      liPreview.append(iBtnPreview);
      ul.append(liPreview);

      fragmentCustomPage.append(ul);

      for (let actualPage = 1; actualPage <= dataPaginate.cantidadPaginas; actualPage++) {
        if (
          actualPage === 1 ||
          actualPage === dataPaginate.cantidadPaginas ||
          actualPage % 3 === 0
        ) {
          // PAGINAS ESPECIFICAS.
          const liActualPage = document.createElement('li');
          liActualPage.style.cursor = 'pointer';
          liActualPage.style.pointerEvents = 'auto';
          addClassItem(liActualPage, { btnPaginate: 'btnPaginate', liPaginate: 'liPaginate' });
          liActualPage.dataset.actualpage = actualPage;
          liActualPage.innerText = actualPage;

          // si estas en la pagina actual entonces agregarle la clase para determinar que esta activa.
          if (actualPage === this.#actualPage) {
            addClassItem(liActualPage, { active: 'active' });
          } else {
            ul.classList.remove('active');
          }

          ul.append(liActualPage);

          fragmentContainer.append(ul);
        }
      }

      // BOTON NEXT
      const liNext = document.createElement('li');
      addClassItem(liNext, { btnPaginate: 'btnPaginate' });
      liNext.dataset.action = 'next';
      if (actualPage === dataPaginate.cantidadPaginas) liNext.setAttribute('disabled', 'disabled');
      liNext.append(iBtnNext);
      liNext.style.cursor = 'pointer'; //hacemos que el li tenga pointerevent para dar click
      liNext.style.pointerEvents = 'auto'; //hacemos que el li tenga pointerevent para dar click

      ul.append(liNext);

      fragmentContainer.append(ul);

      containerPaginate.append(fragmentContainer);
    }
    td.append(containerPaginate);

    selector.append(tr);
  }

  executePaginates(pagina = 1, renderData = () => {}) {}

  get objBotones() {
    return this.#objBotones;
  }

  // void para guardar la pagina en la propiedad del objeto.
  actualPage(page) {
    this.#actualPage = page;
  }
}
