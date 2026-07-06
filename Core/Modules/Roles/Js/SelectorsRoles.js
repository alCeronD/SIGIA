// Selectores asociados al archivo Roles.js

// Mapas de validación (Configuraciones estáticas)
export const rolesConfig = {
  mapObj: {
    rl_nombre: 'Nombre del Rol',
    rl_descripcion: 'Descripción del Rol',
    rl_id: 'ID del Rol',
  },
  mapObjAdd: {
    rl_nombre: 'Nombre del Rol',
    rl_descripcion: 'Descripción del Rol',
  },
};

// Selectores De Roles.js
export const rolesUI = {
  modals: {
    edit: document.querySelector('#modalEditar'),
    assign: document.querySelector('#modalAsingPermisos'),
    confirm: '#modalConfirmacion',
  },
  forms: {
    add: document.querySelector('#formRol'),
    edit: document.querySelector('#formEditarRol'),
  },
  tables: {
    body: document.querySelector('#tableBodyRoles'),
  },
  containers: {
    content: document.querySelector('#asigPermisosContent'),
    header: document.querySelector('#headerRoles'),
  },
  buttons: {
    preconfirm: document.querySelector('#preconfirmButton'),
    closeAssign: document.querySelector('#closeModalBtnAsing'),
    closeEdit: document.querySelector('#closeModalBtnEdit'),
  },
};
