document.addEventListener("DOMContentLoaded", () => {

  // ==== Vist consulta usuarios ========

  const paginacion = document.getElementById('paginacion-usuarios');
  const filas = Array.from(document.querySelectorAll('#tableConfig tbody tr'));
  let filasFiltradas = [...filas];
  const itemsPorPagina = 5;

  if (paginacion && filas.length > 0) {
    function mostrarPagina(pagina) {
      const inicio = (pagina - 1) * itemsPorPagina;
      const fin = inicio + itemsPorPagina;

      filas.forEach(fila => fila.style.display = 'none');

      filasFiltradas.forEach((fila, index) => {
        fila.style.display = (index >= inicio && index < fin) ? 'table-row' : 'none';
      });

      document.querySelectorAll('#paginacion-usuarios li').forEach(li => li.classList.remove('active'));
      const liActivo = document.querySelector(`#paginacion-usuarios li[data-pagina="${pagina}"]`);
      if (liActivo) liActivo.classList.add('active');
    }

    function generarPaginacion() {
      if (!paginacion) return;

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

    // Acciones filtro
    const tipoFiltro = document.getElementById('tipoFiltro');
    const contenedorInputFiltro = document.getElementById('contenedorInputFiltro');

    if (tipoFiltro && contenedorInputFiltro) {
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
            <div class="input-field" style="margin-left: 25px;">
              <select id="inputFiltro" class="browser-default">
                <option value=""> Estados Usuarios</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
              </select>
            </div>
          `;
        } else {
          contenedorInputFiltro.innerHTML = `
            <div class="input-field" style="margin-left: 25px;">
              <input type="text" id="inputFiltro" placeholder="Ingrese valor..." class="validate" />
            </div>
          `;
        }

        setTimeout(() => {
          const input = document.getElementById('inputFiltro');
          if (!input) return;

          const evento = (tipo === 'estado') ? 'change' : 'input';
          input.addEventListener(evento, () => {
            aplicarFiltroTabla(tipo, input.value.trim().toLowerCase());
          });
        }, 0);
      });

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
    }

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
  }

  // ==== Acciones para registro de usuario ===

  const docInput = document.getElementById("usu_docum");
  const telefonoInput = document.getElementById("usu_telefono");
  const nombresInput = document.getElementById("usu_nombres");
  const apellidosInput = document.getElementById("usu_apellidos");
  const correoInput = document.getElementById("usu_email");
  const textarea = document.getElementById("observaciones");

  // Validar que solo se puedan escribir No#.
  const soloNumeros = (input) => {
    input.addEventListener("input", () => {
      input.value = input.value.replace(/\D/g, "");
    });
  };

  // Solo se puedan escribir letras
  const soloLetras = (input) => {
    input.addEventListener("input", () => {
      input.value = input.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúñÑ\s]/g, "");
    });
  };

  // formato para el correo
  const validarCorreo = (input) => {
    input.addEventListener("blur", () => {
      const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (input.value && !correoValido.test(input.value)) {
        M.toast({ html: 'Correo electrónico no válido', classes: 'red darken-1' });
        input.classList.add('invalid');
      } else {
        input.classList.remove('invalid');
      }
    });
  };

  // Aplicar validaciones si los elementos estan
  if (docInput) soloNumeros(docInput);
  if (telefonoInput) soloNumeros(telefonoInput);
  if (nombresInput) soloLetras(nombresInput);
  if (apellidosInput) soloLetras(apellidosInput);
  if (correoInput) validarCorreo(correoInput);
  if (textarea) M.textareaAutoResize(textarea);
});


function cerrarModalUsuario() {
  const modal = document.getElementById("modalEditarUsuario");
  if (modal) modal.style.display = "none";
}
window.cerrarModalUsuario = cerrarModalUsuario;
