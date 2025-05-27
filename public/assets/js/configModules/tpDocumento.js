import { Ajax } from "../libraries/ajax.js";

const formulario = document.querySelector("#formTp");
const objAjax2 = new Ajax();

let table = 'tipo_documento';

let status = 1;
const myModal = document.querySelector("#modalTp");
//Cuerpo de tabla.
const tableBody = document.querySelector("#tableBody");
//Boton de update del modal
const areaUpdateForm = document.querySelector("#tpUpdateForm");

let idPk;
let nombreTp;
let descripcion;

