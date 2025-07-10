document.addEventListener('DOMContentLoaded', function () {
  M.FormSelect.init(document.querySelectorAll('select'));
// console.log("HolaMundo");
  const selectTipo = document.getElementById('tipoElemento');
  const selectEstado = document.getElementById('estadoElemento');
  const tablaBody = document.getElementById('tabla-elementos-body');
  const paginacion = document.getElementById('paginacion-elementos');
  const btnDescargar = document.getElementById('btnDescargar');

  let elementos = [];
  let visibles = [];
  const itemsPorPagina = 10;

  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;

    tablaBody.innerHTML = '';
    visibles.slice(inicio, fin).forEach(e => {
      tablaBody.innerHTML += `
        <tr>
          <td>${e.codigoElemento}</td>
          <td>${e.nombreElemento}</td>
          <td>${e.placa || '—'}</td>
          <td>${e.cantidad || 0}</td>
          <td>${e.estadoElemento}</td>
        </tr>`;
    });

    document.querySelectorAll('#paginacion-elementos li').forEach(el => el.classList.remove('active'));
    const activo = document.querySelector(`#paginacion-elementos li[data-pagina="${pagina}"]`);
    if (activo) activo.classList.add('active');
  }

    //Paginacion
  function generarPaginacion(totalPaginas) {
    paginacion.innerHTML = '';

    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('waves-effect');
      li.setAttribute('data-pagina', i);
      li.innerHTML = `<a href="#!">${i}</a>`;
      li.addEventListener('click', (e) => {
        e.preventDefault();
        mostrarPagina(i);
      });
      paginacion.appendChild(li);
    }

    if (totalPaginas > 0) {
      mostrarPagina(1);
    } else {
      tablaBody.innerHTML = `<tr><td colspan="5" class="red-text">No se encontraron elementos</td></tr>`;
    }
  }

    //Consulta y carga de elementos por estado
  function cargarElementos(tipo, estado) {
    const url = `<?= getUrl('reportes', 'reportes', 'filtrarElementosAjax', false, 'dashboard'); ?>`;
    const formData = new FormData();
    formData.append('estadoElemento', estado);
    formData.append('tipoElemento', tipo);

    fetch(url, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(data => {
      elementos = data;
      visibles = [...elementos];
      const totalPaginas = Math.ceil(visibles.length / itemsPorPagina);
      generarPaginacion(totalPaginas);

      btnDescargar.href = `<?= getUrl('reportes', 'reportes', 'generarReporteExcel'); ?>&tipoElemento=${encodeURIComponent(tipo)}&estadoElemento=${encodeURIComponent(estado)}`;
    })
    .catch(err => {
      console.error('Error al cargar elementos:', err);
      tablaBody.innerHTML = `<tr><td colspan="5" class="red-text">Error al cargar elementos</td></tr>`;
    });
  }

  // Inicial cargar todos
  cargarElementos('', '');

  selectTipo.addEventListener('change', () => {
    cargarElementos(selectTipo.value, selectEstado.value);
  });

  selectEstado.addEventListener('change', () => {
    cargarElementos(selectTipo.value, selectEstado.value);
  });
});