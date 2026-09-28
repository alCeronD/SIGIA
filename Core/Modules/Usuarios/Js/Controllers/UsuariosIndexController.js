import { HttpData, METHOD, StorageHelper } from '../../../../../public/assets/js/utils/index.js';
// clase para exportar y crear
export class UsuariosIndexController {
  // propiedades
  #Http = new HttpData();
  #urls = {
    rolesController: 'dashboard.php?modulo=Roles&controlador=Roles&function=',
    tpController: 'dashboard.php?modulo=Tipodocumento&controlador=Tipodocumento&function=',
  };
  constructor() {
    this.getAllData();
  }

  async getAllData() {
    // valido primero si la clave existe para asi no enviar peticion
    let rolesData = sessionStorage.getItem('RData');
    let tpData = sessionStorage.getItem('TPData');
    // hacer la peticion solamente si no existe la llave

    if (!rolesData) {
      const datosR = await this.getRoles();
      sessionStorage.setItem('RData', JSON.stringify(datosR));
      rolesData = JSON.stringify(datosR);
    }

    if (!tpData) {
      const datosTp = await this.getTpDocumento();
      sessionStorage.setItem('TPData', JSON.stringify(datosTp));
      tpData = JSON.stringify(datosTp);
    }
  }

  async getRoles() {
    try {
      const responseRoles = await this.#Http.getData(
        `${this.#urls.rolesController}getData`,
        METHOD.GET,
        {}
      );

      if (responseRoles.status) {
        return responseRoles.data;
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  async getTpDocumento() {
    try {
      const dataTp = await this.#Http.getData(`${this.#urls.tpController}getData`, METHOD.GET, {});

      if (dataTp.status) {
        return dataTp.data;
      }
    } catch (error) {
      console.error(error.message);
    }
  }
}
