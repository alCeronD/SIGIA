import {
  addClassItem,
  HttpData,
  initAlert,
  optionsSelect,
  validateFormData,
  Validator,
  InitComponents,
  mostrarConfirmacion,
  Storage,
} from '../../../../public/assets/js/utils/index.js';
import { inputOptionals, mapForm, formSelectors, vars } from './Selectors-CreateUser.js';

// Aplicamos validaciones usando el efecto blur
if (formSelectors.docInput)
  Validator.validateInput({ rule: 'numeros', input: formSelectors.docInput });
if (formSelectors.nombresInput)
  Validator.validateInput({ rule: 'letras', input: formSelectors.nombresInput });
if (formSelectors.telefonoInput)
  Validator.validateInput({ rule: 'numeros', input: formSelectors.telefonoInput });
if (formSelectors.apellidosInput)
  Validator.validateInput({ rule: 'letras', input: formSelectors.apellidosInput });
if (formSelectors.correoInput)
  Validator.validateInput({ rule: 'correo', input: formSelectors.correoInput });
// validamos los roles y tipo de documento que este haya sido seleccionado.
if (formSelectors.roles) Validator.validateSelect({ input: formSelectors.roles });
if (formSelectors.tipoDocumento) Validator.validateSelect({ input: formSelectors.tipoDocumento });
if (formSelectors.usu_password) Validator.validateInput({ input: formSelectors.usu_password });
if (formSelectors.observacion) InitComponents.initTextArea({ selector: formSelectors.observacion }); //inicio el textarea

const CreateUser = new HttpData();
let responseDataSelects = null;
let rolesResponse = null;
let rolesData = null;
const getAndRenderSelects = async () => {
  responseDataSelects = await CreateUser.getData(`${vars.url}getDataSelects`, 'GET', {});
  // selector
  let tpDocumentoData = responseDataSelects.data.tipoDocumento;
  let rolesData = responseDataSelects.data.roles;
  let fragmentTpDocumento = document.createDocumentFragment();
  let fragmentRoles = document.createDocumentFragment();

  let optionDisableTpDocumento = document.createElement('option');
  optionDisableTpDocumento.innerText = 'Seleccione tipo documento';
  optionDisableTpDocumento.selected = true;
  optionDisableTpDocumento.disabled = false;
  optionDisableTpDocumento.value = '';
  addClassItem(optionDisableTpDocumento, { validate: 'validate' });

  fragmentTpDocumento.append(optionDisableTpDocumento);
  tpDocumentoData.forEach((element) => {
    let optionTpDocumento = document.createElement('option');
    optionTpDocumento.value = element.tp_id; //adicionamos al value el tipo de elemento
    optionTpDocumento.innerText = `${element.tp_sigla} - ${element.tp_nombre}`;

    fragmentTpDocumento.append(optionTpDocumento);
  });
  formSelectors.tipoDocumento.append(fragmentTpDocumento);

  let optionDisableRoles = document.createElement('option');
  optionDisableRoles.innerText = 'Seleccione el rol';
  optionDisableRoles.selected = true;
  optionDisableRoles.disabled = false;
  optionDisableRoles.value = '';
  fragmentRoles.append(optionDisableRoles);

  rolesData.forEach((element) => {
    let optionRoles = document.createElement('option');
    optionRoles.value = element.rl_id; //adicionamos al value el tipo de elemento
    optionRoles.innerText = `${element.rl_nombre}`;
    fragmentRoles.append(optionRoles);
  });
  formSelectors.roles.append(fragmentRoles);

  // iniciamos los selects de tipo de documento y roles.
  InitComponents.initSelect();
};

document.addEventListener('DOMContentLoaded', () => {
  getAndRenderSelects();
});

// Evento de envio de datos.
formSelectors.formUsuario.addEventListener('submit', async (e) => {
  e.stopPropagation();
  e.preventDefault();

  const formData = new FormData(e.target);
  // Valida que los campos del formulario sean visibles.
  if (formSelectors.roles) Validator.validateSelect({ input: formSelectors.roles });
  if (formSelectors.tipoDocumento) Validator.validateSelect({ input: formSelectors.tipoDocumento });

  if (!validateFormData({ formData: formData, campos: inputOptionals, mapForm: mapForm })) {
    return;
  }
  const data = Object.fromEntries(formData.entries());

  mostrarConfirmacion('Crear usuario', '¿Esta seguro de crear el usuario?', async (response) => {
    try {
      if (!response) return;

      const responseCreateUser = await CreateUser.sendData(`${vars.url}store`, 'POST', data);

      // Éxito
      if (responseCreateUser.status) {
        initAlert(responseCreateUser.message, 'success');
        InitComponents.initSelect();
        formSelectors.formUsuario.reset();
      }
    } catch (error) {
      const message = error.message || error.data?.message || 'Error al registrar el usuario';
      initAlert(message, 'error');
      return;
    }
  });
});
