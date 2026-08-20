/**
 * Selectores y configuraciones del formulario de creación de usuarios
 */

export const inputOptionals = ['usu_observacion', 'usu_direccion'];

export const mapForm = {
  usu_tp_id: 'Tipo de documento',
  usu_docum: 'Número de identificación',
  usr_rl_id: 'Rol',
  usu_nombres: 'Nombres',
  usu_apellidos: 'Apellidos',
  usu_telefono: 'Teléfono',
  usu_password: 'Contraseña',
  usu_email: 'Correo electrónico',
  usu_direccion: 'Dirección',
  usu_observacion: 'Notas adicionales al usuario',
};

export const formSelectors = {
  docInput: document.querySelector('#usu_docum'),
  telefonoInput: document.querySelector('#usu_telefono'),
  nombresInput: document.querySelector('#usu_nombres'),
  apellidosInput: document.querySelector('#usu_apellidos'),
  correoInput: document.querySelector('#usu_email'),
  textarea: document.querySelector('#usu_observacion'),
  passwordInput: document.querySelector('#usu_password'), // Le cambié el nombre a passwordInput para mantener consistencia con los demás inputs
  tipoDocumento: document.querySelector('#usu_tp_id'),
  roles: document.querySelector('#usr_rl_id'),
  observacion: document.querySelector('#usu_observacion'),
  formUsuario: document.querySelector('#formCreateUser'),
};

export const vars = {
  url: 'dashboard.php?modulo=Usuarios&controlador=Usuarios&function=',
};
