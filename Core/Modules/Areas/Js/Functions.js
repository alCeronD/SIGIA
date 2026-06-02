import { createBtn, getData } from '../../../../public/assets/js/utils/index.js';
import * as s from './Selectors.js';

export const renderData = async (pageUser = 1) => {
  try {
    const response = await getData(`${s.url}getData`, 'GET', { pagina: pageUser });
    let data = response.data.data;
    let paginaActual = response.data.paginaActual;
    let totalRegistros = response.data.totalRegistros;
    let cantidadPaginas = response.data.cantidadPaginas;
    // Renderizar los datos.
    s.tableBody.innerHTML = '';
    let fragmentBody = document.createDocumentFragment();
    data.forEach((dta) => {
      let tr = document.createElement('tr');
      let tdCodigo = document.createElement('td');
      let tdNombre = document.createElement('td');
      let tdDescript = document.createElement('td');
      let tdStatus = document.createElement('td');
      let tdOptions = document.createElement('td');

      const keys = Object.keys(dta);
      const values = Object.values(dta);

      let btnEdit = createBtn('btnEdit');
      let btnEliminar = createBtn('btnDelete');
      let btnChangeStatus = createBtn('btnChangeStatus');

      tdCodigo.innerText = dta.ar_cod;
      tdNombre.innerText = dta.ar_nombre;
      tdDescript.innerText = dta.ar_descripcion;
      tdStatus.innerText = dta.ar_status === 2 ? 'Inactivo' : 'Activo';
      btnEdit.innerText = 'Editar';
      btnEdit.value = dta.ar_cod;
      btnEdit.setAttribute('status', dta.ar_status);
      btnEliminar.innerText = 'Eliminar';
      btnEliminar.value = dta.ar_cod;
      btnChangeStatus.innerText = dta.ar_status === 2 ? 'Activar' : 'Inhabilitar';
      btnChangeStatus.value = dta.ar_status;
      btnChangeStatus.setAttribute('dataCod', dta.ar_cod);
      tdOptions.append(btnEdit, btnEliminar, btnChangeStatus);
      tr.append(tdCodigo, tdNombre, tdDescript, tdStatus, tdOptions);

      fragmentBody.appendChild(tr);
    });

    s.tableBody.appendChild(fragmentBody);
    renderPaginate(totalRegistros, paginaActual, cantidadPaginas);
  } catch (error) {
    console.error(error);
  }
};

let actualPage = 1;

export const renderPaginate = (totalRegistros, paginaActual, cantidadPaginas) => {
  s.footerArea.innerHTML = '';
  let btnPreview = createBtn('btnPreview');
  let btnNext = createBtn('btnNext');
  btnPreview.innerText = '<';
  btnNext.innerText = '>';
  let spanText = `Registros totales: ${totalRegistros}`;
  let paginas = `Pagina ${paginaActual} de ${cantidadPaginas}`;
  let fragment = document.createDocumentFragment();
  fragment.append(btnPreview, paginas, btnNext);
  btnPreview.setAttribute('class', 'btnPaginate');
  btnNext.setAttribute('class', 'btnPaginate');
  btnPreview.classList.add('btnPreview');
  btnNext.classList.add('btnNext');
  s.footerArea.appendChild(fragment);
  let btns = document.querySelectorAll('.btnPaginate');
  btns.forEach((element) => {
    element.addEventListener('click', (g) => {
      // evento boton next
      if (g.target.classList.contains('btnNext')) {
        actualPage++;
        if (actualPage > cantidadPaginas) {
          actualPage = cantidadPaginas;
          return;
        }
      }

      // evento para el boton preview
      if (g.target.classList.contains('btnPreview')) {
        actualPage--;
        if (actualPage < 1) {
          actualPage = 1;
        }
      }
      if (actualPage < 1 || actualPage > cantidadPaginas) {
        return;
      }
      renderData(actualPage);
    });
  });
};

export default renderData;
