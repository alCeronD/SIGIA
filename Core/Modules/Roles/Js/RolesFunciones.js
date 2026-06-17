import { optionsSelect, Render } from '../../../../public/assets/js/utils/index.js';

const selectRol = document.querySelector('#selectRol');
const bodyRolesFunciones = document.querySelector('#bodyRolesFunciones');
const url = 'dashboard.php?modulo=Roles&controlador=RolesFunciones&function=';
const RolesFunciones = new Render();

selectRol.addEventListener('change', (e) => {
  e.stopPropagation();
  e.preventDefault();
  let rolId = e.target.value;

  // peticion para renderizado.
});

document.addEventListener('DOMContentLoaded', async () => {
  bodyRolesFunciones.innerHTML = 'seleccione el rol para visualizar las funciones asociadas';
  const dataRoles = await RolesFunciones.getData(`${url}getRoles`, 'GET');
  let dtaRoles = dataRoles.data;
  let optionDesabled = document.createElement('option');
  optionDesabled.setAttribute('disabled', '');
  optionDesabled.setAttribute('selected', '');
  optionDesabled.innerText = 'Seleccione el rol';
  const fragment = document.createDocumentFragment();
  fragment.appendChild(optionDesabled);
  selectRol.appendChild(fragment);
  dtaRoles.forEach((elm) => {
    let id = elm.rl_id;
    let nombre = elm.rl_nombre;

    let optionRol = document.createElement('option');
    optionRol.setAttribute('value', `${id}`);
    optionRol.innerText = nombre;
    fragment.appendChild(optionRol);
  });
  selectRol.appendChild(fragment);

  // inicializar selects
  const selectsMaterialize = document.querySelectorAll('select');
  let instances = M.FormSelect.init(selectsMaterialize, optionsSelect);
});
