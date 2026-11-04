import { Render } from '../../../../../public/assets/js/utils/index.js';
import { ApiElementosView } from './ApiElementosView.js';
// controlador de la vista ElementosView
export class ElementosViewController {
  #Render = new Render();
  #Api = new ApiElementosView();
  constructor() {
    this.loadData();
  }

  // cargar datos
  async loadData() {
    const responseAreas = await this.#Api.getAreas();
    const responseMarcas = await this.#Api.getMarcas();
    const responseCategorias = await this.#Api.getCategorias();
  }
}
