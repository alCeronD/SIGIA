import { instanceModal, options } from "../utils/cases.js";
import { initAlert, toastOptions, validateFormData } from "../utils/cases.js";

// Modal de edición
const modalCategoria = instanceModal("#modalEditarCategoria", {
  inDuration: options.inDuration,
  outDuration: options.outDuration,
  opacity: options.opacity,
});

// Función para asignar eventos a los botones de edición
export function asignarEventosBotonesEditar() {
  const btnEdit = document.querySelectorAll(".btnEditarCategoria");

  btnEdit.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();

      document.getElementById("modal_ca_id").value = btn.dataset.id;
      document.getElementById("modal_ca_nombre").value = btn.dataset.nombre;
      document.getElementById("modal_ca_descripcion").value = btn.dataset.descripcion;
      document.getElementById("modal_ca_status").value = btn.dataset.status;

      M.FormSelect.init(document.querySelectorAll("#modal_ca_status"));
      modalCategoria.open();
      M.updateTextFields();
    });
  });
}

// Pintar colores de estado
export function pintarEstadoCategoria() {
  document.querySelectorAll("[data-statusTd]").forEach((itemTd) => {
    itemTd.style.color = itemTd.textContent === "Activo" ? "green" : "red";
  });
}

// Cerrar modal
document.addEventListener("DOMContentLoaded", () => {
  const closeModalBtn = document.querySelector(".close-modal");
  if (closeModalBtn && modalCategoria) {
    closeModalBtn.addEventListener("click", () => {
      modalCategoria.close();
    });
  }

  const formEditar = document.getElementById("formEditarCategoria");

  if (formEditar) {
    formEditar.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(formEditar);

      try {
        const response = await fetch(formEditar.action, {
          method: "POST",
          headers: {
            "X-Requested-With": "XMLHttpRequest"
          },
          body: formData
        });

        if (!response.ok) throw new Error("Error al enviar la solicitud");

        const resultado = await response.json();

        if (resultado.success) {
          initAlert(resultado.mensaje, "success", toastOptions);
          modalCategoria.close();

          // Actualizar la tabla sin recargar la página
          const categoriasUrl = document.getElementById("tabla-categorias").dataset.url;
          if (categoriasUrl) {
            const response = await fetch(categoriasUrl);
            const data = await response.json();
            if (data.status === "success") {
              renderizarTabla(data.data.categorias);
              asignarEventosBotonesEditar();
              pintarEstadoCategoria();
            }
          }
        } else {
          initAlert(resultado.mensaje, "error", toastOptions);
        }

      } catch (error) {
        console.error("Error al editar categoría:", error);
        initAlert("Error de conexión o formato", "error", toastOptions);
      }
    });
  }

  // Asignar eventos iniciales al cargar
  asignarEventosBotonesEditar();
  pintarEstadoCategoria();
});

// Renderizado de tabla
function renderizarTabla(categorias) {
  const cuerpoTabla = document.getElementById("tbodyCategorias");

  if (!cuerpoTabla) return;

  if (categorias.length === 0) {
    cuerpoTabla.innerHTML = `<tr><td colspan="4">No hay categorías registradas.</td></tr>`;
    return;
  }

  cuerpoTabla.innerHTML = categorias.map(cat => `
    <tr>
      <td>${cat.ca_nombre}</td>
      <td>${cat.ca_descripcion}</td>
      <td data-statusTd="${cat.ca_status}">${cat.ca_status == "1" ? "Activo" : "Inactivo"}</td>
      <td class="accionesUsuarios">
        <button type="button"
          class="btnEditarCategoria waves-effect waves-light btn"
          data-id="${cat.ca_id}"
          data-nombre="${cat.ca_nombre}"
          data-descripcion="${cat.ca_descripcion}"
          data-status="${cat.ca_status}">
          <i class="material-icons">edit</i>
        </button>
      </td>
    </tr>
  `).join("");
}
  