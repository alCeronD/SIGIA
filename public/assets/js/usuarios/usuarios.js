import { soloLetras, soloNumeros, validarCorreo } from "../utils/regex.js";
import {sendData} from "../utils/fetch.js";
import { initAlert, toastOptions, validateFormData } from "../utils/cases.js";


document.addEventListener("DOMContentLoaded", () => {

  // ==== Vist consulta usuarios ======== //

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

        // Limpiar input anterior si existe
        const inputAnterior = document.getElementById("inputFiltro");
        if (inputAnterior) inputAnterior.value = "";

        const tipo = tipoFiltro.value;
        // Reinicia las filas
        filasFiltradas = [...filas];
        generarPaginacion();

        if (!tipo) {
          contenedorInputFiltro.style.display = 'none';
          aplicarFiltroTabla('', '');
          return;
        }

        contenedorInputFiltro.style.display = 'grid';

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
            const valor = input.value.trim().toLowerCase();

            // Validaciones por tipo
            if (tipo === 'documento') {
              input.value = input.value.replace(/\D/g, '');
            } else if (tipo === 'nombre') {
              input.value = input.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúñÑ\s]/g, '');
            }

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

        // Quitar mensaje anterior si existe
        const tablaBody = document.querySelector("#tableConfig tbody");
        const mensajeAnterior = document.getElementById("mensaje-no-resultados");
        if (mensajeAnterior) mensajeAnterior.remove();

        // Si no hay resultados, mostrar mensaje
        if (filasFiltradas.length === 0) {
          const filaMensaje = document.createElement("tr");
          filaMensaje.id = "mensaje-no-resultados";
          const celda = document.createElement("td");
          celda.colSpan = 6; // número de columnas en tu tabla
          celda.className = "center-align red-text";
          celda.textContent = "No se encontraron resultados";
          filaMensaje.appendChild(celda);
          tablaBody.appendChild(filaMensaje);
        }

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
        document.getElementById("usu_direccion").value = btn.dataset.direccion;
        document.getElementById("rol_id").value = btn.dataset.rol;

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

  // Aplicar validaciones si los elementos estan
  if (docInput) soloNumeros(docInput);
  if (telefonoInput) soloNumeros(telefonoInput);
  if (nombresInput) soloLetras(nombresInput);
  if (apellidosInput) soloLetras(apellidosInput);
  if (correoInput) validarCorreo(correoInput);
  if (textarea) M.textareaAutoResize(textarea);

  const inputOptionals = ["usu_observacion", "usu_direccion"];
  const mapForm = {
  usu_tp_id: "Tipo de documento",
  usu_docum: "Número de identificación",
  rol_id: "Rol",
  usu_nombres: "Nombres",
  usu_apellidos: "Apellidos",
  usu_telefono: "Teléfono",
  usu_password: "Contraseña",
  usu_email: "Correo electrónico",
  usu_direccion: "Dirección",
  usu_observacion: "Notas adicionales al usuario"
};

  
  //Validaciones formulario registro usuarios.
  const formUsuario = document.getElementById("formSolicitudPrestamo");
  if (formUsuario) {
    formUsuario.addEventListener("submit", (e) => {
      e.stopPropagation();
      e.preventDefault();
      const tipoDocumento = document.getElementById("usu_tp_id");
      const rol = document.getElementById("rol_id");
      const formData = new FormData(formUsuario);
      // Valida que los campos del formulario sean visibles.
      if (!validateFormData({formData: formData, campos: inputOptionals, mapForm: mapForm})) {
        return;
      }
      const data = Object.fromEntries(formData.entries());
      console.log(data);
      let valid = true;

      if (!tipoDocumento.value) {
        M.toast({ html: 'Seleccione un tipo de documento', classes: 'teal darken-2' });
        tipoDocumento.classList.add("invalid");
        valid = false;
      }

      if (!rol.value) {
        M.toast({ html: 'Seleccione un rol para el usuario', classes: 'teal darken-2' });
        rol.classList.add("invalid");
        valid = false;
      }
      try {
        const response = sendData("modules/usuarios/controller/usuariosController.php", "POST", "addUser", data);
        response.then((result)=>{
          if (result) {
            initAlert("Usuario creado exitosamente", "success", toastOptions);
            formUsuario.reset();
            return;
          }
        });


      } catch (error) {
        throw new Error(`Error al registrar el usuario, intente nuevamente ${error}`);
        
      }


    });

  }
  document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", () => {
      const input = document.querySelector(icon.getAttribute("toggle"));
      const isPassword = input.getAttribute("type") === "password";
      input.setAttribute("type", isPassword ? "text" : "password");
      icon.textContent = isPassword ? "visibility_off" : "visibility";
    });
  });

});

function cerrarModalUsuario() {
  const modal = document.getElementById("modalEditarUsuario");
  if (modal) modal.style.display = "none";
}
window.cerrarModalUsuario = cerrarModalUsuario;
