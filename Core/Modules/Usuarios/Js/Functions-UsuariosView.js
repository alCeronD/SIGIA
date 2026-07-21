import {
  addClassItem,
  createI,
  debounce,
  initAlert,
  InitComponents,
  Render,
  Validator,
} from '../../../../public/assets/js/utils/index.js';
import { events, selectors, typeInput, vars, dataPaginate } from './Selectors-UsuariosView.js';
// actualizar usuario
const updateUser = () => {
  const formUpdateUser = document.getElementById('formUpdateUser');

  if (formUpdateUser) {
    formUpdateUser.addEventListener('submit', async (e) => {
      e.preventDefault();

      const formData = new FormData(formUpdateUser);
      const data = Object.fromEntries(formData.entries());
      // data.action = "updateUser";

      // Validaciones mínimas (puedes usar validateFormData si gustas)
      if (
        !data.usu_nombres ||
        !data.usu_apellidos ||
        !data.usu_email ||
        !data.usu_telefono ||
        !data.usu_direccion ||
        !data.rol_id
      ) {
        initAlert('Por favor complete todos los campos obligatorios', 'error');
        return;
      }

      try {
        const result = await sendData(
          'modules/usuarios/controller/usuariosController.php',
          'POST',
          'updateUser',
          data
        );

        if (result.status === 'success') {
          initAlert(result.message, 'success');
          cerrarModalUsuario();
          setTimeout(() => {
            location.reload(); // Recargar para ver cambios
          }, 1500);
        } else {
          initAlert(result.message || 'Error al actualizar', 'error');
        }
      } catch (error) {
        initAlert(error.message || 'Error en la solicitud', 'error');
      }
    });
  }
};

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
      let iconEditar = createI('info');
      button.appendChild(iconEditar);
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
});
export const renderUsers = async (params = { pagina: 1 }) => {
  // si el filtro esta vacio, debemos de enviar la peticion sin parametros, en caso contrario, con el parametro especificado.
  const responseUsers = await Usuarios.getData(`${vars.url}consultUser`, 'GET', params);
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
  // re direccionamos eliminando el historial de la pagina anterior
  window.location.replace(`${vars.url}detailUser`);
};

//Cambiar estado de inactivar el usuario
document.querySelectorAll('.toggleEstadoBtn').forEach((button) => {
  button.addEventListener('click', async () => {
    const id = button.dataset.id;

    const confirmacion = confirm('¿Estás seguro de que deseas cambiar el estado del usuario?');
    if (!confirmacion) return;

    try {
      const payload = {
        action: 'cambiarEstado',
        usu_id: id,
      };

      const response = await fetch('modules/usuarios/controller/usuariosController.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (result.status === 'success') {
        initAlert(result.message, 'success', toastOptions);
        setTimeout(() => location.reload(), 150); // Recargar para reflejar cambios
      } else {
        initAlert(result.message || 'Error al cambiar el estado', 'error', toastOptions);
      }
    } catch (error) {
      initAlert(error.message || 'Error en la solicitud', 'error', toastOptions);
    }
  });
});
