import {
  initAlert,
  soloLetras,
  soloNumeros,
  validarCorreo,
  Validator,
} from '../../../../public/assets/js/utils/index.js';

const docInput = document.getElementById('usu_docum');
const telefonoInput = document.getElementById('usu_telefono');
const nombresInput = document.getElementById('usu_nombres');
const apellidosInput = document.getElementById('usu_apellidos');
const correoInput = document.getElementById('usu_email');
const textarea = document.getElementById('observaciones');
const usu_password = document.querySelector('#usu_password');
//Validaciones formulario registro usuarios.
const formUsuario = document.getElementById('formSolicitudPrestamo');
// Aplicamos validaciones usando el efecto blur
// if (docInput) soloNumeros(docInput);
if (docInput) Validator.validateInput({ rule: 'numeros', input: docInput });
if (nombresInput) Validator.validateInput({ rule: 'letras', input: nombresInput });
if (telefonoInput) Validator.validateInput({ rule: 'numeros', input: telefonoInput });
if (apellidosInput) Validator.validateInput({ rule: 'letras', input: apellidosInput });
if (correoInput) Validator.validateInput({ rule: 'correo', input: correoInput });
if (usu_password) Validator.validateInput({ input: usu_password });
if (textarea) M.textareaAutoResize(textarea);

const inputOptionals = ['usu_observacion', 'usu_direccion'];
const mapForm = {
  usu_tp_id: 'Tipo de documento',
  usu_docum: 'Número de identificación',
  rol_id: 'Rol',
  usu_nombres: 'Nombres',
  usu_apellidos: 'Apellidos',
  usu_telefono: 'Teléfono',
  usu_password: 'Contraseña',
  usu_email: 'Correo electrónico',
  usu_direccion: 'Dirección',
  usu_observacion: 'Notas adicionales al usuario',
};
formUsuario.addEventListener('submit', async (e) => {
  e.stopPropagation();
  e.preventDefault();
  const tipoDocumento = document.getElementById('usu_tp_id');
  const rol = document.getElementById('rol_id');
  const formData = new FormData(formUsuario);
  // Valida que los campos del formulario sean visibles.
  if (!validateFormData({ formData: formData, campos: inputOptionals, mapForm: mapForm })) {
    return;
  }
  const data = Object.fromEntries(formData.entries());
  let valid = true;

  if (!tipoDocumento.value) {
    // M.toast({ html: 'Seleccione un tipo de documento', classes: 'teal darken-2' });
    initAlert('Seleccione un tipo de documento para el usuario', 'info');
    tipoDocumento.classList.add('invalid');
    valid = false;
    return;
  }

  if (!rol.value) {
    initAlert('Seleccione un rol para el usuario', 'info');
    // M.toast({ html: 'Seleccione un rol para el usuario', classes: 'teal darken-2' });
    rol.classList.add('invalid');
    valid = false;
    return;
  }
  // try {
  //   const result = await sendData(
  //     'modules/usuarios/controller/usuariosController.php',
  //     'POST',
  //     'addUser',
  //     data
  //   );

  //   // Éxito
  //   initAlert('Usuario creado exitosamente', 'success', toastOptions);
  //   formUsuario.reset();
  // } catch (error) {
  //   const message = error.message || error.data?.message || 'Error al registrar el usuario';
  //   initAlert(message, 'error', toastOptions);
  // }
});
