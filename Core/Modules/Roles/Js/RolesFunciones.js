import {
  initAlert,
  optionsSelect,
  Render,
  createI,
  addClassItem,
} from '../../../../public/assets/js/utils/index.js';

const selectRol = document.querySelector('#selectRol');
const bodyRolesFunciones = document.querySelector('#bodyRolesFunciones');
const headerRoles = document.querySelector('#headerRoles');
const footerRolesFunciones = document.querySelector('#footerRolesFunciones');
const url = 'dashboard.php?modulo=Roles&controlador=RolesFunciones&function=';
const RolesFunciones = new Render({
  btnEliminar: {
    value: (rowFunctions, button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('class', 'btnDelete');
      button.dataset.id = rowFunctions.id;
      let iconStatus = null;
      let propertiesButton = null;
      propertiesButton = { btn: 'btn', waves: 'waves-red', red: 'red' };
      iconStatus = createI('delete_forever');
      addClassItem(button, propertiesButton);
      button.appendChild(iconStatus);
    },
    key: 'btnEliminar',
    action: (id) => eliminarFuncion(id),
  },
});
let dataPaginate = {};
let actualPage = 1;
let responseRolesFunciones = null; //variable que se usa para guardar los registros de las funciones asociadas al rol
let rolIdSelect = null; //variable en donde se guarda el id del rol seleccionado por el usuario
const executeRolesFunciones = async (idRol, actualPage) => {
  responseRolesFunciones = await RolesFunciones.getData(`${url}getFuncionesAssocRoles`, 'GET', {
    idRol: idRol,
    limit: 4,
    actualPage: actualPage,
  });

  // objeto para ordenar el objeto de una forma personalizada
  let dtaRolesFunciones = responseRolesFunciones.data.data;
  // si no hay registros, no renderizar.
  if (dtaRolesFunciones.length === 0) {
    initAlert(responseRolesFunciones.message, 'info');
    return;
  }
  const dataReduce = dtaRolesFunciones.map((element) => ({
    id: element.id,
    idFuncion: element.idFuncion,
    nombreFuncion: element.nombreFuncion,
    modulo: element.moduloAsociado,
  }));

  dataPaginate = {};
  dataPaginate['totalRegistros'] = responseRolesFunciones.data.totalRegistros;
  dataPaginate['paginaActual'] = responseRolesFunciones.data.paginaActual;
  dataPaginate['cantidadPaginas'] = responseRolesFunciones.data.cantidadPaginas;
  RolesFunciones.renderData(bodyRolesFunciones, headerRoles, 'id', dataReduce);
  RolesFunciones.renderPaginate(dataPaginate, footerRolesFunciones);
};

// acciones de la vista
const eliminarFuncion = (id) => {
  console.log(id);
};

// evento para seleccionar el rol y visualizar los roles.
selectRol.addEventListener('change', (e) => {
  e.stopPropagation();
  e.preventDefault();
  rolIdSelect = e.target.value;

  // funcion para captura de datos y renderizado.
  executeRolesFunciones(rolIdSelect);
});

footerRolesFunciones.addEventListener('click', (f) => {
  let button = f.target.tagName.toLowerCase();
  // si en donde el usuario dio click el tag es de tipo button, dejecutar ciertas instrucciones.
  if (button === 'button') {
    if (f.target.value === 'preview') {
      actualPage--;
      if (actualPage < 1) {
        actualPage = 1;
        return;
      }
    }
    if (f.target.value === 'next') {
      actualPage++;
      // si el valor de la pagina que presiona el usuario es mayor a las que hay en la tabla, dejar el de la tabla
      if (actualPage > responseRolesFunciones.data.cantidadPaginas) {
        actualPage = responseRolesFunciones.data.cantidadPaginas;
        return;
      }
    }
    RolesFunciones.actualPage(actualPage); //setter para asignar el valor de la pagina a la propiedad de la instancia
    executeRolesFunciones(rolIdSelect, actualPage);
  }
});

document.addEventListener('DOMContentLoaded', async () => {
  // bodyRolesFunciones.innerHTML = 'seleccione el rol para visualizar las funciones asociadas';
  const dataRoles = await RolesFunciones.getData(`${url}getRoles`, 'GET');
  let dtaRoles = dataRoles.data;
  let optionDesabled = document.createElement('option');
  optionDesabled.setAttribute('disabled', '');
  optionDesabled.setAttribute('selected', '');
  optionDesabled.innerText = 'Seleccione el rol';
  const fragment = document.createDocumentFragment();
  fragment.appendChild(optionDesabled);
  selectRol.appendChild(fragment);
  dtaRoles.forEach((elm) => {
    let id = elm.rl_id;
    let nombre = elm.rl_nombre;

    let optionRol = document.createElement('option');
    optionRol.setAttribute('value', `${id}`);
    optionRol.innerText = nombre;
    fragment.appendChild(optionRol);
  });
  selectRol.appendChild(fragment);

  // inicializar selects
  const selectsMaterialize = document.querySelectorAll('select');
  let instances = M.FormSelect.init(selectsMaterialize, optionsSelect);
});
