

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formCreateCategoria");
  const mensajeDiv = document.getElementById("mensajeCategoria");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: "POST",
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        mensajeDiv.textContent = "Categoría registrada correctamente.";

        // Limpiar formulario
        form.reset();

        // Agregar nueva fila a la tabla
        const nuevaFila = document.createElement("tr");

        nuevaFila.innerHTML = `
          <td>${data.categoria.ca_nombre}</td>
          <td>${data.categoria.ca_descripcion}</td>
          <td>${data.categoria.ca_status === 1 ? "Activo" : "Inactivo"}</td>
          <td class="accionesUsuarios">
            <a href="#"
              class="btnEditarCategoria"
              data-id="${data.categoria.ca_id}"
              data-nombre="${data.categoria.ca_nombre}"
              data-descripcion="${data.categoria.ca_descripcion}"
              data-status="${data.categoria.ca_status}">
              Editar
            </a>
          </td>
        `;

        // Insertar la fila en el <tbody>
        const tbody = document.querySelector(".tableCategoria tbody");
        tbody.appendChild(nuevaFila);

        // ✅ Volver a asignar el evento click para el nuevo botón Editar
        nuevaFila.querySelector(".btnEditarCategoria").addEventListener("click", function(e) {
          e.preventDefault();

          document.getElementById("modal_ca_id").value = this.dataset.id;
          document.getElementById("modal_ca_nombre").value = this.dataset.nombre;
          document.getElementById("modal_ca_descripcion").value = this.dataset.descripcion;
          document.getElementById("modal_ca_status").value = this.dataset.status;
          document.getElementById("modalEditarCategoria").style.display = "flex";
        });

      } else {
        mensajeDiv.textContent = data.mensaje || "Ocurrió un error al registrar.";
        mensajeDiv.style.color = "red";
      }
    } catch (error) {
      mensajeDiv.textContent = "Error en la conexión.";
      mensajeDiv.style.color = "red";
    }
  });
});
