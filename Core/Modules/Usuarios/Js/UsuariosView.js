import {
  fillDataForm,
  initAlert,
  InitComponents,
  Render,
  validateFormData,
} from '../../../../public/assets/js/utils/index.js';
import {
  asingRol,
  executePaginate,
  renderFilters,
  renderUsers,
  updateUser,
} from './Functions-UsuariosView.js';
import { selectors, vars } from './Selectors-UsuariosView.js';

document.addEventListener('DOMContentLoaded', () => {
  renderUsers();
  renderFilters();
  executePaginate();
  updateUser();
  asingRol();
  InitComponents.initSelect();
});
