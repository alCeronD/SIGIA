import { HttpData, METHOD } from '../../../../../public/assets/js/utils/index.js';
export class ApiElementosView {
  #httpd;
  constructor() {
    this.#httpd = new HttpData();
  }

  async getAreas() {
    try {
      let AreasUrl = 'dashboard.php?modulo=Areas&controlador=ServicesAreas&function=';
      const AreasData = await this.#httpd.getData(`${AreasUrl}getAreas`, METHOD.GET, {});
      return AreasData;
    } catch (error) {
      return error;
    }
  }

  async getMarcas() {
    try {
      let marcasUrl = 'dashboard.php?modulo=Marcas&controlador=ServicesMarcas&function=';
      const MarcasData = await this.#httpd.getData(`${marcasUrl}getMarcas`, METHOD.GET, {});
      return MarcasData;
    } catch (error) {
      return error;
    }
  }

  async getCategorias() {
    try {
      let categoriasUrl =
        'dashboard.php?modulo=Categorias&controlador=ServicesCategorias&function=';
      const MarcasData = await this.#httpd.getData(
        `${categoriasUrl}getCategoriasEndpoint`,
        METHOD.GET,
        {}
      );
      return MarcasData;
    } catch (error) {
      return error;
    }
  }
}
