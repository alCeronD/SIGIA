const form = document.getElementById("formCreateCategoria");
const mensajeDiv = document.getElementById("mensajeCategoria");
const tbody = document.getElementById("tbodyCategorias");

form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: "POST",
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData, 
    });

    if (!response.ok) throw new Error("Respuesta del servidor no válida");

    const data = await response.json();

    if (data.success) {
      mensajeDiv.textContent = data.message;
      mensajeDiv.style.color = "green";
      form.reset();

      const categoria = data.categoria;

      const nuevaFila = document.createElement("tr");
      nuevaFila.setAttribute("data-id", categoria.ca_id);
      nuevaFila.innerHTML = `
        <td>${categoria.ca_nombre}</td>
        <td>${categoria.ca_descripcion}</td>
        <td data-statusTd="${categoria.ca_status}">
          ${categoria.ca_status === 1 || categoria.ca_status === "1" ? "Activo" : "Inactivo"}
        </td>
        <td class="accionesUsuarios">
          <button type="button"
            class="btnEditarCategoria waves-effect waves-light btn"
            data-id="${categoria.ca_id}"
            data-nombre="${categoria.ca_nombre}"
            data-descripcion="${categoria.ca_descripcion}"
            data-status="${categoria.ca_status}">
            <i class="material-icons">edit</i>
          </button>
        </td>
      `;

      tbody.appendChild(nuevaFila);

      // Evento editar con materialize
      nuevaFila.querySelector(".btnEditarCategoria").addEventListener("click", function (e) {
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
    console.error("Error:", error);
    mensajeDiv.textContent = "Error en la conexión con el servidor.";
    mensajeDiv.style.color = "red";
  }
});
