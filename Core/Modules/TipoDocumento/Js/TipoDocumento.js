import { Render } from '../../../../public/assets/js/utils/Render.js';
const url = 'dashboard.php?modulo=TipoDocumento&controlador=TipoDocumento&function=getData';
const bodyTbl = document.querySelector('#tableBodyTp');
const tableConfigTp = document.querySelector('#tHeadTP');

const render = new Render({
  btnEdit: {
    value: 'Editar',
    key: 'btnEdit',
    action: (id, row) => holaMundoEditar(id, row),
  },
  btnDelete: {
    value: 'eliminar',
    key: 'btnDelete',
    action: (id) => eliminarItem(id),
  },
});
render.renderData(url, bodyTbl, tableConfigTp, 'tp_id');

const holaMundoEditar = (id = 0, row = {}) => {
  console.log({ desdeHolamundoeditar: id });
  console.log({ desdeHolamundoeditar: row });
};

const eliminarItem = (id = 0) => {
  alert(`id elemento ${id}`);
};
