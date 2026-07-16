export const inputOptionals = ['usu_observacion', 'usu_direccion'];
export const mapForm = {
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

export const formSelectosUpdate = {
  formUpdateUser: document.querySelector('#formUpdateUser'),
};

export const modals = {
  modalEditarUsuario: document.querySelector('#modalEditarUsuario'),
};

export const vars = {
  url: 'dashboard.php?modulo=Usuarios&controlador=Usuarios&function=',
};

export const selectors = {
  inputFiltro: document.querySelector('#inputFiltro'),
};

export const typeInput = {
  nombre: 'text',
  documento: 'number',
};
