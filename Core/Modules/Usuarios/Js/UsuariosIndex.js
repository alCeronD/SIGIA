// aca podemos hacer una peticion al tipo de documento y roles y guardar el resultado en localStorage

import { StorageHelper } from '../../../../public/assets/js/utils/Storage.js';
import { UsuariosIndexController } from './Controllers/UsuariosIndexController.js';

document.addEventListener('DOMContentLoaded', () => {
  const UIController = new UsuariosIndexController();
});
