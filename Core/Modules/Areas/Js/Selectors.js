export const url = 'dashboard.php?modulo=Areas&controlador=Areas&function=';
export const btnAreaSend = document.querySelector('#btnAreaSend');
export const tableBody = document.querySelector('#tableBodyArea');
export const areaUpdateForm = document.querySelector('#areaUpdateForm');
export const table = 'areas'; //Nombre de la tabla.
export const status = 1; //Estatus del registro, si está activo o no. 1= activo, 0 =inactivo
export const footerArea = document.querySelector('#footerArea');
export const btnCloseModalUpdate = document.querySelector('.closeModalBtn');
export const modalAreaUpdate = document.querySelector('#modalArea');
export const btnPaginate = document.querySelectorAll('.btnPaginate');
export const formCreate = document.querySelector('#formArea');
export const formUpdate = document.querySelector('#areaUpdateForm');
export const tableHeadArea = document.querySelector('#tableHeadArea');
// mapeo del formulario para guardar cambios y/o adicionar un nuevo registro.
export const mapCampos = {
  ar_nombre: 'Nombre',
  ar_descripcion: 'Descripción',
};
export let actualPage = 1;

export const titleActualizar = 'Actualizar registro';
export const textEstaSeguro = '¿Esta seguro de actualizar el registro?';
export const titleInhabilitar = 'Inhabilitar departamento';
export const titleHabilitar = 'Habilitar departamento';
export const textEstaSeguroHabilitar = '¿Está seguro de habilitar este registro?';
export const textEstaSeguroInhabilitar = '¿Está seguro de Inhabilitar este registro?';
export const textEstaSeguroEliminar = '¿Está seguro de eliminar este registro?';
export const textDepartamentoRequired = 'El nombre del item debe ser obligatorio';

export const buttons = {};
