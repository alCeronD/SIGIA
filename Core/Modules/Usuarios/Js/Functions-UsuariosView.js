import {
  addClassItem,
  closeModal,
  createI,
  debounce,
  fillDataForm,
  initAlert,
  InitComponents,
  messages,
  mostrarConfirmacion,
  openModal,
  Render,
  Storage,
  validateFormData,
  Validator,
} from '../../../../public/assets/js/utils/index.js';
import {
  events,
  selectors,
  typeInput,
  vars,
  dataPaginate,
  modals,
  buttons,
  forms,
  inputOptionals,
  mapForm,
  messagesUser,
  titlesUsers,
} from './Selectors-UsuariosView.js';

/** Function para renderizar los filtros de la vista usuariosView */
export const renderFilters = () => {
  const tipoFiltro = document.querySelector('#tipoFiltro');
  const contenedorInputFiltro = document.querySelector('#contenedorInputFiltro');

  if (tipoFiltro && contenedorInputFiltro) {
    tipoFiltro.addEventListener('change', () => {
      contenedorInputFiltro.innerHTML = '';

      // Limpiar input anterior si existe
      const inputAnterior = document.querySelector('#inputFiltro') ?? null;
      if (inputAnterior) inputAnterior.value = '';

      const tipo = tipoFiltro.value;
      const valuePlaceHolder =
        tipo === 'nombre' ? 'Digite el nombre *' : 'Digite el nro e documento *';

      contenedorInputFiltro.style.display = 'grid';
      const fragmentOptions = document.createDocumentFragment();
      if (tipo === 'estado') {
        const selectEstado = document.createElement('select');
        selectEstado.setAttribute('id', 'inputFiltro');
        const optionDisable = document.createElement('option');
        const optionActive = document.createElement('option');
        const optionInactive = document.createElement('option');
        optionDisable.disabled = true;
        optionDisable.selected = true;
        optionDisable.value = '';
        optionDisable.innerHTML = 'Estado';
        optionActive.value = 'activo';
        optionActive.innerText = 'Activo';
        optionInactive.value = 'inactivo';
        optionInactive.innerText = 'Inactivo';
        selectEstado.append(optionDisable, optionActive, optionInactive);
        fragmentOptions.append(selectEstado);

        contenedorInputFiltro.append(fragmentOptions);
        InitComponents.initSelect();
      } else {
        contenedorInputFiltro.innerHTML = '';
        const spanError = document.createElement('span');

        addClassItem(spanError, { helper: 'helper-text' });
        spanError.dataset.error = '';
        spanError.dataset.success = '';
        const input = document.createElement('input');
        input.setAttribute('id', 'inputFiltro');
        input.setAttribute('placeholder', valuePlaceHolder);

        // definimos el tipo del selector, si es text o directamente number
        input.setAttribute('type', typeInput[tipo]);

        addClassItem(input, { validate: 'validate' });
        fragmentOptions.append(input, spanError);
        contenedorInputFiltro.append(fragmentOptions);
        InitComponents.initInputs();
      }

      let inputFiltro = document.querySelector('#inputFiltro');
      const event = tipo === 'estado' ? 'change' : 'input';

      const keysType = Object.keys(typeInput);

      // evento para enviar la data al backend.
      inputFiltro.addEventListener(
        event,
        debounce((e) => {
          e.preventDefault();
          e.stopPropagation();
          const newValue = e.target.value.replace(/[^a-zA-Z0-9]/g, ''); //reemplazamos lo que escribimos por vacio en caso de que se digiten o se presionen botones diferentes al abecedario y numeros.
          // hacer peticion solo cuando la longitud sea mayor a 5
          let dataFilter = {
            keyFilter: tipo,
            valueFilter: newValue,
          };

          // validamos usando la clase validator con el metodo blur en caso de que el usuario no digite ningun valor y asi, no enviar la peticion.
          if (inputFiltro && tipo === keysType[0] && inputFiltro.value != '') {
            Validator.validateInput({ input: inputFiltro, rule: 'empty' });
          } else if (inputFiltro && tipo === keysType[1]) {
            Validator.validateInput({ input: inputFiltro, rule: 'empty' });
          }

          if (
            tipo === 'nombre' &&
            inputFiltro.value != '' &&
            !Validator.validateRule({ value: inputFiltro.value, rule: 'letras' })
          ) {
            initAlert('solo se permiten letras', 'info');
            return;
          }

          if (event === events.input && String(newValue).length >= 2) {
            renderUsers(dataFilter);
          }
          if (event === events.change && e.target.value != '') {
            renderUsers(dataFilter);
          }

          if (event === events.input && e.target.value === '') {
            renderUsers();
          }
        }, 700)
      );
    });
  }
};

const Usuarios = new Render({
  btnInfo: {
    /**
     * @param {Object} row
     * @param {HTMLButtonElement} button - boton propio para asi acceder a las propiedades del elemento.
     */
    value: (row, button) => {
      button.setAttribute('data-id', `${row.IdUsuario}`);
      let iconInfo = createI('info');
      button.appendChild(iconInfo);
      addClassItem(button, {
        btn: 'btn',
        waves: 'waves-effect',
        hoover: 'waves-orange',
        cyan: 'cyan darken-2', //button color.
      });
    },
    key: 'btnInfo',
    action: (id, fullRow) => verDetalle(id, fullRow),
  },
  btnEdit: {
    /**
     * @param {Object} row = {}
     * @param {HTMLButtonElement} button - boton propio para asi acceder a las propiedades del elemento.
     */
    value: (row, button) => {
      let iconEditar = createI('edit');
      button.appendChild(iconEditar);
      addClassItem(button, {
        btn: 'btn',
        waves: 'waves-effect',
        hoover: 'waves-orange',
        cyan: 'green darken-2', //button color.
      });
    },
    key: 'btnEdit',
    action: (id, fullRow) => editData(id, fullRow),
  },
  btnChangeStatus: {
    /**
     * @param {Object} fullRow = {}
     * @param {HTMLButtonElement} button
     */
    value: (fullRow, button) => {
      // implementamos el estado del usuario.

      let icon =
        fullRow['estado_usuario'] === 'Activo' ? createI('check') : createI('do_not_disturb');

      let cyan = fullRow['estado_usuario'] === 'Activo' ? 'cyan darken-4' : 'red darken-3';
      let hoover = fullRow['estado_usuario'] === 'Activo' ? 'waves-green' : 'waves-teal';

      addClassItem(button, {
        btn: 'btn',
        waves: 'waves-effect',
        hoover: hoover,
        cyan: cyan, //button color.
      });

      button.appendChild(icon);
    },
    key: 'btnChangeStatus',
    action: (id, fullRow) => changeStatusUser(id, fullRow),
  },
});
export const renderUsers = async (params = { pagina: 1 }) => {
  // si el filtro esta vacio, debemos de enviar la peticion sin parametros, en caso contrario, con el parametro especificado.
  const responseUsers = await Usuarios.getData(`${vars.url}getData`, 'GET', params);
  let dataResponse = responseUsers.data;
  let realPage = responseUsers.data.paginaActual;

  Usuarios.actualPage(realPage);
  dataPaginate['totalRegistros'] = dataResponse.totalRegistros;
  dataPaginate['paginaActual'] = dataResponse.paginaActual;
  dataPaginate['cantidadPaginas'] = dataResponse.cantidadPaginas;

  Usuarios.renderData(
    selectors.tbodyUsuarios,
    selectors.tHeaderUsuarios,
    'IdUsuario',
    dataResponse.data
  );
  Usuarios.renderPaginate(dataPaginate, selectors.footerUsers);
};

/** Logica de la paginación. */
export const executePaginate = () => {
  let actualPage = 1;
  // aplicamos la paginacion en la responsabilidad del footer.
  selectors.footerUsers.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    let btnValue = e.target.closest('.btnPaginate') ? e.target.dataset.action : null;

    // ejecutamos el evento para una pagina en especifico.
    if (e.target.closest('.liPaginate')) {
      let actualPageData = e.target.closest('.liPaginate') ? e.target.dataset.actualpage : 1;
      renderUsers({ pagina: actualPageData });
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

      renderUsers({ pagina: actualPage });
    }
  });
};

const verDetalle = (idRow, fullRow) => {
  // Guardamos los datos en localstorage y cuando estemos en el archivo lo recibimos y lo consultamos.
  Storage.addValue({ key: 'detailUSer', item: JSON.stringify(fullRow) });
  window.location.replace(`${vars.url}detailUserView`);
};

const editData = (id, fullRow) => {
  // re asignamos las keys del objeto correspondiente al nombre del input y eliminamos las propiedades nuevas.
  fullRow['usu_docum'] = fullRow.nroDocumento;
  fullRow['usu_id'] = fullRow.IdUsuario;
  fullRow['usu_apellidos'] = fullRow.apellidos;
  fullRow['usu_nombres'] = fullRow.nombreCompleto;
  fullRow['usu_email'] = fullRow.email;
  fullRow['usu_telefono'] = fullRow.telefono;
  fullRow['usu_direccion'] = fullRow.direccion;
  fullRow['usu_password'] = fullRow.password;
  delete fullRow.nroDocumento;
  delete fullRow.apellidos;
  delete fullRow.nombreCompleto;
  delete fullRow.email;
  delete fullRow.telefono;
  delete fullRow.direccion;
  delete fullRow.password;
  delete fullRow.IdUsuario;

  fillDataForm(fullRow, forms.formUpdateDataUser);
  InitComponents.initInputs(); // re iniciamos los inputs.

  // abrir el modal.
  openModal(modals.modalEditarUsuario);
};

closeModal(modals.modalEditarUsuario, buttons.btnCloseModalEditarUsuario);

const changeStatusUser = (id, fullRow) => {
  try {
    const status = fullRow['estado_usuario'] === 'Activo' ? '2' : '1';
    const message = status === '2' ? titlesUsers.inactiveUser : titlesUsers.activeUser;
    const text = status === '2' ? messagesUser.inactiveUser : messagesUser.activeUser;

    mostrarConfirmacion(message, text, async (response) => {
      if (!response) return;

      let dataStatus = {
        usu_id_estado: status,
        usu_id: id,
      };

      const responseChangeStatus = await Usuarios.sendData(
        `${vars.url}changeStatusUser `,
        'PUT',
        dataStatus
      );

      if (responseChangeStatus.status) {
        initAlert(responseChangeStatus.message, 'success');
        renderUsers({ pagina: dataPaginate.paginaActual });
        return;
      }
    });
  } catch (error) {
    initAlert(error.message, 'error');
    return;
  }
};

// evento para actualizar el usuario.
export const updateUser = () => {
  if (forms.formUpdateDataUser) {
    forms.formUpdateDataUser.addEventListener('submit', async (e) => {
      e.preventDefault();
      e.stopPropagation();

      const formData = new FormData(e.target);
      const data = Object.fromEntries(formData);
      let usu_observacion = data.usu_observacion;

      if (String(usu_observacion).length > 100) {
        initAlert('limite de caracteres superado en el campo de observacion', 'info');
        return;
      }
      // esto esta comentado hasta que solucione el rol
      // if (!validateFormData({ formData: formData, campos: inputOptionals, mapForm: mapForm }))
      //   return;

      try {
        const result = await Usuarios.sendData(`${vars.url}save`, 'PUT', data);

        if (result.status) {
          initAlert(result.message, 'success');
          modals.modalEditarUsuario.style.display = 'none';
          // renderizar nuevamente la pagina.
          renderUsers({ pagina: dataPaginate.paginaActual });
          return;
        }
      } catch (error) {
        initAlert(error.message || 'Error en la solicitud', 'error');
      }
    });
  }
};
