import {
  fillDataForm,
  initAlert,
  InitComponents,
  Render,
  validateFormData,
} from '../../../../public/assets/js/utils/index.js';
import { executePaginate, renderFilters, renderUsers } from './Functions-UsuariosView.js';
import { selectors, vars } from './Selectors-UsuariosView.js';

document.addEventListener('DOMContentLoaded', () => {
  renderUsers();
  renderFilters();
  executePaginate();

  InitComponents.initSelect();
});
