export const urls = {
  urlCategoria: 'dashboard.php?modulo=Categorias&controlador=Categorias&function=',
};

export const tables = {
  tblCategoria: {
    table: document.querySelector('#tblCategoria'),
    theader: document.querySelector('#tHeaderCategoria'),
    tbody: document.querySelector('#tBodyCategoria'),
    tfoot: document.querySelector('#tFootCategoria'),
  },
};

export const forms = {
  create: document.querySelector('#formCreateCategoria'),
  update: document.querySelector('#formUpdateCategoria'),
};

export const modals = {
  update: document.querySelector('.modalEditarCategoria'),
};

export const btnClose = {
  closeModalUpdate: document.querySelector('.closeModalBtn'),
};
