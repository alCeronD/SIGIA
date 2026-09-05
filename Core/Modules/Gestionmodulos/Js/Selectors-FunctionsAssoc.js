export const selectors = {
  textTitleFunctions: document.querySelector('#textTitleFunctions'),
};

export const vars = {
  dataModule: null,
  url: 'dashboard.php?modulo=Gestionmodulos&controlador=Gestionmodulos&function=',
  urlsFunciones: 'dashboard.php?modulo=Funciones&controlador=FuncionesModulo&function=',
  dataFunctions: null,
  actualPage: 1,
  dataPaginate: {},
  files: null,
};

export const mapConfigFunctions = {
  mapObjAdd: {
    id_funcion: 'Identificador de la función',
    nombre_funcion: 'Nombre de la funcion',
    nombre_funcion_user: 'Nombre de la función del usuario',
    tp_funcion: 'Tipo de la función',
    is_main_view: 'Tipo de vista',
    nameController: 'Archivo asociado',
  },
  mapObjEdit: {
    id_funcion: 'Identificador de la función',
    nombre_funcion: 'Nombre de la funcion',
    nombre_funcion_user: 'Nombre de la función del usuario',
    tp_funcion: 'Tipo de la función',
    nameController: 'Archivo asociado',
    is_main_view: 'Tipo de vista',
  },
};

export const optionals = [];

export const tableFunctions = {
  header: document.querySelector('#tblHeaderFunctions'),
  body: document.querySelector('#tbodyFunctions'),
  footer: document.querySelector('#tFooterFunciones'),
  fullTable: document.querySelector('#tableFunctions'),
};

export const buttons = {
  btnAddFunction: document.querySelector('#btnAddFunction'),
  btnCloseModalFunctionEdit: document.querySelector('#closeModalBtnEdit'),
  btnCloseModalFunctionInsert: document.querySelector('#closeModalBtnInsert'),
};

export const modals = {
  modalAddFunction: document.querySelector('#modalAddFunction'),
  modalEditFunction: document.querySelector('#modalEditFunction'),
};

export const formAddFunctions = {
  form: document.querySelector('#formInsertFunction'),
  radio: document.querySelectorAll('input[name="tp_funcion"]'),
  radio_is_main_view: document.querySelectorAll('input[name="is_main_view"]'),
  divs: {
    tipoFuncion: document.querySelector('.tipoFuncion'),
    files: document.querySelector('.files'),
    contentIsMain: document.querySelector('.contentIsMain'),
  },
};

export const formUpdateFunctions = {
  form: document.querySelector('#formUpdateFunction'),
  radio: document.querySelector('#formUpdateFunction').querySelectorAll('input[name="tp_funcion"]'),
  divs: {
    tipoFuncion: document.querySelector('.tipoFuncion'),
    files: document.querySelector('.files'),
    contentIsMain: document.querySelector('.contentIsMainUpdate'),
  },
  radio_is_main_view: document.querySelectorAll('.is_main_view_update'),
};

export const footer = document.querySelector('#tFooterFunciones');
