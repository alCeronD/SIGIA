export const selectors = {
  textTitleFunctions: document.querySelector('#textTitleFunctions'),
};

export const vars = {
  dataModule: null,
  url: 'dashboard.php?modulo=Gestionmodulos&controlador=Gestionmodulos&function=',
  dataFunctions: null,
  actualPage: 1,
  dataPaginate: {},
  files: null,
};

export const tableFunctions = {
  header: document.querySelector('#tblHeaderFunctions'),
  body: document.querySelector('#tbodyFunctions'),
  footer: document.querySelector('#tFooterFunciones'),
  fullTable: document.querySelector('#tableFunctions'),
};

export const buttons = {
  btnAddFunction: document.querySelector('#btnAddFunction'),
  btnCloseModalFunction: document.querySelector('.closeModalBtn'),
};

export const modals = {
  modalAddFunction: document.querySelector('#modalAddFunction'),
};

export const formAddFunctions = {
  form: document.querySelector('#formInsertFunction'),
  radio: document.querySelectorAll('input[name="tp_funcion"]'),
  divs: {
    tipoFuncion: document.querySelector('.tipoFuncion'),
    files: document.querySelector('.files'),
  },
};
