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

export const forms = {
  formUpdateUser: document.querySelector('#formUpdateUser'),
  formUpdateDataUser: document.querySelector('#formUpdateDataUser'),
};

// inputs de los formularios
export const inputForms = {
  formUpdateDataUser: {
    observacion: forms.formUpdateDataUser.querySelector('#usu_observacion'),
  },
};

export const modals = {
  modalEditarUsuario: document.querySelector('#modalEditarUsuario'),
};

export const buttons = {
  btnCloseModalEditarUsuario: document.querySelector('.close-modal'),
};

export const vars = {
  url: 'dashboard.php?modulo=Usuarios&controlador=Usuarios&function=',
};

export const selectors = {
  inputFiltro: document.querySelector('#inputFiltro'),
  tbodyUsuarios: document.querySelector('#tbodyUsuarios'),
  tHeaderUsuarios: document.querySelector('#tHeadUsuarios'),
  footerUsers: document.querySelector('#tFooterUsers'),
  btnPaginate: document.querySelectorAll('.btnPaginate'),
};

export const typeInput = {
  nombre: 'text',
  documento: 'number',
};

export const events = {
  change: 'change',
  input: 'input',
};

export const dataPaginate = {};

export const messagesUser = {
  activeUser: '¿Esta seguro de activar el usuario?',
  inactiveUser: '¿Esta seguro de inactivar este usuario? el usuario no podrá acceder al sistema',
};

export const titlesUsers = {
  inactiveUser: 'Inactivar usuario',
  activeUser: 'Activar usuario',
};
