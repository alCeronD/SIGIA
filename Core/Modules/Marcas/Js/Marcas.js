import {
  closeModal,
  initAlert,
  mostrarConfirmacion,
  openModal,
  Render,
} from '../../../../public/assets/js/utils/index.js';

const marcas = new Render({
  btnEdit: {
    value: 'editar',
    key: 'btnEdit',
    action: (row, id) => editarMarca(row, id),
  },
  btnDelete: {
    value: 'eliminar',
    key: 'btnDelete',
    action: (id) => eliminarMarca(id),
  },
  btnChangeStatus: {
    value: (row) => {
      return row.ma_status === 1 ? 'Inhabilitar' : 'Habilitar';
    },
    key: 'btnChangeStatus',
    action: (id, row) => changeStatus(id, row),
  },
});
let headerTable = document.querySelector('#tblHeaderMarca');
let tableBody = document.querySelector('#marcaTblBody');
let marcaTblFooter = document.querySelector('#marcaTblFooter');
let modalMarca = document.querySelector('#modalMarca');
let closeModalBtn = document.querySelector('.closeModalBtn');
let marcaUpdateForm = document.querySelector('#marcaUpdateForm');
let marcaInsertForm = document.querySelector('#marcaForm');
const url = 'dashboard.php?modulo=Marcas&controlador=Marcas&function=';
let responseMarcas = null;
let actualPage = 1;
let dataPaginate = {};
let data = null;

// function para cargar la tabla.
const loadTable = async (actualPage) => {
  // si la cantidad de registros reduce a 0 ir a la pagina anterior.
  responseMarcas = await marcas.getData(`${url}getData`, 'GET', { pagina: actualPage, limit: 4 });
  data = responseMarcas.data.data;
  dataPaginate = {};
  dataPaginate['cantidadPaginas'] = responseMarcas.data.cantidadPaginas;
  dataPaginate['paginaActual'] = responseMarcas.data.paginaActual;
  dataPaginate['totalRegistros'] = responseMarcas.data.totalRegistros;

  // enviamos la respuesta a renderizar.
  marcas.renderData(tableBody, headerTable, 'ma_id', data, {
    ma_status: 'ma_status',
  });
  // creamos la estructura del paginado.
  marcas.renderPaginate(dataPaginate, marcaTblFooter);
};

// Functiones de accion
const editarMarca = (id, row) => {
  openModal(modalMarca);
  let input = null;
  // llenar la data en el formulario
  for (const [key, value] of Object.entries(row)) {
    input =
      marcaUpdateForm.querySelector(`input[name="${key}"]`) ||
      marcaUpdateForm.querySelector(`textarea[name="${key}"]`);

    if (input != null) {
      input.value = value;
    }
  }
  M.updateTextFields();
};
const eliminarMarca = (id) => {
  let dataDelete = {
    ma_id: id,
  };
  mostrarConfirmacion('Eliminar marca', '¿Esta seguro de eliminar este item?', async (response) => {
    if (!response) return;

    try {
      let responseDelete = await marcas.sendData(`${url}deleteMarca`, 'DELETE', dataDelete);
      let allTds = tableBody.querySelectorAll('tr');
      if (!responseDelete.status) {
        initAlert(responseDelete.message, 'info');
        return;
      }
      initAlert(responseDelete.message, 'success');
      loadTable(actualPage);
      return;
    } catch (error) {
      console.error(error);
    }
  });
};
const changeStatus = (id, row) => {
  // console.log(id, row);

  let dataStatus = {
    ma_status: row.ma_status === 1 ? 2 : 1,
    ma_id: id,
  };
  console.log(dataStatus);
  let title = row.ma_status === 1 ? 'Inhabilitar marca' : 'Habilitar Marca';
  let message =
    row.ma_status === 1
      ? '¿Está seguro de inhabilitar este registro?'
      : '¿Esta seguro de habilitar este registro?';
  mostrarConfirmacion(title, message, async (response) => {
    if (!response) return;

    let responseStatus = await marcas.sendData(`${url}changeStatus`, 'PUT', dataStatus);
    console.log(responseStatus);

    if (responseStatus.status) {
      initAlert(responseStatus.message, 'success');
      loadTable(actualPage);
      return;
    }
  });
};

//Eventos
// paginacion.
marcaTblFooter.addEventListener('click', (f) => {
  f.preventDefault();
  f.stopPropagation();

  if (f.target.closest('button')) {
    let valueButton = f.target.closest('button');

    if (valueButton.value === 'next') {
      actualPage++;
      marcas.actualPage(actualPage);
      // validamos si el valor de la pagina es mayor que la cantidad de paginas para asi evitar hacer peticion.
      if (actualPage > dataPaginate.cantidadPaginas) {
        actualPage = dataPaginate.cantidadPaginas;
        return;
      }
    }
    // capturar si el tipo es button
    if (valueButton.value === 'preview') {
      actualPage--;
      marcas.actualPage(actualPage);

      if (actualPage < 1) {
        actualPage = 1;
        return;
      }
    }
    loadTable(actualPage);
  }
});
// update
marcaUpdateForm.addEventListener('submit', (g) => {
  g.preventDefault();
  g.stopPropagation();
  let formData = new FormData(g.target);
  let dataUpdate = Object.fromEntries(formData);

  try {
    mostrarConfirmacion(
      'actualizar marca',
      'Deseas actualizar este registro?',
      async (response) => {
        if (!response) {
          modalMarca.style.display = 'none';
          return;
        }

        const responseMarca = await marcas.sendData(`${url}updateMarca`, 'PUT', dataUpdate);
        // 204 en caso de que el update nos devuelva un 0, significa que no hubo filas afectadas.
        if (responseMarca.status === 204) {
          modalMarca.style.display = 'none';
          return;
        }

        if (responseMarca.status) {
          modalMarca.style.display = 'none';
          initAlert(responseMarca.message, 'success');
          loadTable(actualPage);
          return;
        }
      }
    );
  } catch (error) {
    console.error(error);
  }
});
// insert
marcaInsertForm.addEventListener('submit', (e) => {
  e.stopPropagation();
  e.preventDefault();
  let formData = new FormData(e.target);
  let insertData = Object.fromEntries(formData);

  try {
    mostrarConfirmacion('crear marca', '¿Esta seguro de crear esta marca?', async (response) => {
      if (!response) return;

      let responseInsert = await marcas.sendData(`${url}createMarca`, 'POST', insertData);

      if (responseInsert.status) {
        initAlert(responseInsert.message, 'success');
        // limpiar formulario
        e.target.reset();
        loadTable(actualPage);
        return;
      }
    });
  } catch (error) {
    console.error(error);
  }
});

closeModal(modalMarca, closeModalBtn);

document.addEventListener('DOMContentLoaded', async () => {
  await loadTable(actualPage);
});
