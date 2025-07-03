document.addEventListener("DOMContentLoaded", () => {
  // Botones Editar
  document.querySelectorAll(".btnEditarUsuario").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      document.getElementById("usu_id").value = btn.dataset.id;
      document.getElementById("usu_docum").value = btn.dataset.documento;
      document.getElementById("usu_nombres").value = btn.dataset.nombres;
      document.getElementById("usu_apellidos").value = btn.dataset.apellidos;
      document.getElementById("usu_email").value = btn.dataset.email;
      document.getElementById("usu_telefono").value = btn.dataset.telefono;
      document.getElementById("modalEditarUsuario").style.display = "flex";
    });
  });

  // Variables globales
  const filas = Array.from(document.querySelectorAll('#tableConfig tbody tr'));
  let filasFiltradas = [...filas];
  const paginacion = document.getElementById('paginacion-usuarios');
  const itemsPorPagina = 5;

  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;

    filas.forEach(fila => fila.style.display = 'none'); // Oculta todas

    filasFiltradas.forEach((fila, index) => {
      fila.style.display = (index >= inicio && index < fin) ? 'table-row' : 'none';
    });

    document.querySelectorAll('#paginacion-usuarios li').forEach(li => li.classList.remove('active'));
    const liActivo = document.querySelector(`#paginacion-usuarios li[data-pagina="${pagina}"]`);
    if (liActivo) liActivo.classList.add('active');
  }

  function generarPaginacion() {
    paginacion.innerHTML = '';
    const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);

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
    }
  }

  generarPaginacion();

  // Filtro dinámico
  const tipoFiltro = document.getElementById('tipoFiltro');
  const contenedorInputFiltro = document.getElementById('contenedorInputFiltro');

  tipoFiltro.addEventListener('change', () => {
    contenedorInputFiltro.innerHTML = '';
    const tipo = tipoFiltro.value;

    if (!tipo) {
      contenedorInputFiltro.style.display = 'none';
      aplicarFiltroTabla('', '');
      return;
    }

    contenedorInputFiltro.style.display = 'block';

    if (tipo === 'estado') {
      contenedorInputFiltro.innerHTML = `
        <div class="input-field" style="margin-left: 25px";>
          <select id="inputFiltro" class="browser-default">
            <option value=""> Estados Usuarios</option>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
          </select>
        </div>
      `;
    } else {
      contenedorInputFiltro.innerHTML = `
        <div class="input-field" style="margin-left: 25px";>
          <input type="text" id="inputFiltro" placeholder="Ingrese valor..." class="validate" />
        </div>
      `;
    }

    // Asignar evento con timeout para asegurar que el elemento esté en el DOM
    setTimeout(() => {
      const input = document.getElementById('inputFiltro');
      if (!input) return;

      const evento = (tipo === 'estado') ? 'change' : 'input';
      input.addEventListener(evento, () => {
        aplicarFiltroTabla(tipo, input.value.trim().toLowerCase());
      });
    }, 0);
  });

  // Aplicar filtro y regenerar paginación
  function aplicarFiltroTabla(tipo, valor) {
    filasFiltradas = filas.filter(fila => {
      let texto = '';
      switch (tipo) {
        case 'documento':
          texto = fila.children[0].textContent.toLowerCase();
          break;
        case 'nombre':
          texto = fila.children[1].textContent.toLowerCase();
          break;
        case 'estado':
          texto = fila.children[4].textContent.toLowerCase();
          break;
        default:
          texto = '';
      }
      return !valor || texto.includes(valor);
    });

    generarPaginacion();
  }
});

// Modal
function cerrarModalUsuario() {
  document.getElementById("modalEditarUsuario").style.display = "none";
}
window.cerrarModalUsuario = cerrarModalUsuario;
