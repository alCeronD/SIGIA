// selectores del modulo

export const table = {
  table: document.querySelector('#tableModulos'),
  header: document.querySelector('#tblHeaderModulos'),
  body: document.querySelector('#tbodyModulos'),
  footer: document.querySelector('#tFooterModulos'),
};
export const vars = {
  url: 'dashboard.php?modulo=GestionModulos&controlador=GestionModulos&function=',
  dataModulos: null,
  actualPage: 1,
};

export const forms = {
  formCreate: document.querySelector('#formModuloCreate') ?? null,
  formUpdate: document.querySelector('#formModuloUpdate') ?? null,
  formAsing: document.querySelector('#formModuloAsing') ?? null,
};

export const modals = {
  modalEdit: document.querySelector('#modalEditModulo') ?? null,
  modalAsignFunction: document.querySelector('#modalAsingModulo') ?? null,
};

export const buttons = {
  btnCloseModal: document.querySelector('.closeModalBtn'),
};

export const messagesModal = {
  activeUser: '¿Esta seguro de activar el modulo?',
  inactiveUser:
    '¿Esta seguro de inactivar este modulo? el modulo no estará disponible para el usuario',
};

export const titlesModulo = {
  inactiveUser: 'Inactivar Módulo',
  activeUser: 'Activar Módulo',
};
