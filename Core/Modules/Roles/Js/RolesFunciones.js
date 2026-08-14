import {
  initAlert,
  optionsSelect,
  Render,
  createI,
  addClassItem,
  mostrarConfirmacion,
  InitComponents,
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
const executeRolesFunciones = async (idRol = '', actualPage) => {
  // si no se envia nada, este solamente limpia la tabla y retorna el proceso
  if (!idRol) {
    bodyRolesFunciones.innerHTML = '';
    return;
  }
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
    moduloAsociado: element.moduloAsociado,
  }));

  dataPaginate = {};
  dataPaginate['totalRegistros'] = responseRolesFunciones.data.totalRegistros;
  dataPaginate['paginaActual'] = responseRolesFunciones.data.paginaActual;
  dataPaginate['cantidadPaginas'] = responseRolesFunciones.data.cantidadPaginas;
  RolesFunciones.renderData({
    bodyTbl: bodyRolesFunciones,
    headerTable: headerRoles,
    id: 'id',
    data: dataReduce,
  });
  RolesFunciones.renderPaginate(dataPaginate, footerRolesFunciones);
};

// acciones de la vista
const eliminarFuncion = (id) => {
  try {
    mostrarConfirmacion(
      'Eliminar funcion',
      '¿Está seguro de eliminar esta funcion asociada al rol?',
      async (response) => {
        if (!response) return;

        const responseDeleteRlF = await RolesFunciones.sendData(
          `${url}deleteRoleFuncion`,
          'DELETE',
          {
            rlp_id: id,
          }
        );

        if (!responseDeleteRlF.status) {
          initAlert(responseDeleteRlF.message, 'info');
          return;
        }

        initAlert(responseDeleteRlF.message, 'success');
        executeRolesFunciones(rolIdSelect);
        return;
      }
    );
  } catch (error) {
    console.error(error);
  }
};

// evento para seleccionar el rol y visualizar los roles.
selectRol.addEventListener('change', (e) => {
  e.stopPropagation();
  e.preventDefault();
  rolIdSelect = e.target.value;

  // funcion para captura de datos y renderizado.
  executeRolesFunciones(rolIdSelect);
});

/** Logica de la paginación. */
const executePaginate = () => {
  let actualPage = 1;
  // aplicamos la paginacion en la responsabilidad del footer.
  footerRolesFunciones.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    let btnValue = e.target.closest('.btnPaginate') ? e.target.dataset.action : null;
    // ejecutamos el evento para una pagina en especifico.
    if (e.target.closest('.liPaginate')) {
      let pageLi = e.target.closest('.liPaginate') ? e.target.dataset.actualpage : 1;
      actualPage = parseInt(pageLi); //parseamos el dato porque se requiere de tipo int para renderizar la pagina.

      //setter para asignar el valor de la pagina a la propiedad de la instancia
      RolesFunciones.actualPage = actualPage;
      executeRolesFunciones(rolIdSelect, actualPage);
      return;
    }

    // EJECUTAMOS LOS EVENTOS PARA LOS BOTONES BTNPAGINATE
    if (e.target.closest('.btnPaginate')) {
      if (!btnValue) return;
      if (btnValue === 'preview') {
        // re asignamos la pagina recibida por la peticion para asi reducir el valor y re enviar la peticion con la pagina anterior.
        actualPage = dataPaginate.paginaActual;
        actualPage--;
        if (actualPage < 1) {
          actualPage = 1;
          return;
        }
      }
      if (btnValue === 'next') {
        actualPage++;
        if (actualPage > dataPaginate.cantidadPaginas) {
          actualPage = dataPaginate.cantidadPaginas;
          return;
        }
      }
      // setter para asignar la pagina actual a la instancia.s
      RolesFunciones.actualPage = actualPage;
      executeRolesFunciones(rolIdSelect, actualPage);
    }
  });
};

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
  InitComponents.initSelect();
  // ejecutamos function de paginacion
  executePaginate();
});
