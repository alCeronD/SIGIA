import { instanceModal, options } from "../utils/cases.js";
import { initAlert, toastOptions, validateFormData } from "../utils/cases.js";

const btnEdit = document.querySelectorAll(".btnEditarCategoria");
const closeModalBtn = document.querySelector(".close-modal");

const modalCategoria = instanceModal("#modalEditarCategoria", {
  inDuration: options.inDuration,
  outDuration: options.outDuration,
  opacity: options.opacity,
});

// Pintar colores de estado
document.querySelectorAll("[data-statusTd]").forEach((itemTd) => {
  itemTd.style.color = itemTd.textContent === "Activo" ? "green" : "red";
});

// Mostrar y cargar modal
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

// ciero modal
document.addEventListener("DOMContentLoaded", () => {
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
          M.toast({ html: resultado.mensaje, classes: "green darken-1" });
          modalCategoria.close();
          window.location.reload();
        } else {
          M.toast({ html: resultado.mensaje, classes: "red darken-1" });
        }
      } catch (error) {
        console.error("Error al editar categoría:", error);
        M.toast({ html: "Error de conexión o formato", classes: "red darken-1" });
      }
    });


  }
});
