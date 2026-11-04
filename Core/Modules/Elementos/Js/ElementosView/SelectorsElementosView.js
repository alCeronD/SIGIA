// selectores de la vista elementosView

// objeto tipo de elementos
export const typeElements = {
  dev: 'devolutivo',
  consu: 'consumible',
  all: 'all',
};

export const inputBusqueda = document.querySelector('#inputBusqueda');
export const filtroTipo = document.querySelector('#filtroTipo');
export const tipoElementoSelect = document.querySelector('#tipoElementoSelect');

export const tableElements = {
  table: document.querySelector('#tableElements'),
  tbody: document.querySelector('#tbodyElements'),
};

export const buttons = {
  addElement: document.querySelector('#btnAddElement'), // boton de agregar elemento
};

export const formsAddElement = {
  get formulario() {
    return document.querySelector('#addElementForm');
  },
  get nuevaPlacaRadio() {
    return document.querySelector('#nuevaPlaca');
  },
  get selectPlacaRadio() {
    return document.querySelector('#selectPlaca');
  },
  get placaRadioGroup() {
    return document.querySelectorAll('input[name="placaRadio"]');
  },

  get contentPlaca() {
    return document.querySelector('.contentPlaca');
  },
  get inputPlaca() {
    return document.querySelector('#elm_placa');
  },
  get inputSerie() {
    return document.querySelector('#elm_serie');
  },

  get placaAssocContent() {
    return document.querySelector('.placaAssocContent');
  },
  get searchPlaca() {
    return document.querySelector('#searchPlaca');
  },
  get respuestaPlaca() {
    return document.querySelector('#respuestaPlaca');
  },
  get serialPlacaAssoc() {
    return document.querySelector('#serialPlacaAssoc');
  },
  get tablePlaca() {
    return document.querySelector('#tablePlaca');
  },
  get tbodyPlacaResult() {
    return document.querySelector('#tbodyPlacaResult');
  },

  get nombreElemento() {
    return document.querySelector('#elm_nombre');
  },
  get selectAreas() {
    return document.querySelector('#selectAreas');
  },
  get selectAreaHidden() {
    return document.querySelector('#selectAreaHidden');
  },
  get selectCategorias() {
    return document.querySelector('#selectCategorias');
  },
  get selectCategoriasHidden() {
    return document.querySelector('#selectCategoriasHidden');
  },
  get selectMarcas() {
    return document.querySelector('#selectMarca');
  },
  get selectMarcaHidden() {
    return document.querySelector('#selectMarcaHidden');
  },

  get devolutivoCheckbox() {
    return document.querySelector('#devolutivoCheckbox');
  },
  get consumibleCheckbox() {
    return document.querySelector('#consumibleCheckbox');
  },
  get tpElementoGroup() {
    return document.querySelectorAll('input[name="elm_cod_tp_elemento"]');
  },

  get checkboxTpElemento() {
    return document.querySelector('.checkboxTpElemento');
  },
  get undMedida() {
    return document.querySelector('#undMedida');
  },
  get inputCantidad() {
    return document.querySelector('#inputCantidad');
  },

  get observacionInput() {
    return document.querySelector('#observacionInput');
  },
  get sugerenciaInput() {
    return document.querySelector('#sugerenciaInput');
  },

  get submitButton() {
    return document.querySelector('#addElementForm button[type="submit"]');
  },
};

export const formEditElement = {};
